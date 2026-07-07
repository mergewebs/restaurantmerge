<?php

namespace App\Http\Controllers;

use App\Models\RestaurantSchedule;
use App\Models\RestaurantException;
use Illuminate\Http\Request;

class RestaurantConfigController extends Controller
{
    private function denyNonAdmin()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }
        return null;
    }

    private function denyNonSuperAdmin()
    {
        if (!auth()->user()->isUserAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }
        return null;
    }

    public function index()
    {
        if ($deny = $this->denyNonAdmin()) return $deny;

        $schedules = RestaurantSchedule::orderBy('day_of_week')->get();
        if ($schedules->isEmpty()) {
            foreach (range(0, 6) as $day) {
                $params = [
                    'day_of_week' => $day,
                    'is_closed' => in_array($day, [0, 1], true),
                    'open_time_1' => null,
                    'close_time_1' => null,
                    'open_time_2' => null,
                    'close_time_2' => null,
                    'open_time_3' => null,
                    'close_time_3' => null,
                    'max_party_size' => 25,
                ];

                if (in_array($day, [2, 3, 4], true)) {
                    $params['open_time_1'] = '18:00';
                    $params['close_time_1'] = '23:00';
                }

                if (in_array($day, [5, 6], true)) {
                    $params['open_time_1'] = '13:00';
                    $params['close_time_1'] = '16:30';
                    $params['open_time_2'] = '19:30';
                    $params['close_time_2'] = '23:30';
                }

                RestaurantSchedule::create($params);
            }
            $schedules = RestaurantSchedule::orderBy('day_of_week')->get();
        }

        $exceptions = RestaurantException::whereDate('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->get();

        return view('restaurant.config', compact('schedules', 'exceptions'));
    }

    public function updateSchedule(Request $request)
    {
        if ($deny = $this->denyNonSuperAdmin()) return $deny;

        $data = $request->validate([
            'schedules' => 'required|array',
            'schedules.*.open_time_1' => 'nullable|string',
            'schedules.*.close_time_1' => 'nullable|string',
            'schedules.*.open_time_2' => 'nullable|string',
            'schedules.*.close_time_2' => 'nullable|string',
            'schedules.*.open_time_3' => 'nullable|string',
            'schedules.*.close_time_3' => 'nullable|string',
            'schedules.*.max_party_size' => 'required|integer|min:1',
        ]);

        foreach ($data['schedules'] as $id => $scheduleData) {
            $schedule = RestaurantSchedule::findOrFail($id);
            $schedule->update([
                'is_closed' => isset($request->input('schedules')[$id]['is_closed']),
                'open_time_1' => $scheduleData['open_time_1'],
                'close_time_1' => $scheduleData['close_time_1'],
                'open_time_2' => $scheduleData['open_time_2'],
                'close_time_2' => $scheduleData['close_time_2'],
                'open_time_3' => $scheduleData['open_time_3'],
                'close_time_3' => $scheduleData['close_time_3'],
                'max_party_size' => $scheduleData['max_party_size'],
            ]);
        }

        return back()->with('success', 'Schedule updated successfully.');
    }

    public function storeException(Request $request)
    {
        if ($deny = $this->denyNonAdmin()) return $deny;

        $validated = $request->validate([
            'date' => 'required|date|unique:restaurant_exceptions,date',
            'open_time_1' => 'nullable|string',
            'close_time_1' => 'nullable|string',
            'open_time_2' => 'nullable|string',
            'close_time_2' => 'nullable|string',
            'open_time_3' => 'nullable|string',
            'close_time_3' => 'nullable|string',
            'max_party_size' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $validated['is_closed'] = $request->has('is_closed');

        RestaurantException::create($validated);

        return back()->with('success', 'Exception added successfully.');
    }

    public function destroyException(RestaurantException $exception)
    {
        if ($deny = $this->denyNonAdmin()) return $deny;

        $exception->delete();

        return back()->with('success', 'Exception removed.');
    }

    public function toggleToday()
    {
        // For security, checking admin or manager? The user is already in auth group.
        // Let's use denyNonAdmin to be safe if desired, or allow waiters to stop if they are on dashboard.
        // But usually stopping reservations is an admin task.
        if ($deny = $this->denyNonAdmin()) return $deny;

        $today = now()->toDateString();
        $exception = RestaurantException::whereDate('date', $today)->first();

        if ($exception) {
            if ($exception->is_closed) {
                // If it was closed, we remove the exception (open it)
                $exception->delete();
                $message = 'Reservations are now OPEN for today.';
            } else {
                // If it was an exception but not closed (maybe custom hours), we close it
                $exception->update(['is_closed' => true]);
                $message = 'Reservations STOPPED for today.';
            }
        } else {
            // Create a new closure exception for today
            RestaurantException::create([
                'date' => $today,
                'is_closed' => true,
                'description' => 'Closed via Dashboard shortcut'
            ]);
            $message = 'Reservations STOPPED for today.';
        }

        return back()->with('success', $message);
    }
}

