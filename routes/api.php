<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});

//Store routes
Route::get('viewstore', [StoreController::class, 'index']);
Route::get('/stores/{id}', [StoreController::class, 'show']);
Route::post('createstore', [StoreController::class, 'store']);
Route::post('updatestore/{id}', [StoreController::class, 'update']);
Route::delete('deletestore/{id}', [StoreController::class, 'destroy']);

//product routes
Route::get('viewproduct', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('addproduct', [ProductController::class, 'store']);
Route::post('updateproduct/{id}', [ProductController::class, 'update']);
Route::delete('deleteproduct/{id}', [ProductController::class, 'destroy']);

//Category routes
Route::get('viewcategory', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::post('addcategory', [CategoryController::class, 'store']);
Route::post('editcategory/{id}', [CategoryController::class, 'update']);
Route::delete('deletecategory/{id}', [CategoryController::class, 'destroy']);

// Sales routes
Route::get('viewsale', [SalesController::class, 'index']);
Route::post('createsale', [SalesController::class, 'store']);
Route::put('editsale/{id}', [SalesController::class, 'update']);
Route::delete('deletesale/{id}', [SalesController::class, 'destroy']);
// Customers routes
Route::get('/customers', [CustomerController::class, 'index']);
Route::post('/customers', [CustomerController::class, 'store']);
Route::put('/customers/{id}', [CustomerController::class, 'update']);
Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);

Route::get('images/{filename}', function ($filename) {
    $path = storage_path('app/public/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->where('filename', '^[^/]+$');