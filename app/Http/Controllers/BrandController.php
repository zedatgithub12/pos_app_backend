<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brand = Brand::all();

        return response()->json([
            'success' => true,
            'data' => $brand
        ], 200);
    }

    public function show(string $name)
    {

        $brand = Brand::where('brand', $name)->orderByDesc('id')->get();

        return response()->json([
            'success' => true,
            'data' => $brand
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Generate item code
        $subcatid = $request->input('subcatcode');
        $lastItemId = Brand::latest()->first()->id ?? 0;
        $code = $subcatid . ($lastItemId + 1);

        $brand = Brand::where('brand', $request->brand)->first();
        if ($brand) {
            return response()->json(['success' => false, 'message' => 'This brand already exists.']);
        }
        $brand = new Brand;
        $brand->code = $code;
        $brand->main_category = $request->main;
        $brand->sub_category = $request->sub;
        $brand->brand = $request->brand;
        $brand->save();

        return response()->json(['success' => true, 'message' => 'Brand created successfully.']);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json(['success' => false, 'message' => 'brand not found.']);
        }

        $brand->main_category = $request->main;
        $brand->sub_category = $request->sub;
        $brand->brand = $request->brand;
        $brand->save();

        return response()->json(['success' => true, 'message' => ' Updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json(['success' => false, 'message' => 'brand not found.']);
        }

        $brand->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
    }
}