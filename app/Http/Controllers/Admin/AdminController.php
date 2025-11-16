<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Good;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Get admin statistics
     */
    public function getStats()
    {
        return response()->json([
            'totalUsers' => User::where('role', 'user')->count(),
            'totalVendors' => Vendor::count(),
            'totalGoods' => Good::count(),
        ]);
    }

    /**
     * Get all users
     */
    public function getUsers()
    {
        $users = User::where('role', 'user')->get();
        return response()->json($users);
    }

    /**
     * Create a new user
     */
    public function storeUser()
    {
        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        return response()->json($user, 201);
    }

    /**
     * Update a user
     */
    public function updateUser($id)
    {
        $user = User::findOrFail($id);

        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        $user->update($validated);

        return response()->json($user);
    }

    /**
     * Delete a user
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * Get all vendors
     */
    public function getVendors()
    {
        $vendors = Vendor::with('user')->get();
        return response()->json($vendors);
    }

    /**
     * Create a new vendor
     */
    public function storeVendor()
    {
        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Create user with vendor role
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'role' => 'vendor',
        ]);

        // Create vendor record
        $vendor = Vendor::create([
            'user_id' => $user->id,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        return response()->json($vendor->load('user'), 201);
    }

    /**
     * Update a vendor
     */
    public function updateVendor($id)
    {
        $vendor = Vendor::findOrFail($id);

        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $vendor->user_id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        // Update user
        $vendor->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update vendor
        $vendor->update([
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        return response()->json($vendor->load('user'));
    }

    /**
     * Delete a vendor
     */
    public function deleteVendor($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->user->delete();
        $vendor->delete();

        return response()->json(['message' => 'Vendor deleted successfully']);
    }
}
