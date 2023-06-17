<?php

use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PriceUpdateController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ShopStatController;
use App\Http\Controllers\SoldPackageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminStatController;

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
Route::post('/adduser', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('forgotpassword', [ForgotPasswordController::class, 'forgotpassword']);
Route::post('resetpassword', [ResetPasswordController::class, 'resetpassword']);
Route::put('changepass/{id}', [AuthController::class, 'changepass']);
Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});

//get statistics
Route::get('adminstat', [AdminStatController::class, 'Stats']);
Route::get('shopstat', [ShopStatController::class, 'Stats']);
//Store routes
Route::get('viewstore', [StoreController::class, 'index']);
Route::get('/stores/{id}', [StoreController::class, 'show']);
Route::post('createstore', [StoreController::class, 'store']);
Route::post('updatestore/{id}', [StoreController::class, 'update']);
Route::delete('deletestore/{id}', [StoreController::class, 'destroy']);
Route::post('addmanager/{id}', [StoreController::class, 'addmanager']);
//product routes
Route::get('viewproduct', [ProductController::class, 'index']);
Route::get('viewstoreproduct/{name}', [ProductController::class, 'storeproduct']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('addproduct', [ProductController::class, 'store']);
Route::post('updateproduct/{id}', [ProductController::class, 'update']);
Route::delete('deleteproduct/{id}', [ProductController::class, 'destroy']);

//packages routes
Route::get('viewpackages', [PackageController::class, 'index']);
Route::get('viewstorepackage/{name}', [PackageController::class, 'storepackages']);
Route::get('/packages/{id}', [PackageController::class, 'show']);
Route::post('addpackage', [PackageController::class, 'store']);
Route::put('updatepackage/{id}', [PackageController::class, 'update']);
Route::delete('deletepackage/{id}', [PackageController::class, 'destroy']);

//package sale routes 
Route::get('viewsoldpackages', [SoldPackageController::class, 'index']);
Route::post('createpackagesale', [SoldPackageController::class, 'store']);
Route::get('viewstorepackagesale/{name}', [SoldPackageController::class, 'storepackagesale']);


//Category routes
Route::get('viewcategory', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::post('addcategory', [CategoryController::class, 'store']);
Route::post('editcategory/{id}', [CategoryController::class, 'update']);
Route::delete('deletecategory/{id}', [CategoryController::class, 'destroy']);

// Sales routes
Route::get('viewsale', [SalesController::class, 'index']);
Route::get('viewstoresale/{name}', [SalesController::class, 'storesale']);
Route::post('createsale', [SalesController::class, 'store']);
Route::post('updatesale/{id}', [SalesController::class, 'update']);
Route::delete('deletesale/{id}', [SalesController::class, 'destroy']);
// Customers routes
Route::get('viewcustomer', [CustomerController::class, 'index']);
Route::get('viewstorecustomer/{name}', [CustomerController::class, 'storecustomer']);
Route::post('addcustomer', [CustomerController::class, 'store']);
Route::post('updatecustomer/{id}', [CustomerController::class, 'update']);
Route::delete('deletecustomer/{id}', [CustomerController::class, 'destroy']);

//user routes
Route::get('viewuser', [AuthController::class, 'index']);
Route::post('updateuser/{id}', [AuthController::class, 'update']);
Route::delete('deleteuser/{id}', [AuthController::class, 'destroy']);

//price update route
Route::get('priceupdates/{id}', [PriceUpdateController::class, 'index']);
Route::post('updateprice', [PriceUpdateController::class, 'store']);
Route::post('updateallprice', [PriceUpdateController::class, 'updateallprice']);


//notification routes
Route::get('adminnotification', [NotificationController::class, 'index']);
Route::get('salesnotification/{id}', [NotificationController::class, 'storeNotification']);
Route::put('updatestatus/{id}', [NotificationController::class, 'updateStatus']);
Route::put('updatesalesstatus/{id}', [NotificationController::class, 'updateSalesStatus']);
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