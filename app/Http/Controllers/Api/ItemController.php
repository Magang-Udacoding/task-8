<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // GET /api/items
    public function index(): JsonResponse
    {
        $items = Item::with('category')->get();

        return response()->json([
            'message' => 'List of Items!',
            'data'    => $items,
        ], 200);
    }

    // POST /api/items
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock'       => 'nullable|integer|min:0',
        ]);

        $item = Item::create($validated);

        return response()->json([
            'message' => 'Item Created!',
            'data'    => $item->load('category'),
        ], 201);
    }

    // GET /api/items/{id}
    public function show(string $id): JsonResponse
    {
        $item = Item::with('category')->find($id);

        if (!$item) {
            return response()->json([
                'message' => 'Item Not Found!',
            ], 404);
        }

        return response()->json([
            'message' => 'Item Detail...',
            'data'    => $item,
        ], 200);
    }

    // PUT/PATCH /api/items/{id}
    public function update(Request $request, string $id): JsonResponse
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'message' => 'Item Not Found!',
            ], 404);
        }

        $validated = $request->validate([
            'category_id' => 'sometimes|integer|exists:categories,id',
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'stock'       => 'nullable|integer|min:0',
        ]);

        $item->update($validated);

        return response()->json([
            'message' => 'Item Updated!',
            'data'    => $item->load('category'),
        ], 200);
    }

    // DELETE /api/items/{id}
    public function destroy(string $id): JsonResponse
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'message' => 'Item Not Found!',
            ], 404);
        }

        $item->delete();

        return response()->json([
            'message' => 'Item Deleted!',
        ], 200);
    }
}
