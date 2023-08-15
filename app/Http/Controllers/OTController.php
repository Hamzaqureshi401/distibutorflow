<?php

namespace App\Http\Controllers;

use App\Repositories\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\OtCustomer;
use App\Models\User;
use App\Models\Area;
use App\Models\Product;
use App\Models\CustomOtBenefit;
use App\Models\CustomPrice;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Ordertaker;
use App\Models\PaidOtBenefit;
use Illuminate\Support\Facades\Auth;

class OTController extends Controller
{

    public function index()
    
    {
        $ids = [Auth::id()];
        if (Auth::user()->role < 3) {
            $ids = array_merge($ids, User::where('ot_of', Auth::id())->pluck('id')->toArray());
        }
        $ordertaker = User::where('role', 5)->has('ordertaker')->with('ordertaker.vistorders');
        if (Auth::user()->role == 5) {
            $ordertaker = $ordertaker->where('id', Auth::id());
            $ab = [Auth::id()];
            $ot = User::where('id', Auth::id())->pluck('compare_ot_distance')->first();
            $distance = $ot / 1000;
                 $sum[] = Order::where('ot_customer_distance', '<' , $distance)->where('ot_id' , Auth::id())->count();
                 $sumbig[] = Order::where('ot_customer_distance', '>' , $distance)->where('ot_id' , Auth::id())->count();
                 $sumtdybig[] = Order::where('ot_customer_distance', '>' , $distance)->where('ot_id' , Auth::id())->whereDate('created_at', date('Y-m-d'))->pluck('id')->count();
                 $sumtdyless[] = Order::where('ot_customer_distance', '<' , $distance)->where('ot_id' , Auth::id())->whereDate('created_at', date('Y-m-d'))->pluck('id')->count();
        } else {
            $ordertaker = $ordertaker->where('ot_of', Auth::id());
            $ab = User::where('ot_of', Auth::id())->pluck('id')->toArray();
             foreach ($ab as $key => $a){
                 $sum[] = Order::where('ot_customer_distance', '<' , ((User::where('id', $a)->pluck('compare_ot_distance')->first()) / 1000))->where('ot_id' , $a)->count();
                 $sumbig[] = Order::where('ot_customer_distance', '>' , ((User::where('id', $a)->pluck('compare_ot_distance')->first()) / 1000))->where('ot_id' , $a)->count();
                 $sumtdybig[] = Order::where('ot_customer_distance', '>' , ((User::where('id', $a)->pluck('compare_ot_distance')->first()) / 1000))->where('ot_id' , $a)->whereDate('created_at', date('Y-m-d'))->pluck('id')->count();
                 $sumtdyless[] = Order::where('ot_customer_distance', '<' , ((User::where('id', $a)->pluck('compare_ot_distance')->first()) / 1000))->where('ot_id' , $a)->whereDate('created_at', date('Y-m-d'))->pluck('id')->count();
                 $customers[] = Customer::where('created_by' , $a)->pluck('id')->count();
                 
                 
             }
            if(!empty($sum)){
                array_shift($sum);
                array_shift($sumbig);
                array_shift($sumtdybig);
                array_shift($sumtdyless);
                array_shift($customers);
            }else{
                $sum = ''; 
                $sumbig = ''; 
                $sumtdybig = '';
                $sumtdyless = '';
                $customers = '';
            }
          
        }
        $ordertaker = $ordertaker->get();
        //$paid_benefits = PaidOtBenefit::where('user_id', Auth::id())->where('ot_id', $id)->get();
        //$sum = Order::where('ot_customer_distance', '<' , 50)->where('ot_id')->get();
        return view('ordertaker.all_ordertakers', compact('ordertaker'  , 'sum' , 'sumbig' , 'sumtdybig' , 'sumtdyless' , 'customers'));    
    }

    public function getOT($id)
    {
         $ids = [Auth::id()];
        if (Auth::user()->role < 3) {
            $ids = array_merge($ids, User::where('ot_of', Auth::id())->pluck('id')->toArray());
        }
        $ordertaker  = Ordertaker::where('user_id', $id)->first();
        $otc_allowed = $ordertaker->ot_customer_allowed;
        $allowed_areas = $ordertaker->allowed_areas;

        $allowed_ot  = explode('|', $otc_allowed);
        $allowed_areas  = explode('|', $allowed_areas);
        
        $otnames     = User::whereIn('id', $ids)->get();
        $areas       = Area::whereIn('created_by' , $otnames->pluck('id')->toArray())->get();
        $ot          = User::find($id);
        $custom_prices = CustomOtBenefit::where('ot_id', $id)->get();
        $products    = Product::where(['user_id' => Auth::id()])->whereNotIn('id', $custom_prices->pluck('product_id')->toArray())->get();
        $customers    = Customer::whereIn('created_by', $ids)->get();
        $ot_customers = OtCustomer::where('ot_id', $ot->id)->get()->pluck('customer_id')->toArray();
        return view(
            'ordertaker.edit_ordertaker', compact(
                'ot', 
                'custom_prices', 
                'ot_customers', 
                'customers', 
                'products' , 
                'ids' , 
                'otnames' , 
                'allowed_ot' , 
                'ordertaker',
                'areas',
                'allowed_areas'
            ));
    }

    public function updateOT(Request $request, $id)
    {
        try{
            DB::beginTransaction();
              
      
       // dd($request->all() , $request->ids[0] , $request->customer_id);
        
        $request->validate([
            'email' => 'required',
            'name' => 'required',
            'phone' => 'required',
        ]);
        
        // dd($request);
        $ot = User::find($id);
        $ot->name = $request->name;
        $ot->email = $request->email;
        $ot->phone = $request->phone;
        $ot->ot_hom_location = $request->ot_hom_location;
        $ot->bill_cutting_no = $request->bill_cutting_no;
        $ot->ot_fixed_profit = $request->ot_fixed_profit;
        $ot->ot_visit_profit = $request->ot_visit_profit;
        $ot->compare_ot_distance = $request->compare_ot_distance;
        $ot->order_detail = $request->order_detail;
        if ($request->password) {
            $ot->password = $request->password;
        }
        $ot->customer_itself = $request->has('customer_itself');
        if($request->customer_discount == NULL || $request->customer_discount == 0 || $request->customer_discount < 0)
        {
            $ot->customer_discount = 1;
        }
        else{
         $ot->customer_discount = $request->customer_discount;   
        }
         
        $ot->save();
        if(empty($request->checked_ot_id)){
            $ot_allowes = null;
        }else{
            $ot_allowes = implode('|', $request->checked_ot_id);
            
        }
         if(empty($request->allowed_areas)){
            $allowed_areas = null;
        }else{
            $allowed_areas = implode('|', $request->allowed_areas);
            
        }
        Ordertaker::where('user_id', $id)
        ->update([
            'ot_customer_allowed'   => $ot_allowes , 
            'new_cstmr_prft'        => $request->new_cstmr_prft , 
            'aftr_bil_nw_cst_prft'  =>$request->aftr_bil_nw_cst_prft,
            'allowed_areas'         => $allowed_areas,
            'total_area_profit'     => $request->total_area_profit
        ]);
        
       
        $productData = $request->all();

        CustomOtBenefit::where('ot_id', $id)->each(function($ben, $key){
            $ben->delete();
        });
        if (count($productData['checked_products']) > 0) {
            
            $checkedproductData = array_values($productData['checked_products']);
            
            foreach ($checkedproductData as $key => $rowValue) {
                
                if($rowValue == "on"){
                    $customB = new CustomOtBenefit();
                    $customB->ot_id = $ot->id;
                    $customB->product_id = $productData['product_id'][$key];
                    $customB->ot_benefit = $productData['ot_benefit'][$key];
                    $customB->save();
                }
            }
        }
        
        OtCustomer::where('ot_id', $id)->delete();

        if (!empty($request->customer_id)) {
            foreach($request->customer_id as $c_ids){


            //for ($i = 0; $i < sizeof($customer_ids); $i++) {
                $customerOT = new OtCustomer();
                $customerOT->ot_id = $ot->id;
                $customerOT->customer_id = $c_ids;
                $customerOT->save();
                // $customer_id = $productData['customer_id'][$i];
                // $customer = Customer::find($customer_id);
                // $customer->created_by = $ot->id;
                // $customer->save();
            //}
            }
        }
       // dd($request->customer_id);
        DB::commit();
        return Common::Message("Order Taker", 2);
        }catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            

            ///return back()->with('message', "Something Went Wrong!");
            return Common::Message("Something Went Wrong!");
        }
    }
    public function customeritself(Request $request)
        
        {
            $customer = User::findOrFail($request->user_id);
            $customer->customer_itself = $request->customer_itself;
            $customer->save();
            return response()->json(['message' => 'User status updated successfully.']);
        }
        
         public function AllowCreateCustomer(Request $request)
        
        {
            $customer = Ordertaker::find(Ordertaker::where('user_id' , $request->user_id)->pluck('id')->first());
            $customer->allow_create_customer = $request->allow_create_customer;
            $customer->save();
            return response()->json(['message' => 'User status updated successfully.']);
        }

         public function allowToEditOrder(Request $request)
        
        {
            $customer = Ordertaker::find(Ordertaker::where('user_id' , $request->user_id)->pluck('id')->first());
            $customer->allow_to_edit_order = $request->allow_to_edit_order;
            $customer->save();
            return response()->json(['message' => 'User status updated successfully.']);
        }
         public function allowToDeleteOrder(Request $request)
        
        {
            $customer = Ordertaker::find(Ordertaker::where('user_id' , $request->user_id)->pluck('id')->first());
            $customer->allow_to_delete_order = $request->allow_to_delete_order;
            $customer->save();
            return response()->json(['message' => 'User status updated successfully.']);
        }
         public function AllowStoreAreaData(Request $request)
        
        {
            $customer = Ordertaker::find(Ordertaker::where('user_id' , $request->user_id)->pluck('id')->first());
            $customer->store_varae_isit_data = $request->store_varae_isit_data;
            $customer->save();
            return response()->json(['message' => 'User status updated successfully.']);
        }
         public function DoNotShoPndngCst(Request $request)
        
        {
            $customer = Ordertaker::find(Ordertaker::where('user_id' , $request->user_id)->pluck('id')->first());
            $customer->do_not_sho_pndng_cst = $request->do_not_sho_pndng_cst;
            $customer->save();
            return response()->json(['message' => 'User status updated successfully.']);
        }
        public function PndngOnly(Request $request)
        
        {
            $customer = Ordertaker::find(Ordertaker::where('user_id' , $request->user_id)->pluck('id')->first());
            $customer->pndng_only = $request->pndng_only;
            $customer->save();
            return response()->json(['message' => 'User status updated successfully.']);
        }
        public function discountonoff(Request $request)
        
            {
            $customer = User::findOrFail($request->user_id);
            $customer->discount_on_off = $request->discount_on_off;
            $customer->save();
            return response()->json(['message' => 'User status updated successfully.']);
        }

        public function EneblePerVisitPrice(Request $request){

            $customer =Ordertaker::where('user_id' , $request->user_id)->first();
            //dd($request->eneble_per_visit_price);
            $customer->eneble_per_visit_price = $request->eneble_per_visit_price;
            $customer->save();
            return response()->json(['message' => 'User status updated successfully.']);
        }

        public function auto_area_price(Request $request){

            $customer =Ordertaker::where('user_id' , $request->user_id)->first();
            //dd($request->eneble_per_visit_price);
            $customer->auto_area_price = $request->auto_area_price;
            $customer->save();
            return response()->json(['message' => 'User status updated successfully.']);
        }

    public function deleteOT($id)
    {
        $ot = User::where('id', $id)->get();
        if (sizeof($ot)) {
            User::where('id', $id)->delete();
            Ordertaker::where('user_id', $id)->delete();
            PaidOtBenefit::where('ot_id', $id)->delete();
            return Common::Message("Order Taker", 3);
        } else {
            return Common::Message("Order Taker");
        }
    }

    public function ChangeUserStatus($id, $status)
    {
        if (session('pin')) {

            $updateData = ['created_at' => date('Y-m-d H:i:s'), 'is_blocked' => $status == "block" ? 1 : 0];
            User::where('id', $id)->update($updateData);

            session(['pin' => '']);
            return redirect()->back()->with('success', 'Order Taker Account Status Has Been Changed Successfully');
        }
    }
    public function paidHistory($id)
    {
      //  $paid_benefits = PaidOtBenefit::where('user_id', Auth::id())->where('ot_id', $id)->get();
         $paid_benefits = PaidOtBenefit::where('ot_id', $id)->get();
       
        return view('ordertaker.paid_ot_benefits', compact('paid_benefits'));
    }
    public function ShowUnvisitedSet(){
       
        if (Auth::user()->role == 5){
            $ot = Ordertaker::where('user_id' , Auth::id())->pluck('show_unvisited')->first();
            if ($ot == NULL){
            $data = 1;
       
            }
            else{
                $data = NULL;
            }
        Ordertaker::where('user_id', Auth::id())->update(['show_unvisited' => $data]);
         return redirect()->route('create.order')->with('success' , 'Status Changed');
         
        }
    }

    public function payAmount($id, Request $request)
    { 
        if (Auth::user()->role < 3){
       
            $ot = Ordertaker::where('user_id', $id)->first();
            $ben_paid = $request->amount + $ot->ben_paid;
            
            $total = Ordertaker::where('user_id', $id)->sum('ben_earned');
            $paid = Ordertaker::where('user_id', $id)->sum('ben_paid');

            Ordertaker::where('user_id', $id)->update(['ben_paid' => $ben_paid]);

            

            $paid_benefit = new PaidOtBenefit();
            $paid_benefit->user_id = Auth::id();
            $paid_benefit->ot_id = $id;
            $paid_benefit->total_is = $total;
            $paid_benefit->paid = $paid;
            $paid_benefit->paid_amount = $request->amount;
            $paid_benefit->comments = $request->comments;
            $paid_benefit->save();
            

            return Common::Message('Paid History', 6);
        
        }
        else {
            return Common::Message('Paid History', 7);
        }
    }
}
