<?php

namespace App\Http\Controllers\Inventory;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Show create form
     */
    public function create()
    {
        return Inertia::render('Categories/Create');
    }

    /**
     * Store new category
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        $category = Category::create($validated);

        return redirect()->back()->with('success', 'Category created successfully');
    }
}
