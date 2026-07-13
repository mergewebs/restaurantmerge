<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Deny access to non-admin users.
     */
    private function denyNonAdmin()
    {
        if (! auth()->user()->isUserAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        return null;
    }

    /**
     * Display a listing of all users.
     */
    public function index()
    {
        if ($deny = $this->denyNonAdmin()) {
            return $deny;
        }

        $users = User::orderBy('name', 'asc')->get();

        return view('users.index', compact('users'));
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
        ]);

        User::create([
            'name' => $validated['name'],
            'passcode' => $validated['passcode'],
            'role' => $validated['role'],
        ]);

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
        ]);

        $user->name = $validated['name'];
        $user->role = $validated['role'];

        if (! empty($validated['passcode'])) {
            $user->passcode = $validated['passcode'];
        }

        $user->save();

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
