<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Show the profile page
     */
    public function show(Request $request)
    {
        return Inertia::render('Profile/Show', [
            'user' => $request->user()->load('vendor'),
        ]);
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->user()->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        $user = $request->user();
        $user->update($validated);

        // Update vendor record if user is vendor
        if ($user->role === 'vendor' && $user->vendor) {
            $user->vendor->update([
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ]);
        }

        return response()->json([
            'user' => $user->load('vendor'),
            'message' => 'Profile updated successfully',
        ]);
    }
}
