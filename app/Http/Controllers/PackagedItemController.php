<?php

namespace App\Http\Controllers;

use App\Models\PackagedItem;
use Illuminate\Http\Request;

class PackagedItemController extends Controller
{
    public function index()
    {
        $packagedItems = PackagedItem::all();
        return response()->json([
            'success' => true,
            'data' => $packagedItems
        ]);
    }

    public function addNewItem(Request $request, string $id)
    {
        $packagedItem = new PackagedItem();
        $packagedItem->package_id = $id;
        $packagedItem->stock_id = $request["id"];
        $packagedItem->item_name = $request["item_name"];
        $packagedItem->item_code = $request["item_code"];
        $packagedItem->item_sku = $request["item_sku"];
        $packagedItem->item_quantity = $request["item_quantity"];

        if ($packagedItem->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Package item added successfully',
                'data' => $packagedItem
            ], 201);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Unable to add new item',

            ], 424);
        }

    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'package_id' => 'required',
            'stock_id' => 'required',
            'item_name' => 'required',
            'item_code' => 'required',
            'item_quantity' => 'required|integer',
            'item_sku' => 'required',
        ]);

        $packagedItem = new PackagedItem();
        $packagedItem->package_id = $validatedData['package_id'];
        $packagedItem->stock_id = $validatedData['stock_id'];
        $packagedItem->item_name = $validatedData['item_name'];
        $packagedItem->item_code = $validatedData['item_code'];
        $packagedItem->item_quantity = $validatedData['item_quantity'];
        $packagedItem->item_sku = $validatedData['item_sku'];
        $packagedItem->save();

        return response()->json([
            'success' => true,
            'message' => 'Package item added successfully',
            'data' => $packagedItem
        ], 201);
    }

    public function show(PackagedItem $packagedItem)
    {
        return response()->json([
            'success' => true,
            'data' => $packagedItem
        ]);
    }

    public function getPackagedItems(string $id)
    {
        $packagedItem = PackagedItem::where("package_id", $id)->get();
        return response()->json([
            'success' => true,
            'data' => $packagedItem
        ]);
    }

    public function update(Request $request, PackagedItem $packagedItem)
    {
        $validatedData = $request->validate([
            'package_id' => 'required',
            'stock_id' => 'required',
            'item_name' => 'required',
            'item_code' => 'required',
            'item_quantity' => 'required|integer',
            'item_sku' => 'required',
        ]);

        $packagedItem->package_id = $validatedData['package_id'];
        $packagedItem->stock_id = $validatedData['stock_id'];
        $packagedItem->item_name = $validatedData['item_name'];
        $packagedItem->item_code = $validatedData['item_code'];
        $packagedItem->item_quantity = $validatedData['item_quantity'];
        $packagedItem->item_sku = $validatedData['item_sku'];
        $packagedItem->save();

        return response()->json([
            'success' => true,
            'message' => 'Package item updated successfully',
            'data' => $packagedItem
        ]);
    }

    public function updateItemQuantity(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'item_quantity' => 'required|integer',
        ]);

        $packagedItem = PackagedItem::find($id);
        if ($packagedItem) {
            $packagedItem->item_quantity = $validatedData['item_quantity'];

            if ($packagedItem->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Package item updated successfully',
                    'data' => $packagedItem
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot update the quantity',

                ]);
            }

        } else {
            return response()->json([
                'success' => false,
                'message' => 'The item is not found',

            ]);
        }




    }
    public function destroy(string $id)
    {
        $packagedItem = PackagedItem::find($id);

        if ($packagedItem) {
            $packagedItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Package item deleted successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Package item is not found'
            ]);
        }

    }
}