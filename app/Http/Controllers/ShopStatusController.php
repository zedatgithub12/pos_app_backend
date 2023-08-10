<?php

namespace App\Http\Controllers;

use App\Models\shopStatus;
use Illuminate\Http\Request;

class ShopStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Retrieve the input data
        $data = request()->validate([
            'shop_id' => 'required',
            'user_id' => 'required',
            'status' => 'required|in:Open,Closed,Temporarily Closed,Permanently Closed',
        ]);

        // Create a new instance of the model
        $shopStatus = new shopStatus();

        // Assign the values
        $shopStatus->shop_id = $data['shop_id'];
        $shopStatus->user_id = $data['user_id'];
        $shopStatus->status = $data['status'];

        // Save the model
        $shopStatus->save();

        // Save the updated model
        if ($shopStatus->save()) {
            return response()->json(['success' => true, 'message' => 'Successfully done!'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Try again later!'], 404);
        }

    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'shop_id' => 'required',
            'user_id' => 'required',
            'status' => 'required|in:Open,Closed,Temporarily Closed,Permanently Closed',
        ]);

        // Find the model instance by ID
        $shopStatus = shopStatus::findOrFail($id);

        // Assign the updated values
        $shopStatus->shop_id = $validatedData['shop_id'];
        $shopStatus->user_id = $validatedData['user_id'];
        $shopStatus->status = $validatedData['status'];

        // Save the updated model
        if ($shopStatus->save()) {
            return response()->json(['success' => true, 'message' => 'Successfully updated status'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Unable to change the status for a moment retry later!'], 404);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}