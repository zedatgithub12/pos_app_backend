<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackagedItemController;
use App\Http\Controllers\PriceUpdateController;
use App\Http\Controllers\ReplanishController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ShopStatController;
use App\Http\Controllers\ShopStatusController;
use App\Http\Controllers\ShopTargetController;
use App\Http\Controllers\SoldItemController;
use App\Http\Controllers\SoldPackageController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\SubCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockController;
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


//admin dashboard api routes
Route::get('againsttarget/{name}', [DashboardController::class, 'getSalesAgainstTarget']);
Route::get('lowstock/{name}', [DashboardController::class, 'getProductsByShopAndQuantity']);
Route::get('customercount', [DashboardController::class, 'totalCustomers']);
Route::get('monthlytarget/{month}', [DashboardController::class, 'getMonthlyTargets']);
Route::get('shopstarget', [DashboardController::class, 'retrieveShopData']);
//get statistics
Route::get('adminstat', [AdminStatController::class, 'Stats']);
Route::get('shopstat', [ShopStatController::class, 'Stats']);
Route::get('connection', [AdminStatController::class, 'checkconnection']);

//Store routes
Route::get('viewstore', [StoreController::class, 'index']);
Route::get('/stores/{id}', [StoreController::class, 'show']);
Route::post('createstore', [StoreController::class, 'store']);
Route::post('updatestore/{id}', [StoreController::class, 'update']);
Route::delete('deletestore/{id}', [StoreController::class, 'destroy']);
Route::post('addmanager/{id}', [StoreController::class, 'addmanager']);

//store status changing routes
Route::post("changeStatus", [ShopStatusController::class, 'store']);
Route::post("updateStatus", [ShopStatusController::class, 'update']);

// items route
Route::get('items', [ItemController::class, 'index']);
Route::get('getallitems', [ItemController::class, 'getAllItems']);
Route::post('add-items', [ItemController::class, 'store']);
Route::get('get-items/{id}', [ItemController::class, 'show']);
Route::post('update-items/{id}', [ItemController::class, 'update']);
Route::delete('delete-items/{id}', [ItemController::class, 'destroy']);
Route::put('update-product-status/{id}', [ItemController::class, 'updateStatus']);

//stock routes
Route::get('stocks', [StockController::class, 'index']);
Route::get('shopStocks/{name}', [StockController::class, 'shopStocks']);
Route::get('getShopStocks/{name}', [StockController::class, 'getShopStocks']);
Route::post('create-stocks', [StockController::class, 'store']);
Route::get('get-stocks/{id}', [StockController::class, 'show']);
Route::post('update-stocks/{id}', [StockController::class, 'update']);
Route::put('update-stock-status/{id}', [StockController::class, 'updateStatus']);
Route::delete('delete-stocks/{id}', [StockController::class, 'destroy']);

//product routes
Route::get('viewproduct', [ProductController::class, 'index']);
Route::get('viewstoreproduct/{name}', [ProductController::class, 'storeproduct']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('addproduct', [ProductController::class, 'store']);
Route::post('updateproduct/{id}', [ProductController::class, 'update']);
Route::delete('deleteproduct/{id}', [ProductController::class, 'destroy']);

//packages routes
Route::get('viewpackages', [PackageController::class, 'index']);
Route::get('viewstorepackage/{id}', [PackageController::class, 'storepackages']);
Route::get('/packages/{id}', [PackageController::class, 'show']);
Route::post('addpackage', [PackageController::class, 'store']);
Route::put('updatepackage/{id}', [PackageController::class, 'update']);
Route::delete('deletepackage/{id}', [PackageController::class, 'destroy']);

//packaged item routes
Route::get('packaged-items', [PackagedItemController::class, 'index']);
Route::post('packaged-items', [PackagedItemController::class, 'store']);
Route::post('add-packaged-items/{id}', [PackagedItemController::class, 'addNewItem']);
Route::get('packaged-single/{id}', [PackagedItemController::class, 'show']);
Route::get('packaged-items/{id}', [PackagedItemController::class, 'getPackagedItems']);
Route::put('packaged-items/{packagedItem}', [PackagedItemController::class, 'update']);
Route::put('single-package-item/{id}', [PackagedItemController::class, 'updateItemQuantity']);
Route::delete('packaged-items/{id}', [PackagedItemController::class, 'destroy']);

//package sale routes 
Route::get('viewsoldpackages', [SoldPackageController::class, 'getPackages']);
Route::post('createpackagesale', [SoldPackageController::class, 'store']);
Route::get('viewstorepackagesale/{name}', [SoldPackageController::class, 'storepackagesale']);
Route::post('updatepackagesale/{id}', [SoldPackageController::class, 'update']);
Route::delete('deletepackagesale/{id}', [SoldPackageController::class, 'destroy']);

//Category routes
Route::get('viewcategory', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::post('addcategory', [CategoryController::class, 'store']);
Route::post('editcategory/{id}', [CategoryController::class, 'update']);
Route::delete('deletecategory/{id}', [CategoryController::class, 'destroy']);

//Sub Categories routes
Route::get('viewsubcategory', [SubCategoryController::class, 'index']);
Route::get('subcategory/{name}', [SubCategoryController::class, 'show']);
Route::post('addsubcategory', [SubCategoryController::class, 'store']);
Route::post('editsubcategory/{id}', [SubCategoryController::class, 'update']);
Route::delete('deletesubcategory/{id}', [SubCategoryController::class, 'destroy']);

//brand routes
Route::get('viewbrand', [BrandController::class, 'index']);
Route::get('brand/{name}', [BrandController::class, 'show']);
Route::post('createbrand', [BrandController::class, 'store']);
Route::post('editbrand/{id}', [BrandController::class, 'update']);
Route::delete('deletebrand/{id}', [BrandController::class, 'destroy']);

//sku routes
Route::get('viewsku', [BrandController::class, 'index']);
Route::get('sku/{name}', [BrandController::class, 'show']);
Route::post('createsku', [BrandController::class, 'store']);
Route::post('editsku/{id}', [BrandController::class, 'update']);
Route::delete('deletesku/{id}', [BrandController::class, 'destroy']);

// Sales routes
Route::get('viewsale', [SalesController::class, 'getSales']);
Route::get('viewstoresale/{name}', [SalesController::class, 'storesale']);
Route::post('createsale', [SalesController::class, 'store']);
Route::post('updatesale/{id}', [SalesController::class, 'update']);
Route::delete('deletesale/{id}', [SalesController::class, 'destroy']);

//sold item routes 
Route::get('sold_items', [SoldItemController::class, 'index']);
Route::post('sold_items', [SoldItemController::class, 'store']);
Route::get('sold_items/{id}', [SoldItemController::class, 'show']);
Route::put('sold_items/{id}', [SoldItemController::class, 'update']);
Route::delete('sold_items/{id}', [SoldItemController::class, 'destroy']);
Route::get('filter_sold_item', [SoldItemController::class, 'filterSoldItem']);
Route::get('export_sold_item', [SoldItemController::class, 'ExportSoldItems']);


//targets
Route::get('targets', [ShopTargetController::class, 'index']);
Route::get('singleshop/{id}', [ShopTargetController::class, 'show']);
Route::post('addtarget', [ShopTargetController::class, 'store']);
Route::post('updatetarget/{id}', [ShopTargetController::class, 'update']);
Route::delete('deletetarget/{id}', [ShopTargetController::class, 'destroy']);

// Customers routes
Route::get('viewcustomer', [CustomerController::class, 'index']);
Route::get('viewstorecustomer/{name}', [CustomerController::class, 'storecustomer']);
Route::get('customer-details/{id}', [CustomerController::class, 'getCustomerPurchaseDetails']);
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

//Replanishing route
Route::get('allreplanishments', [ReplanishController::class, 'index']);
Route::get('singlereplanishment/{id}', [ReplanishController::class, 'show']);
Route::post('newreplanishment', [ReplanishController::class, 'store']);
Route::delete('deleterecord/{id}', [ReplanishController::class, 'destroy']);

//Trafer routes
Route::get('alltransfers', [StockTransferController::class, 'index']);
Route::post('transfer', [StockTransferController::class, 'transferItems']);
Route::post('updatetransfer/{id}', [StockTransferController::class, 'update']);
Route::delete('deletetransfer/{id}', [StockTransferController::class, 'destroy']);

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