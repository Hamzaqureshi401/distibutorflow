<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
return view('welcome');
});
// Route::get('/',function(){
// return redirect()->route('login');
// });
// Route::get('/forgot-password', function () {
//     return view('auth.forgot-password');
// });

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');


Route::get('/verifyuser' , [App\Http\Controllers\UserController::class, 'verifyuser'])->name('verify.user');
Auth::routes();
Route::get('logout', 'App\Http\Controllers\Auth\LoginController@logout', function () {
    return abort(404);
});
Route::get('/markAttendance', function () {
    return view('employeeAttandence.mark_unmark_attandence');
})->name('mark.att');

//Route::get('/allCategories', 'App\Http\Controllers\CategoryController@index')->name('all.categories');

Route::get('/email/verify', 'App\Http\Controllers\VerificationController@show')->name('verification.notice');
Route::get('/signUp', 'App\Http\Controllers\UserController@signUp')->name('sign.Up');
Route::post('find-user-email', 'App\Http\Controllers\UserController@FindUserEmail')->name('find.user');

Route::post('/registerUser','App\Http\Controllers\UserController@registerNewSubadmin')->name('register.user');

Route::get('/signUp', function () {
    return view('auth.signUp');
})->name('mark.att');
    Route::get('/email/verify/{id}/{hash}', 'App\Http\Controllers\VerificationController@verify')->name('verification.verify')->middleware(['signed']);
    Route::post('/email/resend', 'App\Http\Controllers\VerificationController@resend')->name('verification.resend');
 
Route::group(['middleware' => ['auth', 'verified']], function () {

   
   
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'GetDashboard'])->name('admin.home');
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'GetProfile'])->name('get.profile');
Route::get('/editprofile', [App\Http\Controllers\ProfileController::class, 'EditProfile'])->name('edit.profile');
Route::post('/updateprofile', [App\Http\Controllers\ProfileController::class, 'UpdateProfile'])->name('update.profile');
Route::get('recover', 'App\Http\Controllers\InvoiceController@updation')->name('recover_data');
Route::post('suggest-user-email', 'App\Http\Controllers\UserController@suggestEmailForNewUser')->name('user.suggest.email');

Route::get('/print-invoice/{id}','App\Http\Controllers\InvoiceController@printInvoice')->name('print.invoice');
//Route::get('/dashboard','App\Http\Controllers\HomeController@userDashboard')->name('admin.home');
Route::get('/allCategories','App\Http\Controllers\CategoryController@index')->name('all.categories');
Route::post('/storeCategory','App\Http\Controllers\CategoryController@storeCategory')->name('save.category');
Route::post('/updateCategory/{id}','App\Http\Controllers\CategoryController@updateCategory')->name('update.category');
Route::get('/getCategory/{id}','App\Http\Controllers\CategoryController@getCategory');
Route::get('/deleteCategory/{id}','App\Http\Controllers\CategoryController@deleteCategory')->name('delete.category');

Route::get('/allProducts/{customer_id?}','App\Http\Controllers\ProductController@index')->name('all.products');
Route::get('/addProduct', 'App\Http\Controllers\ProductController@addProduct')->name('add.product');
Route::get('/storeProduct','App\Http\Controllers\ProductController@storeProduct')->name('save.product');
Route::match(array('GET','POST'),'/updateProduct/','App\Http\Controllers\ProductController@updateProduct')->name('update.product');
Route::get('/deleteProduct/{id?}','App\Http\Controllers\ProductController@deleteProduct')->name('delete.product');
Route::get('/addProductKit', 'App\Http\Controllers\ProductKitController@addProductKit')->name('add.productkit');
Route::post('/storeKit','App\Http\Controllers\ProductKitController@storeKit')->name('save.Kit');
Route::get('/allProductKit', 'App\Http\Controllers\ProductKitController@allProductKit')->name('all.productkit');
Route::get('/productKitDetail/{id?}', 'App\Http\Controllers\ProductKitController@productKitDetail')->name('product.KitDetail');
Route::get('/editKit/{id?}', 'App\Http\Controllers\ProductKitController@editKit')->name('edit.Kit');
Route::post('/updateProductKit','App\Http\Controllers\ProductKitController@updateProductKit')->name('update.ProductKit');



Route::get('/getProduct/{id?}','App\Http\Controllers\ProductController@getProduct')->name('edit.product');
Route::get('showinpos', 'App\Http\Controllers\ProductController@ShowInPos')->name('show.in.pos');
Route::get('allow/to/all/customer', 'App\Http\Controllers\ProductController@AllowToAllCustomer')->name('allow.to.all.customer');
Route::get('/GetCustomerProductStock/','App\Http\Controllers\ProductController@GetStock')->name('get.customer.product.stock');
Route::get('/findcustomerstockproduct/{id?}','App\Http\Controllers\ProductController@findStockProduct')->name('find.customer.stock.product');  
Route::post('/AddRemoveCustomerStock','App\Http\Controllers\ProductController@AddRemoveCustomerStock')->name('add.remove.customer.stock');  
Route::get('/GetCustomerStockInvoices','App\Http\Controllers\ProductController@GetCustomerStockInvoices')->name('Get.Customer.Stock.Invoices');  
Route::get('/GetTransectionRecord/{id?}','App\Http\Controllers\ProductController@GetTransectionRecord')->name('Get.Transection.Record');  




Route::group(['middleware' => 'admin'] , function(){
//Notifications
Route::get('notifySeen' , function(){
$user = App\User::find(Auth::id());
$user->is_notified = 0;
$user->save();
return "true";
})->name('notify.seen');
Route::get('notifyClicked/{id?}' , function($id){
App\Notification::where('id' , $id)->delete();
})->name('notify.clicked');
Route::get('/clearAll','App\Http\Controllers\UserController@clearAll')->name('clear.all');
Route::get('/sellTotalClear','App\Http\Controllers\UserController@sellTotalClear')->name('admin.sell.clear');
//Category
//Product
Route::get('/download/','App\Http\Controllers\ProductController@download')->name('download');
Route::get('/linkproduct/storelink', 'App\Http\Controllers\ProductController@savelinkproduct')->name('link.product');
Route::get('/linkproduct/deletelink', 'App\Http\Controllers\ProductController@deletelinkproduct')->name('delete.link.product');
//Seller , Users
Route::group(['middleware' => 'super'] , function(){
Route::get('/allUsers','App\Http\Controllers\SubAdminController@getallSubAdmin')->name('all.users');
// Route::get('/getSubAdmins','SellerController@getUsers')->name('all.users');
Route::get('/subAdminSellers/{id}','App\Http\Controllers\SellerController@indexAll')->name('subadmin.sellers');
Route::get('/deleteLocation/{id}','App\Http\Controllers\CustomerController@deleteLocation')->name('delete.location');
Route::get('/assignCustomer','App\Http\Controllers\SellerController@allowBranches')->name('assign.Customer');
Route::get('/subAdminCustomers/{id}','App\Http\Controllers\CustomerController@indexAll')->name('subadmin.customers');
Route::get('/status/update', 'App\Http\Controllers\CustomerController@updateStatus')->name('users.update.status');
Route::get('/area/update', 'App\Http\Controllers\CustomerController@updatearea')->name('users.update.area');
Route::get('/deleteSubAdmin/{id}','App\Http\Controllers\SubAdminController@deleteSubAdmin')->name('delete.subadmin');
Route::get('/subAdminInvoices/{id}','App\Http\Controllers\InvoiceController@indexAll')->name('subadmin.invoices');


});

Route::group(['prefix' => 'defualtOrder'] , function(){
    Route::get('/getProductForDefualtOrder', [App\Http\Controllers\DefualtOrderController::class, 'getProductForDefualtOrder'])->name('getProduct.For.DefualtOrder');
    Route::post('/setDefualtOrder', [App\Http\Controllers\DefualtOrderController::class, 'setDefualtOrder'])->name('setDefualt.order');
});

Route::get('/assignOrder/update', 'App\Http\Controllers\SellerController@assignOrder')->name('update.assign_order');
Route::get('/deleviry_product_profit/update', 'App\Http\Controllers\SellerController@DeleviryProductProfit')->name('update.deleviry_product_profit');
Route::get('/add/remove/stock', 'App\Http\Controllers\SellerController@ChkAddRemoveStock')->name('chk.add.remove.stock');
Route::group(['prefix' => 'product'], function(){
Route::get('/status/update', 'App\Http\Controllers\ProductController@updateStatusAllow')->name('allow.update.status');
Route::get('/show/status/update', 'App\Http\Controllers\ProductController@updateStatusShow')->name('show.update.status1');
});
Route::get('/addSeller','App\Http\Controllers\SellerController@addSeller')->name('add.user');
Route::post('/storeSeller/{update?}','App\Http\Controllers\SellerController@storeSeller')->name('create.user');
Route::post('/updateSeller/{id}','App\Http\Controllers\SellerController@updateSeller')->name('update.old.seller');
Route::post('/paySellerAmount/{id}' , 'App\Http\Controllers\SellerController@payAmount')->name('pay.seller.amount'); 
Route::post('/updateoldSeller/{id}','App\Http\Controllers\SubAdminController@updateSubadmin')->name('update.old.subadmin');
Route::get('/confirm/expnece','App\Http\Controllers\SellerController@SellerExpnceProcessing')->name('confirm.expnce');
Route::get('/getSeller/{id}','App\Http\Controllers\SellerController@getSeller')->name('edit.seller');
Route::get('/getSellerSells/{id}','App\Http\Controllers\SellerController@getSellerSells')->name('view.seller.sells');
Route::get('/getSellerOrders/{id}','App\Http\Controllers\SellerController@getSellerOrders')->name('view.seller.orders');
Route::get('/deleteSeller/{id}','App\Http\Controllers\SellerController@deleteSeller')->name('delete.seller');
Route::get('/deleteSubadmin/{id}','App\Http\Controllers\SubAdminController@deleteSuAdmin')->name('delete.subadmin');
Route::get('/getSubadmin/{id}','App\Http\Controllers\SubAdminController@getSubAdmin')->name('edit.subadmin');
Route::get('/ChangeUserStatus/{id}/{status}','App\Http\Controllers\SellerController@ChangeUserStatus')->name('unblock.admin');
Route::get('/deleteInvoice/{id}','App\Http\Controllers\InvoiceController@deleteInvoice')->name('delete.invoice');
Route::get('/SetInvoiceZero/{id}','App\Http\Controllers\InvoiceController@SetInvoiceZero')->name('set.invoice.zero');
Route::get('/equalInvoice/{id}','App\Http\Controllers\InvoiceController@equalInvoice')->name('equal.invoice');
Route::post('/UpdateReceivingofInvoice/{id}','App\Http\Controllers\InvoiceController@updateReceivingInvoice')->name('update.invoice.receiving');
//   Route::post('/storeCategory','CategoryController@storeCategory')->name('save.category');
Route::get('/istatusInvoice/', 'App\Http\Controllers\InvoiceController@updateStatus')->name('invoice.update.status');
Route::get('/approveInvoice/{id?}','App\Http\Controllers\InvoiceController@approveInvoice')->name('approve.invoice');
Route::post('/approveInvoiceMult','App\Http\Controllers\InvoiceController@approveInvoiceMult')->name('approve.invoice.multiple');
Route::post('validatePin' , 'App\Http\Controllers\UserController@validatePin')->name('validate.pin');
//Pay Amount
Route::get('/paidHistory' , 'App\Http\Controllers\UserController@paidHistory')->name('paid.history');
Route::post('/payAmount' , 'App\Http\Controllers\UserController@payAmount')->name('pay.amount');    
Route::get('/sellRecord' , 'App\Http\Controllers\UserController@sellRecord')->name('sell.record');
});
Route::get('/customerInvoices/{customer_id?}' , 'App\Http\Controllers\InvoiceController@customerInvoices')->name('customer.invoices');
Route::group(['prefix' => 'area'], function(){
Route::get('add', 'App\Http\Controllers\AreaController@add')->name('add.area');
Route::get('list', 'App\Http\Controllers\AreaController@list')->name('list.area');
Route::get('edit/{id}', 'App\Http\Controllers\AreaController@edit')->name('edit.area');
Route::post('save/{id?}', 'App\Http\Controllers\AreaController@save')->name('save.area');
Route::post('update/{id}', 'App\Http\Controllers\AreaController@update')->name('update.area');
Route::post('saveAjax/{id?}', 'App\Http\Controllers\AreaController@saveAjax')->name('save.area.ajax');
Route::get('delete/{id}', 'App\Http\Controllers\AreaController@delete')->name('delete.area');
});
//Customer
Route::get('/o','App\Http\Controllers\CustomerController@index')->name('check.customer.allowed.products');
Route::get('/b','App\Http\Controllers\CustomerController@index')->name('remove.customer.product');
Route::get('/allCustomers/{id?}/{lc?}','App\Http\Controllers\CustomerController@index')->name('all.customers');
Route::get('/filtercallcustomer','App\Http\Controllers\CustomerController@filtercallcustomer')->name('call.customers');
Route::get('/addCustomer', 'App\Http\Controllers\CustomerController@addCustomer')->name('add.customer');
Route::post('/email_available/check', 'App\Http\Controllers\CustomerController@check')->name('email_available.check');
Route::get('/order/getOrderTakerOrders/{ot_id}', 'App\Http\Controllers\OrderController@getOrderTakerOrders')
    ->name('ot.orders');
Route::get('/order/getSellerOrders/{ot_id}', 'App\Http\Controllers\OrderController@getOrderSellerOrders')
    ->name('seller.orders');

Route::get('/order/customerOrders/{customer_id?}' , 'App\Http\Controllers\OrderController@customerOrders')->name('customer.orders');
Route::get('/set/profitguru', 'App\Http\Controllers\SendEmailController@EnableProfitGuru')->name('update.profit.guru');
Route::get('/myCustomers/{user_id}','App\Http\Controllers\CustomerController@myCustomers');
Route::post('/storeCustomer','App\Http\Controllers\CustomerController@storeCustomer')->name('create.customer');
Route::post('/setcustomerpriority','App\Http\Controllers\CustomerController@setcustomerpriority')->name('set.customer.priority'); 
Route::post('/updateCustomer/{id}','App\Http\Controllers\CustomerController@updateCustomer')->name('update.customer');
Route::get('/updateCustomerPrices','App\Http\Controllers\CustomerController@updateCustomerPrices')->name('update.customer.product.prices');
Route::post('/updateareaofcustomer/{id}','App\Http\Controllers\CustomerController@updateCustomerarea')->name('change.area.customer');
Route::post('/area/updatemultiple', 'App\Http\Controllers\CustomerController@updatemultipleareas')->name('change.area.multiple');
Route::get('/area/UnVisitCustomer', 'App\Http\Controllers\CustomerController@UnVisitCustomer')->name('un.visit.customer');
Route::get('/admin/updatemultiple', 'App\Http\Controllers\CustomerController@changecustomeradmin')->name('change.admin.multiple');
Route::get('/editCustomer/{id}','App\Http\Controllers\CustomerController@getCustomer')->name('edit.customer');
Route::get('/deleteCustomer','App\Http\Controllers\CustomerController@deleteCustomer')->name('delete.customer');
Route::get('/restoreCustomer/{id}','App\Http\Controllers\CustomerController@restoreCustomer')->name('restore.customer');
Route::get('/deleteCustomerByOt/{id}','App\Http\Controllers\CustomerController@deleteCustomerByOrderTaker')->name('delete.customer.by.ot');
Route::get('/visitclear/update', 'App\Http\Controllers\CustomerController@visitclear')->name('users.update.visit_clear');
Route::get('/findnearestcustomer', 'App\Http\Controllers\CustomerController@findNearestCustomer')->name('find.nearest.customer');
Route::post('/storeNewSop', 'App\Http\Controllers\CustomerController@StoreNewSop')->name('Store.New.Sop');
Route::get('/store/ids', 'App\Http\Controllers\CustomerController@storeids')->name('store.ids.list');
Route::get('/delete/store/ids', 'App\Http\Controllers\CustomerController@deletestoreids')->name('deletestore.ids.list');
Route::get('/customerpending/up', 'App\Http\Controllers\CustomerController@customerpending')->name('uptodate.customer_pending');
Route::get('/checkCustomPrice/{id?}','App\Http\Controllers\CustomerController@checkCustomPrice')->name('check.custom.price');
Route::get('/findstockproduct/{id?}','App\Http\Controllers\CustomerController@findStockProduct')->name('find.stock.product');  
Route::get('/allSellers','App\Http\Controllers\SellerController@index')->name('all.sellers');
Route::get('/getSellerOrdersProcessings/{id?}','App\Http\Controllers\SellerController@getSellerOrdersProcessings')->name('view.seller.orders.processing');
Route::get('/SellerPaidHistory/{id}' , 'App\Http\Controllers\SellerController@paidHistory')->name('sellerpaid.history');
Route::get('/SetCash/Processing' , 'App\Http\Controllers\SellerController@SetCashProcessing')->name('set.cash.processing'); 
Route::get('/get/cash/remaining' , 'App\Http\Controllers\SellerController@getlastcashremaining')->name('get.cash.remaining'); 
Route::get('/delete/expnece','App\Http\Controllers\SellerController@DeleteExpence')->name('delete.expnce');
Route::get('/show/processed/orders/{id?}','App\Http\Controllers\OrderController@CashProcessedrOrders')->name('show.processed.orders');
//Order Taker
Route::get('/all_ot','App\Http\Controllers\OTController@index')->name('all.ot');
Route::get('/editOT/{id}','App\Http\Controllers\OTController@getOT')->name('edit.ot');
Route::post('/updateOT/{id}','App\Http\Controllers\OTController@updateOT')->name('update.ot');
Route::get('/deleteOT/{id}','App\Http\Controllers\OTController@deleteOT')->name('delete.ot');
Route::get('/ChangeOTStatus/{id}/{status}','App\Http\Controllers\OTController@ChangeUserStatus')->name('unblock.ot');
Route::post('/payOTAmount/{id}' , 'App\Http\Controllers\OTController@payAmount')->name('pay.ot.amount'); 
Route::get('/OtPaidHistory/{id}' , 'App\Http\Controllers\OTController@paidHistory')->name('ot.paid.history');
Route::get('/customeritself/update', 'App\Http\Controllers\OTController@customeritself')->name('update.customer_itself');
Route::get('/discountonof/update', 'App\Http\Controllers\OTController@discountonoff')->name('update.discount_on_off');
Route::get('/eneble_per_visit_price/update', 'App\Http\Controllers\OTController@EneblePerVisitPrice')->name('update.eneble_per_visit_price');

Route::get('/auto_area_price/update', 'App\Http\Controllers\OTController@auto_area_price')->name('update.auto_area_price');


Route::get('/allow/create/customer', 'App\Http\Controllers\OTController@AllowCreateCustomer')->name('allow.create.customer');
Route::get('/allow/edit/order', 'App\Http\Controllers\OTController@allowToEditOrder')->name('allow.to.edit.order');
Route::get('/allow/delete/order', 'App\Http\Controllers\OTController@allowToDeleteOrder')->name('allow.to.delete.order');

Route::get('/allow/store/area/data', 'App\Http\Controllers\OTController@AllowStoreAreaData')->name('store.varae.isit.data');
Route::get('/DoNotShoPndngCst/', 'App\Http\Controllers\OTController@DoNotShoPndngCst')->name('donot.sho.pndng.cst');
Route::get('/Pndngonly/', 'App\Http\Controllers\OTController@PndngOnly')->name('pndng.only');
Route::get('/unvisited/set', 'App\Http\Controllers\OTController@ShowUnvisitedSet')->name('show.unvisited.set');
Route::get('/stockproducts','App\Http\Controllers\ProductController@checkstock')->name('products.stock');
Route::get('/allSellers','App\Http\Controllers\SellerController@index')->name('all.sellers');
//Orders
Route::group(['prefix' => 'order'] , function(){
Route::get('/dashboard', 'App\Http\Controllers\OTController@index')->name('ot_dashboard.orderstaker');
Route::get('/orderDetails/{id?}','App\Http\Controllers\OrderController@getorderDetail')->name('order.detail');
Route::get('/customerDetails/{id?}','App\Http\Controllers\CustomerController@getcustomerDetail')->name('customer.detail');
Route::get('/orderDetailsMultiples','App\Http\Controllers\OrderController@getorderDetailMultiples')->name('order.detail.multiple');
Route::get('/createOrder/{id?}/{lc?}','App\Http\Controllers\OrderController@createOrder')->name('create.order');
Route::get('/create/Order/by/customer','App\Http\Controllers\OrderController@CreateOrderByCustomer')->name('create.order.by.customer');
Route::get('/call/update', 'App\Http\Controllers\CustomerController@setcallstatus')->name('users.update.call');

Route::get('/DoNotShow/update', 'App\Http\Controllers\CustomerController@DoNotShow')->name('users.update.do_not_call');
Route::get('/storesellerreport/{id?}','App\Http\Controllers\OrderController@storesellerreport')->name('store.seller.report'); 
Route::get('/deletestoresellerreport/{id?}','App\Http\Controllers\OrderController@deletestoresellerreport')->name('delete.seller.report'); 
Route::get('/Use/Stock/Set', 'App\Http\Controllers\OrderController@SetStock')->name('set.stock');
Route::get('/Pick/Order/Set', 'App\Http\Controllers\OrderController@SetPickOrder')->name('pick.order');
Route::match(array('GET','POST'),'/storeOrder','App\Http\Controllers\OrderController@storeOrder')->name('store.order');
Route::match(array('GET','POST'),'/allOrders','App\Http\Controllers\OrderController@getAllOrders')->name('all.orders');
//   Route::group(['middleware' => 'sellercheck'] , function(){
Route::match(array('GET','POST'),'/unconfirmedOrders','App\Http\Controllers\OrderController@getUnconfirmedOrders')->name('unconfirmed.orders');
// });
Route::match(array('GET','POST'),'/sellerConfirmedOrders','App\Http\Controllers\OrderController@getSellerConfirmedOrders')->name('confirmed.orders.seller');
Route::match(array('GET','POST'),'/sellerProcessedOrders','App\Http\Controllers\OrderController@getSellerProcessedOrders')->name('processed.orders.seller');

Route::match(array('GET','POST'),'/ConfirmedOrders','App\Http\Controllers\OrderController@getConfirmedOrders')->name('confirmed.orders');
Route::match(array('GET','POST'),'/ThisConfirmedOrders/{type?}/{date?}','App\Http\Controllers\OrderController@getThisConfirmedOrders')->name('get.confirmed.order');
Route::match(array('GET','POST'),'/verify/order/{type?}/{date?}','App\Http\Controllers\OrderController@VerifyOrder')->name('verify.order');
Route::get('/ajax/get-fitered-confirm-seller-order','App\Http\Controllers\OrderController@getFilteredSellerConfirmedOrders')->name('filter-by-seller');
});
//Invoices
Route::get('/newInvoice','App\Http\Controllers\InvoiceController@newInvoice')->name('add.invoice');

Route::group(['prefix' => 'invoice'] , function(){
Route::get('/manageStock','App\Http\Controllers\InvoiceController@manageStock')->name('manage.stock');
Route::get('/allInvoices/{get_unapproved?}','App\Http\Controllers\InvoiceController@index')->name('invoices');
Route::get('/unApprovedStockInvoices','App\Http\Controllers\InvoiceController@unApprovedStockInvoices')->name('unApproved.Stock.Invoices');
Route::get('/invoiceDetails/{id?}','App\Http\Controllers\InvoiceController@getinvoiceDetail')->name('invoice.detail');
Route::get('/stockDetails/{id?}','App\Http\Controllers\InvoiceController@getstockDetail')->name('stock.detail');
Route::get('/invoiceDetailsMultiples','App\Http\Controllers\InvoiceController@getinvoiceDetailMultiples')->name('invoice.detail.multiple');
Route::post('/storeInvoice','App\Http\Controllers\InvoiceController@storeInvoice')->name('store.invoice');
Route::post('/updateInvoice/{id}','App\Http\Controllers\InvoiceController@updateInvoice')->name('update.invoice');
Route::get('/getInvoice/{id}','App\Http\Controllers\InvoiceController@getInvoice')->name('edit.invoice');
Route::get('/getdatedInvoice/{date?}','App\Http\Controllers\InvoiceController@getdatedInvoice')->name('dated.invoice');
Route::post('/searchByDate/{unapproved?}','App\Http\Controllers\InvoiceController@dateFilter')->name('date.filter');
});   
Route::group(['prefix'=>'customer','middleware' => 'auth:api'], function () {
});
Route::get('/sendemail', 'App\Http\Controllers\SendEmailController@index');
Route::post('/sendemail/send', 'App\Http\Controllers\SendEmailController@send');

Route::get('/AddPosSale', [App\Http\Controllers\PosController::class, 'GetPosData'])->name('Add.Pos.Sale');

Route::get('/GetPosAjaxPrices/{id?}', [App\Http\Controllers\PosController::class, 'GetPosAjaxPrices'])->name('Get.Pos.Ajax.Prices');
Route::post('/StorePosSale', [App\Http\Controllers\PosController::class, 'StorePosSale'])->name('Store.Pos.Sale');
Route::post('/addPosSale', [App\Http\Controllers\PosController::class, 'addPosSale'])->name('add.PosSale');
Route::get('/GetPosUncnfirmedSale', [App\Http\Controllers\PosController::class, 'GetPosUncnfirmedSale'])->name('Get.Pos.Uncnfirmed.Sale');
Route::get('/GetPosManagerSale', [App\Http\Controllers\PosController::class, 'GetPosManagerSale'])->name('Get.PosManager.Sale');
Route::get('/GetPosSaleDeatils/{id?}', [App\Http\Controllers\PosController::class, 'GetPosSaleDeatils'])->name('Get.Pos.Sale.Deatils');
Route::post('/ApprovePosSale', [App\Http\Controllers\PosController::class, 'ApprovePosSale'])->name('Approve.Pos.Sale');
Route::get('/GetCustomerCashReceivings', [App\Http\Controllers\PosController::class, 'GetCustomerCashReceivings'])->name('Get.Customer.Cash.Receivings');
Route::post('/AddPaymentToAdmin', [App\Http\Controllers\PosController::class, 'AddPaymentToAdmin'])->name('Add.Payment.To.Admin');
Route::get('/GetIdsOrder/{id}', [App\Http\Controllers\PosController::class, 'GetIdsOrder'])->name('Get.Ids.Order');
Route::get('/PassSellerCords', [App\Http\Controllers\AttendanceController::class, 'CheckIn'])->name('pass.seller.cords');
Route::post('/PassSellerimage', [App\Http\Controllers\AttendanceController::class, 'SellerImage'])->name('seller.img');
Route::get('/GetAttendenceRecord/{id}', [App\Http\Controllers\AttendanceController::class, 'GetAttendenceRecord'])->name('Get.Attendence.Record');

Route::get('/BtnHandle', [App\Http\Controllers\AttendanceController::class, 'BtnHandle'])->name('Btn.Handle');
Route::get('/HandleForceCheckout', [App\Http\Controllers\AttendanceController::class, 'HandleForceCheckout'])->name('Handle.Force.Checkout');
Route::get('/againCheckout/{id?}', [App\Http\Controllers\AttendanceController::class, 'againCheckout'])->name('again.Checkout');
Route::get('/apdateAttandenceRecord', [App\Http\Controllers\AttendanceController::class, 'updateAttendenceRecord'])->name('update.attandence.record');

Route::get('/attandenceDetail/{id?}', [App\Http\Controllers\AttendanceController::class, 'getAttanceDetail'])->name('attandence.detail');

Route::get('/AddCustomerEmployee', [App\Http\Controllers\CustomerEmployeeController::class, 'AddCustomerEmployee'])->name('Add.Customer.Employee');
Route::get('/EditCustomerEmployee/{id}', [App\Http\Controllers\CustomerEmployeeController::class, 'GetCustomerSellerToEdit'])->name('Edit.Customer.Employee');
Route::get('/deleteCustomerEmployee/{id}', [App\Http\Controllers\CustomerEmployeeController::class, 'deleteCustomerEmplyee'])->name('delete.Customer.Employee');
Route::post('/StoreUser', [App\Http\Controllers\CustomerEmployeeController::class, 'StoreUser'])->name('Store.User');
Route::get('/GetCustomerSeller', [App\Http\Controllers\CustomerEmployeeController::class, 'GetCustomerSeller'])->name('Get.Customer.Seller');
Route::get('/GetCustomerManager', [App\Http\Controllers\CustomerEmployeeController::class, 'GetCustomerManager'])->name('Get.Customer.Manager');
Route::get('/GetEmployeeSellary/{id}', [App\Http\Controllers\EmployeeSellaryController::class, 'getEmployeeSellary'])->name('get.Employee.Sellary');
Route::post('/paysellersellery', [App\Http\Controllers\EmployeeSellaryController::class, 'paySellerSellary'])->name('pay.seller.sellery');
Route::get('/StoreNewCstmrLocation','App\Http\Controllers\CustomerController@store_new_cstmr_location')->name('store.new.cstmr.location');
Route::get('/GetNewCstmrLocation','App\Http\Controllers\CustomerController@getNewCustomerLocation')->name('get.new.cstmr.location');
Route::get('/posSale','App\Http\Controllers\OrderController@posSale')->name('pos.Sale'); 
Route::match(array('GET','POST'),'/getOrderTakerPosOrders','App\Http\Controllers\OrderController@getOrderTakerPosOrders')->name('get.Order.Taker.Pos.Orders');

});
Route::group(['prefix' => 'receipts'] , function(){
    Route::get('/receiptSettings', [App\Http\Controllers\ReceiptSettingsController::class, 'receiptSettings'])->name('receipt.Settings');
    Route::post('/storeReceipt', [App\Http\Controllers\ReceiptSettingsController::class, 'storeReceipt'])->name('store.Receipt');

});


//open routes
Route::get('/GetStockInvoices','App\Http\Controllers\InvoiceController@GetStockInvoices')->name('Get.Stock.Invoices');
Route::get('/approveSellerStock','App\Http\Controllers\InvoiceController@approveSellerStock')->name('approve.Seller.Stock');
