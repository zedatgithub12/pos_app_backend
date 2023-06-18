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
        $packages = Packages::orderByDesc('id')->get();

        return response()->json([
            'success' => true,
            'data' => $packages
        ], 200);
    }

    public function storepackages(string $id)
    {

        $packages = Packages::where('shopid', '=', $id)->orderByDesc('id')->get();
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



        if ($request->has('shopid')) {
            $packages->shopid = $request->shopid;
        }

        if ($request->has('shopname')) {
            $packages->shopname = $request->shopname;
        }

        if ($request->has('userid')) {
            $packages->userid = $request->userid;
        }
        if ($request->has('name')) {
            $packages->name = $request->name;
        }
        if ($request->has('items')) {
            $packages->items = json_encode($request->items);
        }

        if ($request->has('price')) {
            $packages->price = $request->price;
        }

        if ($request->has('expiredate')) {
            $packages->expiredate = $request->expiredate;
        }

        $packages->save();

        return response()->json(['success' => true, 'message' => 'packages updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $packages = Packages::find($id);
        if (!$packages) {
            return response()->json(['success' => false, 'message' => 'package not found'], 404);
        }
        $packages->delete();

        return response()->json(['success' => true, 'message' => 'package deleted successfully']);

    }
}