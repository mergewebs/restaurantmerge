<?php

namespace App\Http\Controllers;

use App\Mail\NewReservationAdminNotification;
use App\Mail\ReservationConfirmationClient;
use App\Models\Reservation;
use App\Models\RestaurantException;
use App\Models\RestaurantSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $todayReservations = Reservation::whereDate('reservation_time', now()->toDateString())
            ->orderBy('reservation_time', 'asc')
            ->paginate(10, ['*'], 'today_page');

        $upcomingReservations = Reservation::whereDate('reservation_time', '>', now()->toDateString())
            ->orderBy('reservation_time', 'asc')
            ->paginate(10, ['*'], 'upcoming_page');

        $pastReservations = Reservation::whereDate('reservation_time', '<', now()->toDateString())
            ->orderBy('reservation_time', 'desc')
            ->paginate(10, ['*'], 'past_page');

        return view('reservations.index', compact('todayReservations', 'upcomingReservations', 'pastReservations'));
    }

    public function create()
    {
        return view('reservations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'required|email|max:255',
            'party_size' => 'required|integer|min:1',
            'reservation_time' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        $error = $this->checkAvailability($validated['reservation_time'], $validated['party_size']);
        if ($error) {
            return back()->withInput()->with('error', $error);
        }

        $reservation = Reservation::create($validated);
        $locale = request()->getPreferredLanguage(['es', 'en']);

        // Send Email to Admin (for notification of new manual reservation)
        $adminEmail = env('ADMIN_EMAIL', 'koyabcn@gmail.com');
        Mail::to($adminEmail)->send(new NewReservationAdminNotification($reservation));

        // Send Email to Client if email exists
        if ($reservation->customer_email) {
            Mail::to($reservation->customer_email)->send(new ReservationConfirmationClient($reservation, $locale));
        }

        return back()->with('success', __('Reservation created successfully.'));
    }

    public function edit(Reservation $reservation)
    {
        return view('reservations.edit', compact('reservation'));
    }

    public function show(Reservation $reservation)
    {
        return redirect()->route('reservations.edit', $reservation);
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'party_size' => 'required|integer|min:1',
            'reservation_time' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|string|in:confirmed,cancelled,completed',
        ]);

        $error = $this->checkAvailability($validated['reservation_time'], $validated['party_size'], $reservation->id);
        if ($error) {
            return back()->withInput()->with('error', $error);
        }

        $reservation->update($validated);

        return back()->with('success', __('Reservation updated successfully.'));
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return back()->with('success', __('Reservation deleted.'));
    }

    private function checkAvailability($time, $partySize, $excludeId = null)
    {
        $dt = Carbon::parse($time);
        $date = $dt->toDateString();
        $timeStr = $dt->format('H:i:s');
        $dayOfWeek = $dt->dayOfWeek;

        $config = null;
        $isException = false;

        // Check Exceptions first
        $exception = RestaurantException::whereDate('date', $date)->first();
        if ($exception) {
            $config = $exception;
            $isException = true;
        }
        else {
            $config = RestaurantSchedule::where('day_of_week', $dayOfWeek)->first();
        }

        // Fallback if no configuration is found
        if (!$config) {
            $capacity = 25;
            $isClosed = false;
            $openSlots = [['start' => '12:00:00', 'stop' => '16:00:00'], ['start' => '19:00:00', 'stop' => '23:00:00']];
        }
        else {
            $capacity = $config->max_party_size ?? 25;
            $isClosed = (bool)$config->is_closed;
            $openSlots = [];
            for ($i = 1; $i <= 3; $i++) {
                $o = "open_time_$i";
                $c = "close_time_$i";
                if ($config->$o) {
                    $openSlots[] = [
                        'start' => Carbon::parse($config->$o)->format('H:i:s'),
                        'stop' => Carbon::parse($config->$c)->format('H:i:s')
                    ];
                }
            }
            if (empty($openSlots) && !$isClosed) {
                $openSlots[] = ['start' => '12:00:00', 'stop' => '23:00:00'];
            }
        }

        if ($isClosed) {
            return $isException
                ? __('Reservations are closed for this date.')
                : __('The restaurant is closed on this weekday.');
        }

        // Identify matching slot
        $slotStart = null;
        $slotEnd = null;

        foreach ($openSlots as $slot) {
            if ($timeStr >= $slot['start'] && $timeStr < $slot['stop']) {
                $slotStart = $slot['start'];
                $slotEnd = $slot['stop'];
                break;
            }
        }

        if (!$slotStart) {
            $slotStrings = [];
            foreach ($openSlots as $slot) {
                $slotStrings[] = substr($slot['start'], 0, 5) . '-' . substr($slot['stop'], 0, 5);
            }
            $slotsText = implode(' / ', $slotStrings);
            return __('Reservations are only available during: :slots.', ['slots' => $slotsText]);
        }

        // Dynamic Seating Validation (If Session 3 exists, evening slots only allow start times)
        $hasSession3 = !empty($config->open_time_3);
        if ($hasSession3 && $dt->hour >= 17) {
            $eveningStartTimes = [];
            foreach ($openSlots as $slot) {
                if (Carbon::parse($slot['start'])->hour >= 17) {
                    $eveningStartTimes[] = substr($slot['start'], 0, 5);
                }
            }

            $reqTime = $dt->format('H:i');
            if (!in_array($reqTime, $eveningStartTimes)) {
                $allowedFormatted = implode(' or ', $eveningStartTimes);
                return __('Evening reservations can only start at :seatings.', ['seatings' => $allowedFormatted]);
            }
        }

        // Check Capacity
        $isSeatingMode = $hasSession3 && $dt->hour >= 17;

        $query = Reservation::whereDate('reservation_time', $date)
            ->where('status', '!=', 'cancelled')
            ->when($excludeId, function ($q) use ($excludeId) {
            return $q->where('id', '!=', $excludeId);
        });

        if ($isSeatingMode) {
            $totalPeople = $query->whereTime('reservation_time', '>=', $slotStart)
                ->whereTime('reservation_time', '<', $slotEnd)
                ->sum('party_size');
        }
        else {
            $totalPeople = $query->whereTime('reservation_time', $timeStr)
                ->sum('party_size');
        }

        if ($totalPeople + $partySize > $capacity) {
            $available = max(0, $capacity - $totalPeople);
            return __('This time is at capacity. Remaining seats: :available.', ['available' => $available]);
        }

        return null;
    }

    public function getScheduleData(Request $request)
    {
        $date = $request->query('date', now()->toDateString());
        $dt = Carbon::parse($date);
        $dayOfWeek = $dt->dayOfWeek;

        $config = RestaurantException::whereDate('date', $date)->first();
        if (!$config) {
            $config = RestaurantSchedule::where('day_of_week', $dayOfWeek)->first();
        }

        if (!$config) {
            $slots = [
                ['start' => '12:00:00', 'end' => '16:00:00'],
                ['start' => '19:00:00', 'end' => '23:00:00']
            ];
            $maxPartySize = 25;
            $closed = false;
        }
        else {
            $closed = (bool)$config->is_closed;
            $maxPartySize = $config->max_party_size ?? 25;
            $slots = [];
            for ($i = 1; $i <= 3; $i++) {
                $open = "open_time_$i";
                $close = "close_time_$i";
                if ($config->$open) {
                    $slots[] = [
                        'start' => Carbon::parse($config->$open)->format('H:i:s'),
                        'end' => Carbon::parse($config->$close)->format('H:i:s'),
                    ];
                }
            }
            if (empty($slots) && !$closed) {
                $slots = [['start' => '12:00:00', 'end' => '23:00:00']];
            }
        }

        if ($closed) {
            return response()->json([
                'closed' => true,
                'description' => $config->description ?? null
            ]);
        }

        $excludeId = $request->query('exclude_id');

        $reservations = Reservation::whereDate('reservation_time', $date)
            ->where('status', '!=', 'cancelled')
            ->when($excludeId, function ($q) use ($excludeId) {
            return $q->where('id', '!=', $excludeId);
        })
            ->get();

        $takenPerTime = [];
        $hasSession3 = !empty($config->open_time_3);

        foreach ($slots as $slot) {
            $isSeatingMode = $hasSession3 && Carbon::parse($slot['start'])->hour >= 17;

            if ($isSeatingMode) {
                // Seating Mode: Sum entire slot
                $totalInSlot = 0;
                foreach ($reservations as $res) {
                    $resTime = $res->reservation_time->format('H:i:s');
                    if ($resTime >= $slot['start'] && $resTime < $slot['end']) {
                        $totalInSlot += $res->party_size;
                    }
                }
                // Apply to all increments in this seating
                $curr = Carbon::parse($slot['start']);
                $end = Carbon::parse($slot['end']);
                while ($curr < $end) {
                    $takenPerTime[$curr->format('H:i')] = $totalInSlot;
                    $curr->addMinutes(15);
                }
            }
            else {
                // Flow Mode: Sum per-time
                $curr = Carbon::parse($slot['start']);
                $end = Carbon::parse($slot['end']);
                while ($curr < $end) {
                    $t = $curr->format('H:i');
                    $tWithSeconds = $curr->format('H:i:s');

                    $takenAtThisTime = 0;
                    foreach ($reservations as $res) {
                        if ($res->reservation_time->format('H:i:s') === $tWithSeconds) {
                            $takenAtThisTime += $res->party_size;
                        }
                    }
                    $takenPerTime[$t] = $takenAtThisTime;
                    $curr->addMinutes(15);
                }
            }
        }

        return response()->json([
            'closed' => false,
            'slots' => array_map(function ($s) {
            return ['start' => substr($s['start'], 0, 5), 'end' => substr($s['end'], 0, 5)];
        }, $slots),
            'taken_per_time' => $takenPerTime,
            'max_party_size' => $maxPartySize,
            'day_of_week' => $dayOfWeek,
            'has_session_3' => !empty($config->open_time_3)
        ]);
    }
    public function getClosedDates()
    {
        $exceptions = RestaurantException::where('is_closed', true)
            ->whereDate('date', '>=', now()->toDateString())
            ->pluck('date')
            ->map(fn($d) => $d->format('Y-m-d'));

        $weeklyClosed = RestaurantSchedule::where('is_closed', true)
            ->pluck('day_of_week');

        return response()->json([
            'exceptions' => $exceptions,
            'weekly_closed' => $weeklyClosed,
        ]);
    }
}

