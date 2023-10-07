<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sucategory = SubCategory::all();

        return response()->json([
            'success' => true,
            'data' => $sucategory
        ], 200);
    }

    public function show(string $name)
    {

        $subcat = SubCategory::where('main_category', $name)->orderByDesc('id')->get();

        return response()->json([
            'success' => true,
            'data' => $subcat
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Generate item code
        $categoryId = $request->input('category_id');
        $lastItemId = SubCategory::latest()->first()->id ?? 0;
        $code = $categoryId . ($lastItemId + 1);

        $sub_category = SubCategory::where('sub_category', $request->sub)->first();
        if ($sub_category) {
            return response()->json(['success' => false, 'message' => 'This sub category already exists.']);
        }
        $sub_category = new SubCategory;
        $sub_category->code = $code;
        $sub_category->main_category = $request->main;
        $sub_category->sub_category = $request->sub;
        $sub_category->save();

        return response()->json(['success' => true, 'message' => 'Sub Category added successfully.']);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sub_category = SubCategory::find($id);
        if (!$sub_category) {
            return response()->json(['success' => false, 'message' => 'Sub Category not found.']);
        }

        $sub_category->main_category = $request->main;
        $sub_category->sub_category = $request->sub;
        $sub_category->save();

        return response()->json(['success' => true, 'message' => ' Updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sub_category = SubCategory::find($id);
        if (!$sub_category) {
            return response()->json(['success' => false, 'message' => 'Sub Category not found.']);
        }

        $sub_category->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
    }
}