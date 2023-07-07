<?php

namespace App\Http\Controllers;

use App\Models\ShopTarget;
use Illuminate\Http\Request;

class ShopTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $targets = ShopTarget::orderByDesc('id')->get();

        return response()->json([
            'success' => true,
            'data' => $targets
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $shopTarget = new ShopTarget();
        $shopTarget->shopid = $request->shopid;
        $shopTarget->shopname = $request->shopname;
        $shopTarget->s_daily = $request->s_daily;
        $shopTarget->r_daily = $request->r_daily;
        $shopTarget->s_monthly = $request->s_monthly;
        $shopTarget->r_monthly = $request->r_monthly;
        $shopTarget->s_yearly = $request->s_yearly;
        $shopTarget->r_yearly = $request->r_yearly;
        $shopTarget->start_date = $request->start_date;
        $shopTarget->end_date = $request->end_date;
        $shopTarget->status = $request->status;
        if ($shopTarget->save()) {
            return response()->json(['success' => true, 'message' => 'Added successfully'], 200);
        } else {

            return response()->json(['success' => false, 'message' => 'Unable to add target'], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shopTarget = ShopTarget::findOrFail($id);

        return response()->json(['success' => true, 'data' => $shopTarget], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $shopTarget = ShopTarget::findOrFail($id);
        $shopTarget->shopid = $request->shopid;
        $shopTarget->shopname = $request->shopname;
        $shopTarget->s_daily = $request->s_daily;
        $shopTarget->r_daily = $request->r_daily;
        $shopTarget->s_monthly = $request->s_monthly;
        $shopTarget->r_monthly = $request->r_monthly;
        $shopTarget->s_yearly = $request->s_yearly;
        $shopTarget->r_yearly = $request->r_yearly;
        $shopTarget->start_date = $request->start_date;
        $shopTarget->end_date = $request->end_date;
        $shopTarget->status = $request->status;
        $shopTarget->save();
        if ($shopTarget->save()) {
            return response()->json(['success' => true, 'message' => 'Updated successfully'], 200);
        } else {

            return response()->json(['success' => false, 'message' => 'Unable to update target'], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $shopTarget = ShopTarget::findOrFail($id);
        if ($shopTarget->delete()) {
            return response()->json(['success' => true, 'message' => 'Deleted successfully'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Unable to delete'], 500);
        }


    }
}