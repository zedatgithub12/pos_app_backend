<?php

namespace App\Http\Controllers;

use App\Models\Packages;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = Packages::all();

        return response()->json([
            'success' => true,
            'data' => $packages
        ], 200);
    }

    public function storepackage(string $name)
    {

        $packages = Packages::where('shop', '=', $name)->orderByDesc('id')->get();

        return response()->json([
            'success' => true,
            'data' => $packages
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $package = new Packages();
        $package->shopid = $request->shopid;
        $package->shopname = $request->shop;
        $package->userid = $request->userid;
        $package->name = $request->name;
        $package->items = json_encode($request->items);
        $package->price = $request->price;
        $package->expiredate = $request->expiredate;
        $package->status = "active";

        if ($package->save()) {
            return response()->json(['success' => true, 'message' => 'package created successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'unable to create package']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $packages = Packages::find($id);

        if ($request->has('name')) {
            $packages->name = $request->name;
        }

        if ($request->has('category')) {
            $packages->category = $request->category;
        }

        if ($request->has('brand')) {
            $packages->brand = $request->brand;
        }

        if ($request->has('code')) {
            $packages->code = $request->code;
        }

        if ($request->has('cost')) {
            $packages->cost = $request->cost;
        }

        if ($request->has('unit')) {
            $packages->unit = $request->unit;
        }

        if ($request->has('price')) {
            $packages->price = $request->price;
        }

        if ($request->has('quantity')) {
            $packages->quantity = $request->quantity;
        }

        if ($request->has('description')) {
            $packages->description = $request->description;
        }

        if ($request->has('shop')) {
            $packages->shop = $request->shop;
        }

        if ($request->has('status')) {
            $packages->status = $request->status;
        }


        $packages->save();

        return response()->json(['success' => true, 'message' => 'packages updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}