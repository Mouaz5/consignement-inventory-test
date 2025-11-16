<?php

namespace App\Http\Controllers;

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

        return response()->json([
            'category' => $category,
            'message' => 'Category created successfully',
        ], 201);
    }
}
