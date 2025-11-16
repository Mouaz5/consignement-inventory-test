<?php

namespace App\Http\Controllers;

use App\Models\Good;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GoodsController extends Controller
{
    /**
     * Display vendor's goods
     */
    public function index(Request $request)
    {
        $vendor = $request->user()->vendor;

        if (!$vendor) {
            return redirect('/profile')->with('error', 'Only vendors can manage goods');
        }

        $goods = Good::where('vendor_id', $vendor->id)
            ->with('category')
            ->paginate(15);

        $categories = Category::all();

        return Inertia::render('Vendor/Goods/Index', [
            'goods' => $goods,
            'categories' => $categories,
        ]);
    }

    /**
     * Show create form
     */
    public function create(Request $request)
    {
        $vendor = $request->user()->vendor;

        if (!$vendor) {
            return redirect('/profile')->with('error', 'Only vendors can manage goods');
        }

        $categories = Category::all();

        return Inertia::render('Vendor/Goods/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store new good
     */
    public function store(Request $request)
    {
        $vendor = $request->user()->vendor;

        if (!$vendor) {
            return response()->json(['error' => 'Only vendors can manage goods'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'recived_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        $good = Good::create([
            ...$validated,
            'vendor_id' => $vendor->id,
        ]);

        return response()->json([
            'good' => $good->load('category'),
            'message' => 'Good created successfully',
        ], 201);
    }

    /**
     * Show edit form
     */
    public function edit(Good $good, Request $request)
    {
        $vendor = $request->user()->vendor;

        if (!$vendor || $good->vendor_id !== $vendor->id) {
            return redirect('/vendor/goods')->with('error', 'Unauthorized');
        }

        $categories = Category::all();

        return Inertia::render('Vendor/Goods/Edit', [
            'good' => $good->load('category'),
            'categories' => $categories,
        ]);
    }

    /**
     * Update good
     */
    public function update(Good $good, Request $request)
    {
        $vendor = $request->user()->vendor;

        if (!$vendor || $good->vendor_id !== $vendor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'recived_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        $good->update($validated);

        return response()->json([
            'good' => $good->load('category'),
            'message' => 'Good updated successfully',
        ]);
    }

    /**
     * Delete good
     */
    public function destroy(Good $good, Request $request)
    {
        $vendor = $request->user()->vendor;

        if (!$vendor || $good->vendor_id !== $vendor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $good->delete();

        return response()->json(['message' => 'Good deleted successfully']);
    }
}
