<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Replanish;
use App\Models\Stock;
use Illuminate\Http\Request;

class ReplanishController extends Controller
{
    /**
     * Display a listing of the resource.
     */public function index()
    {
        $Replanishes = Replanish::orderByDesc('id')->get();

        return response()->json([
            'success' => true,
            'data' => $Replanishes
        ], 200);

    }

    public function show(string $id)
    {
        $Stock = Replanish::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $Stock,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $stock = Stock::find($request->stock_id);
        $Replanish = new Replanish();
        $Replanish->shop_id = $request->shopid;
        $Replanish->shop_name = $request->shopname;
        $Replanish->stock_id = $request->stock_id;
        $Replanish->stock_name = $request->stock_name;
        $Replanish->stock_code = $request->stock_code;
        $Replanish->existing_amount = $stock->stock_quantity;
        $Replanish->added_amount = $request->added_amount;
        $Replanish->user_id = $request->userid;

        if ($Replanish->save()) {
            $stock->stock_quantity += $request->added_amount;
            if ($stock->save()) {
                return response()->json(['success' => true, 'message' => 'Replanished successfully'], 200);
            }
        } else {

            return response()->json(['success' => false, 'message' => 'Error replanishing item'], 500);
        }

    }
    public function destroy(string $id)
    {
        $Replanish = Replanish::find($id);
        if (!$Replanish) {
            return response()->json(['success' => false, 'message' => 'Record not found'], 404);
        }
        $Replanish->delete();

        return response()->json(['success' => true, 'message' => 'Record deleted successfully']);
    }
}