<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Customer;
use App\Models\CustomOtBenefit;
use App\Models\CustomPrice;
use App\Models\Invoice;
use App\Models\Seller;
use App\Models\Order;
use App\Models\OtCustomer;
use App\Models\Product;
use App\Repositories\Common;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Ordertaker;
use App\Models\SubAdmin;
use App\Models\StoreCstmrLocation;
use App\Models\DefualtOrder;
use App\Http\Controllers\OrderController;

use DateTime;

class CustomerController extends BaseController
{

    public function getCustomers()
    {
        $id = [Auth::id()];

        if (Auth::user()->role == 5) {
            $alloewd_cst_ot = Ordertaker::where('user_id', Auth::id())->pluck('ot_customer_allowed')->first();// gets allowed ot of other ot
            $allowed_ot = explode('|', $alloewd_cst_ot);
            $creater_ids = array_merge($id, $allowed_ot);// merge auth id and allowed ots
            
        } else if (Auth::user()->role < 3) {
            $creater_ids = $this->getAdminOt(); // if admin login get all customer of his ots and own
        }elseif (Auth::user()->role == 3){
            $seller = Seller::where('seller_id' , Auth::id())->first();
            if(!empty($seller->allowed_seller)){
                $creater_ids = $this->getAdminOt();    
            }else{
                return response()->json([
                'code' => '404',
                'status' => 'Failed',
                'message' => 'Not Allowed!',
                'data' => ''
            ]);
            }
            
        }
        $otCustomer = OtCustomer::whereIn('ot_id' , $creater_ids)->pluck('customer_id')->toArray();

        $customers = Customer::join('users', 'users.id', '=', 'customers.user_id')
            ->join('areas', 'areas.id', '=', 'customers.area_id')
            ->leftJoin('invoices', 'invoices.customer_id', '=', 'customers.id')
            ->leftJoin('orders', 'orders.customer_id', '=', 'customers.id')
            ->join('users as admin', 'admin.id', '=', 'customers.created_by')
            ->whereIn('customers.id' , $otCustomer)
            ->orWhereIn('customers.created_by', $creater_ids)
            ->groupBy('customers.id');

           $customers = $customers->select(
            'users.name',
            'users.email',
            'customers.id',
            'customers.customer_name as shop_name',
            'customers.user_id',
            'customers.visit_date',
            'customers.phone',
            'customers.address',
            'customers.location_url as customer_location',
            'customers.call_customer',
            'customers.last_order_date',
            'customers.location_url',
            'customers.created_at',
            'customers.updated_at',
            'customers.visit_date',
            'customers.area_id',
            'areas.name as area_name',
            'customers.created_by',
            'admin.name as creator_name',
            'customers.id',
            DB::raw('(SELECT COUNT(invoices.id) FROM invoices WHERE invoices.customer_id = customers.id) as num_invoices'),
            DB::raw('(SELECT COUNT(orders.id) FROM orders WHERE orders.customer_id = customers.id) as num_orders'),
            DB::raw('(SELECT amount_left FROM invoices WHERE invoices.customer_id = customers.id ORDER BY invoices.id DESC LIMIT 1) as last_invoice_amount_left'),
            DB::raw('(SELECT amount_left FROM orders WHERE orders.customer_id = customers.id ORDER BY orders.id DESC LIMIT 1) as last_order_amount_left')
        )->get();

        return response()->json([
            'code' => '200',
            'status' => 'success',
            'message' => 'Data Fetch Successfully!',
            'data' => $customers
        ]);

    }

    // public function getAdminOt(){
    //     $auth_id = Auth::id();
    //     if(Auth::user()->role < 3 ){
    //     $user = $auth_id;
    // }elseif(Auth::user()->role == 5 ){
    //     $user =  User::where('id' , Auth::id())->first()->ot_of;
    // }elseif(Auth::user()->role == 3){
    //     $user = Auth::user()->seller_of;
    // }
    // $ots = User::where('ot_of' , $user)->orWhere('id' , $user)->pluck('id')->toArray();
    //  array_push($ots ,$auth_id);
    //  return array_unique($ots);
    // }


    protected function getareasofaminandots(){

        $ids = [Auth::id()];
        $adminid = [User::where('id' , Auth::id())->pluck('ot_of')->first()]; //this uses ordertaker to get his admin
        if (Auth::user()->role == 2){
            $adminots = User::where('ot_of' , $ids)->pluck('id')->toArray();// this combines admin ot and admin id 
            $adminots = array_unique(array_merge($ids , $adminots));
           // dd($adminots);
        }
        else{
        $adminots = User::where('ot_of' , $adminid)->pluck('id')->toArray();// this combines admin ot and admin id 
        $adminots = array_unique(array_merge($ids , $adminots ,$adminid));
        }
        //dd($adminots); 
        return  Area::whereIn('created_by' , $adminots)->get();//this get all areas of same admin and his ot
    }

    public function index(Request $req , $areaids = NULL , $lc = NULL )
    {
       // dd($req->all());
        $ids = [Auth::id()];
        $areas = $this->getareasofaminandots();
        //dd($areas);
        $stord_ids = Ordertaker::where('user_id' , Auth::id())->pluck('stored_ids')->first();
        $stord_ids = json_decode($stord_ids, TRUE);
       // dd($lc);
      
        if ($areaids == "assigned_customer"){
             $customers = Customer::where('created_by' , Auth::id())->whereNotNull('transfer_by');
        }
          elseif ($areaids == "last_30_days_customer" && $lc != NULL){
             $monthdata = \Carbon\Carbon::today()->subDays($lc);
           //dd($monthdata);
             $customers = Customer::whereIn('created_by' , $adminots)->whereDate('created_at','>=',$monthdata);

             //dd($customers);
        }
        elseif($stord_ids != null && $areaids == NULL){ // if user has stored ids in 
        $ids_ordered = implode(',', $stord_ids);
        $customers = Customer::whereIn('id', $stord_ids)->orderByRaw("FIELD(id, $ids_ordered)");
        }

        else
        {
        if (Auth::user()->role == 5){
         $alloewd_cst_ot = Ordertaker::where('user_id' , Auth::id())->pluck('ot_customer_allowed')->first();// gets allowed ot of other ot
         $allowed_ot = explode('|', $alloewd_cst_ot);
         $arr = array_merge($ids , $allowed_ot);// merge auth id and allowed ots
         $otcustomers = OtCustomer::whereIn('ot_id', $arr)->pluck('customer_id')->toArray();// gets arr customer ids
         $cstomer_created = Customer::where('created_by' , Auth::id())->pluck('id')->toArray();
         $otcustomers = array_unique(array_merge($otcustomers , $cstomer_created));

         //dd($otcustomers);
        }
        else if (Auth::user()->role < 3){
            $arr = array_merge($ids, User::where('ot_of', Auth::id())->pluck('id')->toArray()); // if admin login get all customer of his ots and owns
           // dd($arr);
        }
        else{}
        
        if($areaids != NULL){
         $areaids = explode(',', $areaids);// sperate areas 
         $getcordslast = explode(',', $lc);
         $cords = @$getcordslast[0];
         $cords1 = @$getcordslast[1]; // sperate locations
         if (Auth::user()->role == 5){
                 $customers = Customer::whereIn('id', $otcustomers)->whereIn('area_id' , $areaids)->pluck('id')->toArray();// gets customer    
                if ($customers == null){
                     return redirect()->back()->with('error', 'No Customer Found In This Area');
           
                }
          }else{
              
         $customers = Customer::whereIn('created_by', $arr)->whereIn('area_id' , $areaids)->pluck('id')->toArray();// gets arr customer ids
        // dd($customers);
         if ($customers == null){
                     return redirect()->back()->with('error', 'No Customer Found In This Area');
           
                }
           }
          
        }
        else{// if user dont pass area and location
            if (Auth::user()->role == 5){
                 $customers = Customer::whereIn('id', $otcustomers)->pluck('id')->toArray();// gets customer    
               
            }
            else{
               $customers = Customer::whereIn('created_by', $arr)->pluck('id')->toArray();// gets customer    
            }
        }
        
        if (empty($cords)){
            $cords = "31.5815886";
            $cords1 = "74.3779746";
        }
            
         $shortestids = $this->sorteddata($cords ,$cords1 , $customers);
         if ($shortestids != 'empty') {
                $ids_ordered = implode(',', $shortestids);
            }else{
                $shortestids = [];
                $ids_ordered = '';
            }

         $customers = Customer::whereIn('id', $shortestids)
         ->orderByRaw("FIELD(id, $ids_ordered)");
        }
        
        if (!empty($ids_ordered)){
        $c_id = $customers->pluck('id')->toArray();
        }
        if (Auth::user()->role == 5){
        $customers = $customers->where('ot_del_customer' , 0)->paginate(300);
        $shortestids = "";
        }
        else{
             if (empty($ids_ordered)){
                 return redirect()->back()->with('error', 'Customer Not Fount');
             }
             else{
            $customers = $customers->paginate(500);
             }
        }
        if(!empty($c_id)){
            foreach($c_id as $a){
            $balance[] = Invoice::where('customer_id', $a)->pluck('amount_left')->last();
            }
        }else{
            $balance[] = 0;
        }
        $total_credit = array_sum($balance);
        if (Auth::user()->role == 2){
        
            $subadmins = User::where('id' , Auth::id())->pluck('user_of')->first();
            $subadmins = User::where('id' , $subadmins)->select('id' , 'name')->get();
            
            
        }
        else{
            $subadmins = User::where('user_of' , Auth::id())->select('id' , 'name')->get();
        
        }
        //dd(array_unique($customers->pluck('area_id')->toArray()));
        //dd($subadmins);
        // if($req->area == 'yes' || empty($req->area))
        // {
        //     $customers = $customers->get();
        // }
        // else{
        //     $customers = $customers->where('area_id', $req->area)->get();
        // }
        //dd(OtCustomer::where('ot_id' , Auth::id())->pluck('customer_id')->toArray());
        return view('customers.all_customers', compact('customers' , 'areas' , 'balance' , 'total_credit' , 'shortestids' , 'subadmins'));
    }
     public function filtercallcustomer (){
        
        $ids = [Auth::id()];
        $adminid = User::where('id' , Auth::id())->pluck('ot_of')->first(); //this uses ordertaker to get his admin
        $adminots = User::where('ot_of' , $adminid)->pluck('id')->toArray();// this combines admin ot and admin id 
        $customers = Customer::whereIn('created_by' , $adminots)->where('call_customer' , 1)->paginate(50);

        $areas = $this->getareasofaminandots();
        // gets arr customer ids
        return view('customers.call_customers', compact('customers' , 'areas'));

         
     }
        
        
    public function sorteddata($cords = NULL ,$cords1 = NULL , $idss = NULL){



        //Customer::where('location_url' , null)->update(['location_url' => "31.5815886,74.3779746"]);
        //Customer::where('location_url' , 0)->update(['location_url' => "31.5815886,74.3779746"]);
        //Customer::where('area_id' , null)->update(['area_id' => "199"]);
      
        
        $new_ids = "empty";
        if ($cords == NULL && $cords1 == NULL){
        $cords = 31.5815886;
        $cords1 = 74.3779746;
        }
        //dd($idss);
        $customersids = $idss; // this id should be updated for looping
        //dd($customersids);
        if (sizeof($customersids) > 3000){
            $loop = 3000;
        }
        else{
            $loop = sizeof($customersids);
        }
        for ($a = 0; $a < $loop ; $a++){
         $customerscords = Customer::whereIn('id', $customersids)->where('location_url' , "!=" , NULL)->where('location_url' , "!=" , 0 )->pluck('location_url')->toArray();
       //dd($customerscords);
        $shortest = array();
        foreach ($customerscords as $short){
         $shortest[] = $this->getsortedDistance($cords , $cords1 ,$short);
        }

        //dd($shortest , $customersids);
        $maping = array();
        for($i = 0; $i < sizeof($customersids); $i++){
            $maping[$customersids[$i]] =  $shortest[$i];
        }
        $key_val = array();
        // if ($a == 323){
        //     dd($key_val , $maping);
        // }
        if (!empty($maping)){
            $key_val = array_keys($maping, min($maping));// this gets keys and shortest value
        }
            
        if( $new_ids == "empty"){ // this stores srtoed ids
            $new_ids = $key_val;
        }
        else{
         $new_ids = array_merge($new_ids , $key_val);
        }
        $get_last_id = end($new_ids);
        $getcordslastid = Customer::where('id' , $get_last_id)->pluck('location_url')->first();
        $getcordslast = explode(',', $getcordslastid);
        $cords = @$getcordslast[0];
        $cords1 = @$getcordslast[1];
        $customersids = array_diff($customersids , $new_ids); // this sets customer ids for looping
        $customersids = array_values($customersids);
        }
           return $new_ids;
    }
     private function getsortedDistance($cords , $cords1 ,$short){
        
        $cLat1 = $cords;
        $cLat2 = $cords1;

        $customerLocation = explode(',', $short);
        $otLat1 = @$customerLocation[0];
        $otLat2 = @$customerLocation[1];

        return $this->distance($cLat1, $cLat2, $otLat1, $otLat2, 'K');

    }

    public function indexAll($admin_id)
    {
       $ids = User::where('ot_of', $admin_id)->pluck('id')->toArray();
        $ids = [$admin_id , $ids];
        //dd($ids);
        $customers = Customer::whereIn('created_by', $ids )->get();
        $subadmin_name = User::find($admin_id)->name;
       // dd($subadmin_name);
        
        $areas = Area::whereIn('id', Customer::whereIn('id', $customers->pluck('customer_id'))->pluck('area_id')->toArray())->get();
        return view('customers.all_customers', compact('customers', 'subadmin_name', 'areas'));
    }

    public function myCustomers($user_id)
    {
        $customers = Customer::where('created_by', $user_id)->with('User')->get();
        return Common::Data($customers) ? Common::Data($customers) : Common::Message("Customer");
    }

    public function addCustomer()
    {
        // $customer = Customer::pluck('id')->toArray();
        // $var = 0;
        // foreach($customer as $c){
        //     $v = OtCustomer::where('customer_id', $c)->pluck('customer_id')->count();
        //     if ($v >= 1){
                
        //     }else{
        //         $cs[] = $c;
        //         $var = $var + 1;
        //     }
        //     $a = Customer::where('id' , $c)->pluck('created_by')->toArray();
        //     foreach($a as $b){
        //         $store = new OtCustomer();
        //         $store->ot_id = $b;
        //         $store->customer_id = $c;
        //         $store->save();
        //     }
        // }
        // dd($var);
        $ordertaker = Ordertaker::where('user_id' , Auth::id())->pluck('allow_create_customer')->first();
        if (Auth::user()->role == 5 && $ordertaker == 0) {
            return redirect()->route('all.customers')->with('error', 'Your Access is Restricted!');

        }
        
        $otowns = User::findOrFail(Auth::id());
        $otowner = $otowns->ot_of;
        $otid = $otowns->id;
        $adminotarea = [$otowns->ot_of , $otowns->id];
        //dd($adminotarea);
        $areas = Area::whereIn('created_by' , $adminotarea)->get();
        $ordertaker =  Ordertaker::where('user_id', Auth::id());
        if (Auth::user()->role == 5) {
            $allowed_products = CustomOtBenefit::where('ot_id', Auth::user()->id)->pluck('product_id')->toArray();
            // print_r($allowed_products);
            // exit;
            $check = Auth::user()->is_ot_custom;
            if (Auth::user()->is_ot_custom == 1) {

                $products = DB::table('products')
                    ->join('custom_ot_benefits', 'products.id', 'custom_ot_benefits.product_id')
                    ->where('custom_ot_benefits.ot_id', Auth::user()->id)
                    ->whereIn('product.id', $allowed_products)
                    ->orderBy('category_id', 'asc')
                    ->select('products.*', 'custom_ot_benefits.ot_benefit')
                    ->get();

                    if(empty($products->first())){
                        return redirect()->back()->with('error', 'No Product Found Please Ask Admin to Allow At least 1 Product!');
                    }

                return view('customers.create_customer', compact('products', 'areas'));
            } else {
                $products = Product::whereIn('id', $allowed_products)->orderBy('category_id', 'asc')->get();
                 if(empty($products->first())){
                        return redirect()->back()->with('error', 'No Product Found Please Ask Admin to Allow At least 1 Product!');
                    }
                return view('customers.create_customer', compact('products', 'areas'));
            }
        } else {
            $products = Product::where('user_id', Auth::id())->orderBy('category_id', 'asc')->get();
             if(empty($products->first())){
            return redirect()->route('add.product')->with('error', 'Please Add at Least 1 Product First!');
        }
            return view('customers.create_customer', compact('products', 'areas'));
        }
    }

    public function storeCustomer(Request $request)
    {
       //dd($request->all());


         try{

            DB::beginTransaction();
        if ($request->image) {
            $request->validate([
                'image' => 'required|max:2000|mimes:jpg,jpeg,png,PNG',
            ]);
        }
       

        $customer = new User();
        $customer->name = $request->customer_name;
        $customer->email = $this->generateRandomString()."@scoops.com";
        $customer->email_verified_at = date('Y-m-d H:i:s');
        $customer->password = '123456789';
        $customer->password_string = '123456789';
        $customer->role = 4;
        $customer->save();

        $new_ot_id = User::with('syncCustomerProduct')->find($customer->id);
        $new_ot_id->syncCustomerProduct()->sync($request->final_allowed_products);

        $new_user_id = $customer->id;

        $customerData = new Customer();
        $customerData->user_id = $new_user_id;
        $customerData->customer_name = $request->customer_name;
        $customerData->customer_name = $request->shop_name;
        if (!empty($request->findarea)){
            $customerData->area_id = $request->findarea;
        }
        else{
        $customerData->area_id = $request->area;
            
        }
        if(!empty($request->customCords)){
            $customerData->location_url = $request->customCords;
        }else{
            $customerData->location_url = $request->location_url;
        }
        $customerData->created_by = Auth::id();
        $customerData->address = $request->address;
        $customerData->phone = $request->phone;
        $customerData->cnic = $request->cnic;
        $customerData->balance_limit = $request->balance_limit ?: 0;
        $customerData->freezer_model = $request->freezer_model;
        $customerData->other = $request->other;
        $customerData->payment_method = $request->payment_method;
        $customerData->customer_request = $request->customer_request;
        $customerData->suggestion_in_email = $request->suggestion_in_email;
        $customerData->allowed_products = implode('|', $request->allowed_products);
        $customerData->final_allowed_products = implode('|', $request->final_allowed_products);

        if ($request->image) {
            $img = $request->image;
            $upload_image = time() . $img->getClientOriginalName();
            $img->move('images/agreements', $upload_image);
            $customerData->image = 'images/agreements/' . $upload_image;
        }

        $customerData->save();

        User::where('id', $new_user_id)->update(['customer_id' => $customerData->id]);

        $productData = $request->all();
        if (!empty($request->product_id)) {
            for ($i = 0; $i < sizeof($request->product_id); $i++) {
                $customP = new CustomPrice();
                $customP->customer_id = $customerData->id;
                $customP->product_id = $productData['product_id'][$i];
                $customP->price = $productData['price'][$i];
                $customP->sell_price = $productData['sell_price'][$i];
                // $customP->p_price = $productData['p_price'][$i];
                $customP->a_benefit = $productData['a_benefit'][$i];
                $customP->c_benefit = $productData['c_benefit'][$i];
                $customP->ot_benefit = $productData['ot_benefit'][$i];
                $customP->save();
            }
        }

        //if (Auth::user()->role == 5) {
            $customerOT = new OtCustomer();
            $customerOT->ot_id = Auth::id();
            $customerOT->customer_id = $customerData->id;
            $customerOT->save();
        //}
    if($request->defualtOrder){
                

        $user = Auth::id();
        if(Auth::user()->role == 5){
            $user = Auth::user()->ot_of;
        }
         $DefualtOrders = DefualtOrder::where('user_id' , $user);
         if($DefualtOrders->exists() == true){
            foreach($DefualtOrders->get() as $DefualtOrder){
            $pr = CustomPrice::where(['customer_id' =>  $customerData->id, 'product_id' =>  $DefualtOrder->product_id]);
            if($pr->exists()){
                 $priceGet  = $pr->first()->price;
            }else{
                $priceGet   = Product::where(['id' =>  $DefualtOrder->product_id])->first()->price;
            }
            $product_id[]     = $DefualtOrder->product_id;
            $price[]        = $priceGet;
            $unit[]         = $DefualtOrder->unit;
            $amount[]       = $priceGet * $DefualtOrder->unit;
         }
         $date = new DateTime(); // create a DateTime object representing the current date and time
        $date->modify('+1 day'); // add 1 day to the date
        $new_date_str = $date->format('Y-m-d'); // format the date as 'Y-m-d' 

        $data = new Request
        ([
        'product_id'  =>$product_id,
        'price'       =>$price,
        'unit'        =>$unit,
        'amount'      =>$amount,
        'customer_id' => $customerData->id.'-'.$customerData->user->name,
        'ot_id'       => $user,
        'old_balance' => 0,
        'discount'    => 0,
        'location_url_ot' => $customerData->location_url,
        'advance'     => 0,
        'order_date'  => $new_date_str
        ]);
        $get_order_class = new OrderController();
        $order = $get_order_class->storeOrderNew($data);
                
            }
        }
        DB::commit();
        return response()->json(['message' => 'Customer Saved!']);
        

     }catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

               return response()->json(['message' => 'Something Went Wrong!']);
        }
    }

    public function getCustomer($id)
    {
        $getotid = Customer::where('id' , $id)->pluck('created_by')->first();
        $gethisadmin = [Auth::id()];
        if (Auth::user()->role < 3) {
            $admin_ot = User::where('ot_of', $gethisadmin)->pluck('id')->toArray();
             $ids =  array_merge($gethisadmin, $admin_ot);
              $areas = Area::whereIn('created_by', $ids)->select('id' , 'name')->get();
        }elseif(Auth::user()->role == 5){
            $admin_ot = User::where('id', $gethisadmin)->pluck('ot_of')->first();
            $admin_ot = User::where('ot_of', $admin_ot)->pluck('id')->toArray();

             $ids =  array_merge($gethisadmin, $admin_ot);
              $areas = Area::whereIn('created_by', $ids)->select('id' , 'name')->get();
        }
        
        $customer = Customer::find($id);
        if (Auth::user()->role == 5) {
            $allowed_products = CustomOtBenefit::where('ot_id', Auth::user()->id)->pluck('product_id')->toArray();
            $check = Auth::user()->is_ot_custom;
            if (Auth::user()->is_ot_custom == 1) {

                $products = DB::table('products')
                    ->join('custom_ot_benefits', 'products.id', 'custom_ot_benefits.product_id')
                    ->where('custom_ot_benefits.ot_id', Auth::user()->id)
                    ->whereIn('product.id', $allowed_products)
                    ->orderBy('category_id', 'asc')
                    ->select('products.*', 'custom_ot_benefits.ot_benefit')
                    ->get();

                return view('customers.edit_customer', compact('customer' , 'products', 'areas'));
            } else {
                $products = Product::whereIn('id', $allowed_products)->orderBy('category_id', 'asc')->get();
                return view('customers.edit_customer', compact('customer' , 'products', 'areas'));
            }
        } else {
            
            $products = Product::where('user_id', Auth::user()->id)->orderBy('category_id', 'asc')->get();
            return view('customers.edit_customer', compact('customer' , 'products', 'areas'));
        }
    }

    public function updateCustomer(Request $request, $id)
    {
       // dd($request->all());

        try{

            DB::beginTransaction();

        $customer = Customer::where(['id' => $id])->get();
        if (sizeof($customer)) {
            $updateUser['name'] = $request->name;
            if ($request->password) {
                $updateUser['password'] = $request->password;
            }
            $updateCustomer = [
                'address'               => $request->address,  
                'phone'                 => $request->phone, 
                'customer_name'         => $request->customer_name,
                'transfer_by'           => NULL , 
                'area_id'               => $request->area, 
                'allowed_products'      => implode('|', $request->allowed_products), 
                'final_allowed_products'=> implode('|', $request->final_allowed_products),
                'cnic'                  => $request->cnic, 
                'freezer_model'         => $request->freezer_model, 
                'customer_name'         => $request->customer_name , 
                'other'                 => $request->other, 
                'location_url'          => $request->location_url, 
                'payment_method'        => $request->payment_method, 
                'customer_request'      => $request->customer_request,
                'suggestion_in_email'   => $request->suggestion_in_email, 
                'balance_limit' => $request->balance_limit];
            if ($request->image) {
                $img = $request->image;
                $upload_image = time() . $img->getClientOriginalName();
                $img->move('images/agreements', $upload_image);
                $updateCustomer['image'] = 'images/agreements/' . $upload_image;
            }
            if (empty(User::where('email', $request->email)->first())) {
                $updateUser['email'] = $request->email;
            }
            //dd($updateCustomer , $request->customer_name);
            User::where('id', $customer[0]->user_id)->update($updateUser);
            Customer::where('id', $id)->update($updateCustomer);

            $productData = $request->all();
            if ($request->this_id) {
                for ($i = 0; $i < sizeof($request->this_id); $i++) {
                    $customP = CustomPrice::find($request->this_id[$i]);
                    $customP->price = $productData['c_price'][$i];
                    $customP->sell_price = $productData['sell_price'][$i];
                    // $customP->p_price = $productData['p_price'][$i];
                    $customP->a_benefit = $productData['c_a_benefit'][$i];
                    $customP->c_benefit = $productData['c_c_benefit'][$i];
                    $customP->ot_benefit = $productData['ot_benefit'][$i];
                    $customP->save();
                }
            }
             $get_products = CustomPrice::where('customer_id' , $id)->pluck('id')->toArray();
             $pr[] = $request->this_id;
             if (!empty($get_products) && !empty($request->this_id)){
                $result = array_diff($get_products , $request->this_id); 
             }else{
                $result = null;
             }
                
                if($result == null  && $request->has('delete-custom-price')){
                    foreach($get_products as $a){
                    CustomPrice::find($a)->delete();
                }
                }
                elseif (!empty($result)){
                    foreach($result as $a){
                    CustomPrice::find($a)->delete();
                }
                }
                
                

            if (!empty($request->product_id)) {
                for ($i = 0; $i < sizeof($request->product_id); $i++) {
                    $customP = new CustomPrice();
                    $customP->customer_id = $id;
                    $customP->product_id = $productData['product_id'][$i];
                    $customP->price = $productData['price'][$i];
                    $customP->sell_price = $productData['sell_price'][$i];
                    // $customP->p_price = $productData['p_price'][$i];
                    $customP->a_benefit = $productData['a_benefit'][$i];
                    $customP->c_benefit = $productData['c_benefit'][$i];
                    $customP->ot_benefit = $productData['ot_benefit'][$i];
                    
                    $customP->save();
                }

            }

            $new_ot_id = User::with('syncCustomerProduct')->find($customer[0]->user_id);
            $new_ot_id->syncCustomerProduct()->sync($request->final_allowed_products);
            DB::commit();
            return Common::Message("Customer", 2);
        } else {
            DB::commit();
            return Common::Message("Customer");
        }

        }

     catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            return Common::Message("Customer");

        }
    }
    
    //update customer status
    public function updateStatus(Request $request)
        
        {
            $customer = Customer::find($request->user_id);
            $customer->status = $request->status;
            $customer->save();
            return response()->json(['message' => 'User status updated successfully.']);
        }
    
    public function storeids(Request $request)
        
        {
            $id = $request->ids;
            $ids = Auth::id();
            $customer = Ordertaker::where('user_id' , $ids)->update(['stored_ids' => $id]);
            return response()->json(['message' => 'Stored Route Successfully']);
        }
    public function deletestoreids(Request $request)
        
        {
            $ids = Auth::id();
            $customer = Ordertaker::where('user_id' , $ids)->update(['stored_ids' => null]);
            return response()->json(['message' => 'Delete Route Successfully']);
        }


    //update customer status
    public function updatemultipleareas(Request $request)
        
        {
            $deli = $request->get('user_id');
            foreach($deli as $us_id) {
            $customer = Customer::find($us_id);
            $customer->area_id = $request->areaselected;
            $customer->save();
            }
            return response()->json(['message' => 'User status updated successfully.']);
            
        }
         public function changecustomeradmin(Request $request)
        
        {
            $deli = $request->get('user_id');
            foreach($deli as $us_id) {
            $customer = Customer::find($us_id);
            $creater_anme = $customer->created_by;
            $customer->created_by = $request->adminselected;
            $subadmin = SubAdmin::find(SubAdmin::where('sub_admin_id' , $request->adminselected)->pluck('id')->first());
            $customer->area_id = $this->findsubAdminNearestArea($request->adminselected , $customer->location_url);
            $customer->allowed_products = "23|27|39|59|158|177";//$subadmin->assign_products;
            $customer->final_allowed_products = "23|24|27|28|29|30|31|32|39|59|158|177";//$subadmin->final_allowed_products;
            $customer->transfer_by = Auth::id();
            $customer->creater_name = User::where('id' , $creater_anme)->pluck('name')->first();
            $customer->save();
            OtCustomer::where('customer_id' , $us_id)->update(['ot_id' => $request->adminselected ]);
            }
            return response()->json(['message' => 'User admin change successfully.']);
            
        }
        
        protected function findsubAdminNearestArea($id , $cords){
            
            $getcordslast = explode(',', $cords);
            $cords = @$getcordslast[0];
            $cords1 = @$getcordslast[1]; // sperate locations
            $admin_id = [$id];
           
            if (Auth::user()->role < 3){
                   $get_users = User::where('ot_of' , $id)->pluck('id')->toArray();
            }
            
            $ids = array_unique(array_merge($admin_id , $get_users));
            $area_ids = Area::whereIn('created_by' , $ids)->pluck('id')->toArray();
            
            $customers = Customer::whereIn('created_by', $ids)->whereIn('area_id' , $area_ids)->pluck('id')->toArray();// gets customer    
            
            $shortestids = $this->sorteddata($cords ,$cords1 , $customers);
            $ids_ordered = implode(',', $shortestids);
            $customers = Customer::whereIn('id', $shortestids)->orderByRaw("FIELD(id, $ids_ordered)");
            $c_id = $customers->pluck('id')->first();
         
            $get_cst_cords = $customers->pluck('location_url')->first();
            $distance = ($this->getsortedDistance($cords , $cords1 , $get_cst_cords)) * 1000;
                      
            $get_nearest_area = Customer::where('id' , $c_id)->pluck('area_id')->first();// this is neraest area id
            return $get_nearest_area;
        }
        
        
    public function updatearea(Request $request)
        
        {
            $customer = Customer::find($request->user_id);
            $customer->area_id = $request->area;
            $customer->save();
            return response()->json(['message' => 'User status updated successfully.']);
        }
   
    private function distanceofot($lat1, $lon1, $lat2, $lon2, $unit = "K") {
   
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        else {
            $R = 6371; // km
            $dLat = $this->toRad($lat2-$lat1);
            $dLon = $this->toRad($lon2-$lon1);
            $lat1 = $this->toRad($lat1);
            $lat2 = $this->toRad($lat2);
    
            $a = sin($dLat/2) * sin($dLat/2) +sin($dLon/2) * sin($dLon/2) * cos($lat1) * cos($lat2); 
            $c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
            $d = $R * $c;
            return $d;
            
        }
    }
     

        // Converts numeric degrees to radians
    public function toRad($Value) 
    {
        return $Value * pi() / 180;
    }

    private function getDistance(Customer $customer){
        
        //$customer = $customerlocation;
        $customerlocation = $customer->location_url;
        $customerLocation = explode(',', $customerlocation);
        $cLat1 = @$customerLocation[0];
        $cLat2 = @$customerLocation[1];
        
        $otlocation = $customer->ot_location;
        //$getOTLocation = $otlocation;
        $customerLocation = explode(',', $otlocation);
        $otLat1 = @$customerLocation[0];
        $otLat2 = @$customerLocation[1];

        return $this->distanceofot($cLat1, $cLat2, $otLat1, $otLat2, 'K');

    }
     public function findNearestCustomer(Request $request)
        
        {
            $getcordslast = explode(',', $request->cords);
            $cords = @$getcordslast[0];
            $cords1 = @$getcordslast[1]; // sperate locations
            $admin_id = [Auth::id()];
            if (Auth::user()->role < 3){
                   $get_users = User::where('ot_of' , Auth::id())->pluck('id')->toArray();
                        $ids = array_unique(array_merge($admin_id , $get_users));
                       
     
            }
            else if (Auth::user()->role == 5){
                   $find_admin = [User::where('id' , Auth::id())->pluck('ot_of')->first()];
                    $get_users = User::where('ot_of' , $find_admin)->pluck('id')->toArray();
                    $ids = array_unique(array_merge($admin_id , $get_users , $find_admin));
                    
            }
             // dd($ids);
            $area_ids = Area::whereIn('created_by' , $ids)->pluck('id')->toArray();
            //dd($area_ids);
             $customers = Customer::whereIn('created_by', $ids)->whereIn('area_id' , $area_ids)->pluck('id')->toArray();// gets customer    
             if(empty($customers)){
                  return response()->json(['error'=> true, 'message' => 'No Customer Found chose or add new area' , 'data1' => 'failed' ]);
             }
            $shortestids = $this->sorteddata($cords ,$cords1 , $customers);
            $ids_ordered = implode(',', $shortestids);
            if ($ids_ordered != null){
            $customers = Customer::whereIn('id', $shortestids)->orderByRaw("FIELD(id, $ids_ordered)");
            //if ($customers == [])
            $c_id = $customers->pluck('id')->first();
         
            $get_cst_cords = $customers->pluck('location_url')->first();
            $distance = ($this->getsortedDistance($cords , $cords1 , $get_cst_cords)) * 1000;
                        // dd(User::w here('id' ,$c_id)->pluck('name')->toArray());
  
            $customer = Customer::where('id' , $c_id)->first(); 
            
            $area_name = Area::where('id' , $customer->area_id)->pluck('name')->first();
            $b = [$customer->area_id , 
                $area_name , 
                $distance , 
                $customer->user->name.'/'.$customer->customer_name ?? '' , 
                $customer->phone , 
                $customer->Address];
           // dd($b);
            if ($distance < 2000){
                return response()->json(['success'=> true, 'message' => 'Area Find Successfully' , 'data1' => $b ]); 
            }
            else{
                 return response()->json(['error'=> true, 'message' => 'No Nearest Area Found chose or add new area' , 'data1' => 'failed' ]); 
            }
            
            }
            else{
                 return response()->json(['error'=> true, 'message' => 'No Nearest Area Found chose or add new area' , 'data1' => 'failed' ]); 
            }
        }
       
   public function setcallstatus(Request $request)
        
        {
           // dd($request->call);
            $customer = Customer::findOrFail($request->user_id);
            if  ($customer->call_customer == null){
                $customer->call_customer = 1;
                $customer->save();
                return response()->json(['success'=> true, 'message' => 'Call Status Is True' ]); 
         
            }
            else{
                $customer->call_customer = null;
                $customer->save();
                return response()->json(['error'=> true, 'message' => 'Call Status Is False' ]); 
         
            }
            
            
            
        }
        public function DoNotShow(Request $request)
        
        {
           // dd($request->call);
            $customer = Customer::findOrFail($request->user_id);
            if  ($customer->do_not_show == 0){
                $customer->do_not_show = 1;
                $customer->do_not_show_date = date('Y-m-d H:i');
                $customer->save();
                return response()->json(['error'=> true, 'message' => 'Now Customer Will Not Be Shown In List!' ]); 
         
            }
            else{
                $customer->do_not_show = 0;
                $customer->save();
                return response()->json(['success'=> true, 'message' => 'Customer Will Be Show In List!' ]); 
         
            }
            
            
            
        }
   public function visitclear(Request $request)
        
        {
            $get_ot = User::where('id' , Auth::id());
            $get_allowed_distance = $get_ot->pluck('compare_ot_distance')->first();
            
            $customer = Customer::findOrFail($request->user_id);
            $customer->ot_location = $request->location;
            $visit_value = $customer->visit_clear;
            $var = ($this->getDistance($customer) * 1.37);
            $distance_in_meter = $var * 1000;
            //dd($distance_in_meter);
            // there is 2 condition paas one if user pass visit_clear == 1 and customer distance is < ot locaion and visit_clear value in db 0 an user also send id of ot
            
            if($request->visit_clear == 1){
                
                if($var <= 1 &&  $distance_in_meter <= $get_allowed_distance ){
                     if($visit_value == 0 && $request->ot_id)//  fails in all customer blade 
            {
                
            
            $ordertaker =  Ordertaker::where('user_id', $request->ot_id);
            $totalotben = $ordertaker->sum('ben_earned');
            $totalotvisit = $ordertaker->sum('total_visit');
            $totalotvisitprofit = $ordertaker->sum('total_visit_profit');
            $todayotvisitprofit = $ordertaker->sum('today_visit_profit');

            $totalotvisit = $totalotvisit + 1;
            $ot_visit_profit = User::findOrFail($request->ot_id);
            $ot_visit_profit = $ot_visit_profit->ot_visit_profit;
            $benefit = $totalotben + $ot_visit_profit;
            $totalotvisitprofit = $totalotvisitprofit + $ot_visit_profit;
            $todayotvisitprofit = $todayotvisitprofit + $ot_visit_profit;
            
            $updateData = ['ben_earned' => $benefit , 'total_visit' => $totalotvisit , 'total_visit_profit' => $totalotvisitprofit , 'today_visit_profit' => $todayotvisitprofit];
            Ordertaker::where('user_id', $request->ot_id)->update($updateData);
            $var1 = $var * 1000;
                $customer = Customer::findOrFail($request->user_id);
                $customer->visit_clear = 1;
                $customer->visit_date = date('Y-m-d H:i');
                $customer->ot_location = $var1."<br>".$request->location;
                $customer->save();
                return response()->json(['success'=> true, 'message' => 'Visit Clear Thanx' ]);
                
            }//  fails in all customer blade
            else if($visit_value == 0)
                {
            $totalotben = Ordertaker::where('user_id', Auth::id())->sum('ben_earned');
            $totalotvisit = Ordertaker::where('user_id', Auth::id())->sum('total_visit');
            $totalotvisitprofit = Ordertaker::where('user_id', Auth::id())->sum('total_visit_profit');
            $todayotvisitprofit = Ordertaker::where('user_id', Auth::id())->sum('today_visit_profit');
            
            $totalotvisit = $totalotvisit + 1;
            $ot_visit_profit = User::findOrFail(Auth::id());
            $ot_visit_profit = $ot_visit_profit->ot_visit_profit;
            $benefit = $totalotben + $ot_visit_profit;
            $totalotvisitprofit = $totalotvisitprofit + $ot_visit_profit;
            $todayotvisitprofit = $todayotvisitprofit + $ot_visit_profit;
            
            $updateData = ['ben_earned' => $benefit , 'total_visit' => $totalotvisit , 'total_visit_profit' => $totalotvisitprofit , 'today_visit_profit' =>                 
            $todayotvisitprofit];
            Ordertaker::where('user_id', Auth::id())->update($updateData);
            $var1 = $var * 1000;
                $customer = Customer::findOrFail($request->user_id);
                $customer->visit_clear = 1;
                $customer->visit_date = date('Y-m-d H:i');
                $customer->ot_location = $var1."<br>".$request->location;
                $customer->save();
                return response()->json(['success'=> true, 'message' => 'Visit Clear Thanx' ]);
                
            }
            
                }
                else{
                    return response()->json(['error'=> true, 'message'=> 'Visit Failed distance is too long']);
                     //return response()->json(['message' => 'Visit Failed Distance is too long' ]);
                }
            }
            else if($request->visit_clear == 0){
                
                if($visit_value == 1 && $request->ot_id)
             {
            $totalotben = Ordertaker::where('user_id', $request->ot_id)->sum('ben_earned');
            $totalotvisit = Ordertaker::where('user_id', $request->ot_id)->sum('total_visit');
            $totalotvisitprofit = Ordertaker::where('user_id', $request->ot_id)->sum('total_visit_profit');
            $todayotvisitprofit = Ordertaker::where('user_id', $request->ot_id)->sum('today_visit_profit');
            
            $totalotvisit = $totalotvisit - 1;
            $ot_visit_profit = User::findOrFail($request->ot_id);
            $ot_visit_profit = $ot_visit_profit->ot_visit_profit;
            $benefit = $totalotben - $ot_visit_profit;
            $totalotvisitprofit = $totalotvisitprofit - $ot_visit_profit;
            $todayotvisitprofit = $todayotvisitprofit - $ot_visit_profit;
            
            $updateData = ['ben_earned' => $benefit , 'total_visit' => $totalotvisit , 'total_visit_profit' => $totalotvisitprofit , 'today_visit_profit' => $todayotvisitprofit];
            Ordertaker::where('user_id', $request->ot_id)->update($updateData);
            $var1 = $var * 1000;
                $customer = Customer::findOrFail($request->user_id);
                $customer->visit_clear = 0;
                $customer->visit_date = date('Y-m-d H:i');
                $customer->ot_location = $var1."<br>".$request->location;
                $customer->save();
                   
            return response()->json(['error'=> true, 'message'=> 'Profit Reduce Try Again to Visit!']);
            }
            else if ($visit_value == 1 )
                {
            $totalotben = Ordertaker::where('user_id', Auth::id())->sum('ben_earned');
            $totalotvisit = Ordertaker::where('user_id', Auth::id())->sum('total_visit');
            $totalotvisitprofit = Ordertaker::where('user_id', Auth::id())->sum('total_visit_profit');
            $todayotvisitprofit = Ordertaker::where('user_id', Auth::id())->sum('today_visit_profit');
            
            $totalotvisit = $totalotvisit - 1;
            $ot_visit_profit = User::findOrFail(Auth::id());
            $ot_visit_profit = $ot_visit_profit->ot_visit_profit;
            $benefit = $totalotben - $ot_visit_profit;
            $totalotvisitprofit = $totalotvisitprofit - $ot_visit_profit;
            $todayotvisitprofit = $todayotvisitprofit - $ot_visit_profit;
            
            $updateData = ['ben_earned' => $benefit , 'total_visit' => $totalotvisit , 'total_visit_profit' => $totalotvisitprofit , 'today_visit_profit' => $todayotvisitprofit];
            
            Ordertaker::where('user_id', Auth::id())->update($updateData);
            $var1 = $var * 1000;
                $customer = Customer::findOrFail($request->user_id);
                $customer->visit_clear = 0;
                $customer->visit_date = date('Y-m-d H:i');
                $customer->ot_location = $var1."<br>".$request->location;
                $customer->save();
                return response()->json(['error'=> true, 'message'=> 'Profit Reduce Try Again to Visit!']);
               
            }
            
                       
            }
           
        }
        
        public function customerpending(Request $request)
        
        {
            $customer = Customer::findOrFail($request->user_id);
            $customer->customer_pending = $request->customer_pending;
           // $customer->visit_date = date('Y-m-d H:i');
            $customer->save();
            return response()->json(['message' => 'User status updated successfully.']);
        }
   
   // email check
   public function check(Request $request)
    {
     if($request->get('email'))
     {
      $email = $request->get('email');
      $data = DB::table("users")
       ->where('email', $email)
       ->count();
      if($data > 0)
      {
       echo 'not_unique';
      }
      else
      {
       echo 'unique';
      }
     }
    }



    public function deleteCustomer(Request $req)
    {
        $customer = Customer::where(['id' => $req->ids])->get();
        if (sizeof($customer) && Auth::user()->role < 3) {
            
            if (Invoice::where('customer_id', $req->ids)->orderBy('id' , 'desc')->pluck('amount_left')->first() == 0) {
                Customer::where('id', $req->ids)->delete();
                User::where('customer_id', $req->ids)->delete();
                Invoice::where('customer_id', $req->ids)->delete();
               return response()->json(['message' => 'Customer Deleted successfully.']);
            }
            
        }
         return response()->json(['message' => 'Something Went Wrong Deletion Failed!']);
    }
    
    
     public function deleteCustomerByOrderTaker($id)
    {
        $customer = Customer::findOrFail($id);
        // $customer->created_by = 4;
        $customer->ot_del_customer = 1;
        $customer->save();
        return Common::Message("Customer deletion request submitted successfully!");
    }
    public function restoreCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        // $customer->created_by = 4;
        $customer->ot_del_customer = 0;
        $customer->save();
        return Common::Message("Customer Restored successfully and Assigned to Its Creater!");
    }

    public function checkCustomPrice($customer_id)
    {
         $get_customer_order = Order::where('customer_id' , $customer_id )->whereNull('is_confirmed_admin')->orderBy('id', 'desc');

        if ($get_customer_order->exists() == true){
            $get_order =   $get_customer_order->first();
            if(empty($get_order->seller_processed_order)){
                
                 $editurl = url("order/geOrder") . '/' . $get_order->id;
                 $deleteurl = url("order/deleteOrder") . '/' . $get_order->id;
   
                return '1 Order Is Already Exist Ask your Admin to Approve it First Then You Can create New order! 
                <br> 
                <a href="'. $editurl .'" class="btn btn-primary" style="color:white">Edit Order</a>
                <a href="'. $deleteurl .'" class="btn btn-danger" style="color:white">Delete Order</a>';
            }
            
        }
        
        $parent = Auth::id();
        $customer = Customer::find($customer_id);
        $customer_priority = $customer->pluck('automatic_create_order')->first();
        $shortListed = explode('|', $customer->allowed_products);
        if (Auth::user()->role == 3) {
            $parent = Auth::user()->seller_of;
        }

        if (Auth::user()->role == 5 || Auth::user()->role == 4) {
            
            $custome_ot_products_allowed = CustomOtBenefit::where('ot_id', Auth::id())->pluck('product_id')
                    ->toArray();
            $products = Product::whereIn('id', $custome_ot_products_allowed)->orderBy('category_id', 'asc')
                ->whereIn('id', explode('|', $customer->final_allowed_products))->orWhere('allow_to_all_customer' , 1)->where('user_id' , Auth::user()->ot_of)->get();
        } else {
            
                $products = Product::where('user_id', $parent)->orderBy('category_id', 'asc')
                    ->whereIn('id', explode('|', $customer->final_allowed_products))->orWhere('allow_to_all_customer' , 1)->where('user_id' , Auth::id())->get();
            
        }
        


        $customPrices = CustomPrice::where('customer_id', $customer_id)->get();
        if (sizeof($customPrices)) {
            foreach ($products as $product) {
                foreach ($customPrices as $custom) {
                    if ($custom->product_id == $product->id) {
                        $product->price = $custom->price;
                        $product->c_benefit = $custom->c_benefit;
                        $product->sell_price = $custom->sell_price;
                    }
                }
            }
        }
        if (Auth::user()->role == 5) {
            $order_balance = Order::where([
                'customer_id' => $customer_id,
                'is_confirmed_admin' => null,
            ])->orderBy('id', 'desc')->first();
            if ($order_balance) {
                $old_balance = $order_balance->amount_left;
            } else {
                $old_balance = Invoice::where(['customer_id' => $customer_id])->orderBy('id', 'desc')->first()->amount_left ?? 0;
                
            }
            $old_date = Invoice::where(['customer_id' => $customer_id])->orderBy('id', 'desc')->first()->created_at ?? "No Previous Order!";
            $order_count = sizeof(Order::where(['customer_id' => $customer_id, 'is_confirmed_admin' => null])->get());
            $invoices_count = sizeof(Invoice::where(['customer_id' => $customer_id])->get());

            $invoice_count = $order_count + $invoices_count;
        } else {

            $customerInv = Invoice::where(['customer_id' => $customer_id])->orderBy('id', 'desc');
            $count = $customerInv->pluck('id')->count();

            if ($count > 0){

                $customerdata = (clone $customerInv)->get();
                $old_balance = $customerdata->first()->amount_left;
                $old_date = $customerdata->first()->created_at;
                $invoice_count = $count + 1;
            }else{
                $old_balance = 0;
            $old_date = "Order Not Exist!";
            $invoice_count = 0;
            }
            
       
    }
            
            //dd($prevOrder);
          
            
            
           // dd($customer);
            
        
        return view('ajax.process_custom_price', compact('customer' , 'products', 'old_balance', 'invoice_count', 'shortListed' , 'old_date' , 'customer_priority' , 'customer_id'));
    }
    
    public function findStockProduct($allowed_ids){
        
        if (Auth::user()->role <= 3) {
            if ($allowed_ids != NULL){
             $stock_products = Product::where('user_id', $allowed_ids)->orderBy('category_id', 'asc')->get();        
            }
            else
            {
                $stock_products = Product::where('user_id', Auth::id())->orderBy('category_id', 'asc')->get();        
            }
             $stock_type = 1;
        }
         return view('ajax.stock_products', compact('stock_products' , 'stock_type'));
    }
    
    public function updateCustomerarea(Request $request , $id)
    {
     
        $customer = Customer::find($id);
            $customer->area_id = $request->area;
            $customer->save();
            return response()->json(['message' => 'User status updated successfully.']);
       
    }


    public function distance($lat1, $lon1, $lat2, $lon2, $unit) {
      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      $unit = strtoupper($unit);
    
      if ($unit == "K") {
          return ($miles * 1.609344);
      } else if ($unit == "N") {
          return ($miles * 0.8684);
      } else {
          return $miles;
      }
    }
    
    public function findShortest($coords, $start, $startIndex)
    {
        $shortest = -1;
        $s_arr = [];
        for($i=$startIndex;$i<sizeof($coords);$i++)
        {
            $c = $coords[$i]['points'];
            $chunks = explode(',', $c);
            $dist = $this->distance((float)$start[0], (float)$start[1], (float)$chunks[0], (float)$chunks[1], 'K');
            
            if($shortest < $dist || $shortest == -1)
            {
                $s_arr = [$coords[$i]['info'], $chunks[0], $chunks[1], $dist];
                $shortest = $dist;
            }
        }
        return $s_arr;
    }
    
    public function getNearestShop(Request $req)
    {
       //dd($req->all());
        $coords = $req->coords;
        $unsorted = [];
        $start = explode(',', $req->current_coords);
        
        for($i=0;$i<sizeof($coords);$i++)
        {
            $c = $coords[$i]['points'];
            $chunks = explode(',', $c);
            $dist = $this->distance((float)$start[0], (float)$start[1], (float)$chunks[0], (float)$chunks[1], 'K');
           // dd($coords[$i]);
            
            $unsorted[] = [$c, ($coords[$i]['info']) ?? 'no', $coords[$i]['row_check'], $dist];
        }
        usort($unsorted, function($a, $b){
            return ($a[3] < $b[3]) ? -1 : 1;
        });
        
        return $unsorted;
    }
    public function getcustomerDetail($id){
        $customer = Customer::find($id);
         return view('ajax.customer_detail' , compact('customer'));
    }
    public function UnVisitCustomer(Request $request)
        
        {
            $deli = $request->get('user_id');
            foreach($deli as $us_id) {
            $customer = Customer::find($us_id);
            $customer->visit_clear = 0;
            $customer->save();
            }
            return response()->json(['message' => 'User status updated successfully.']);
    }
    public function setcustomerpriority(Request $request){
           $customer = Customer::find($request->userid);
           $customer->automatic_create_order = $request->no_of_days;
           $customer->save();
           return redirect()->back()->with('success', 'Priority set successfully!');
    }
    
        public function StoreNewSop(Request $request)
    {
        $cords = "31.5815886"."74.3779746";
       
        $data = $this->findNearestCustomer($request); // need to work
        $content = $data->getContent();
        $data = explode(',', $content);
        $area_name = $d[3];
        $str = $area_name;
        $str = str_replace('"', '', $str);
        $area_id = Area::where('name' , $str)->pluck('id')->first();
        
     

        $customer = new User();
        $customer->name = "Un verified Shop";
        $customer->email = $this->generateRandomString()."@scoops.com";
        $customer->password = '123456789';
        $customer->role = 4;
        //$customer->save();
        $new_ot_id = User::with('syncCustomerProduct')->find($customer->id);
        $new_ot_id->syncCustomerProduct()->sync($request->final_allowed_products);
        $new_user_id = User::orderBy('id', 'desc')->first()->id;

        $customerData = new Customer();
        $customerData->user_id = $new_user_id;
        $customerData->customer_name = "Plz edit it!";
        if (!empty($request->findarea)){
            $customerData->area_id = $request->findarea;
        }
        else{
        $customerData->area_id = $request->area;
        }
        
        $customerData->created_by = Auth::id();
        $customerData->address = $request->address; // add address
        $customerData->phone = 03;
        $customerData->cnic = 123;
        $customerData->location_url = $request->location_url; // add
        $customerData->balance_limit = 0;
        $customerData->freezer_model = "No freezer";
        $customerData->payment_method = "Cash On Delivery";
        $customerData->allowed_products = implode('|', $request->allowed_products);
        $customerData->final_allowed_products = implode('|', $request->final_allowed_products);

        
        $customerData->save();

        $new_customer_id = Customer::orderBy('id', 'desc')->first()->id;

        User::where('id', $new_user_id)->update(['customer_id' => $new_customer_id]);

       

        if (Auth::user()->role == 5) {
            $customerOT = new OtCustomer();
            $customerOT->ot_id = Auth::id();
            $customerOT->customer_id = $customerData->id;
            $customerOT->save();
        }
        return Common::Message("Customer", 1);
    }
    
    function generateRandomString(int $n=0)
        {
          $al = ['a','b','c','d','e','f','g','h','i','j','k'
          , 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u',
          'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E',
          'F','G','H','I','J','K', 'L', 'M', 'N', 'O', 'P',
          'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
          '0', '2', '3', '4', '5', '6', '7', '8', '9'];
        
          $len = !$n ? random_int(7, 12) : $n; // Chose length randomly in 7 to 12
        
          $ddd = array_map(function($a) use ($al){
            $key = random_int(0, 60);
            return $al[$key];
          }, array_fill(0,$len,0));
          return implode('', $ddd);
        }

    public function updateCustomerPrices(Request $request)
        {
            
            if (Auth::user()->role < 3){
            $customer = CustomPrice::where(['customer_id' => $request->customer_id , 'product_id' => 
                $request->product_id]);
            if($customer->pluck('id')->count() != 0){
                $store = $customer->first();
            }else{
                $store = new CustomPrice();
            }
            $store->product_id = $request->product_id;
            $store->customer_id = $request->customer_id;
            $store->sell_price  = $request->sell_price;
            $store->a_benefit   = $request->a_benefit;
            $store->c_benefit   = $request->c_benefit;
            $store->price       = $request->price;
            $store->ot_benefit  = $request->ot_benefit ?? 0;

            $store->save();   

            return $store;
        }
           
        }

        public function store_new_cstmr_location(Request $request){

            if(empty($request->cords)){
                 return response()->json(['message' => 'Cords Empty Try Again! Or Enable geo location' , 'success' => false]);
            }else{
            $store = new StoreCstmrLocation();
            $store->user_id = Auth::id();
            $store->cords   = $request->cords;
            $store->save();
             return response()->json(['message' => 'Saved Successfully!' , 'success' => true]);
            }
        }

        public function getNewCustomerLocation(){

            $users = $this->GetOrdertakerList();

             $ids_ordered = $this->sortlocations();
              $records = StoreCstmrLocation::whereIn('id', $ids_ordered)->whereIn('user_id' , $users)
              ->orderByRaw("field(id,".implode(',',$ids_ordered).")")
              ->get();
              return view('customers.new_customer_location', compact('records'));
        }

        protected function GetOrdertakerList(){

        if (Auth::user()->role < 3){
            return User::where('ot_of' , Auth::id())->pluck('id')->toArray();
        }elseif(Auth::user()->role == 3){
            $admin_id = User::where('id' , Auth::id())->pluck('seller_of')->first();
            return User::where('ot_of' , $admin_id)->pluck('id')->toArray();
        }elseif(Auth::user()->role == 5){
            $admin_id = User::where('id' , Auth::id())->pluck('ot_of')->first();
            return User::where('ot_of' , $admin_id)->pluck('id')->toArray();
        }

}

        public function deleteLocation($id){

             StoreCstmrLocation::where('id' , $id)->delete();
             return redirect()->back()->with('success', 'Location Deleted successfully!');
        }

       
    public function sortlocations($cords = NULL ,$cords1 = NULL , $idss = NULL){

    
         $new_ids = "empty";
        if ($cords == NULL && $cords1 == NULL){
        $cords = 31.5815886;
        $cords1 = 74.3779746;
        }
        //dd($idss);
        $customersids =  $customerscords = StoreCstmrLocation::where('cords' , '!=' , 'null,null')->pluck('id')->toArray(); // this id should be updated for looping
        //dd($customersids);
        for ($a = 0; $a < 3000 ; $a++){
        $customerscords = StoreCstmrLocation::where('cords' , '!=' , 'null,null')->pluck('cords')->toArray();
        //dd($customerscords);
        $shortest = array();
        foreach ($customerscords as $short){
         $shortest[] = $this->getsortedDistance($cords , $cords1 ,$short);
        }
        $maping = array();
        for($i = 0; $i < sizeof($customersids); $i++){
            $maping[$customersids[$i]] =  $shortest[$i];
        }
        $key_val = array();
        if (!empty($maping)){
         $key_val = array_keys($maping, min($maping));// this gets keys and shortest value
           
        }
        if( $new_ids == "empty"){ // this stores srtoed ids
            $new_ids = $key_val;
        }
        else{
         $new_ids = array_merge($new_ids , $key_val);
        }
        $get_last_id = end($new_ids);
        $getcordslastid = StoreCstmrLocation::where('id' , $get_last_id)->pluck('cords')->first();
        $getcordslast = explode(',', $getcordslastid);
        $cords = @$getcordslast[0];
        $cords1 = @$getcordslast[1];
        $customersids = array_diff($customersids , $new_ids); // this sets customer ids for looping
        $customersids = array_values($customersids);
        }
        return $new_ids;
    }

    public function apiGetCustomer(Request $request){

        $success = Customer::where('created_by' , $request->id)->get();

           // $success['user'] = $success;
            return response()->json(['code' => 200 , 'status' => 'success', 'message' => 'User Found' , 'data' => $success], 200);

    }

}

