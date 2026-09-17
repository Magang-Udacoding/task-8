<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET category
     */
    public function index(): JsonResponse
    {
        $categories = Category::with('items')->get();

        return response()->json([
            'message'=> 'List of Category!',
            'data' => $categories,
        ], 200
        );
    }

    /**
     * Store a newly created resource in storage.
     * POST Category
     */
    public function store(Request $request) : JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string'
        ]);

        $category = Category::create($validated);
        return response()->json([
            'message' => 'Category Created!',
            'data' => $category,
        ], 201);
    }

    /**
     * Display the specified resource.
     * GET Category per ID
     */
    public function show(string $id)
    {
        $category = Category::with('items')->find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category Not Found!',                
            ], 404
            );
        }

        return response()->json([
            'message' => 'Category Detail...',
            'data' => $category,
        ], 
        200);
    }

    /**
     * Update the specified resource in storage.
     * PUT Category
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category Not Found!',
            ], 
            404);
        }

        $validated = $request->validate([
            'name'        => 'sometimes|string|max:255|unique:categories,name' . $id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return response()->json([
            'message' => 'Category Updated!',
            'data' => $category,
        ], 
        200);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE Category
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category Not Found!',
            ], 
            404);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category Deleted!',
            'data' => $category,
        ], 
        200);
    }
}
