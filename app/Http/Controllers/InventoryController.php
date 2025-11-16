<?php

namespace App\Http\Controllers;

use App\Models\Good;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryController extends Controller
{
    /**
     * Display inventory list with filters
     */
    public function index(Request $request)
    {
        $query = Good::with(['vendor.user', 'category']);

        // Filter by vendor
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by good name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by received date range
        if ($request->filled('start_date')) {
            $query->whereDate('recived_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('recived_date', '<=', $request->end_date);
        }

        $goods = $query->paginate(15);
        $vendors = Vendor::with('user')->get();
        $categories = Category::all();

        return Inertia::render('Inventory/Index', [
            'goods' => $goods,
            'vendors' => $vendors,
            'categories' => $categories,
            'filters' => $request->only(['vendor_id', 'category_id', 'search', 'start_date', 'end_date']),
        ]);
    }

    /**
     * Print receipt for selected vendor
     */
    public function printReceipt(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
        ]);

        $vendor = Vendor::with('user')->findOrFail($validated['vendor_id']);
        $goods = Good::where('vendor_id', $vendor->id)
            ->with('category')
            ->get();

        $totalAmount = $goods->sum(function ($good) {
            return $good->price * $good->quantity;
        });

        // Create receipt record
        $receipt = Receipt::create([
            'vendor_id' => $vendor->id,
            'user_id' => $request->user()->id,
            'total_amount' => $totalAmount,
            'printed_at' => now()->toDateString(),
        ]);

        return response()->json([
            'receipt' => $receipt->load('user'),
            'vendor' => $vendor,
            'goods' => $goods,
            'totalAmount' => $totalAmount,
        ]);
    }

    /**
     * Print custom receipt with selected goods
     */
    public function printCustomReceipt(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'goods' => 'required|array',
            'goods.*.id' => 'required|exists:goods,id',
        ]);

        $vendor = Vendor::with('user')->findOrFail($validated['vendor_id']);
        $goodIds = array_column($validated['goods'], 'id');
        $goods = Good::whereIn('id', $goodIds)
            ->with('category')
            ->get();

        $totalAmount = $goods->sum(function ($good) {
            return $good->price * $good->quantity;
        });

        // Create receipt record
        $receipt = Receipt::create([
            'vendor_id' => $vendor->id,
            'user_id' => $request->user()->id,
            'total_amount' => $totalAmount,
            'printed_at' => now()->toDateString(),
        ]);

        return response()->json([
            'receipt' => $receipt->load('user'),
            'vendor' => $vendor,
            'goods' => $goods,
            'totalAmount' => $totalAmount,
        ]);
    }

    /**
     * Show receipt page
     */
    public function showReceipt($receiptId)
    {
        $receipt = Receipt::with('user')->findOrFail($receiptId);
        $vendor = Vendor::with('user')->findOrFail($receipt->vendor_id);
        $goods = Good::where('vendor_id', $vendor->id)
            ->with('category')
            ->get();

        $totalAmount = $goods->sum(function ($good) {
            return $good->price * $good->quantity;
        });

        return Inertia::render('Inventory/Receipt', [
            'receipt' => $receipt,
            'vendor' => $vendor,
            'goods' => $goods,
            'totalAmount' => $totalAmount,
        ]);
    }

    /**
     * Get vendor goods for dropdown
     */
    public function getVendorGoods($vendorId)
    {
        $goods = Good::where('vendor_id', $vendorId)
            ->with('category')
            ->get();

        return response()->json($goods);
    }
}

