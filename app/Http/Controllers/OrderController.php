<?php

namespace App\Http\Controllers;
use App\Repositories\Common;
use Illuminate\Http\Request;
use App\Models\OtCustomer;
use App\Models\User;
use App\Models\Product;
use App\Models\CustomOtBenefit;
use App\Models\CustomPrice;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Category;
use App\Models\OrderDetail;
use App\Models\Ordertaker;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Area;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;
use App\Models\SellerSellsReport;
use App\Models\SubAdmin;
use App\Models\Seller;
use App\Models\SellerProductProfit;
use App\Models\SellerSell;
use Carbon\Carbon;
use App\Models\ReceiptSetting;
use App\Models\OtAreaVisitsProfit;
use App\Models\CustomerProductsStock;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Artisan;
use Image;


class OrderController extends BaseController{

    public function index($orders,$type, $isJsonRequest = false , $seller_id = NULL){
        $ids = [Auth::id()];
        $adminid = [User::where('id' , Auth::id())->pluck('ot_of')->first()]; //this uses ordertaker to get his admin
        if (Auth::user()->role == 2){
            $adminots = User::where('ot_of' , $ids)->pluck('id')->toArray();// this combines admin ot and admin id 
            $adminots = array_merge($ids , $adminots);
        }
        else{
        $adminots = User::where('ot_of' , $adminid)->pluck('id')->toArray();// this combines admin ot and admin id 
        $adminots = array_unique(array_merge($ids , $adminots ,$adminid));
        }
        $allareas = Area::whereIn('created_by' , $adminots)->get();//this get all areas of same admin and his ot
        
        $product_report  = $this->getproductreport($orders);
        $key_counted = [];
        $keys_generated = []; 
        foreach (array_reverse($orders->toArray(), true) as $key => $row) {
            if (!in_array($row['customer_id'], $key_counted)) {
                $key_counted[$key] = $row['customer_id'];
                $keys_generated[] = $key;
            }
        }
        $ordertakers = User::where('role',5)->whereIn('id', $orders->pluck('ot_id')->toArray())->get();
        $areas = Area::whereIn('id', Customer::whereIn('id', $orders->pluck('customer_id'))->pluck('area_id')->toArray())->get();
        $idss = [Auth::id()];
        if (Auth::user()->role < 3) {
            $idss = array_merge($idss, User::where('ot_of', Auth::id())->pluck('id')->toArray());
            $sellerUser = User::whereIn('seller_of' , $idss)->where(['role'=> 3, 'is_blocked'=> 0])->get();
        }
         $get_assign_order_status = Seller::where('seller_id' , Auth::id())->pluck('assign_order')->first();
          $seller_data = Seller::where('seller_id' , Auth::id())->first();
          
         if (Auth::user()->role == 3 && $get_assign_order_status == 1) {
         $allowed_seller = $this->getallowedseller();    
         $sellerUser = User::whereIn('id' , $allowed_seller)->where(['role'=> 3, 'is_blocked'=> 0])->get();
         }else if(Auth::user()->role == 3){
            $sellerUser = '';
         }
        $sellers = User::where('role',3)->get();
        
        $ortaker = User::where('role', 5)->has('ordertaker')->with('ordertaker');
        $ortaker = $ortaker->where('ot_of', Auth::id());
        $ortaker = $ortaker->get();
        if (Auth::user()->role == 5){
            $sellerUser = "";
        }

    /*    foreach ($orders as $key => $row) {
            $orders[$key]['order_details'] = Order::find($row->id)->orderdetail;
            $prev_invoices = Order::where('id', '<', $row->id)->where('customer_id', $row->customer_id)->orderBy('id', 'desc');
            $count = $prev_invoices->pluck('id')->count();
            if ($count != 0){
                 $prev_invoices = $prev_invoices->get();
                 $orders[$key]['prev_invoices_details'] = $prev_invoices[0];
                 $orders[$key]['bill_no'] = count($prev_invoices);
            }else{
                $prev_invoices = 0;
            }
            
        }
        */
         $cords = 31.5815886;
         $cords1 = 74.3779746;
         $get_customer_id = $orders->pluck('customer_id')->toArray();
                        $get_order_id = $orders->pluck('id')->toArray();
                        if ($type != 6){
                        $customer_order = $this->sorteddata($cords ,$cords1 , $get_customer_id);
                         //$customer_order = implode(',', $shortestids);

                        // dd($ids_ordered);
                        //dd($orders->pluck('id')->toArray());
                        
                    if($orders->count('id') > 0){
                        $orders = Order::whereIn('id', $orders->pluck('id')->toArray())->get();

                        $orders = $orders->sortBy(function ($order) use ($customer_order) {
                            return array_search($order->customer_id, $customer_order);
                        });

                        foreach($orders as $or){
                            $a[] = $or->customer->user->name;
                        }

    //dd($a);
    
                    }
                        }
        if($type==1){
            return view('orders.all' , compact('orders' , 'product_report','ordertakers', 'areas', 'keys_generated' ));
        }
        else if($type==2){
            //$compare_ot_distance = $orders->ordertaker->compare_ot_distance;
            $redTotalDistance = Order::has('get_ot_creater')->where('ot_customer_distance', '>', 0.3)->whereNull('is_confirmed_seller')->whereNull('is_confirmed_admin')->pluck('id')->count();
            $redTodayDistance = Order::has('get_ot_creater')->where('ot_customer_distance', '>', 0.3)->whereDate('created_at', date('Y-m-d'))->pluck('id')->count();
            $greenTotalDistance = Order::has('get_ot_creater')->where('ot_customer_distance', '<', 0.3)->whereNull('is_confirmed_seller')->whereNull('is_confirmed_admin')->pluck('id')->count();
            $greenTodayDistance = Order::has('get_ot_creater')->where('ot_customer_distance', '<', 0.3)->whereDate('created_at', date('Y-m-d'))->pluck('id')->count();
            if (Auth::user()->role == 5){
            $redTotalDistance = Order::has('get_ot_creater')->where('ot_id' , Auth::id())->where('ot_customer_distance', '>', 0.3)->whereNull('is_confirmed_seller')->whereNull('is_confirmed_admin')->pluck('id')->count();
            $redTodayDistance = Order::has('get_ot_creater')->where('ot_id' , Auth::id())->where('ot_customer_distance', '>', 0.3)->whereDate('created_at', date('Y-m-d'))->pluck('id')->count();
            $greenTotalDistance = Order::has('get_ot_creater')->where('ot_id' , Auth::id())->where('ot_customer_distance', '<', 0.3)->whereNull('is_confirmed_seller')->whereNull('is_confirmed_admin')->pluck('id')->count();
            $greenTodayDistance = Order::has('get_ot_creater')->where('ot_id' , Auth::id())->where('ot_customer_distance', '<', 0.3)->whereDate('created_at', date('Y-m-d'))->pluck('id')->count();
                
            }
    
            $distance_data['red_total_distance'] = $redTotalDistance;
            $distance_data['red_today_distance'] = $redTodayDistance;
            $distance_data['green_total_distance'] = $greenTotalDistance;
            $distance_data['green_today_distance'] = $greenTodayDistance;
            
            return view('orders.unconfirmed' , compact('orders' , 'product_report' , 'seller_data' ,  'ordertakers', 'areas' , 'allareas' , 'keys_generated' , 'sellerUser', 'distance_data'));
        }
        else if($type==3){


            $data =  $this->seller_report_diff ($product_report , $seller_id);
             $unitdiff = $data[1] ?? '0';
             $amountdiff = $data[0] ?? '0';
             $getsellerreport = $data[2] ?? '0';
            // dd($getsellerreport);
        $selected_seller = User::whereIn('id' , $orders->pluck('selected_seller')->toArray())->get();
        
             if($isJsonRequest){
            
               $html = view('ajax.__seller_confirmed_orders' , compact('orders' , 'product_report' , 'keys_generated' , 'seller_id' , 'getsellerreport' , 'unitdiff' , 'amountdiff' , 'selected_seller'))->render();
                return response()->json(['success'=> true, 'html'=> $html]);
            }
             
             
             return view('orders.seller_confirmed' , compact('orders' , 'product_report' , 'seller_data' , 'ordertakers', 'areas' , 'allareas' , 'keys_generated', 'sellerUser' , 'unitdiff' , 'selected_seller'));
        }
        else if($type==4){
            $redTotalDistance = Order::where('ot_customer_distance', '>', 0.3)->whereNull('is_confirmed_admin')->pluck('id')->count();
            $redTodayDistance = Order::where('ot_customer_distance', '>', 0.3)->whereNull('is_confirmed_admin')->whereDate('created_at', date('Y-m-d'))->pluck('id')->count();
            $greenTotalDistance = Order::where('ot_customer_distance', '<', 0.3)->whereNull('is_confirmed_admin')->pluck('id')->count();
            $greenTodayDistance = Order::where('ot_customer_distance', '<', 0.3)->whereNull('is_confirmed_admin')->whereDate('created_at', date('Y-m-d'))->pluck('id')->count();
    
            $distance_data['red_total_distance'] = $redTotalDistance;
            $distance_data['red_today_distance'] = $redTodayDistance;
            $distance_data['green_total_distance'] = $greenTotalDistance;
            $distance_data['green_today_distance'] = $greenTodayDistance;
            
            return view('orders.admin_confirmed' , compact('orders' , 'product_report','ordertakers', 'areas' , 'allareas'  , 'keys_generated' , 'distance_data'));
        }
        else if($type==5){
            return view('orders.important' , compact('orders' , 'product_report','ordertakers', 'areas' , 'allareas' , 'keys_generated'));
        }
        else if($type==6){
            $data =  $this->seller_report_diff ($product_report , $seller_id);
            if (!empty($data)){
             $unitdiff = $data[1];
             $amountdiff = $data[0];
             $getsellerreport = $data[2];   
            }
             
            // dd($getsellerreport);
             if($isJsonRequest){
            
               $html = view('ajax.__seller_confirmed_orders' , compact('orders' , 'product_report' , 'keys_generated' , 'seller_id' , 'getsellerreport' , 'unitdiff' , 'amountdiff'))->render();
                return response()->json(['success'=> true, 'html'=> $html]);
            }
             
             
             return view('orders.processed_seller_confirmed' , compact('orders' , 'product_report' , 'seller_data' , 'ordertakers', 'areas' , 'allareas' , 'keys_generated', 'sellerUser'));
        }
        else{}
       
    }
    
    protected function getallowedseller(){
         $get_alloewd_seller = Seller::where('seller_id' , Auth::id())->pluck('allowed_seller')->first();// gets allowed ot of other ot
         $allowed_seller = explode('|', $get_alloewd_seller);
         $id = Auth::id();
         $sellerUser = [$allowed_seller , $id];
         $allowed_seller = array();
            foreach($sellerUser as $array)
            {
                if (is_array($array) || is_object($array))
                {
                foreach($array as $val)
                {
                    array_push($allowed_seller, $val);
                } 
                }   
            }
            return $allowed_seller;
    }
    
    public function storesellerreport(Request $req){
        
        $data = SellerSellsReport::where('seller_id' , $req->seller_id);
        $check = $data->pluck('id')->first();
        $get_assign_order_status = Seller::where('seller_id' , Auth::id())->pluck('assign_order')->first();
          
        if ($check == null){
        $sellerUser = User::find($req->seller_id);
        if($sellerUser && Auth::user() == 1 && Auth::user()->role < 3){
            $orders = Order::has('get_ot_creater')
                        ->where('is_confirmed_seller',"!=",NULL)
                        ->where('is_confirmed_admin',NULL)
                        ->where('selected_seller', $sellerUser->id)
                        ->get();
        }
        else if (Auth::user()->role == 3 && $get_assign_order_status == 1 ){
           
             $orders = Order::where('is_confirmed_seller',"!=",NULL)
                            ->where('is_confirmed_admin', NULL)
                            ->where('selected_seller', $sellerUser->id)
                            ->get();
            
        }
        
        $product_report  = $this->getproductreport($orders);
        foreach($product_report as $preport){
                if($preport['amount'] != 0 || $preport['unit'] != 0){
                $id[] = $preport['id'];
                $unit[] = $preport['unit'];
                $amount[] = $preport['amount'];
                }
             }

        for($counter = 0;$counter < sizeof($amount);$counter++){
            if(!empty($amount[$counter]))
            {
            $store_report = new SellerSellsReport();
            $store_report->product_id = $id[$counter];
            $store_report->amount = $amount[$counter];
            $store_report->unit = $unit[$counter];
            $store_report->seller_id = $req->seller_id;
            $store_report->save();
             }
            }
            $orders = Order::has('get_ot_creater')
                        ->where('is_confirmed_seller',"!=",NULL)
                        ->where('is_confirmed_admin',NULL)
                        ->where('selected_seller', $req->seller_id)
                        ->get();
            $product_report  = $this->getproductreport($orders);
            $data =  $this->seller_report_diff ($product_report , $req->seller_id);
             $unitdiff = $data[1];
             $amountdiff = $data[0];
             $getsellerreport = $data[2];
             
             return response()->json(['success'=> true, 'message' => 'Seller Report Saved Successfully.' , 'getsellerreport' => $getsellerreport  ]);
                }
                else{
                    return response()->json(['error'=> true, 'message'=> 'Already exist One report']);
                }
    }
    
     public function deletestoresellerreport(Request $request){
          
          $data = SellerSellsReport::where('seller_id' , $request->seller_id);
          $check = $data->pluck('id')->first();
          if ($check != null){
          $data->delete();
             return response()->json(['success'=> true, 'message' => 'Seller Report Deleted Successfully.' ]);
                }
                else{
                    return response()->json(['error'=> true, 'message'=> 'No report found for this User']);
                }
         
     }
    
    public function getproductreport($orders){
        
        $products = Product::get();
        $product_report = [];
        $counter = 0;
        if($orders!=null){
        foreach($products as $p){
            $ppunit = 0;$ppamount = 0;
            $product_report[$counter]['id'] = $p->id;
            $product_report[$counter]['name'] = $p->name;
            $product_report[$counter]['stock'] = $p->remaining_stock;
            foreach($orders as $in){
                $idet =  $in->orderdetail->where('product_id' , $p->id);
                $ppunit += $idet->sum('unit');
                $ppamount += $idet->sum('p_amount');
            }
            $product_report[$counter]['unit'] = $ppunit;
            $product_report[$counter]['amount'] = $ppamount;
            $counter++;
        }
    }
        return $product_report;    
    }
    
    protected function seller_report_diff ($product_report , $seller_id){
        $getsellerreport = SellerSellsReport::where('seller_id' , $seller_id);
             $getunitdiff = $getsellerreport->pluck('unit')->toArray();
             $getamountdiff = $getsellerreport->pluck('amount')->toArray();
             $getproductdiff = $getsellerreport->pluck('product_id')->toArray();
             $getsellerreport = $getsellerreport->get();


             foreach($product_report as $preport){
                if($preport['amount'] != 0 || $preport['unit'] != 0){
                $unit[] = $preport['unit'];
                $amount[] = $preport['amount'];
                $pid[] = $preport['id'];
               
                }
                if (!empty($pid)){
                     for($i = 0; $i < sizeof($pid); $i++){
            $unit_maping[$pid[$i]] =  $unit[$i];
            $amount_maping[$pid[$i]] =  $amount[$i];
        }
        for($i = 0; $i < sizeof($getproductdiff); $i++){
            $punit_maping[$getproductdiff[$i]] =  $getunitdiff[$i];
            $pamount_maping[$getproductdiff[$i]] =  $getamountdiff[$i];
        }

                }
       
              }
            //   if (!empty($punit_maping)){
            //       dd($punit_maping);
            //       $unitdiff = collect($punit_maping)->map(function($aItem, $index) use ($unit_maping) {
            //      return ($aItem - $unit_maping[$index]) ?? '0';
            //     });
            //     $amountdiff = collect($pamount_maping)->map(function($aItem, $index) use ($amount_maping) {
            //       return $aItem - $amount_maping[$index];
            //     });
            //      $unitdiff = $unitdiff->toArray();
            //     $amountdiff = $amountdiff->toArray();
            //     $amountdiff = array_values($amountdiff);
            //     $unitdiff = array_values($unitdiff);
            //     return array($amountdiff , $unitdiff , $getsellerreport);
          
            //   }
               
    }


   
    public function getUnconfirmedOrders(Request $request){
       // Artisan::call('cron:create-automatic-order');
         $cords = 31.5815886;
         $cords1 = 74.3779746;
        if($request->from){
            $dates=$this->dateFilter($request->from,$request->to);
            $from=$dates[0];
            $to=$dates[1];
        }

        $id=Auth::id();

        if(Auth::user()->role == 1){

            if(empty($from)){
                $orders = Order::has('get_ot_creater')->where('is_confirmed_seller',NULL)
                        ->where('is_confirmed_admin',NULL)->get();
            }
            else {
                $orders = Order::has('get_ot_creater')
                            ->where('is_confirmed_seller',NULL)
                            ->where('is_confirmed_admin',NULL)
                            ->whereBetween('created_at', array($from, $to))
                            ->get();
            } 
        }
        else if(Auth::user()->role == 2){
            
            if(empty($from)){
                $orders = Order::has('get_sub_admin_ot_creater')->has('customers')->with('customers')
                        ->where('is_confirmed_seller',NULL)
                        ->where('is_confirmed_admin',NULL)
                        ->get();
            }
            else {
                $orders = Order::has('get_sub_admin_ot_creater')->has('customers')->with('customers')
                        ->where('is_confirmed_seller',NULL)
                        ->where('is_confirmed_admin',NULL)
                        ->whereBetween('created_at', array($from, $to))
                        ->get();
            }
        }


        if(Auth::user()->role == 3){

            $common_ot = User::where('ot_of', Auth::user()->seller_of)->pluck('id')->toArray();
            $admin[] = Auth::user()->seller_of;


            $common_ot = array_merge($common_ot , $admin);
           
            //dd($common_ot);

            if(empty($from)){
                $orders = Order::where('is_confirmed_seller',NULL)
                            ->whereIn('ot_id', $common_ot)
                            ->where('is_confirmed_admin',NULL)
                            ->get();
            }
            else {
                $orders = Order::where('is_confirmed_seller',NULL)
                            ->where('is_confirmed_admin',NULL)
                            ->whereIn('ot_id', $common_ot)
                            ->whereBetween('created_at', array($from, $to))
                            ->get();
            } 

        }

        else if(Auth::user()->role == 5){
            if(empty($from)){

                $orders = Order::where('is_confirmed_seller',NULL)
                            ->where('is_confirmed_admin',NULL)
                            ->where('ot_id',$id)
                            ->get();
            }  
            else {

                $orders = Order::where('is_confirmed_seller',NULL)
                            ->where('is_confirmed_admin',NULL)
                            ->whereBetween('created_at', array($from, $to))
                            ->where('ot_id',$id)
                            ->get();
                }
        }
        else{
            
        }
        

        return $this->index($orders,2);
    }

    public function getFilteredSellerConfirmedOrders(Request $request){
         
        $sellerID = $request->get('id');
        $sellerUser = User::find($sellerID);
         $get_assign_order_status = Seller::where('seller_id' , Auth::id())->pluck('assign_order')->first();
        if($sellerUser && Auth::user()->role < 3){
            $orders = Order::where('is_confirmed_seller',"!=",NULL)
                        ->where('is_confirmed_admin',NULL)
                        ->where('seller_processed_order',NULL)
                        ->where('selected_seller', $sellerUser->id)
                        ->get();
            return $this->index($orders, 3, true , $sellerUser->id);
        }
        else if (Auth::user()->role == 3 && $get_assign_order_status == 1){
 
             $orders = Order::where('is_confirmed_seller',"!=",NULL)
                            ->where('is_confirmed_admin', NULL)
                            ->where('seller_processed_order',NULL)
                            ->where('selected_seller', $sellerUser->id)
                            ->get();
            return $this->index($orders, 3, true , $sellerUser->id);
        }
        else{
            
            return response()->json(['success'=> false, 'message'=> 'Not authorized!']);
        }
    }

    public function getSellerConfirmedOrders(Request $request){

        if($request->from){
            $dates=$this->dateFilter($request->from,$request->to);
            $from=$dates[0];
            $to=$dates[1];
        } 

        $id=Auth::id();
        if(Auth::user()->role == 1){

            if(empty($from)){
                $orders = Order::has('get_ot_creater')
                            ->with('seller')
                            ->where('is_confirmed_seller',"!=",NULL)
                            ->where('is_confirmed_admin',NULL)
                            ->where('seller_processed_order',NULL)
                            ->get();
            }
            else {
                $orders = Order::has('get_ot_creater')
                            ->where('is_confirmed_seller',"!=", NULL)
                            ->where('is_confirmed_admin', NULL)
                            ->where('seller_processed_order',NULL)
                            ->whereBetween('created_at', array($from, $to))
                            ->get();
                }
        }
        else if(Auth::user()->role == 2){
            
            
            if(empty($from)){
                $orders = Order::has('get_sub_admin_ot_creater')->has('customers')->with('customers')
                        ->where('is_confirmed_seller',"!=", NULL)
                        ->where('is_confirmed_admin',NULL)
                        ->where('seller_processed_order',NULL)
                        ->get();
            }
            else {
                $orders = Order::has('get_sub_admin_ot_creater')->has('customers')->with('customers')
                        ->where('is_confirmed_seller',"!=", NULL)
                        ->where('is_confirmed_admin',NULL)
                        ->where('seller_processed_order',NULL)
                        ->whereBetween('created_at', array($from, $to))
                        ->get();
            }
        }
        if(Auth::user()->role == 3){
            
            $get_assign_order_status = Seller::where('seller_id' , Auth::id())->pluck('assign_order')->first();
            $authUserAndAdminIDs = User::where('id', Auth::id())->orWhere('role', 1)->orWhere('role', 2)->pluck('id');
            if ($get_assign_order_status == 1){
            $allowed_seller = $this->getallowedseller();
            $users = [$allowed_seller , $authUserAndAdminIDs];
            $selected_user = array();
            foreach($users as $array)
            {
                foreach($array as $val)
                {
                    array_push($selected_user, $val);
                }    
            }
            }
            else{
                $selected_user = User::where('id', Auth::id())->orWhere('role', 1)->orWhere('role', 2)->pluck('id');
            }
            
             $common_ot = User::where('ot_of', Auth::user()->seller_of)->pluck('id')->toArray();
            $admin[] = Auth::user()->seller_of;


            $common_ot = array_merge($common_ot , $admin);
            
            //dd($selected_user);

            if(empty($from)){
                $orders = Order::where('is_confirmed_seller',"!=",NULL)
                            ->where('is_confirmed_admin', NULL)
                            ->where('seller_processed_order',NULL)
                            ->whereIn('selected_seller', $selected_user)
                            ->whereIn('ot_id', $common_ot)
                            ->get();
            }
            else {
                $orders = Order::where('is_confirmed_seller',"!=",NULL)
                            ->where('is_confirmed_admin', NULL)
                            ->where('seller_processed_order',NULL)
                            ->whereIn('selected_seller', $selected_user)
                            ->whereIn('ot_id', $common_ot)
                            ->whereBetween('created_at', array($from, $to))
                            ->get();
            }
        }
       
        else if(Auth::user()->role == 5){
            if(empty($from)){
                $orders = Order::where('is_confirmed_seller',"!=",NULL)
                        ->where('is_confirmed_admin', NULL)
                        ->where('seller_processed_order',NULL)
                        ->where('ot_id',$id)->get();
            }
               
            else {
                $orders = Order::where('is_confirmed_seller',"!=",NULL)
                        ->whereBetween('created_at', array($from, $to))
                        ->where('is_confirmed_admin', NULL)
                        ->where('seller_processed_order',NULL)
                        ->where('ot_id',$id)
                        ->get();
            }
        }  
        else{}

        return $this->index($orders,3);
    }
    
        public function getSellerProcessedOrders(Request $request){

        if($request->from){
            $dates=$this->dateFilter($request->from,$request->to);
            $from=$dates[0];
            $to=$dates[1];
        } 

        $id=Auth::id();
        if(Auth::user()->role == 1){

            if(empty($from)){
                $orders = Order::has('get_ot_creater')
                            ->with('seller')
                            ->where('is_confirmed_seller',"!=",NULL)
                            ->where('is_confirmed_admin',NULL)
                            ->where('seller_processed_order',"!=",NULL)
                            ->get();
            }
            else {
                $orders = Order::has('get_ot_creater')
                            ->where('is_confirmed_seller',"!=", NULL)
                            ->where('is_confirmed_admin', NULL)
                            ->where('seller_processed_order',"!=",NULL)
                            ->whereBetween('created_at', array($from, $to))
                            ->get();
                }
        }
        else if(Auth::user()->role == 2){
            
            
            if(empty($from)){
                $orders = Order::has('get_sub_admin_ot_creater')->has('customers')->with('customers')
                        ->where('is_confirmed_seller',"!=", NULL)
                        ->where('is_confirmed_admin',NULL)
                        ->where('seller_processed_order',"!=",NULL)
                        ->get();
            }
            else {
                $orders = Order::has('get_sub_admin_ot_creater')->has('customers')->with('customers')
                        ->where('is_confirmed_seller',"!=", NULL)
                        ->where('is_confirmed_admin',NULL)
                        ->where('seller_processed_order',"!=",NULL)
                        ->whereBetween('created_at', array($from, $to))
                        ->get();
            }
        }
        if(Auth::user()->role == 3){
            
            $get_assign_order_status = Seller::where('seller_id' , Auth::id())->pluck('assign_order')->first();
            $authUserAndAdminIDs = User::where('id', Auth::id())->orWhere('role', 1)->orWhere('role', 2)->pluck('id');
            if ($get_assign_order_status == 1){
            $allowed_seller = $this->getallowedseller();
            $users = [$allowed_seller , $authUserAndAdminIDs];
            $selected_user = array();
            foreach($users as $array)
            {
                foreach($array as $val)
                {
                    array_push($selected_user, $val);
                }    
            }
            }
            else{
                $selected_user = User::where('id', Auth::id())->orWhere('role', 1)->orWhere('role', 2)->pluck('id');
            }
              
             $common_ot = User::where('ot_of', Auth::user()->seller_of)->pluck('id')->toArray();
            $admin[] = Auth::user()->seller_of;


            $common_ot = array_merge($common_ot , $admin);
          
            //dd($selected_user);

            if(empty($from)){
                $orders = Order::where('is_confirmed_seller',"!=",NULL)
                            ->where('is_confirmed_admin', NULL)
                            ->where('seller_processed_order',"!=",NULL)
                            ->whereIn('selected_seller', $selected_user)
                            ->whereIn('ot_id', $common_ot)
                            ->get();
            }
            else {
                $orders = Order::where('is_confirmed_seller',"!=",NULL)
                            ->where('is_confirmed_admin', NULL)
                            ->where('seller_processed_order',"!=",NULL)
                            ->whereIn('selected_seller', $selected_user)
                            ->whereIn('ot_id', $common_ot)
                            ->whereBetween('created_at', array($from, $to))
                            ->get();
            }
        }
       
        else if(Auth::user()->role == 5){
            if(empty($from)){
                $orders = Order::where('is_confirmed_seller',"!=",NULL)
                        ->where('is_confirmed_admin', NULL)
                        ->where('seller_processed_order',"!=",NULL)
                        ->where('ot_id',$id)->get();
            }
               
            else {
                $orders = Order::where('is_confirmed_seller',"!=",NULL)
                        ->whereBetween('created_at', array($from, $to))
                        ->where('is_confirmed_admin', NULL)
                        ->where('seller_processed_order',"!=",NULL)
                        ->where('ot_id',$id)
                        ->get();
            }
        }  
        else{}

        return $this->index($orders,6);
    }

    public function getOrderTakerPosOrders(Request $request){

       // Artisan::call('cron:create-automatic-order');
         $cords = 31.5815886;
         $cords1 = 74.3779746;
        if($request->from){
            $dates=$this->dateFilter($request->from,$request->to);
            $from=$dates[0];
            $to=$dates[1];
        }

        $admin_ot = $this->getAdminOt();

        if(Auth::user()->role < 3){

            if(empty($from)){
                $orders = Order::whereIn('is_confirmed_seller',$admin_ot)
                        ->whereIn('is_confirmed_admin',$admin_ot)
                        ->whereIn('selected_seller',$admin_ot)
                        ->whereIn('seller_processed_order',$admin_ot)
                        ->whereNull('approve_date')
                        ->get();
            }
            else {
                $orders = Order::whereIn('is_confirmed_seller',$admin_ot)
                        ->whereIn('is_confirmed_admin',$admin_ot)
                        ->whereIn('selected_seller',$admin_ot)
                        ->whereIn('seller_processed_order',$admin_ot)
                        ->whereNull('approve_date')
                            ->whereBetween('created_at', array($from, $to))
                            ->get();
            } 
        }
        // else if(Auth::user()->role == 2){
            
        //     if(empty($from)){
        //         $orders = Order::has('get_sub_admin_ot_creater')->has('customers')->with('customers')
        //                 ->where('is_confirmed_seller',NULL)
        //                 ->where('is_confirmed_admin',NULL)
        //                 ->get();
        //     }
        //     else {
        //         $orders = Order::has('get_sub_admin_ot_creater')->has('customers')->with('customers')
        //                 ->where('is_confirmed_seller',NULL)
        //                 ->where('is_confirmed_admin',NULL)
        //                 ->whereBetween('created_at', array($from, $to))
        //                 ->get();
        //     }
        // }


        // if(Auth::user()->role == 3){

        //     $common_ot = User::where('ot_of', Auth::user()->seller_of)->pluck('id')->toArray();
        //     $admin[] = Auth::user()->seller_of;


        //     $common_ot = array_merge($common_ot , $admin);
           
        //     //dd($common_ot);

        //     if(empty($from)){
        //         $orders = Order::where('is_confirmed_seller',NULL)
        //                     ->whereIn('ot_id', $common_ot)
        //                     ->where('is_confirmed_admin',NULL)
        //                     ->get();
        //     }
        //     else {
        //         $orders = Order::where('is_confirmed_seller',NULL)
        //                     ->where('is_confirmed_admin',NULL)
        //                     ->whereIn('ot_id', $common_ot)
        //                     ->whereBetween('created_at', array($from, $to))
        //                     ->get();
        //     } 

        // }

        else if(Auth::user()->role == 5){
            if(empty($from)){

                $orders = Order::whereIn('is_confirmed_seller',Auth::id())
                        ->whereIn('is_confirmed_admin',Auth::id())
                        ->whereIn('selected_seller',Auth::id())
                        ->whereIn('seller_processed_order',Auth::id())
                        ->whereNull('approve_date')
                            ->get();
            }  
            else {

                $orders = Order::whereIn('is_confirmed_seller',Auth::id())
                        ->whereIn('is_confirmed_admin',Auth::id())
                        ->whereIn('selected_seller',Auth::id())
                        ->whereIn('seller_processed_order',Auth::id())
                        ->whereNull('approve_date')
                            ->whereBetween('created_at', array($from, $to))
                            ->where('ot_id',Auth::id())
                            ->get();
                }
        }
        else{
            
        }
        $product_report  = $this->getproductreport($orders);
        $seller_data     = Ordertaker::where('user_id' , Auth::id())->first();
        $ordertakers = User::where('role',5)->whereIn('id', $orders->pluck('ot_id')->toArray())->get();
        $areas = Area::whereIn('id', Customer::whereIn('id', $orders->pluck('customer_id'))->pluck('area_id')->toArray())->get();
        $allareas = Area::whereIn('created_by' , $admin_ot)->get();
        $key_counted = [];
        $keys_generated = []; 
        foreach (array_reverse($orders->toArray(), true) as $key => $row) {
            if (!in_array($row['customer_id'], $key_counted)) {
                $key_counted[$key] = $row['customer_id'];
                $keys_generated[] = $key;
            }
        }
        $sellerUser = '';

        return view('orders.getOrderTakerPosOrders' , compact('orders' , 'product_report' , 'seller_data' , 'ordertakers', 'areas' , 'allareas' , 'keys_generated', 'sellerUser'));
        
    }
    
    public function getConfirmedOrders(Request $request){

        if($request->from){
            $dates=$this->dateFilter($request->from,$request->to);
            $from=$dates[0];
            $to=$dates[1];
        } 

        $id=Auth::id();
        if(Auth::user()->role == 1){

            if(empty($from)){
                $orders = Order::has('get_ot_creater')
                            ->where('approve_date',"!=",NULL);
                            $data = $orders->pluck('approve_date')->toArray();
                            foreach ($data as $date){
                                $d[] = date('Y-m-d', strtotime($date));
                            }
                            $unique_date = array_values(array_unique($d));
                            rsort($unique_date);
                            foreach($unique_date as $a ){
                                $orders = Order::has('get_ot_creater')
                            ->where('approve_date',"!=",NULL);
                            $data = $orders->whereDate('approve_date', '=', new Carbon($a));
                                $orde[] = $data->get();
                                $get_verified_ord[] = $data->where('verified_order' , 0)->pluck('id')->count();
                                $get_stock_ord[] = $this->getunstockorder($a);
                                
                            }

                return view('orders.confirmed_orders', compact('orde', 'get_verified_ord' , 'unique_date' , 'get_stock_ord'));
            }
            else {
                $orders = Order::has('get_ot_creater')
                            ->where('is_confirmed_seller',"!=", NULL)
                            ->where('is_confirmed_admin', NULL)
                            ->where('seller_processed_order',"!=",NULL)
                            ->whereBetween('created_at', array($from, $to))
                            ->get();
                }
        }
        else if(Auth::user()->role == 2){
            
            
            if(empty($from)){
                $orders = Order::has('get_sub_admin_ot_creater')->has('customers')->with('customers')
                        ->where('approve_date',"!=",NULL);
                            $data = $orders->pluck('approve_date')->toArray();
                            foreach ($data as $date){
                                $d[] = date('Y-m-d', strtotime($date));
                            }
                            $unique_date = array_values(array_unique($d));
                            rsort($unique_date);
                            foreach($unique_date as $a ){
                            $orders = Order::has('get_sub_admin_ot_creater')->has('customers')->with('customers')
                            ->where('approve_date',"!=",NULL);
                            $data = $orders->whereDate('approve_date', '=', new Carbon($a));
                                $orde[] = $data->get();
                                $get_verified_ord[] = $data->where('verified_order' , 0)->pluck('id')->count();
                                $get_stock_ord[] = $data->where('use_stock' , 0)->pluck('id')->count();
                                
                            }
            
                           return view('orders.confirmed_orders', compact('orde', 'get_verified_ord' , 'unique_date' , 'get_stock_ord'));
     
            }
            
            else {
                $orders = Order::has('get_sub_admin_ot_creater')->has('customers')->with('customers')
                        ->where('is_confirmed_seller',"!=", NULL)
                        ->where('is_confirmed_admin',NULL)
                        ->where('seller_processed_order',"!=",NULL)
                        ->whereBetween('created_at', array($from, $to))
                        ->get();
            }
        }
        if(Auth::user()->role == 3){
            
            $get_assign_order_status = Seller::where('seller_id' , Auth::id())->pluck('assign_order')->first();
            $authUserAndAdminIDs = User::where('id', Auth::id())->orWhere('role', 1)->orWhere('role', 2)->pluck('id');
            if ($get_assign_order_status == 1){
            $allowed_seller = $this->getallowedseller();
            $users = [$allowed_seller , $authUserAndAdminIDs];
            $selected_user = array();
            foreach($users as $array)
            {
                foreach($array as $val)
                {
                    array_push($selected_user, $val);
                }    
            }
            }
            else{
                $selected_user = User::where('id', Auth::id())->orWhere('role', 1)->orWhere('role', 2)->pluck('id');
            }
            $common_ot = User::where('ot_of', Auth::user()->seller_of)->pluck('id');
            
            if(empty($from)){
                            
                            //this
                $orders = Order::whereIn('ot_id', $common_ot)
                            ->where('approve_date',"!=",NULL);
                            $data = $orders->pluck('approve_date')->toArray();
                            foreach ($data as $date){
                                $d[] = date('Y-m-d', strtotime($date));
                            }
                            $unique_date = array_values(array_unique($d));
                            rsort($unique_date);
                            foreach($unique_date as $a ){
                            $orders = Order::whereIn('ot_id', $common_ot)
                            ->where('approve_date',"!=",NULL);
                            $data = $orders->whereDate('approve_date', '=', new Carbon($a));
                                $orde[] = $data->get();
                                $get_verified_ord[] = $data->where('verified_order' , 0)->pluck('id')->count();
                                $get_stock_ord[] = $data->where('use_stock' , 0)->pluck('id')->count();
                                
                            }

                return view('orders.confirmed_orders', compact('orde', 'get_verified_ord' , 'unique_date' , 'get_stock_ord'));                
            }
            else {
                $orders = Order::where('is_confirmed_seller',"!=",NULL)
                            ->where('is_confirmed_admin', NULL)
                            ->where('seller_processed_order',"!=",NULL)
                            ->whereIn('selected_seller', $selected_user)
                            ->whereIn('ot_id', $common_ot)
                            ->whereBetween('created_at', array($from, $to))
                            ->get();
            }
        }
       
 
        return $this->index($orders,7);
    }
     public function getunstockorder($a){
        $orders = Order::has('get_ot_creater')->where('approve_date',"!=",NULL);
        $data = $orders->whereDate('approve_date', '=', new Carbon($a));
        $get_stock_ord = $data->where('use_stock' , 0)->pluck('id')->count();
        return $get_stock_ord;
    }

    public function getAdminConfirmedOrders(Request $request){

        if($request->from){
            $dates=$this->dateFilter($request->from,$request->to);
            $from=$dates[0];
            $to=$dates[1];
        } 

        $id=Auth::id();
        if(Auth::user()->role == 1){

            if(empty($from)){
                $orders = Order::has('get_ot_creater')
                            ->where('is_confirmed_admin',"!=",NULL)
                            ->get();
            }
            else {
                $orders = Order::has('get_ot_creater')
                            ->where('is_confirmed_admin',"!=",NULL)
                            ->whereBetween('created_at', array($from, $to))
                            ->get();
                }
        }
        else if(Auth::user()->role == 2){
            
            if(empty($from)){

                $orders = Order::has('get_sub_admin_ot_creater')->has('customers')->with('customers')
                        ->where('is_confirmed_admin',"!=",NULL)
                        ->get();
            }     
            else{

                $orders = Order::has('get_sub_admin_ot_creater')->has('customers')->with('customers')
                            ->where('is_confirmed_admin',"!=",NULL)
                            ->whereBetween('created_at', array($from, $to))
                            ->get();
            }
        }
        if(Auth::user()->role == 3){

            // $common_ot = User::where('ot_of', Auth::user()->seller_of)->pluck('id');

            // if(empty($from)){
            //     $orders = Order::where('is_confirmed_admin',"!=",NULL)
            //                 ->whereIn('ot_id', $common_ot)
            //                 ->get();
            // }
            // else {
            //     $orders = Order::where('is_confirmed_admin',"!=",NULL)
            //                 ->whereIn('ot_id', $common_ot)
            //                 ->whereBetween('created_at', array($from, $to))
            //                 ->get();
            // }
            // return redirect()->back()->with('error', 'UnAuthorized Access');
            return redirect()->route('unconfirmed.orders')->with('error', 'UnAuthorized Access');


        }
       
        else if(Auth::user()->role == 5){
        //     if(empty($from)){
        //         $orders = Order::where('is_confirmed_admin',"!=",NULL)
        //                 ->where('ot_id',$id)->get();
        //     }
               
        //     else {
        //         $orders = Order::where('is_confirmed_admin',"!=",NULL)
        //                 ->where('is_confirmed_admin', NULL)
        //                 ->where('ot_id',$id)
        //                 ->get();
        //     }
         
             //return redirect()->back()->with('error', 'UnAuthorized Access');
             return redirect()->route('unconfirmed.orders')->with('error', 'UnAuthorized Access');


        }  
        else{}

        return $this->index($orders,4);
    }

    public function getAllOrders(Request $request){

        if($request->from){
            $dates=$this->dateFilter($request->from,$request->to);
            $from=$dates[0];
            $to=$dates[1];
        }

        $id=Auth::id();

        if(Auth::user()->role == 1){

            if(empty($from)){
                $orders = Order::has('get_ot_creater')->get();
            }
            else {
                $orders = Order::has('get_ot_creater')
                            ->whereBetween('created_at', array($from, $to))
                            ->get();
            }
        }
        else if(Auth::user()->role == 2){
            
            if(empty($from)){
                    
                $orders = Order::has('is_subadmin_customer')
                            ->has('get_ot_creater')
                            ->with('customers')
                            ->get();
            }     
            else{
                $orders = Order::has('is_subadmin_customer')
                            ->has('get_ot_creater')
                            ->with('customers')
                            ->whereBetween('created_at', array($from, $to))
                            ->get();
            }
        }
        else if( Auth::user()->role == 3){

            // $common_ot = User::where('ot_of', Auth::user()->seller_of)->pluck('id');

            // if(empty($from)){
            //     $orders = Order::whereIn('ot_id', $common_ot)->get();
            // }
            // else {
            //     $orders = Order::whereIn('ot_id', $common_ot)->whereBetween('created_at', array($from, $to))->get();
            // }
             //return redirect()->back()->with('error', 'UnAuthorized Access');
             return redirect()->route('unconfirmed.orders')->with('error', 'UnAuthorized Access');


        }

        else if(Auth::user()->role == 5){
            // if(empty($from)){
            //     $orders = Order::where('ot_id',Auth::id())->get();
            // }
            // else {
            //     $orders = Order::whereBetween('created_at', array($from, $to))
            //                 ->where('ot_id',$id)
            //                 ->get();
            // }
             return redirect()->route('unconfirmed.orders')->with('error', 'UnAuthorized Access');

            // return redirect()->back()->with('error', 'UnAuthorized Access');

        }   

        else{}
//        $orders = Order::all();
        return $this->index($orders,1);
    }
    
    public function subadminorder($subadminid)
    {
        
        $sub_admin = User::where('ot_of' , $subadminid)->pluck('id')->toArray();
        $ids = [$subadminid , $sub_admin];
        //dd($ids);
        $invoices = Order::whereIn('ot_id', $ids)->get();
        return view('subadmin_invoices', compact('invoices', 'sub_admin'));
    }
    
     public function subadminunconfirmedorder($subadminid){
         
         $sub_admin = User::where('ot_of' , $subadminid)->pluck('id')->toArray();
        $ids = [$admin_id , $sub_admin];
        
         $orders = Order::whereIn('ot_id', $ids)
                            ->where('is_confirmed_seller',NULL)
                            ->where('is_confirmed_admin',NULL)
                            ->get();
                            return $this->index($orders,2);
    }
    
     public function subadminsellerconfirmedorder($subadminid){
         
         $sub_admin = User::where('ot_of' , $subadminid)->pluck('id')->toArray();
        $ids = [$admin_id , $sub_admin];
        
         $orders = Order::whereIn('ot_id', $ids)
                            ->where('is_confirmed_seller',"!=", NULL)
                            ->where('is_confirmed_admin',NULL)
                            ->get();
                            return $this->index($orders,3);
    }
    
     public function subadminadminconfirmedorder($subadminid){
         
         $sub_admin = User::where('ot_of' , $subadminid)->pluck('id')->toArray();
        $ids = [$admin_id , $sub_admin];
        
         $orders = Order::whereIn('ot_id', $ids)
                            ->where('is_confirmed_admin',"!=",NULL)
                            ->get();
                            return $this->index($orders,4);
    }    
        // dd($creater , $customers , $shortestids , $ncustomers->get());

    

    public function getAllowedOts(){

        $ordertaker = Ordertaker::where('user_id' , Auth::id())->first();
        $allowed_ot = explode('|', $ordertaker->ot_customer_allowed);// allowed ots
        array_push($allowed_ot , Auth::id());// merge auth id and allowed ots
        return array_unique($allowed_ot);

    }

    public function createOrder($areaids = NULL , $lc = NULL){

//         $array = [
//     21, 24, 46, 52, 117, 215, 226, 227, 245, 255, 270, 271, 281, 283, 292, 296,
//     301, 311, 315, 316, 319, 323, 328, 330, 347, 349, 367, 369, 373, 386, 399,
//     409, 413, 416, 420, 427, 450, 451, 453, 454, 456, 457, 462, 472, 489, 492,
//     493, 494, 496, 501, 506, 507, 518, 520, 527, 530, 532, 539, 540, 568, 570,
//     581, 585, 586, 587, 591, 594, 613, 624, 631, 636, 638, 646, 655, 671, 683,
//     694, 695, 706, 717, 733, 739, 810, 811, 820, 821, 822, 856, 866, 867, 877,
//     887, 938, 944, 951, 954, 955, 964, 967, 971, 983, 986, 992, 996, 1031, 1041, 
//     1075, 1079, 1108, 1185, 1263, 1270, 1281, 1284, 1287, 1296, 1328, 1330,
//     1331, 1363, 1048
// ];



        
//          $c = Customer::whereIn('created_by' , $this->getAdminOt())->get();
        
        
// foreach ($c as $key => $a) {
//     $inv = Invoice::where('customer_id', $a->id)->orderBy('id', 'desc')->first()->amount_left ?? 0;
//     $order = Order::where('customer_id', $a->id)->select('subtotal', 'received_amount', 'discount')->get();

//     $total_balance = $order->sum('subtotal') - $order->sum('received_amount') - $order->sum('discount');

//     if ($inv != $total_balance) {
//         $balance[] = $a->id .' ' .$a->user->name . ' ' . 'Inv=' . $inv . ' ' . 'Ord=' . $total_balance;
//         $tb[] = $total_balance;

//         $diff = $total_balance - $inv;
//         $orders = Order::where('customer_id', $a->id);
//         $ids[] = $a->id;

//         if ($inv > $total_balance) {

//             $diff = $inv - $total_balance;
//             if ($orders->exists()) {
//                 $n = $orders->first();
//                 if ($total_balance < 0) {
//                     $n->received_amount = $n->received_amount - $diff;
//                 } else {
//                     $n->received_amount = $n->received_amount + $diff;
//                 }
//                 //$n->save();
//             }
//         }elseif($inv < $total_balance){
//             if($inv >= 0){
//                 if ($orders->exists()) {
//                 $n = $orders->first();

//                 $diff = $total_balance - $inv;
                
//                     $n->received_amount = $n->received_amount + $diff;
//                     //dd($inv , $total_balance , $n->received_amount , $diff);
                
//                 //$n->save();
//             }

//             }
//         } 

//         // else {
//         //     // Calculate the difference between the sum of orders and the last invoice
//         //     $diff = $total_balance - $inv;

//         //     // Update the received_amount of the first order
//         //     $firstOrder = $orders->first();
//         //     $firstOrder->received_amount -= $diff;
//         //     $firstOrder->save();
//         // }
//     }
// }


// dd($balance , array_sum($tb) , $diff , $ids);



        $sperate_area = explode(',', $areaids);
        foreach ($sperate_area as $area){
        $area_name[] = Area::where('id' , $area)->pluck('name')->first();    
        }
          
        $ids                    = [Auth::id()];
        $adminid                = [User::where('id' , Auth::id())->pluck('ot_of')->first()]; //this uses ordertaker to get his admin
        $adminots               = $this->getAdminOt();
        $area                   = Area::whereIn('created_by' , $adminots)->get();//this get all areas of same admin and his ot
        $ot                     = Ordertaker::where('user_id' , Auth::id());
        // ->select(
        //     'stored_ids',
        //     'show_unvisited',
        //     'do_not_sho_pndng_cst',
        //     'pndng_only'
        // )->first();
        // dd($ot->first());
        // $set_unvisited_check    = $ot->pluck('show_unvisited')->first();
        // $do_not_sho_pndng_cst   = $ot->pluck('do_not_sho_pndng_cst')->first();
        // $pndng_only             = $ot->pluck('pndng_only')->first();
        
        // $stord_ids = json_decode($ot->stored_ids, TRUE);
        // if($stord_ids != null && $areaids == NULL){ // if user has stored ids in 
        // $ids_ordered = implode(',', $stord_ids);
        // $customers = Customer::whereIn('id', $stord_ids)->orderByRaw("FIELD(id, $ids_ordered)");

        // $un_visit_customer = $customers->where('visit_clear' , 0)->pluck('user_id')->toArray();
        // $un_visit_customer = User::whereIn('id', $un_visit_customer)->pluck('name')->toArray();
        // }else{
        if (Auth::user()->role == 5){
            $data = $this->findOtCustomers($area);
            if($data == 'error'){
                return redirect()->back()->with('error', 'No Ordertaker Is Assigned for Customer Please Ask Admin To Assign Ordertaker!');
            }else{
                $otcustomers = $data['customers'];
                $area        = $data['area'];

               
            }
        }
        
        if($areaids != NULL){
         $areaids       = explode(',', $areaids);// sperate areas 
         $getcordslast  = explode(',', $lc);
         $cords         = @$getcordslast[0];
         $cords1        = @$getcordslast[1]; // sperate locations
          if (Auth::user()->role == 5){
                  $customers = $otcustomers->whereIn('area_id' , $areaids)->pluck('id')->toArray();// gets customer    
          }else{
         $customers = Customer::whereIn('created_by', $adminots)->whereIn('area_id' , $areaids)->pluck('id')->toArray();// gets arr customer ids
         //dd($arr , $customers , $areaids);
          }
        }else{// if user dont pass area and location
            if (Auth::user()->role == 5){
                 $customers = $otcustomers->pluck('id')->toArray();// gets customer    
            }else{
               $customers = Customer::whereIn('created_by', $adminots)->pluck('id')->toArray();// gets customer
            }
        }

        if(empty($customers)){
             return redirect()->route('add.customer')->with('error', 'No Customer Found! Please Create Customer First');
        }
        
         $shortestids = $this->sorteddata($cords ?? null ,$cords1  ?? null, $customers);
         $ids_ordered = implode(',', $shortestids);

         $customers = Customer::whereIn('id', $shortestids)
         ->orderByRaw("FIELD(id, $ids_ordered)")->select(
            'id',
            'address',
            'phone',
            'location_url',
            'visit_clear',
            'customer_name',
            'user_id',
            'created_by',
            'area_id'
        );

        //}
        if (Auth::user()->role == 5){
             $date = \Carbon\Carbon::today()->subDays(1);
             $ot   = Ordertaker::where('user_id' , Auth::id())->first();
             $set_unvisited_check    = $ot->show_unvisited;
       
           if ($ot && $ot->show_unvisited == 1){
                 $customers = $customers->where([
                    'ot_del_customer' => 0 ,  
                    'visit_clear' => 0 , 
                    'order_exist' => 0 , 
                    'do_not_show' => 0
                ])->where('last_order_date' , "<=" , $date);
                $customers = $customers->get();
         //        $ordertaker = Ordertaker::where('user_id' , Auth::id());
         //       $alloewd_area_data_check = $ordertaker->pluck('store_varae_isit_data')->first();// gets allowed ot of other ot
         // if ($alloewd_area_data_check == 1){
         //     $are = implode(",",$area_name);
         //     $get_total_customers = $customers->pluck('id')->count(); 
         //     $ot_of = User::where('id', Auth::id())->pluck('ot_of')->first();
         //     if ($ordertaker->first()->eneble_per_visit_price == 1){
         //        $cost_per_visit = 1100 / $get_total_customers;
         //      //  dd($cost_per_visit);
         //        $ordertaker->update(['per_visit_price' => $cost_per_visit]);

         //     }
         //     SubAdmin::where('sub_admin_id' , $ot_of)->update(['total_customer' => $get_total_customers , 'ot_visit_area' => "$are"]);
         // }       
           }elseif ($ot->pndng_only == 1){
               $customers = $customers->where([
                'ot_del_customer' => 0 , 
                'customer_pending' => 1
            ])->get();
           }elseif ($ot->do_not_sho_pndng_cst == 1){
               $customers = $customers->where([
                'ot_del_customer' => 0 , 
                'customer_pending' => 0
            ])->get();
           }else{
               $customers = $customers->where(
                'ot_del_customer' , 0 )->get();
           }
           $shortestids = "";
           $un_visit_customer = "";
           $this->setAreaVisitProfit($ot->show_unvisited , $customers , 
$customers , $areaids);
        }else{
          //  dd($customers->pluck('id')->toArray());
            $customers = $customers->get();
        }
        // if (($key = array_search($adminid, $adminots)) !== false) {
        //         unset($adminots[$key]);
        //     }
        $ordertaker = User::whereIn('id', $adminots);
        if(Auth::user()->role < 3){
        // $ordertaker = $ordertaker->where('ot_of', Auth::id());
        if (empty($un_visit_customer)){
            $un_visit_customer = null;    
        }
        $set_unvisited_check = '';
        }
        $ordertaker = $ordertaker->get();

        
        return view('orders.create' , compact('customers', 'ordertaker' , 'ids' , 'shortestids' , 'area' , 'un_visit_customer' , 'set_unvisited_check' , 'area_name'));
    }


    public function findOtCustomers($area){

         $query = Ordertaker::where('user_id' , Auth::id())->first();// gets allowed ot of other ot
        $alloewd_cst_ot = $query->ot_customer_allowed;
        $allowed_areas =  $query->allowed_areas;
        if(empty($allowed_areas)){ 
            $data['area'] = $area;   // Area::where('created_by' , Auth::id())->get();
        }else{
            $allowed_areas = explode('|', $allowed_areas);
            if(!empty($alloewd_cst_ot)){
                $data['area'] = Area::whereIn('id' , $allowed_areas)->orWhere('created_by' , Auth::id())->get();
            }else{
                $data['area'] = $area; 
            }
        }

        
         if(empty($alloewd_cst_ot)){
            return 'error';
         }
         $allowed_ot = explode('|', $alloewd_cst_ot);
         $arr = array_merge([Auth::id()] , $allowed_ot);// merge auth id and allowed ots
         $data['customers'] = Customer::whereIn('created_by', $arr)->select('id' , 'area_id')->get();
         return $data;// gets arr customer ids isko otcutomer sy hta kr customer pr change kia hy
        
    }

    public function setAreaVisitProfit($set_unvisited_check , $customers , 
$ot_customers , $areaids){

        // dd($set_unvisited_check , $customers->pluck('id')->toArray() , $ot_customers->pluck('id')->toArray() , $areaids);

        if (!empty($set_unvisited_check) && !empty($areaids)) {
            $total_customer = $ot_customers->pluck('id')->count();
            $ot = Ordertaker::where('user_id', Auth::id())->first();
            $user = User::where('id', Auth::id())->first(); // Fixed variable name

            $currentDate = Carbon::now()->toDateString();

            $todayRecords = OtAreaVisitsProfit::where(['user_id' => Auth::id(), 'total_areas' => count($areaids)])
                ->whereIn('area_id', $areaids)
                ->where('visit_date', $currentDate) // Remove date() function here
                ;

            if ($todayRecords->exists() == true) {
                $todayRecords = $todayRecords->first();
                // The record exists for today, you can use it if needed
            } else {
                foreach ($areaids as $ar) {
                     $todayRecords = $this->storeOtAreaVisitsProfit($ar, $total_customer, count($areaids));
                }
            }

            if($ot->eneble_per_visit_price == 1 && $ot->auto_area_price == 1 && !empty($ot->total_area_profit)){
                $ot_visit_profit =  $ot->total_area_profit / $todayRecords->total_customer;
                $user->ot_visit_profit = $ot_visit_profit;
                $user->save();

                //dd($ot_visit_profit);
            }

             
    }



    }

    public function storeOtAreaVisitsProfit($ar , $total_customer , $total_areas){

        $ot = new OtAreaVisitsProfit();

        $ot->user_id = Auth::id();
        $ot->area_id = $ar;
        $ot->total_customer = $total_customer;
        $ot->total_areas    = $total_areas;
        $ot->visit_date     = Carbon::today();
        $ot->save();

        return $ot;
        
    }
    
    
    public function CreateOrderByCustomer(){
        
        
        $customers = Customer::where('id' , Auth::id())->get();
        $customer_id = Auth::id();
        
        
         return view('orders.createorderbycustomer' , compact('customers', 'customer_id'));
    }
    
    
    public function sorteddata($cords = NULL, $cords1 = NULL, $idss = NULL)
{
    $new_ids = "empty";
    if ($cords == NULL && $cords1 == NULL) {
        $cords = 31.5815886;
        $cords1 = 74.3779746;
    }

    $customersids = $idss; // this id should be updated for looping

    for ($a = 0; $a < 3000; $a++) {
        $customerscords = Customer::whereIn('id', $customersids)->pluck('location_url')->toArray();
        $shortest = array();
        foreach ($customerscords as $short) {
            $shortest[] = $this->getsortedDistance($cords, $cords1, $short);
        }

        $maping = array();
        for ($i = 0; $i < sizeof($customersids); $i++) {
            if (isset($shortest[$i])) { // Check if $shortest array has enough elements
                $maping[$customersids[$i]] = $shortest[$i];
            }
        }

        $key_val = array();
        if (!empty($maping)) {
            $min_distance = min($maping);
            $key_val = array_keys($maping, $min_distance);

            // Check if the key 20 exists in $key_val array
            if (!in_array(20, $key_val)) {
                // Add key 20 to $key_val array if it doesn't exist
                $key_val[] = 20;
            }
        }

        if ($new_ids == "empty") { // this stores sorted ids
            $new_ids = $key_val;
        } else {
            $new_ids = array_merge($new_ids, $key_val);
        }

        $get_last_id = end($new_ids);
        $getcordslastid = Customer::where('id', $get_last_id)->pluck('location_url')->first();
        $getcordslast = explode(',', $getcordslastid);
        $cords = @$getcordslast[0];
        $cords1 = @$getcordslast[1];
        $customersids = array_diff($customersids, $new_ids); // this sets customer ids for looping
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
    
    //AJAX function for order details
   public function getorderDetail($id){

        $receipt = ReceiptSetting::where('user_id' , Auth::id())->orWhere('user_id' , Auth::user()->ot_of)->orWhere('user_id' , Auth::user()->seller_of)->first();
        $order = Order::find($id);

        $check = Order::where('customer_id' , $order->customer_id)->orderBy('id' , 'desc')->first();
        if($check->id != $id){
             return redirect()->back()->with('error', 'Update Failed! Note: Only Last Order Can be Update');
        }
        $ordertaker = Ordertaker::find($id);
        $idetails = Order::find($id)->orderdetail;
        $prev_orders = Order::where('id' , '<' , $id)->where('customer_id' , $order->customer_id)->orderBy('id' , 'desc')->get();
        $prev_order = $prev_orders[0] ?? 0;
        $bill_no = count($prev_orders);

        $PosClass = new PosController();
        $GetCashPaidhistory = $PosClass->GetCashPaidHistory($order->customer_id , $bill_no);
        $stock_purchase = $this->GetSumOrderSubtotal($order->customer_id);

        //dd($receipt);

        

       // dd($bill_no , $GetCashPaidhistory);
        return view('ajax.order_detail' , compact('idetails' , 'order' , 'prev_order' , 'bill_no' , 'ordertaker' , 'GetCashPaidhistory' , 'stock_purchase' , 'receipt'));
    }
    
    // customer orders incomplete
    
    public function customerOrders($customer_id, $from = "", $to = "")
    {
        if ($from != "") {
            $orders = Order::where(['customer_id' => $customer_id])->whereBetween('created_at', array($from, $to))->get();
        } else {
            $orders = Order::where(['customer_id' => $customer_id])->get();
        }
        $customer = Customer::find($customer_id);


        $products = Product::where('user_id', Auth::id())->get();
        $product_report = [];
        $counter = 0;
        foreach ($products as $p) {
            $ppunit = 0;
            $ppamount = 0;
            $product_report[$counter]['id'] = $p->id;
            $product_report[$counter]['name'] = $p->name;
            foreach ($orders as $in) {
                $idet =  $in->orderdetail->where('product_id', $p->id);
                $ppunit += $idet->sum('unit');
                $ppamount += $idet->sum('p_amount');
            }
            $product_report[$counter]['unit'] = $ppunit;
            $product_report[$counter]['amount'] = ($p->p_price * $ppunit);
            $product_report[$counter]['tamount'] = ($p->price * $ppunit);
            $product_report[$counter]['checks'] = $ppamount;

            $counter++;
        }

        // if ($customer->created_by == Auth::id() || Auth::user()->role == 1 || Auth::user()->customer_id == $customer_id) {
            return view('orders.customer_orders', compact('orders', 'customer', 'product_report'));
        // } else {
        //     return redirect()->back()->with('error', 'Invalid Request');
        // }
    }


    public function getOrderTakerOrders(Request $request ,$ot_id = null)
    {

        if (empty($ot_id)){
            $ot_id = $request->id;
        }
       // dd($ot_id);
        set_time_limit(0);
        if ($request->expectsJson()) {
            $type = 'get';
        } else {
            $type = 'paginate';
            $perPage = 500;
        }
        
        if ($request->from != "") {
            if(empty($request->to)){
                $to = date('Y-m-d');
            }else{
                $to = $request->to;
            }
            $orders = Order::where(['ot_id' => $ot_id])->whereBetween('created_at', array($request->from, $to))->whereNotNull('is_confirmed_admin');
            } else {
                $orders = Order::where(['ot_id' => $ot_id])->whereNotNull('is_confirmed_admin');
            }

            if ($type === 'paginate') {
                $orders = $orders->paginate($perPage);
            }
        

        $products = Product::where('user_id', Auth::id())->get();
        $product_report = [];
        $counter = 0;
        foreach ($products as $p) {
            $ppunit = 0;
            $ppamount = 0;
            $product_report[$counter]['id'] = $p->id;
            $product_report[$counter]['name'] = $p->name;
            foreach ($orders as $in) {
                $idet =  $in->orderdetail->where('product_id', $p->id);
                $ppunit += $idet->sum('unit');
                $ppamount += $idet->sum('p_amount');
            }
            $product_report[$counter]['unit'] = $ppunit;
            $product_report[$counter]['amount'] = ($p->p_price * $ppunit);
            $product_report[$counter]['tamount'] = ($p->price * $ppunit);
            $product_report[$counter]['checks'] = $ppamount;

            $counter++;
        }

        $title = 'Order Tacker Orders';
        $details = 'Order Tacker Orders list';




         if ($request->expectsJson()) {
            $orders = $orders->join('customers' , 'customers.id' , 'orders.customer_id')
        ->join('users' , 'users.id' , 'customers.user_id')
        ->join('users as ot' , 'ot.id' , 'orders.ot_id')
        ->join('areas' , 'areas.id' , 'customers.area_id')
        ->select(
            'users.name',
            'customers.id as customer_id',
            'customers.customer_name as shop_name',
            'customers.phone',
            'customers.address',
            'customers.location_url as customer_location',
            'customers.call_customer',
            'orders.id as order_id',
            'orders.unit',
            'orders.amount',
            'orders.subtotal',
            'orders.discounted_subtotal',
            'orders.p_amount',
            'orders.received_amount',
            'orders.discount',
            'orders.amount_left',
            'orders.advance',
            'orders.c_benefit',
            'orders.order_comments',
            'orders.cancel_status',
            'orders.cancel_reason',
            'orders.urgent',
            'orders.clear',
            'orders.order_date',
            'orders.is_important',
            'orders.is_confirmed_seller',
            'orders.is_confirmed_admin',
            'orders.seller_processed_order',
            'orders.selected_seller',
            'orders.location_url_ot',
            'orders.ot_customer_distance',
            'orders.chk_ord_vst',
            'orders.use_stock',
            'areas.name as area_name',
            'ot.name as ordertaker_name',
            'orders.ot_id'

        )->get();



        $data['orders'] = $orders;

        $data['orderDetail'] = OrderDetail::whereIn('order_id' , $data['orders']->pluck('order_id')->toArray())->join('products' , 'products.id' , 'order_details.product_id')->select('order_details.*' , 'products.name')->get();
        
        return response()->json([
            'code' => '200',
            'status' => 'success',
            'message' => 'Data Fetch Successfully!',
            'data' => $data
        ]);
    } else{
        return view('orders.ot_orders', compact('orders', 'ot_id',  'product_report' , 'title' , 'details'));
    }
            
        // } else {
        //     return redirect()->back()->with('error', 'Invalid Request');
        // }
    }

    public function getOrderSellerOrders(Request $request ,$ot_id)
    {
        
        if ($request->from != "") {
            if(empty($request->to)){
                $to = date('Y-m-d');
            }else{
                $to = $request->to;
            }
            
            $orders = Order::where(['seller_processed_order' => $ot_id])->whereBetween('created_at', array($request->from, $to))->whereNotNull('is_confirmed_admin')->paginate(10000);
        } else {
            $orders = Order::where(['ot_id' => $ot_id])->whereNotNull('is_confirmed_admin')->paginate(10000);
        }
        

        $products = Product::where('user_id', Auth::id())->get();
        $product_report = [];
        $counter = 0;
        foreach ($products as $p) {
            $ppunit = 0;
            $ppamount = 0;
            $product_report[$counter]['id'] = $p->id;
            $product_report[$counter]['name'] = $p->name;
            foreach ($orders as $in) {
                $idet =  $in->orderdetail->where('product_id', $p->id);
                $ppunit += $idet->sum('unit');
                $ppamount += $idet->sum('p_amount');
            }
            $product_report[$counter]['unit'] = $ppunit;
            $product_report[$counter]['amount'] = ($p->p_price * $ppunit);
            $product_report[$counter]['tamount'] = ($p->price * $ppunit);
            $product_report[$counter]['checks'] = $ppamount;

            $counter++;
        }
        $title = 'Seller Orders';
        $details = 'Seller Orders list';

        // if ($customer->created_by == Auth::id() || Auth::user()->role == 1 || Auth::user()->customer_id == $customer_id) {
            return view('orders.ot_orders', compact('orders', 'ot_id',  'product_report' , 'title' , 'details'));
        // } else {
        //     return redirect()->back()->with('error', 'Invalid Request');
        // }
    }
     
        public function CashProcessedrOrders($req, $from = "", $to = "")
    {
        $order_id_array = SellerSell::where('id' , $req)->first();
        $order_ids = explode('|', $order_id_array->order_ids);
        if ($from != "") {
            $orders = Order::whereIn(['id' => $order_ids])->whereBetween('created_at', array($from, $to))->get();
        } else {
            $orders = Order::whereIn('id' , $order_ids)->get();
        }
        $product_report  = $this->getproductreport($orders);
            return view('orders.cash_processed_orders', compact('orders', 'product_report'));
    
    }

    
    public function getorderDetailMultiples(Request $request)
    {
        $ids = explode(':', $request->ids);

        $data = Order::whereIn('id', $ids)->get();

        foreach ($data as $key => $row) {
            $data[$key]['order_details'] = Order::find($row->id)->orderdetail;
            
            $data[$key]['ordertakers'] = User::where('role',5)->whereIn('id', $data->pluck('ot_id')->toArray())->get();

            $prev_invoices = Order::where('id', '<', $row->id)->where('customer_id', $row->customer_id)->orderBy('id', 'desc')->get();
            $data[$key]['prev_invoices_details'] = $prev_invoices[0];
            $data[$key]['bill_no'] = count($prev_invoices);
        }

        return view('ajax.order_detail_ajax', compact('data'));
    }
    

    public function dateFilter( $from,$to ){
        $from = str_replace("/" , "-" , $from);
        $to = str_replace("/" , "-" , $to);

        if($to == null){
            $to = date('Y-m-d');
        }
        if($from == null){
            $from = date('Y-m-d');
        }
        return array($from , $to);
    }

    public function checkMinBalance($cid , $balance){
        $customer = Customer::find($cid);
        $old_customer_balance = Order::where(['customer_id' => $cid])->orderBy('id' , 'desc')->first()->balance_limit;
        if(($old_customer_balance + $balance) > $customer->balance_limit){
            return $customer->balance_limit;
        }
        return false;
    }

    private function distance($lat1, $lon1, $lat2, $lon2, $unit = "K") {

        if (empty($lat1) || empty($lon1) || empty($lat2) || empty($lon2)) {
            return 0;
        }
        //dd($lat1, $lon1, $lat2, $lon2, $unit = "K");        
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
    // calculate ot home location
    private function othomdistance($lat1, $lon1, $lat2, $lon2, $unit = "K") {
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

    private function getDistance($customer , $getOTLocation){

        if(empty($customer)){
            $customer = '31.5815886,74.3779746';
        }
        if($getOTLocation){
            $getOTLocation = '31.5815886,74.3779746';
        }
        
        $customerLocation = explode(',', $customer);
        $cLat1 = @$customerLocation[0];
        $cLat2 = @$customerLocation[1];

        $customerLocation = explode(',', $getOTLocation);
        $otLat1 = @$customerLocation[0];
        $otLat2 = @$customerLocation[1];

        return $this->distance($cLat1, $cLat2, $otLat1, $otLat2, 'K');

    }
    
    // get ot home location
    
    private function getothomDistance(Order $order){
        
        $customer = $order->ordertaker->ot_hom_location;
        $customerLocation = explode(',', $customer);

        
        $cLat1 = @$customerLocation[0];
        $cLat2 = @$customerLocation[1];

        $getOTLocation = $order->location_url_ot;
        $customerLocation = explode(',', $getOTLocation);
        $otLat1 = @$customerLocation[0];
        $otLat2 = @$customerLocation[1];

        if(!empty($customer)){
            return $this->othomdistance($cLat1, $cLat2, $otLat1, $otLat2, 'K');

        }else{
            return 0;

        }

        

    }

    public function storeOrderApi(Request $request){

        //dd($request->all());
        $customer = Customer::where('id' , $request->customer_id)->first();
        $old_inv  = Invoice::where(['customer_id' => $customer->id])->orderBy('id', 'desc')->first();
        $products    = json_decode($request->products , true);
        foreach($products as $product){
            $product_id[] = $product['id'];
            $price[]      = $product['price'];
            $unit[]       = $product['unit'];
            $amount[]     = $product['price'] * $product['unit'];

        }
        $data = new Request
        ([
        'product_id'  =>$product_id,
        'price'       =>$price,
        'unit'        =>$unit,
        'amount'      =>$amount,
        'customer_id' => $customer->id.'-'.$customer->user->name,
        'ot_id'       => $request->ot_id,
        'old_balance' => $old_inv->amount_left,
        'discount'    => $request->discount,
        'location_url_ot' => $request->location_url_ot,
        'advance'     => $request->advance,
        'order_date'  => $request->order_date
        ]);
        $order = $this->storeOrder($data);
        $json_data = json_decode(json_encode($order));
        if($json_data->original->message == 'Order Already exist You can Edit Old Order!'){
            $message = $json_data->original->message;
        }elseif($json_data->original->message == 'Order Created'){
            $message = 'Order Store Successfully!';
             return response()->json([
                'code' => 200 , 
                'status'=>'Success' , 
                'message'=>$message , 
                'data' => $data
            ]);
        }else{
            return response()->json([
                'code' => 500 , 
                'status'=>'Error' , 
                'message'=>$json_data->original->message , 
                'data' => $data
            ]);
        }
         
    }

    public function storeOrderNew(Request $request){

        $customer_id        = explode('-' , $request->customer_id)[0];
        $customer           = Customer::where('id' , $customer_id)->first();
        $product_data       = $this->findCustomerProductValue($customer_id , $request->product_id);
        $orderDetails       = $this->calculateOrderDetails($request , $product_data);
        $old_orders         = $this->findCustomerOldorders($customer_id , $update = false);
        //dd($old_orders);
        $orderCalculation   = $this->calculateSubtotalAmountAnddiscounted_subtotal($customer_id , $orderDetails , $old_orders , ($request->ot_id ?? Auth::id()) , $request);
        $distance           = $this->getDistance($customer->location_url , $request->location_url_ot);
        $chk_ord_vst        = $this->checkOrderVisit($distance , $customer_id , $request->ot_id);

        $storeOrder         = $this->StoreOrderDataInTable($request , $orderDetails , $old_orders , $orderCalculation , $distance , $chk_ord_vst);
        $storeOrderDetail   = $this->storeOrderDetailInTable($storeOrder , $orderDetails);

        return $storeOrder;
    }

    protected function storeOrderDetailInTable($order , $orderDetails){
        
        foreach($orderDetails['unit'] as $key => $data){
            $storeOrderDetails = new OrderDetail();
            $storeOrderDetails->order_id    = $order->id;
            $storeOrderDetails->product_id  = $orderDetails['product_id'][$key];
            $storeOrderDetails->unit        = $orderDetails['unit'][$key];
            $storeOrderDetails->amount      = $orderDetails['amount'][$key];
            $storeOrderDetails->p_amount    = $orderDetails['p_price'][$key];
            $storeOrderDetails->ot_benefit  = $orderDetails['ot_benefit'][$key];
            $storeOrderDetails->c_benefit   = $orderDetails['c_benefit'][$key];

            $storeOrderDetails->save();
        }
    }

    protected function calculateOtBenefit($ot_id , $product_id){

        $ot = CustomOtBenefit::where('ot_id' , $ot_id)->where('product_id' , $product_id);
        if($ot->exists()){
            return $ot->first()->ot_benefit;
        }else{
            return 0;
        }
    }

    protected function checkOrderVisit($getDistanceValue , $customer_id , $ot_id){
        
        $old_order = Order::where('customer_id' , $customer_id)->orderBy('id' , 'desc');
         

            if ($old_order->exists()){
                 $chk_ord = $old_order->first()->chk_ord_vst; 
            }else{
                 $chk_ord = 0;    
            }
            
        if(Auth::user()->role < 3 || Auth::user()->role == 4){
            return $chk_ord;
        }
        $ordertaker =  OrderTaker::where('user_id' , $ot_id);
        if($ordertaker->exists()){
            $ordertaker = $ordertaker->first()->compare_ot_distance;
        
                if($getDistanceValue * 1000 > $ordertaker){
                if($chk_ord == 0)
                {
                    $chk_ord_vst = 1;
                }
                elseif($chk_ord != 0){
                $chk_ord_vst = $chk_ord + 1;
                }
                }
                elseif($getDistanceValue * 1000 <= $ordertaker ){
                    if($chk_ord >= 4){
                     $chk_ord_vst = 2;
                    }
                    elseif($chk_ord == 3){
                     $chk_ord_vst = 2;
                    }
                    elseif($chk_ord == 2){
                     $chk_ord_vst = 1;
                    }
                    elseif($chk_ord <= 1){
                    $chk_ord_vst = 0;
                    }
                }
            }else{
                $chk_ord_vst = 0;
            }

                return $chk_ord_vst;
            
    }

    protected function findCustomerProductValue($customer_id , $product_id){

        foreach($product_id as $p){
            $check_product = CustomPrice::where(['customer_id' => $customer_id , 'product_id' => $p]); 
            if($check_product->exists()){
                $products[] = $check_product->first();
            }else{
                $products[] = Product::where('id' , $p)->select('id as product_id' , 'price' , 'c_benefit')->first();
            }
        }
        return $products;
    }

    protected function findCustomerOldorders($customer_id , $order_id = false){
        $orders = Order::where('customer_id', $customer_id)->whereNotIn('id' , [$order_id])->select('subtotal', 'received_amount', 'discount')->get();
         if ($orders->count() <= 1) {
        $defaultResult = collect([
            [
                'subtotal'          => 0,
                'received_amount'   => 0,
                'discount'          => 0,
            ]
        ]);
            $orders = $orders->concat($defaultResult);
        }
        return $orders;
    }


    protected function calculateOrderDetails($request , $product_data){

        $arrayWithoutNull = array_filter($request->unit, function ($value) {
            return $value !== null;
        });
        $request->unit              = $arrayWithoutNull;
        foreach($request->unit as $key => $unit){
            if (Auth::user()->role < 3) {
                $data['amount'][]       = $unit * $request->price[$key];
            }else{
                $data['amount'][]       = $unit * $product_data[$key]->price;
            }
            $data['p_price'][]          = $unit * Product::where('id' , $product_data[$key]->product_id)->first()->p_price;
            $data['product_id'][]       = $product_data[$key]->product_id;
            $data['unit'][]             = $unit;
            if(!empty($request->ot_id)){
                $data['ot_benefit'][]     = $unit * $this->calculateOtBenefit($request->ot_id , $product_data[$key]->product_id);    
            }else{
                $data['ot_benefit'][]     = 0; 
            }
            $data['c_benefit'][]    = $unit * $product_data[$key]->c_benefit;
        }
        return $data;
    }

    protected function calculateSubtotalAmountAnddiscounted_subtotal($customer_id , $orderDetails , $old_orders , $ot_id , $request){

        $ordertaker = OrderTaker::where('user_id' , $ot_id)->first();
        $total_amount = $old_orders->sum('subtotal') - $old_orders->sum('received_amount') - $old_orders->sum('discount');

        if (!empty($ordertaker) 
            && 
            $ordertaker->discount_on_off == 1 
            && 
            Customer::where('id' , $customer_id)->first()->user->discount_on_off == 0
        ){
           $data['subtotal']            = array_sum($orderDetails['amount'])   - (array_sum($orderDetails['amount']) * ($ordertaker->customer_discount/100));
           $data['discounted_subtotal'] = array_sum($orderDetails['amount']) - $data['subtotal'];
           $data['total_amount']        = $total_amount + $data['subtotal'];
           $data['amount_left']         = $data['total_amount'] -  $request->received_amount - $request->discount;
        }else{
                $data['subtotal']       = array_sum($orderDetails['amount']);    
                $data['total_amount']   = $total_amount + $data['subtotal'];
                $data['amount_left']    = $data['total_amount'] - $request->received_amount - $request->discount;
                $data['discounted_subtotal'] = 0;
            }
        return $data;
    }


    protected function StoreOrderDataInTable($request , $orderDetails , $old_orders , $orderCalculation , $distance , $chk_ord_vst){

        $storeOrder = New Order();
        if($request->ot_id){
                $storeOrder->user_id = $request->ot_id;
                 $storeOrder->ot_id  = $request->ot_id;
        }else{
            $storeOrder->user_id = Auth::id();
            $storeOrder->ot_id   = Auth::id();    
        }
        if(Auth::user()->role == 4){
            $storeOrder->ot_id = Customer::where('user_id', Auth::id())->first()->created_by;
        }
        $storeOrder->customer_id    = explode('-' , $request->customer_id)[0];
        $storeOrder->unit           = array_sum($request->unit);
        $storeOrder->amount         = $orderCalculation['total_amount'];
        $storeOrder->subtotal       = $orderCalculation['subtotal'];
        $storeOrder->discounted_subtotal = $orderCalculation['discounted_subtotal'];
        $storeOrder->p_amount        = array_sum($orderDetails['p_price']);
        if(empty($request->received_amount)){
            $request->received_amount = 0;
        }
        $storeOrder->received_amount = $request->received_amount;
        $storeOrder->discount        = $request->discount;
        $storeOrder->amount_left     = $orderCalculation['amount_left'];
           if($storeOrder->amount_left < 0){
                $storeOrder->advance = $storeOrder->amount_left;
           }
        $storeOrder->ot_benefit      = array_sum($orderDetails['ot_benefit']);
        $storeOrder->c_benefit       = array_sum($orderDetails['c_benefit']);
        $storeOrder->order_comments  = $request->order_comments;
           if($request->has('urgent')){
                    $storeOrder->urgent = 'urgent' ;
           }
        $storeOrder->order_date       = date('Y/m/d', strtotime($request->order_date));
        $storeOrder->allow_next_order = 0;// may be need to update
        $storeOrder->location_url_ot  = $request->location_url_ot;
        $storeOrder->ot_customer_distance = $distance;// may be need to check
        $storeOrder->chk_ord_vst      = $chk_ord_vst;// need to work
           if($request->use_stock){
            $storeOrder->use_stock      = 0;
           }
        $storeOrder->save();
        return $storeOrder;

     }

    public function storeOrder(Request $request , $update = null){

        try{
            DB::beginTransaction();  
        $cus_det = explode("-", $request->customer_id);
    
        $get_customer_order = Order::where('customer_id' , $cus_det[0])->whereNull('is_confirmed_admin');    
         if ($get_customer_order->exists() == true && empty($update)){
            $get_order =   $get_customer_order->first();
            if(empty($get_order->seller_processed_order)){
                return response()->json(['message'=> 'Order Already exist You can Edit Old Order!']);
            }      
        }
        $order = $this->storeOrderNew($request);
        if($order->id){
            Customer::where('id' , $cus_det[0])->update(['order_exist' => 1]);
            DB::commit();
             if(Auth::user()->role == 4){
                return back()->with('success' , 'Order Created');
             }else{
                return response()->json(['message'=> 'Order Created']);
             }
        }    
     }catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
          return response()->json(['message'=> "File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage()]);
        }
    }
    public function notifyAdmin($invoice_id)
    {
        $notify = new Notification();
        $notify->user_id = Auth::id();
        if(Auth::user()->role == 3)
         {
            $notify->notify_to = Auth::id();
         }
         elseif (Auth::user()->role == 5){
             $notify->notify_to = Auth::user()->ot_of;
         }
         else
         {
             $notify->notify_to = 10;
         }
        $notify->invoice_id = $invoice_id;
        $notify->save();
    }
    
    public function orderprofit($id){
        
        $orderprofit = Ordertaker::where('user_id', $id)->sum('today_ord_profit');
        $profit = User::findOrFail($id);
        $profit = $profit->ot_fixed_profit;
        $orderprofit = $orderprofit + $profit;
        $updateData = ['today_ord_profit' => $orderprofit];
        Ordertaker::where('user_id', Auth::id())->update($updateData);
        
    }

    public function getOrder($id , $unconfirm = NULL){

        $order = Order::where('id' , $id)->first();

        $check = Order::where('customer_id' , $order->customer_id)->orderBy('id' , 'desc')->first();
        if($check->id != $id){
             return redirect()->back()->with('error', 'Update Failed! Note: Only Last Order Can be Update');
        }

        $allowed_products = explode('|', $order->customers->final_allowed_products);
        if(Auth::user()->role == 5){
            $query = Ordertaker::where('user_id' , Auth::id())->first();
           if($query->allow_to_edit_order == 0){
            return redirect()->back()->with('error', 'UnAuthorized Access');
           }
            $ot_products = $order->ot->customOtbenefit->pluck('product_id')->toArray();
            $allowed_products = array_intersect($allowed_products, $ot_products);
        }
        $admin_id = $this->findMyAdmin();

        $products = Product::whereIn('id' , $allowed_products)
        ->orderBy('category_id', 'asc')
        ->orWhere('allow_to_all_customer' , 1)
        ->where('user_id' , $admin_id)
        ->get();

        $old_orders = $this->findCustomerOldorders($order->customer_id , $order_id = $id);
        $amount_left = $old_orders->sum('subtotal') - $old_orders->sum('received_amount') - $old_orders->sum('discount'); 
          
         
        
        if ($unconfirm == 'ajax'){
             return view('orders.edit_ajax' , compact('order' , 'products' , 'amount_left'));
        }else{
             return view('orders.edit' , compact('order' , 'products' , 'amount_left' ));
        }
        }
       


    public function updateOrder(Request $request , $id){
        
        //dd($request->all());

        $product_data       = $this->findCustomerProductValue($request->customer_id , $request->product_id);
        $orderDetails       = $this->calculateOrderDetails($request , $product_data);
        $old_orders         = $this->findCustomerOldorders($request->customer_id , $order_id = $request->id);
        $orderCalculation   = $this->calculateSubtotalAmountAnddiscounted_subtotal($request->customer_id , $orderDetails , $old_orders , ($request->ot_id ?? Auth::id()) , $request);

        $storeOrder                 = Order::where('id' , $request->id)->first();
        $storeOrder->unit           = array_sum($request->unit);
        $storeOrder->amount         = $orderCalculation['total_amount'];
        $storeOrder->subtotal       = $orderCalculation['subtotal'];
        $storeOrder->discounted_subtotal = $orderCalculation['discounted_subtotal'];
        $storeOrder->p_amount        = array_sum($orderDetails['p_price']);
        $storeOrder->received_amount = $request->received_amount;
        $storeOrder->discount        = $request->discount;
        $storeOrder->amount_left     = $orderCalculation['amount_left'];
           if($storeOrder->amount_left < 0){
                $storeOrder->advance = $storeOrder->amount_left;
           }
        $storeOrder->ot_benefit      = array_sum($orderDetails['ot_benefit']);
        $storeOrder->c_benefit       = array_sum($orderDetails['c_benefit']);
        $storeOrder->order_comments  = $request->order_comments;
           if($request->has('urgent')){
                    $storeOrder->urgent = 'urgent' ;
           }
           if($request->order_date){
                $storeOrder->order_date = date('Y/m/d', strtotime($request->order_date));    
           }
           if($request->use_stock){
            $storeOrder->use_stock      = 0;
           }
        $storeOrder->save();

        OrderDetail::where('order_id' , $storeOrder->id)->delete();
        $storeOrderDetail   = $this->storeOrderDetailInTable($storeOrder , $orderDetails);

         if(Auth::user()->role == 4){
                return redirect()->route('create.order.by.customer')->with('Success' , 'Order Updated');
         }else{
            return response()->json(['message'=> 'Order Updated!']);
           
         }
    }


    public function deleteOrder($id){
        
        if(Auth::user()->role == 5){
           $query = Ordertaker::where('user_id' , Auth::id())->first();
           if($query->allow_to_delete_order == 0){
            return redirect()->back()->with('error', 'UnAuthorized Access');
           }
        }
        $order = Order::find($id);
        $check = Order::where('customer_id' , $order->customer_id)->orderBy('id' , 'desc')->first();
        if($check->id != $id){
             return redirect()->back()->with('error', 'Deletion Failed! Note: Only Last Order Can be Delete');
        }
        if(!empty($order)){
            Customer::where('id' , $order->customer_id)->update(['order_exist' => 0]);
            Order::where('id' , $order->id)->delete();
            OrderDetail::where('order_id' , $id)->delete();
            return Common::Message("Order" , 3);
        }else{
            return Common::Message("Order");
        }   
    }
    
    public function equalOrder(Request $request){
            $order = Order::findOrFail($request->user_id);
            if($order->received_amount == 0 && $order->amount_left >= 0)
            {
            $order->received_amount = $order->subtotal - $order->discount;
            $order->amount_left = $order->amount_left - $order->received_amount;
            $order->save();
            }
            return response()->json(array('success' => true, 'message' => 'Subtotal Received Successfully!'));
        }
        
        public function updateStatus(Request $request){
        $order = Order::findOrFail($request->user_id);
        $order->cancel_status = $request->cancel_status;
        $order->save();
        return response()->json(['message' => 'User status updated successfully.']);
        }
        
        public function clearOrder(Request $req){
            
        $order = Order::find($req->order_id);
            Order::where('id' , $order->id)->get();
            $order->clear = 'clear';
            $order->save();
             return response()->json(['success'=> true, 'message'=> 'Clear Order Successfully!']);
     
        }
        
        public function unclearOrder($id){
        $order = Order::find($id);
            Order::where('id' , $order->id)->get();
            $order->clear = NULL;
            $order->save();
            return Common::Message("Order" , 2);
        }
        
        
        public function updatereceiving(Request $request , $id){
        $order = Order::find($id);
            Order::where('id' , $order->id)->get();
            
             if ($request->received_amount != NULL && $request->amount_left != NULL)
             {
                 $order->received_amount = $request->received_amount;
                 $order->amount_left = $request->amount_left ;
                 $order->order_comments = $request->order_comments;
                 $order->save();
                 return Common::Message("Order" , 2);
             }
            else if ($request->received_amount != NULL && $request->amount_left== NULL){
            $order->amount_left = $order->amount_left + $order->received_amount - $request->received_amount;
            $order->received_amount = $request->received_amount;
            $order->order_comments = $request->order_comments;
            $order->save();
            return Common::Message("Order" , 2);
            }
            else 
            {
                $order->amount_left = $request->amount_left ;
                $order->order_comments = $request->order_comments;
                $order->save();
                return Common::Message("Order" , 2);
            }
        
        }
        
        public function updateotvisit(Request $request , $id){
         $order = Order::findOrFail($request->user_id);
            //$order-chk_ord_vst = $order->chk_ord_vst - 1;
            $order->chk_ord_vst = 0;
               $order->save();
                 return response()->json(array('success' => true, 'message' => 'User status updated successfully.' , 'received_amount' => $received_amount));
            }
   

        
        
        
         public function updatecanceltext(Request $request , $id){
            $order = Order::find($id);
            if($order->is_confirmed_seller != NULL)
            {
            
                Order::where('id' , $order->id)->get();
                $order->is_confirmed_seller = NULL;
                $order->is_confirmed_admin = NULL;
                $order->is_important = NULL;
                $order->cancel_reason = $request->cancel_reason;
                 $order->cancel_status = $request->cancel_status;
                  $order->order_date = $request->order_date;
                //$order->cancel_status = 2;
            }
            else
             {
               $order = Order::find($id);
               Order::where('id' , $order->id)->get();
               $order->cancel_reason = $request->cancel_reason;
               $order->cancel_status = $request->cancel_status;
               $order->order_date = $request->order_date;
             }
               $order->save();
               return Common::Message("Order" , 2);
            }
        
       
    protected function ProcesseSellerBenefit ($seller_id , $product_id , $unit , $order_id){
        
        $getseller = Seller::where('seller_id' , $seller_id);
        $old_processed_profit = $getseller->pluck('total_prosess_benefit')->first();
        $allowed_product_profit = SellerProductProfit::where(['seller_id' => $seller_id , 'product_id' => $product_id])->pluck('profit')->first();
        if ($allowed_product_profit != NULL){
        $ben_earned = $old_processed_profit + ($unit * $allowed_product_profit);
        $getseller->update(['total_prosess_benefit' => $ben_earned]);
        OrderDetail::where(['order_id' => $order_id , 'product_id' => $product_id])->update(['seller_benefit' => ($unit * $allowed_product_profit)]);
        }
        
    }
     protected function SellerCashProcessing($confirmed_orders){

        
        $filter_seller_ids = array_unique(Order::whereIn('id' , $confirmed_orders)->pluck('seller_processed_order')->toArray());
                foreach($filter_seller_ids as $seller_id){
                    if(!empty($seller_id && Auth::user()->id != $seller_id)){
                        
                        $get_orders = Order::where('seller_processed_order' , $seller_id)->whereIn('id' , $confirmed_orders);
                        $old_cash_history = SellerSell::where(['seller_id' => $seller_id , 'unconfirmed_expences' => 1])->orderBy('id', 'desc')->first();
                          
                        $subtotal = $get_orders->sum('subtotal');
                        $receiving = $get_orders->sum('received_amount');
                        $discount = $get_orders->sum('discount');
                        $cash_processing = new SellerSell();
                        $cash_processing->seller_id = $seller_id;
                        $cash_processing->order_confirmed_by = Auth::id();
                        $cash_processing->order_ids = implode("|",$confirmed_orders);
                        if(!empty($old_cash_history->current_cash_remaining)){
                            $cash_processing->old_cash_remaining = $old_cash_history->current_cash_remaining;
                        }
                        $cash_processing->cash_paid_or_added = $receiving;
                        $cash_processing->subtotal = $subtotal;
                        $cash_processing->discount = $discount;
                        if(empty($old_cash_history->current_cash_remaining)){
                            $current_cash_remaining = 0;
                        }else{
                             $current_cash_remaining = $old_cash_history->current_cash_remaining;
                        }
                        $cash_processing->current_cash_remaining = $current_cash_remaining + $receiving;
                        $cash_processing->no_of_orders = count($confirmed_orders);

                        $cash_processing->save();
                        
            }
        
    }
    }
    protected function DeliveredSellerBenefit ($seller_id , $delivered_profit){
        
        $getseller = Seller::where('seller_id' , $seller_id);
        $old_delivered_profit = $getseller->pluck('total_delivered_benefit')->first();
        $ben_earned = $old_delivered_profit + $delivered_profit;
        $getseller->update(['total_delivered_benefit' => $ben_earned]);
        
    }

    

protected function confirmOrder($id, $sendToUnapprove = true, $selectedSeller = null , $processed_order = null){

         try{
            DB::beginTransaction();
        
        $order = Order::where('id' , $id)->first();
        
        if(Auth::user()->role <= 3 && $sendToUnapprove != true && empty($processed_order)){
           
                 $order->is_confirmed_seller = Auth::id();
                 $order->selected_seller     = $selectedSeller;
            
        }else if(Auth::user()->role == 3 && $sendToUnapprove != true && $processed_order != null){
           
                 $order->seller_processed_order = Auth::id(); 
                 $order->allow_next_order       = 1;
    
        
             Customer::where('id' , $order->customer_id)
                ->update([
                'order_exist' => 0
            ]);

        }else if (Auth::user()->role < 3 && $sendToUnapprove = true && empty($order->is_confirmed_admin) && empty($order->approve_date)){
            
             $order->is_confirmed_admin = Auth::id();
             $order->approve_date       = date('Y-m-d H:i');
             $order->allow_next_order   = 1;
            
            Customer::where('id' , $order->customer_id)
            ->update([
            'order_exist' => 0 , 'customer_pending' => 0 , 
            'last_order_date' => date('Y-m-d H:i')
            ]);

            if($sendToUnapprove){
                //$this->dangerSebalance($order);  
                $invoice       = $this->storeInvoice($order);
                $this->giveNewCustomerProfittoOt($order);
                $this->giveOtOrderBen($order);
                $order_details = OrderDetail::where('order_id' , $id)->get();

                foreach ($order_details as $od) {
                    $this->storeInvoiceDetails($od , $invoice);
                    $this->calculateSellerAndManagerBenefits($order , $od);
                    $this->stockSet($order , $od);   
                }  
            } 
        }else if (Auth::user()->role < 3 && $sendToUnapprove = true && !empty($order->is_confirmed_admin) && empty($order->approve_date)){
            
             $order->is_confirmed_admin = Auth::id();
             $order->approve_date       = date('Y-m-d H:i');
             $order->allow_next_order   = 1;

            if($sendToUnapprove){
                //$this->dangerSebalance($order);  
                $invoice       = $this->storeInvoice($order);
                $this->giveNewCustomerProfittoOt($order);
                $this->giveOtOrderBen($order);
                $order_details = OrderDetail::where('order_id' , $id)->get();

                foreach ($order_details as $od) {
                    $this->storeInvoiceDetails($od , $invoice);
                    $this->calculateSellerAndManagerBenefits($order , $od);
                    $this->stockSet($order , $od);   
                }  
            } 
        }else{
        return redirect()->back()->with('error', 'UnAuthorized Access');
    }
    $order->save();
    DB::commit();
    }catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

               return response()->json(['message' => "File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage()]);
        }
     
    }

    protected function storeInvoice($order){

                $invoice                = new Invoice();
                $invoice->user_id       = Auth::id();
                $invoice->customer_id   = $order->customer_id;
                $invoice->order_id      = $order->id;
                $invoice->unit          = $order->unit;
                $invoice->amount        = $order->amount;
                $invoice->subtotal      = $order->subtotal;
                $invoice->p_amount      = $order->p_amount;
                $invoice->received_amount = $order->received_amount;
                $invoice->discount      = $order->discount;
                $invoice->p_amount      = $order->p_amount;
                $invoice->amount_left   = $order->amount_left;
                $invoice->advance       = $order->advance;
                $invoice->a_benefit     = $order->subtotal - $order->ot_benefit - $order->p_amount - $order->discount;
                $invoice->c_benefit     = $order->c_benefit;
                
                $invoice->save();

                return $invoice;                
    }

    protected function storeInvoiceDetails($od , $invoice){

        $invoice_detail = new InvoiceDetail();
        $invoice_detail->invoice_id = $invoice->id;
        $invoice_detail->product_id = $od->product_id;
        $invoice_detail->unit       = $od->unit;
        $invoice_detail->amount     = $od->amount;
        $invoice_detail->p_amount   = $od->p_amount;
        $invoice_detail->a_benefit  = $od->ot_benefit;
        $invoice_detail->c_benefit  = $od->c_benefit;
                    
        $invoice_detail->save();
    }

    protected function calculateSellerAndManagerBenefits($order, $od){
    if (!empty($order->seller_processed_order)) {
        $seller = $order->seller_processed_order;
    } else {
        $seller = $order->is_confirmed_seller;
    }

    $getseller = Seller::where('seller_id', $seller)->first();

    if ($getseller) {
        if (!empty($order->selected_seller) 
            && 
            !empty($getseller->delivered_order_profit) 
            && 
            $getseller->assign_order != 1
        ) {
            $this->DeliveredSellerBenefit(
                $order->selected_seller,
                $getseller->delivered_order_profit
            );
        }

        if (!empty($order->seller_processed_order) 
            && 
            $getseller->assign_order == 1 
            && 
            $order->use_stock == 1
        ) {
            $this->ProcesseSellerBenefit(
                $order->seller_processed_order,
                $od->product_id,
                $od->unit,
                $od->order_id
            );
        } elseif (!empty($order->is_confirmed_seller) 
            && 
            $getseller->assign_order != 1 
            && 
            $order->use_stock == 1 
            && 
            $getseller->deleviry_product_profit == 1
        ) {
            $this->ProcesseSellerBenefit(
                $order->is_confirmed_seller,
                $od->product_id,
                $od->unit,
                $od->order_id
            );
        }
    } else {
        // Handle the case when $getseller is null (seller record not found).
        // You can choose to skip the calculations or take alternative actions here.
    }
}


    protected function stockSet($order , $od){

        $admin = SubAdmin::where('sub_admin_id' , Auth::id())->first()->product_link;
                    if ($order->use_stock == 1 && $order->seller_processed_order != null){
                    if($admin != null)
                        {
                            $this->managestock($od->product_id , $od->unit);
                        }
                    $productstock = Product::where('id' , $od->product_id)->first()->remaining_stock;
                    $remaining_stock = $productstock - $od->unit;
                    Product::where('id' , $od->product_id)->update(['remaining_stock' => $remaining_stock]);
                    }
    }

    protected function giveNewCustomerProfittoOt($order){

         // new customer profit 
                $getcustomer = Customer::where('id' , $order->customer_id)->first(); // get customer
                $get_no_of_invoice = Invoice::where('customer_id' , $order->customer_id)->pluck('id')->count(); // count inv
                 
                if ($getcustomer->new_cstmr_prft_paid == 0){ // if new
                    $get_ot = Ordertaker::where('user_id' , $getcustomer->created_by)->first(); // get ot
                    if(!empty($get_ot->aftr_bil_nw_cst_prft)){
                      if ($get_no_of_invoice == $get_ot->aftr_bil_nw_cst_prft){
                        $new_ben = $get_ot->new_cstmr_prft + $get_ot->ben_earned;
                        Ordertaker::where('user_id' , $getcustomer->created_by)->update(['ben_earned' => $new_ben]);
                        Customer::where('user_id' , $order->customer_id)->update(['new_cstmr_prft_paid' => 1]);
                    }
                    }
                    
                }
    }

   protected function giveOtOrderBen($order){

    if ($order->chk_ord_vst >= $order->ordertaker->bill_cutting_no) {
        $otben = $order->ordertaker->ot_fixed_profit ?? 0;
        $order->ot_benefit = $otben;
        $order->save();
    } else {
        $otben = $order->ot_benefit;
    }
    $a_ben = $order->subtotal - $otben - $order->discount - $order->p_amount;

    $totalotben = ($order->ot->ben_earned ?? 0) + $otben;
    $totalaben = ($order->ot->a_ben ?? 0) + $a_ben;
    $totalsell = ($order->ot->total_subtotal ?? 0) + $order->subtotal;
    $ordertaker = Ordertaker::where('user_id', $order->ot_id)->first();

    if(!empty($ordertaker)){
         $ordertaker->ben_earned     = $totalotben;
         $ordertaker->a_ben          = $totalaben;
         $ordertaker->total_subtotal = $totalsell;

         $ordertaker->save();
         }
    
}


    public function dangerSebalance($order){

         $findBalance = $order->customers->scopeorder->sum('subtotal') - $order->customers->scopeorder->sum('discount') - $order->customers->scopeorder->sum('received_amount');
         $amount_left = $order->amount_left;

         if($amount_left > $findBalance){
            $oldorder = Order::where('customer_id' , $order->customer_id)->first();
            $oldorder->subtotal = $oldorder->subtotal + $amount_left - $findBalance;
            $oldorder->save();
         }
    }
    protected function managestock($product_link , $unit){
            $product_id = Product::where('id' , $product_link)->pluck('link_product')->first();
            $productstock = Product::where('id' , $product_id)->pluck('remaining_stock')->first();
            //dd($productstock , $product_id);
            $remaining_stock = $productstock - $unit;
            Product::where('id' , $product_id)->update(['remaining_stock' => $remaining_stock]);
                  
    }

    public function confirmOrderMult(Request $request){
        //dd($request->all());

      
            try{
            DB::beginTransaction();
     
       
         $selectedSeller = ($request->selected_seller > 0) ? $request->selected_seller : Auth::id();
         $productData = $request->all();
        if(!empty($productData['confirm-to'])){
            $confirmed_orders= array_unique($productData['confirm-to']);
            foreach($confirmed_orders as $ap){
                $sendToUnapprove = $request->send_to_unapprove ? true : false;
                $processed_order = $request->processed_order;
                $this->confirmOrder($ap, $sendToUnapprove, $selectedSeller,$processed_order);
             }
              $productData = $request->all();

            if(Auth::user()->role < 3 && !empty($request->send_to_unapprove)){
                $this->SellerCashProcessing($confirmed_orders);
            }
            DB::commit();
            return Common::Message("Order" , 4);
        }
        elseif(!empty($productData['approve-to'])){
            $approved_orders=$productData['approve-to'];
            foreach($approved_orders as $ap){
                $this->approveOrder($ap);
            }
            DB::commit();
            return Common::Message("Order" , 4);
        }
        else{
            return back()->with('error', 'Please Select Customer First!');
        }
      }catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => 0,
                            'msg' => __('messages.something_went_wrong')
                        ];

           return response()->json(['message'=> 'Something Went Wrong!']);
        }
        
    }


 public function updateCustomerarea(Request $request , $id)
    {
        $order = Order::find($id);
        if (isset($order->customers)) {
            $updateCustomer = $order->customers()->update(['area_id' => $request->area]);
            
            
            return Common::Message("Customer", 2);
        } else {
            return Common::Message("Customer");
        }
    }
      public function updatenullCustomerreq($id)
    {
          Customer::where('id' , $id)->update(['customer_request' => NULL]);   
            return Common::Message("Customer", 2);
    }
     public function updateCustomerreq(Request $request , $id)
    {
        $a = $request->all();
       $data = $a['customer_req'];
           $updateCustomer = Customer::where('id' , $id)->update(['customer_request' => $data]);
            return Common::Message("Customer" , 2);

    }
    function SetStock(Request $req){
            if(Auth::user()->role < 3 ){
            $order = Order::find($req->order_id);
            if ($order->use_stock == 0 )
            $order->use_stock = 1;
            elseif ($order->use_stock == 1){
            $order->use_stock = 0;
            }
            $order->save();
             return response()->json(['Success'=> true, 'message'=> 'Order Status Change']);
        }
    }
    
        function SetPickOrder(Request $req){
            
            $order = Order::find($req->order_id);
            if ($order->pick_order == 0 )
            $order->pick_order = 1;
            elseif ($order->pick_order == 1){
            $order->pick_order = 0;
            }
            $order->save();
             return response()->json(['Success'=> true, 'message'=> 'Order Status Change']);
        
    }
    function getThisConfirmedOrders($type , $date){
        if (Auth::user()->role == 3) {
         $common_ot = User::where('ot_of', Auth::user()->seller_of)->pluck('id');
                            $orders = Order::whereIn('ot_id', $common_ot)
                            ->where('approve_date',"!=",NULL);
        }
        else{
         $orders = Order::has('get_ot_creater')->where('approve_date',"!=",NULL);
        }
        $data = $orders->whereDate('approve_date', '=', new Carbon($date));
        if ($type == "un_verified"){
             $orders = $data->where('verified_order' , 0)->get();
        }
        elseif($type == "unstock"){
            $orders = $data->where('use_stock' , 0)->get();
        }
        else{
            $orders = $data->get();
        }
            return view('orders.confirmed_orders_history' , compact('orders'));
        
    }

function VerifyOrder(Request $date){
        if (Auth::user()->role == 3) {
         $common_ot = User::where('ot_of', Auth::user()->seller_of)->pluck('id');
                            $orders = Order::whereIn('ot_id', $common_ot)
                            ->where('approve_date',"!=",NULL);
        }
        else{
         $orders = Order::has('get_ot_creater')->where('approve_date',"!=",NULL);
        }
        $data = $orders->whereDate('approve_date', '=', new Carbon($date->date));
        $orders = $data->where('verified_order' , 0)->pluck('id')->toArray();
        foreach ($orders as $update){
           Order::where('id' , $update)->update(['verified_order' => 1]);
        }
        
        return redirect()->back()->with('success', 'Order Confirmed Successfully');
          
    }

     public function GetSumOrderSubtotal($customer_id){

         $query = Order::where('customer_id' , $customer_id);
        if ($query->latest()->first()->exists()){
            $purchase = $query->sum('subtotal');
        }else{
            $purchase = 0;
        }
        return $purchase;
    }

    public function posSale(){

        $categories = $this->getPosProduct();
        $data  = $this->getCustomerForOrder();
        if($data == 'No Customer Found!'){
            if(empty($categories->first())){
            return redirect()->route('add.customer')->with('error', 'No Customer Found! Please Create Customer First');
        }
        }

        // foreach((($data['product'])[0]) as $a){
        //     $b[] = $a->id;
        // }
        // dd($b);

        return view('orders.posSale' , compact('categories' , 'data'));
    }

    //   public function fetchProduct($customer){

    //     $customer_products = $customer->final_allowed_products;
    //     $products = explode('|' , $customer_products);
    //     foreach($products as $product){
    //         $pr = CustomPrice::where(['customer_id' => $customer->id , 'product_id' =>  $product]);
    //         if($pr->exists()){
    //              $newProduct = $pr->first();
    //              $attributes[] = [
    //                 'id' => $newProduct->product_id,
    //                 'name' => $newProduct->product->name,
    //                 'price' => $newProduct->price,
    //             ];
    //         }else{
    //              $newProduct = Product::where('id' , $product)->first();
    //              $attributes[] = [
    //                 'id' => $newProduct->id ?? '',
    //                 'name' => $newProduct->name ?? '',
    //                 'price' => $newProduct->price ?? '',
    //             ];
    //         }

    //     }
    //     return $attributes;

    // }

    public function fetchProduct($customer){

       $customer_products = $customer->final_allowed_products;
        $customer_al_products = $customer->allowed_products;

        $products = explode('|', $customer_products);
        $al_products = explode('|', $customer_al_products);

        if(Auth::user()->role == 5){
            $admin = Auth::user()->ot_of;
        }else{
            $admin = Auth::id();
        }
        $mergedArray = array_merge($products, $al_products, Product::where(['allow_to_all_customer' => 1 , 'user_id' => $admin])->pluck('id')->toArray());
        $products = array_unique($mergedArray);

        //dd($products);

        $old_inv = Invoice::where(['customer_id' => $customer->id])->orderBy('id', 'desc')->first();
       // dd($customer->user->name , $old_order);
        foreach($products as $product){
            $pr = CustomPrice::join('products' , 'products.id' , 'custom_prices.product_id')->where(['customer_id' => $customer->id , 'product_id' =>  $product]);
            //$data['customer_id'] =  $customer->id;
            if($pr->exists()){
               $cpr = $pr->select(
                'products.name',
                'products.id',
                'custom_prices.price',
                'products.category_id'

            )->first();
               // $cpr->setAttribute('customer_id', $customer->id);
               // $cpr->setAttribute('customer_usere_id', $customer->user_id);
               // $name = $cpr->product->name;
               // $cpr->setAttribute('name', $name);
               if(!empty($old_inv)){
                 //   $cpr->setAttribute('old_customer_balance', $old_inv->amount_left ?? null); 
               }
               
                 $data[] = $cpr;
            }else{
                $cpr = Product::where('id' , $product)->select(
                    'name',
                    'id',
                    'price',
                    'category_id'
                )->first();
                if(!empty($cpr)){
                    // $cpr->setAttribute('customer_id', $customer->id); 
                    // $cpr->setAttribute('customer_usere_id', $customer->user_id);
               
                   if(!empty($old_inv)){
               //     $cpr->setAttribute('old_customer_balance', $old_inv->amount_left ?? null); 
                }
                }
                 $data[] = $cpr;
            }
            
        }
        return $data;

    }

    public function getAllOrder(){

        if(Auth::user()->role == 5){
            $admin = Auth::user()->ot_of;
        }elseif(Auth::user()->role == 3){
            $admin = Auth::user()->seller_of;
        }else{
            $admin = Auth::id();
        }

       $orders['orders'] = Order::join('customers' , 'customers.id' , 'orders.customer_id')
        ->join('users' , 'users.id' , 'customers.user_id')
        ->join('users as ot' , 'ot.id' , 'orders.ot_id')
        ->join('areas' , 'areas.id' , 'customers.area_id')
        ->whereNull('orders.is_confirmed_admin')
        ->whereIn('orders.user_id' , array_merge($this->getAdminOt(),[$admin]))
        ->select(
            'users.name',
            'customers.id as customer_id',
            'customers.customer_name as shop_name',
            'customers.phone',
            'customers.address',
            'customers.location_url as customer_location',
            'customers.call_customer',
            'orders.id as order_id',
            'orders.unit',
            'orders.amount',
            'orders.subtotal',
            'orders.discounted_subtotal',
            'orders.p_amount',
            'orders.received_amount',
            'orders.discount',
            'orders.amount_left',
            'orders.advance',
            'orders.c_benefit',
            'orders.order_comments',
            'orders.cancel_status',
            'orders.cancel_reason',
            'orders.urgent',
            'orders.clear',
            'orders.order_date',
            'orders.is_important',
            'orders.is_confirmed_seller',
            'orders.is_confirmed_admin',
            'orders.seller_processed_order',
            'orders.selected_seller',
            'orders.location_url_ot',
            'orders.ot_customer_distance',
            'orders.chk_ord_vst',
            'orders.use_stock',
            'areas.name as area_name',
            'ot.name as ordertaker_name',
            'orders.ot_id'

        )
        ->get();

        $orders['orderDetail'] = OrderDetail::whereIn('order_id' , $orders['orders']->pluck('order_id')->toArray())->join('products' , 'products.id' , 'order_details.product_id')->select('order_details.*' , 'products.name')->get();

         return response()->json([
                'code' => 200 , 
                'status'=>'success' , 
                'message'=>'Data Fetch Successfully!' , 
                'data' => $orders
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

    public function getCustomerForOrder(){

         $customers  =  Customer::whereIn('created_by' , $this->getAdminOt())->get();
         if(empty($customers ->first())){
            return "No Customer Found!";
         }
        foreach($customers as $customer){

          $data['cst'][] = $customer;
          $data['product'][]= $this->fetchProduct($customer);
          
          $data['old_balance'][] = $this->getCustomerBalance($customer);
        }


        
        return $data;

    }

    public function getCustomerBalance($customer){

        $inv = Invoice::where('customer_id' , $customer->id)->orderBy('id' , 'desc');
            if($inv->exists() == true){
                $old_balance = $inv->first()->amount_left;
            }else{
                $old_balance = 0;
            }
            return $old_balance;
    }

    public function getPosProduct(){

        $ids = [Auth::id()];
        $adminid = [User::where('id' , Auth::id())->pluck('ot_of')->first()]; //this uses ordertaker to get his admin
        if (Auth::user()->role == 2){
            $adminots = User::where('ot_of' , $ids)->pluck('id')->toArray();// this combines admin ot and admin id 
            $adminots = array_merge($ids , $adminots);
        }
        else{
        $adminots = User::where('ot_of' , $adminid)->pluck('id')->toArray();// this combines admin ot and admin id 
        $adminots = array_unique(array_merge($ids , $adminots ,$adminid));
        }
        $cat = Category::where('user_id' , $adminots)->get();
        //dd($cat->product->first()->name);
        return $cat;
    }

     public function getCustomerProductForOrder(Request $request){
       // dd(111 , Auth::id());

        //dd($request->all());
         $customer  =  Customer::where('id' , $request->id)->first();

        
            $data= $this->fetchProduct($customer);
           
        
       // dd($data);
        
        return response()->json([
                'code' => '200' , 
                'status'=>'success' , 
                'message'=>'Data Fetch Successfully!' , 
                'data' => $data
            ]);

    }

  
}