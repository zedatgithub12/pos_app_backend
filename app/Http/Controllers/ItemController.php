<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return response()->json([
            'success' => true,
            'message' => 'Items retrieved successfully',
            'data' => $items
        ]);
    }

    public function store(Request $request)
    {
        // Generate item code
        $categoryPrefix = $request->input('item_category');
        $lastItemId = Item::latest()->first()->id ?? 0;
        $itemCode = $categoryPrefix . ($lastItemId + 1);

        // Create the item
        $item = Item::create([
            'item_code' => $itemCode,
            'item_name' => $request->input('item_name'),
            'item_category' => $request->input('item_category'),
            'item_sub_category' => $request->input('item_sub_category'),
            'item_brand' => $request->input('item_brand'),
            'item_description' => $request->input('item_description'),
            'item_price' => $request->input('item_price'),
            'item_status' => $request->input('item_status')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item created successfully',
            'data' => $item
        ], 201);
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'Item retrieved successfully',
            'data' => $item
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $item->update([
            'item_name' => $request->input('item_name'),
            'item_category' => $request->input('item_category'),
            'item_sub_category' => $request->input('item_sub_category'),
            'item_brand' => $request->input('item_brand'),
            'item_description' => $request->input('item_description'),
            'item_price' => $request->input('item_price'),
            'item_status' => $request->input('item_status')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item updated successfully',
            'data' => $item
        ]);
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item deleted successfully'
        ]);
    }
}
