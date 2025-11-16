<?php

namespace App\Http\Controllers\Inventory;
use App\Http\Controllers\Controller;
use App\Models\Good;
use App\Models\Category;
use App\Http\Requests\StoreGoodRequest;
use App\Http\Requests\UpdateGoodRequest;
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
    public function store(StoreGoodRequest $request)
    {
        $vendor = $request->user()->vendor;

        if (!$vendor) {
            return redirect('/vendor/goods')->with('error', 'Only vendors can manage goods');
        }

        $validated = $request->validated();

        $good = Good::create([
            ...$validated,
            'vendor_id' => $vendor->id,
        ]);

        return redirect('/vendor/goods')->with('success', 'Good created successfully');
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
    public function update(Good $good, UpdateGoodRequest $request)
    {
        $vendor = $request->user()->vendor;

        if (!$vendor || $good->vendor_id !== $vendor->id) {
            return redirect('/vendor/goods')->with('error', 'Unauthorized');
        }

        $validated = $request->validated();

        $good->update($validated);

        return redirect('/vendor/goods')->with('success', 'Good updated successfully');
    }

    /**
     * Delete good
     */
    public function destroy(Good $good, Request $request)
    {
        $vendor = $request->user()->vendor;

        if (!$vendor || $good->vendor_id !== $vendor->id) {
            return redirect('/vendor/goods')->with('error', 'Unauthorized');
        }

        $good->delete();

        return redirect('/vendor/goods')->with('success', 'Good deleted successfully');
    }
}
