<?php

namespace App\Http\Controllers;
use App\Repositories\Common;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use App\Models\CustomOtBenefit;
use App\Models\OtCustomer;
use App\Models\Ordertaker;
use App\Models\Order;
use App\Models\SubAdmin;
use App\Models\Seller;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\EmployeeAttandenceSetting;
use App\Http\Controllers\UserController;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

use App\Models\PaidSellerBenefit;
use App\Models\SellerProductProfit;
use App\Models\SellerSell;
use Carbon\Carbon;
class SellerController extends Controller{
    
    public function index(){
        if(Auth::user()->role < 3){
        $sellers = User::where('seller_of',Auth::id())->get();
        $sellers_data = Seller::where('seller_of', Auth::id())->get();
        $sellers_benefits = PaidSellerBenefit::where('paid_by', Auth::id())->get();
        foreach ($sellers as $a){
        $orders[] = Order::where('is_confirmed_seller' , $a->id)->count();
        }
        }
        elseif(Auth::user()->role == 3){
        $sellers = User::where('id', Auth::id())->get();
        $sellers_data = Seller::where('seller_id', Auth::id())->get();
        $sellers_benefits = PaidSellerBenefit::where('seller_id', Auth::id())->get();
        
        foreach ($sellers as $a){
        $orders[] = Order::where('is_confirmed_seller' , Auth::id())->count();
        }
        
        }
        if(empty($orders)){
            $orders = [];
        }
        return view('seller.all_sellers' , compact('sellers' , 'orders' , 'sellers_data' , 'sellers_benefits'));
        
    }

    public function indexAll($admin_id){
        $sellers = User::where('seller_of',$admin_id)->get();
        $subadmin_name = User::find($admin_id)->name;
        return view('seller.all_sellers' , compact('sellers' , 'subadmin_name'));
    }

    public function addSeller(){

        $admin_ots = $this->get_admin_ots();

        $products = Product::where(['user_id' => Auth::id()])->get();
        $customers = Customer::whereIn('created_by' , $admin_ots)->get();
        return view('add_user', compact('products','customers'));
    }
    
    
    
    public function getSeller($id){
        $seller = User::find($id);
        
        $get_seller = Seller::where('seller_id', $id);
        $chk_assign_order_status = $get_seller->pluck('assign_order')->first();
        $chk_add_remove_stock = $get_seller->pluck('chk_add_remove_stock')->first();
        $assigned_seller = $get_seller->pluck('allowed_seller')->first();
        $seller_data = $get_seller->first();
        $allowed_seller = explode('|', $assigned_seller);
        $allowed_cstmr_branches = $get_seller->pluck('allowed_cstmr_branches')->first();
        $allowed_branches = explode('|', $allowed_cstmr_branches);
        $seller_name = User::where('seller_of', Auth::id())->get();
        //$get_product = Product::select('id' , 'name' , 'seller_benefit')->where('user_id' , Auth::id())->get();
        $custom_prices = SellerProductProfit::where('seller_id', $id)->get();
        $get_product = Product::where(['user_id' => Auth::id()])->whereNotIn('id', $custom_prices->pluck('product_id')->toArray())->get();
        $EmployeeAttandenceSettings = EmployeeAttandenceSetting::where('user_id' , $id)->first();

       $branches = $this->getBranches();
        
       // dd($get_product);
        return view('seller.edit_user' , compact('seller' , 'custom_prices', 'seller_name' , 'allowed_seller' , 'chk_assign_order_status' , 'seller_data' , 'get_product' , 'chk_add_remove_stock' , 'branches' , 'allowed_branches' , 'EmployeeAttandenceSettings'));
    }

    public function getSellerSells($id){
        $invoices = Invoice::where('user_id' , $id)->get();
        $orders = Order::where('is_confirmed_seller' , $id)->get();
        $seller = User::find($id);
        if($seller->seller_of == Auth::id() || Auth::user()->role == 1){
            return view('seller.seller_invoices' , compact('invoices' , 'seller' , 'orders'));
        }
        else{
            return redirect()->back()->with('error' , 'Invalid Request');
        }
    }
    
     public function getSellerOrders($id){
        $invoices = Order::where('is_confirmed_seller' , $id)->orderBy('id' , 'desc')->paginate('10');
        $seller = User::find($id);
        if($seller->seller_of == Auth::id() || Auth::user()->role == 1){
            return view('seller.seller_orders' , compact( 'seller' , 'invoices'));
        }
        else{
            return redirect()->back()->with('error' , 'Invalid Request');
        }
    }
    
    public function updateSeller(Request $request , $id ){

         try{

            //dd($request->all());

            DB::beginTransaction();
        if (Auth::user()->role < 3){
        $seller = User::where('id' , $id )->get();
         
         if (!empty($request->checked_seller_id)){
         $allowed_seller = implode('|', $request->checked_seller_id);
            Seller::where('seller_id', $id)
            ->update([
                'allowed_seller' => $allowed_seller 
                , 
                'process_order_profit' => $request->process_order_profit 
                , 
                'delivered_order_profit' => $request->delivered_order_profit
                ]);
            }

        
        if(Common::Data($seller)){

            if (!empty($request->password)){
                $updateData = ['name' => $request->name , 'password' => bcrypt($request->password) , 'phone' => $request->phone];    
            }else{
                $updateData = ['name' => $request->name , 'phone' => $request->phone];
            }
           
            if(empty(User::where('email' , $request->email)->first()->name)){
                $updateData['email'] = $request->email;
            }
            User::where('id' , $id)->update($updateData);
            // If this user is itself order taker and have own customer
            User::where('id', $id)->update(['ot_of'=> null]);
            if($request->itself_order_taker == "on"){
                User::where('id', $id)->update(['ot_of'=> $id]);
            }
            $seller_data = Seller::where('seller_id' , $id )->get();
            
            //data 
            $productData = $request->all();

        SellerProductProfit::where('seller_id', $id)->each(function($ben, $key){
            $ben->delete();
        });
        if (count($productData['checked_products']) > 0) {
            foreach (array_values($productData['checked_products']) as $key => $rowValue) {
                if($rowValue == "on"){
                    $customB = new SellerProductProfit();
                    $customB->seller_id = $id;
                    $customB->product_id = $productData['product_id'][$key];
                    $customB->profit = $productData['seller_benefit'][$key];
                    $customB->save();
                }
            }
        }

        $GetUserController      = new UserController();
            $GetUserController->EmployeeAttandenceSettings($request , $id);
            
        DB::commit();
            return Common::Message("Seller" , 2);
        }
        else{
            DB::commit();
            return Common::Message("Seller");
        }
    }
    }catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

               return response()->json(['message' => 'Something Went Wrong!']);
        }
    }
    
    public function assignOrder(Request $request)
        
        {
            if ($request->assign_order == 0){
                $data = NULL;
            }
            else{
                $data = 1;
            }
            $seller_data = Seller::where('seller_id' , $request->user_id )->update(['assign_order' => $data]);
            return response()->json(['message' => 'User status updated successfully.']);
        }

       public function DeleviryProductProfit(Request $request)
        {
            if ($request->deleviry_product_profit == 0){
                $data = 0;
            }
            else{
                $data = 1;
            }
            $seller_data = Seller::where('seller_id' , $request->user_id )->update(['deleviry_product_profit' => $data]);
            return response()->json(['message' => 'Status set successfully.']);
        }
    
    
     public function ChkAddRemoveStock(Request $request)
        
        {
            if ($request->chk_add_remove_stock == 0){
                $data = NULL;
            }
            else{
                $data = Auth::id();
            }
            $seller_data = Seller::where('seller_id' , $request->user_id )->update(['chk_add_remove_stock' => $data]);
            return response()->json(['message' => 'User status updated successfully.']);
        }
    
    public function deleteSeller($id){
        $seller = User::where(['id' => $id , 'seller_of' => Auth::id()])->get();
        if(sizeof($seller)){
            User::where(['id' => $id , 'seller_of' => Auth::id()])->delete();
             Seller::where(['seller_id' => $id , 'seller_of' => Auth::id()])->delete();
           
                    return Common::Message("Seller" , 3);
        }
        else{
            return Common::Message("Seller");
        }
    }
    
    public function ChangeUserStatus($id , $status){
            
            if (Auth::user()->role == 1){
            $user = User::find($id);
            $user->is_blocked = $status == "block" ? 1 : 0;
            $user->created_at = date('Y-m-d H:i:s');
            $user->save();
            session(['pin' => '']);
            return redirect()->back()->with('success' , 'Admin Account Status Has Been Changed Successfully');
            }
            else{
                return redirect()->back()->with('error' , 'Unauthenticated Access!');
            }
        
    }

    public function getUsers(){
        $sub_admins = User::where('user_of' , '!=' , null)->get();
         
        
        return view('sub_admins' , compact('sub_admins'));
    }
    public function payAmount($id, Request $request)
    { 
        if (Auth::user()->role < 3){
 
            $ot = Seller::where('seller_id', $id)->first();
            
            $sellerPaiData = PaidSellerBenefit::where('seller_id', $id);
            $total_old_ben = $ot->total_delivered_benefit + $ot->total_prosess_benefit; // get total remaining form seller
            if ($sellerPaiData->pluck('seller_id')->first() == null){
                $total_paid = 0;
            }
            else{
            $total_paid = $sellerPaiData->sum('paid'); // get total paid
            }
            $current_remaining  = $total_old_ben - $request->amount - $total_paid; // current remaining
            $paid_benefit = new PaidSellerBenefit();
            $paid_benefit->paid_by = Auth::id();
            $paid_benefit->seller_id = $id;
            $paid_benefit->old_remaining = $total_old_ben - $total_paid;
            $paid_benefit->paid = $request->amount;
            $paid_benefit->current_remaining = $current_remaining;
            $paid_benefit->comments = $request->comments;
            $paid_benefit->save();
            

            return Common::Message('Paid History', 6);
        }
        else {
            return Common::Message('Paid History', 7);
        }
    }
    public function paidHistory($id)
    {
         $paid_benefits = PaidSellerBenefit::where('seller_id', $id)->get();
        return view('seller.paid_seller_benefits', compact('paid_benefits' ));
    }
    public function getlastcashremaining(){
        
        if(Auth::user()->role == 3){
            $data = SellerSell::where('seller_id', Auth::id())->where('current_cash_remaining' , "!=" , 0)->orderBy('id' , 'desc')->pluck("current_cash_remaining")->first();
        }else{
            $data = SellerSell::where('order_confirmed_by', Auth::id())->where('current_cash_remaining' , "!=" , 0)->orderBy('id' , 'desc')->pluck("current_cash_remaining")->first();
        }
        return response()->json(['data' => $data]);
         
    }
    public function getSellerOrdersProcessings(Request $request , $id = NULL)
    {
         if(empty($id)){
            $id = $request->id;
         }
         $week = \Carbon\Carbon::today()->subDays(7);
         $month = \Carbon\Carbon::today()->subDays(30);
         $expences =  SellerSell::where(['seller_id' => $id , 'order_confirmed_by' => $id ]);  
         
         $week_expence = (clone $expences)->where('created_at','>=',$week)->sum('expenses');
         $month_expence = (clone $expences)->where('created_at','>=',$month)->sum('expenses');
             //dd($week_expence , $month_expence);
         $data = SellerSell::where('seller_id', $id);

         $orders_processign_history = $data->orderBy('id' , 'desc');
          if ($request->expectsJson()) {
            $orders_processign_history = $orders_processign_history->get();
         }else{
            $orders_processign_history = $orders_processign_history->paginate(50);
         }
         $seller =  $data->where(['seller_id' => $id , 'order_confirmed_by' => $id]);
         $sum_expences = $seller->sum('expenses');
         
         $today_expences = $seller->whereDate('created_at', Carbon::today())->sum('expenses');
         //dd($week_expences);
         $order_data = Order::where('seller_processed_order' , $id);
         $order_datam = Order::where('seller_processed_order' , $id);
         $sum_p_amount = $order_data->sum('p_amount');
         $sum_subtotal = $order_data->sum('subtotal');
         $sum_discount = $order_data->sum('discount');
         $sum_receiving = $order_data->sum('received_amount');
         $balance = $sum_subtotal - $sum_receiving;
         $sum_total_profit = $sum_subtotal - $sum_p_amount - $sum_discount - $sum_expences - $balance;
         $week_data = $order_data->where('created_at','>=',$week);
         $week_sum_p_amount = (clone $week_data)->get()->sum('p_amount');
         $week_sum_subtotal = (clone $week_data)->get()->sum('subtotal');
         $week_sum_discount = (clone $week_data)->get()->sum('discount');
         $week_sum_receiving = (clone $week_data)->get()->sum('received_amount');
         $weekbalance = $week_sum_subtotal - $week_sum_receiving;
         $sum_week_profit = $week_sum_subtotal - $week_sum_p_amount - $week_sum_discount - $week_expence - $weekbalance;
         $month_data = $order_datam->where('created_at','>=',$month);
         $month_sum_p_amount = (clone $month_data)->get()->sum('p_amount');
         $month_sum_subtotal = (clone $month_data)->get()->sum('subtotal');
         $month_sum_discount = (clone $month_data)->get()->sum('discount');
         $month_sum_receiving = (clone $month_data)->get()->sum('received_amount');
         $monthbalance = $month_sum_subtotal - $month_sum_receiving;
         $sum_month_profit = $month_sum_subtotal - $month_sum_p_amount - $month_sum_discount - $month_expence - $monthbalance;

         if ($request->expectsJson()) {
        $data = [
            'orders_processign_history' => $orders_processign_history,
            'id' => $id,
            'sum_expences' => $sum_expences,
            'sum_discount' => $sum_discount,
            'sum_p_amount' => $sum_p_amount,
            'sum_subtotal' => $sum_subtotal,
            'sum_receiving' => $sum_receiving,
            'balance' => $balance,
            'sum_total_profit' => $sum_total_profit,
            'sum_week_profit' => $sum_week_profit,
            'sum_month_profit' => $sum_month_profit,
            'today_expences' => $today_expences,
            'week_expence' => $week_expence,
            'month_expence' => $month_expence
        ];
        
        return response()->json([
                'code' => '200' , 
                'status'=>'success' , 
                'message'=>'Data Fetch Successfully!' , 
                'data' => $data
            ]); 
    }
     
         
         //dd($week_sum_subtotal);
        return view('seller.get_seller_order_processign', compact(
            'orders_processign_history',
             'id' ,
             'sum_expences' ,
             'sum_discount' ,
             'sum_p_amount' ,
             'sum_subtotal' ,
             'sum_receiving' ,
             'balance' ,
             'sum_total_profit' ,
             'sum_week_profit' ,
             'sum_month_profit' ,
             'today_expences' ,
             'week_expence' ,
             'month_expence')
    );
    }
    public function SetCashProcessing(Request $request){
       
       
             // dd($request->all());
              if(Auth::user()->role < 3){
                $old_cash_history = SellerSell::where(['seller_id' => $request->seller , 'unconfirmed_expences' => 1])->orderBy('id', 'desc')->first();
              //  dd($old_cash_history);
                $cash_processing = new SellerSell();
                $cash_processing->seller_id = $request->seller;
                $cash_processing->order_confirmed_by = Auth::id();
                if(!empty($old_cash_history->current_cash_remaining)){
                    $cash_processing->old_cash_remaining = $old_cash_history->current_cash_remaining;
                }
                $cash_processing->cash_paid_or_added = -$request->amount;
                if (!empty($old_cash_history->current_cash_remaining)){
                    $cash_processing->current_cash_remaining = $old_cash_history->current_cash_remaining - $request->amount;                
                }else{
                    $cash_processing->current_cash_remaining = 0 - $request->amount;
                
                }
                $cash_processing->comments = $request->comment;
                $cash_processing->save();
                return response()->json(['message' => 'Cash Handeled Successfully']);
      
              }
              elseif(Auth::user()->role == 3) {
                $cash_processing = new SellerSell();
                $cash_processing->seller_id = Auth::id();
                $cash_processing->order_confirmed_by = Auth::id();
                $cash_processing->expenses = $request->expence;
                $cash_processing->comments = $request->comment;
                $cash_processing->unconfirmed_expences = 0;
                $cash_processing->save();
                return response()->json(['message' => 'Expanse Added Successfully']);
              }
    }
    public function DeleteExpence(Request $req) {
        
        
         $expence_processing = SellerSell::where(['id' => $req->order_id , 'unconfirmed_expences' => 0])->delete();
         //if (!empty($expence_processing->id)){
         return response()->json(['success'=> true, 'message' => 'Expanse deleted Successfully']);
         //}
        //  else{
        //      return response()->json(['error' , 'message' => 'Expanse Not Found']);
        //  }
             
    }
    
              
    public function SellerExpnceProcessing (Request $req){
        
              $expence_processing = SellerSell::where('id' , $req->order_id)->first();
              $old_cash_history = SellerSell::where(['seller_id' => $expence_processing->seller_id , 'unconfirmed_expences' => 1])->orderBy('id', 'desc')->first();
              if(Auth::user()->role < 3 && $expence_processing->unconfirmed_expences == 0){
                $handle_expence = new SellerSell();
                $handle_expence->seller_id = $expence_processing->seller_id;
                $handle_expence->order_confirmed_by = Auth::id();
                $handle_expence->expenses = $expence_processing->expenses;
                $handle_expence->old_cash_remaining = $old_cash_history->current_cash_remaining;
                $handle_expence->current_cash_remaining = $old_cash_history->current_cash_remaining - $expence_processing->expenses;
                $handle_expence->comments = $expence_processing->comments;
                $handle_expence->unconfirmed_expences = 1;
                $handle_expence->save();
                SellerSell::where('id' , $req->order_id)->update(['unconfirmed_expences' => 1]);
                return response()->json(['success'=> true, 'message' => 'Expanse Handeled Successfully']);
              }
              else{
                 return response()->json(['error'=> true, 'message' => 'Already Hnadeled This Expence']); 
              }
             
             
    }

    public function get_admin_ots(){

        $ids = [Auth::id()];
        $otowns = User::where('ot_of' , Auth::id())->pluck('id')->toArray();
        $adminotarea = array_merge($ids , $otowns);

        return $adminotarea;
       
    }

    public function getBranches(){

        $array = $this->get_admin_ots();
        $customer = Customer::whereIn('created_by' , $array)->where('is_active_for_product' , 1)->get();
        return $customer;
       
    }

    public function allowBranches(Request $request){

        $seller = Seller::where('seller_id' , $request->user_id)->first();
        if(empty($seller->allowed_cstmr_branches)){
            $seller->allowed_cstmr_branches = $request->c_id;
        }else{
            $allowed_branches = explode('|' , $seller->allowed_cstmr_branches);
            if($request->checked == 0){
                if (in_array($request->c_id, $allowed_branches)) 
                    {
                        unset($allowed_branches[array_search($request->c_id,array_unique($allowed_branches))]);
                    }
            }else{
                array_push($allowed_branches , $request->c_id);
            }    
            $images = implode('|', array_unique($allowed_branches));
            $seller->allowed_cstmr_branches = $images;
        }
        $seller->save();

        return response()->json(['message' => 'Operation Performed Successfully!']);
    }
    
}