<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiCustomerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ApiOrderController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ApiSellerController;
use App\Http\Controllers\ApiOTController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\ApiInvoiceController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login2', [UserController::class, 'login2']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
});
Route::middleware(['auth:api'])->group(function(){
     Route::post('/getOrderTakerOrders',[OrderController::class, 'getOrderTakerOrders']);
    Route::get('/details', [UserController::class, 'details']);
    Route::get('/getMyCustomer', [CustomerController::class, 'getCustomers']);
   Route::post('/storeOrder',[OrderController::class, 'storeOrderApi']);
    Route::post('/getCustomerProductForOrder',[App\Http\Controllers\OrderController::class, 'getCustomerProductForOrder']);
    Route::get('/getAllProducts', [ProductController::class, 'getProducts']); 
    Route::get('/getAllCategories', [CategoryController::class, 'getCategories']);
    Route::post('/storeCategory', [CategoryController::class, 'storeCategory']);
      Route::get('/allAreas',[AreaController::class, 'list'])->name('list.area');
     Route::post('/storeArea',[AreaController::class, 'save']);

    Route::get('/getPaidHistory' , [UserController::class, 'paidHistory'])->name('paid.history');
      Route::post('/getSellerOrdersProcessings',[SellerController::class, 'getSellerOrdersProcessings'])->name('view.seller.orders.processing');
     







});
