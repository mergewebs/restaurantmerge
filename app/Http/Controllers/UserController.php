<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\RestaurantTable;
use App\Models\StaffAttendance;
use App\Models\StaffSchedule;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Deny access to non-admin users.
     */
    private function denyNonAdmin()
    {
        if (! auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        return null;
    }

    /**
     * Display a listing of all users.
     */
    public function index()
    {
        $users = User::with(['assignedTables', 'attendances' => fn($q) => $q->whereNull('clocked_out_at')->latest()])->orderBy('name')->get();
        $tables = RestaurantTable::orderBy('area')->orderBy('number')->get();
        $areas = $tables->pluck('area')->filter()->unique()->values();
        $schedules = StaffSchedule::with('user')->whereBetween('shift_date', [today(), today()->addDays(7)])->orderBy('shift_date')->orderBy('starts_at')->get();
        $todayAttendance = StaffAttendance::with('user')->whereDate('clocked_in_at', today())->latest('clocked_in_at')->get();
        $salesByUser = Order::whereDate('completed_at', today())->whereNotNull('assigned_user_id')->selectRaw('assigned_user_id, SUM(total) total_sales, COUNT(*) order_count')->groupBy('assigned_user_id')->get()->keyBy('assigned_user_id');

        return view('users.index', compact('users','tables','areas','schedules','todayAttendance','salesByUser'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        if ($deny = $this->denyNonAdmin()) {
            return $deny;
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'passcode' => ['required', 'digits:6', 'confirmed'],
            'role' => 'required|string|in:waiter,admin,owner,superadmin',
            'assigned_area' => ['nullable','string','max:60'],
            'can_discount' => ['nullable','boolean'], 'can_refund' => ['nullable','boolean'], 'can_cancel_orders' => ['nullable','boolean'],
            'table_ids' => ['nullable','array'], 'table_ids.*' => ['integer','exists:restaurant_tables,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'passcode' => $validated['passcode'],
            'role' => $validated['role'],
            'assigned_area' => $validated['assigned_area'] ?? null,
            'can_discount' => $request->boolean('can_discount'), 'can_refund' => $request->boolean('can_refund'), 'can_cancel_orders' => $request->boolean('can_cancel_orders'),
        ]);
        if (!empty($validated['table_ids'])) RestaurantTable::whereIn('id',$validated['table_ids'])->update(['assigned_user_id'=>$user->id]);

        return back()->with('success', 'Staff member created successfully.');
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        if ($deny = $this->denyNonAdmin()) {
            return $deny;
        }

        if ($user->id === auth()->id() && $request->role !== $user->role) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|in:waiter,admin,owner,superadmin',
            'passcode' => ['nullable', 'digits:6', 'confirmed'],
            'assigned_area' => ['nullable','string','max:60'],
            'can_discount' => ['nullable','boolean'], 'can_refund' => ['nullable','boolean'], 'can_cancel_orders' => ['nullable','boolean'],
            'table_ids' => ['nullable','array'], 'table_ids.*' => ['integer','exists:restaurant_tables,id'],
        ]);

        $user->name = $validated['name'];
        $user->role = $validated['role'];
        $user->assigned_area = $validated['assigned_area'] ?? null;
        $user->can_discount = $request->boolean('can_discount');
        $user->can_refund = $request->boolean('can_refund');
        $user->can_cancel_orders = $request->boolean('can_cancel_orders');

        if (! empty($validated['passcode'])) {
            $user->passcode = $validated['passcode'];
        }

        $user->save();
        RestaurantTable::where('assigned_user_id',$user->id)->update(['assigned_user_id'=>null]);
        if (!empty($validated['table_ids'])) RestaurantTable::whereIn('id',$validated['table_ids'])->update(['assigned_user_id'=>$user->id]);

        return back()->with('success', 'Staff member updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        if ($deny = $this->denyNonAdmin()) {
            return $deny;
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'Staff member deleted successfully.');
    }
}
