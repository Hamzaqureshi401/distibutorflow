<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PosSale;
use App\Models\Order;
use App\Models\Customer;
use App\Models\User;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\PosSalesDetail;
use App\Models\PosSaleCashReceiving;
use App\Models\CustomPrice;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use File;

use Auth;

class PosController extends Controller
{
     public function StorePosSale(Request $request){

       try{
         DB::beginTransaction();
        
        $customer_id = $this->GetCustomerId();
       // yay for each my product id ki price or c_ben ko unit sy * kryga
       $result       = $this->GetProductSubtotal($request , $customer_id);
       $sum_subtotal = array_sum($result['subtotal']);
       // possale table my record save hoga
       $sale_id      = $this->StorePosSaleInDb($sum_subtotal , $customer_id , $request);
       // hr product ka record details my save hoga
       foreach($request->product_id as $key => $product_id){
            $this->StorePosSaleDetail($sale_id , $customer_id , $product_id , $request->unit[$key]);
       }
        DB::commit();
        return redirect()->route('Add.Pos.Sale')->with('success', 'Sale Added Successfully');
        }catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
           return response()->json(['message'=> 'Something Went Wrong!']);
        }
     }
     protected function GetProductSubtotal($request , $customer_id){

         $product = new Product();
         foreach ($request->product_id as $key => $products_id){
            $data['subtotal'][$key]  = $product->getProductrecord($customer_id , $products_id)
                                            ->sell_price * $request->unit[$key];
            $data['c_benefit'][$key] = $product->getProductrecord($customer_id , $products_id)
                                            ->c_benefit * $request->unit[$key];
         }
           return $data;
      }

     protected function StorePosSaleInDb ($sum_subtotal , $customer_id , $request){

        $store                  = new PosSale();
        $store->customer_id     = $customer_id;
        $store->sell_creater_id = Auth::id();
        $store->subtotal        = $sum_subtotal;
        $store->received_amount = $sum_subtotal; // in future may be uppdated
        $store->amount_left     = 0;
        $store->discount        = 0;
        $store->comments        = $request->comments;
        $store->save();

        return $store->id;
     }

     protected function StorePosSaleDetail($sale_id , $customer_id , $product_id , $unit){

        $product = new Product();
        $data['subtotal']       = $product->getProductrecord($customer_id , $product_id)->sell_price * $unit;
        $data['c_benefit']      = $product->getProductrecord($customer_id , $product_id)->c_benefit * $unit;
        $store                  = new PosSalesDetail();
        $store->pos_sale_id     = $sale_id;
        $store->product_id      = $product_id;
        $store->unit            = $unit;
        $store->c_benefit       = $data['c_benefit'];
        $store->subtotal        = $data['subtotal'];

        if($unit != 0){
            $store->save();
        }
    }

    public function GetPosData(){
        $customer_user_id = $this->FindCustomerAuthId();
        $users = User::where('id' , $customer_user_id)->first();
        return view('pos.pos_view' , compact( 'users'));
    }

    public function FindCustomerAuthId(){
        if (Auth::user()->role == 4){
            return  Auth::id();
        }else{
            return Auth::user()->customer_id;
        }
    }

    public function GetPosAjaxPrices($id){
         
        $get_products_class = new ProductController();
        $customer_user_id   = $this->FindCustomerAuthId();
        $get_products       = $get_products_class->getCustomerproduct($customer_user_id);
        $stock_products     = Product::whereIn('id' , $get_products)->orWhere('user_id' , Auth::id())->get();
        $customer_id        = $this->GetCustomerId();
        
        return view('pos.ajax_pos_unit_prices', compact('stock_products' , 'customer_id' , 'customer_user_id'));
    }

    public function GetPosUncnfirmedSale(){

         $customer_id = $this->GetCustomerId();
         $sales = PosSale::where(['customer_id' => $customer_id])->whereNull('is_confirmed_admin')->whereNull('is_confirmed_manager')->get();
         $product_report = $this->GetPosSaleDeatilsMultiple($sales);
         return view('pos.unconfirmed_sale', compact('sales' , 'product_report'));
    }
    
    public function GetIdsOrder($id){

         $customer_id = $this->GetCustomerId();
         $confirmedids = PosSaleCashReceiving::where('id' , $id)->first()->pos_sell_ids;
         $ids = explode('|' , $confirmedids);
         $sales = PosSale::where(['customer_id' => $customer_id])->whereIn('id' , $ids)->get();
         $product_report = $this->GetPosSaleDeatilsMultiple($sales);
         return view('pos.GetIdsOrder', compact('sales' , 'product_report'));
    }
    public function GetPosManagerSale(){

         $customer_id = $this->GetCustomerId();
         $sales = PosSale::where(['customer_id' => $customer_id])->whereNull('is_confirmed_admin')->whereNotNull('is_confirmed_manager')->get();
          $product_report = $this->GetPosSaleDeatilsMultiple($sales);
        
         return view('pos.manager_confirmed_sell', compact('sales' , 'product_report'));
    }

    public function GetPosSaleDeatils($id){

        $saledetails = PosSalesDetail::where('pos_sale_id' , $id)->get();

        return view('pos.ajax_pos_sale_details', compact('saledetails'));

    }
    public function GetPosSaleDeatilsMultiple($Sales){
        $ids = $Sales->pluck('id')->toArray();
        $query =  PosSalesDetail::whereIn('pos_sale_id' , $ids);
        $data['product_id'] = array_unique($query->pluck('product_id')->toArray());
        foreach ($data['product_id'] as $key => $products_id){
            $data['name'][$key] = Product::where('id' , $products_id)->first()->name;
            $data['units'][$key] = (clone $query)->where('product_id' , $products_id)->sum('unit');
            $data['subtotal'][$key] = (clone $query)->where('product_id' , $products_id)->sum('subtotal');
        }
        return $data;
    }
    public function GetCustomerId(){

        if (Auth::user()->role ==4){
            $customer_id = Auth::user()->customer_id;
        }else{
            $customer_id = Auth::user()->seller_of;
        }

        return $customer_id;
    }
    public function ApprovePosSale(Request $request){

         try{
         DB::beginTransaction();
        $customer_id = $this->GetCustomerId();
        if (Auth::user()->role == 4){
            $this->CashProcessing($request);
            $this->ReducePosStock($request['select-check-box'] , Auth::user()->id);// product stock k liyay auth::id use hoi h
        }elseif(Auth::user()->role == 7){
            $this->CashProcessing($request);
            }
        foreach($request['select-check-box'] as $SingleSale){
            $query = PosSale::where('id' , $SingleSale)->whereNull('is_confirmed_admin');
            if (Auth::user()->role == 4){
                $query->update(['is_confirmed_admin' => $customer_id , 'approve_date' => date('Y-m-d H:i')]);
            }elseif (Auth::user()->role == 7){
                $query->whereNull('is_confirmed_manager')->update(['is_confirmed_manager' => Auth::id()]);
            }
        }
            DB::commit();
            return redirect()->route('Add.Pos.Sale')->with('success', 'Sale Added Successfully');
        }catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
           return response()->json(['message'=> 'Something Went Wrong!']);
        }
    }
    public function CashProcessing($request){

        $customer_id              = $this->GetCustomerId();
        $PosSaleQuery             = PosSale::whereIn('id' , $request['select-check-box']);
        if(Auth::user()->role    == 7){
            $GetOldDataReceiving  = $this->GetAllUserCashReceivingData();
            $processor_id         = Auth::id();
            $GetDataPosSale       = $PosSaleQuery->whereNull('is_confirmed_admin')->whereNull('is_confirmed_manager')->get();
        }elseif(Auth::user()->role  == 4){
            $GetOldDataReceiving  = $this->GetAllUserCashReceivingData();
            $processor_id         = NULL;
            $GetDataPosSale       = $PosSaleQuery->whereNull('is_confirmed_admin')->get();
        }
        $pos_sell_ids             = implode('|' , $request['select-check-box']);
        $cash_paid_added          = $GetDataPosSale->sum('subtotal');
        if(!empty($GetOldDataReceiving)){
            $old_cash_remaining       = $GetOldDataReceiving->current_cash_remaining;
        }else{
            $old_cash_remaining       = $GetOldDataReceiving->old_cash_remaining ?? 0;    
        }
        $current_cash_remaining   = $GetDataPosSale->sum('subtotal') + $old_cash_remaining;
        $discounts                = NULL;
        $expenses                 = NULL;
        $unconfirmed_expences     = NULL;
        $outside_payments         = NULL;
        $comments                 = NULL;
        $attach_to_bill_number    = NULL;

        $this->CreatePosSaleCashReceiving(
            $customer_id,
            $processor_id,
            $pos_sell_ids,
            $old_cash_remaining,
            $cash_paid_added,
            $current_cash_remaining,
            $discounts,
            $expenses,
            $unconfirmed_expences,
            $outside_payments,
            $attach_to_bill_number,
            $comments
        );
 
    }
    public function CreatePosSaleCashReceiving(
            $customer_id,
            $processor_id,
            $pos_sell_ids,
            $old_cash_remaining,
            $cash_paid_added,
            $current_cash_remaining,
            $discounts,
            $expenses,
            $unconfirmed_expences,
            $outside_payments,
            $attach_to_bill_number,
            $comments){

        $Modal = new PosSaleCashReceiving();
        $Modal->customer_id               = $customer_id;
        $Modal->processor_id              = $processor_id;
        $Modal->pos_sell_ids              = $pos_sell_ids;
        $Modal->old_cash_remaining        = $old_cash_remaining;
        $Modal->cash_paid_added           = $cash_paid_added;
        $Modal->current_cash_remaining    = $current_cash_remaining;
        $Modal->discounts                 = $discounts;
        $Modal->expenses                  = $expenses;
        $Modal->unconfirmed_expences      = $unconfirmed_expences;
        $Modal->outside_payments          = $outside_payments;
        $Modal->attach_to_bill_number     = $attach_to_bill_number;
        $Modal->comments                  = $comments;

        $Modal->save();
    }
     public function ReducePosStock($pos_sell_ids , $customer_id){

        $getproductcontroller = new ProductController();
        $transection_id = $getproductcontroller->GetUniqueTransectionId();  
        $query =  PosSalesDetail::whereIn('pos_sale_id' ,$pos_sell_ids);
        $GetPosSaleDeatils = array_unique((clone $query)->pluck('product_id')->toArray());
        foreach($GetPosSaleDeatils as $product_id){
            $SumUnits = (clone $query)->where('product_id' , $product_id)->get()->sum('unit');
            $getproductcontroller->SetProductStock(
            $product_id, 
            $customer_id , // product k stock k liyay user table ki id leni hy
            -$SumUnits , 
            $comments = NULL , 
            $invoice_type = "sell_added", 
            $stock_adder_id = false,
            $transection_id 
        );
        }
    }

    public function GetCustomerCashReceivings(){

        $customer_id = $this->GetCustomerId();
        $customer_Auth_id = $this->FindCustomerAuthId();
        if(Auth::user()->role == 4){
            $receivings = PosSaleCashReceiving::where(['customer_id' => $customer_id])->whereNull('processor_id')->orderBy('id' , 'desc')->get();
            $AllManager = User::where(['seller_of' => $customer_id , 'role' => 7])->pluck('id')->toArray();
        }elseif(Auth::user()->role == 7){
            $AllManager = User::where(['seller_of' => $customer_id , 'role' => 7])->pluck('id')->toArray();
            $receivings = PosSaleCashReceiving::where(['customer_id' => $customer_id])->whereIn('processor_id' , $AllManager)->orderBy('id' , 'desc')->get();
        }
        $exppayment =   PosSaleCashReceiving::whereIn('customer_id' , $AllManager)->where('unconfirmed_expences' , 1)->get();
        $last_invoice = Invoice::where('customer_id' , $customer_id)->orderBy('id' , 'desc')->first();
        $UserModel = new User();

        $get_order_class = new OrderController();

        $purchase = $get_order_class->GetSumOrderSubtotal($customer_id , $bill_no = "All");
        
       
       return view('CustomerCashReceiving.customer_cash_receivings' , compact('receivings' , 'last_invoice' , 'customer_id' , 'UserModel' , 'exppayment' , 'purchase'));  

    }

    public function GetCashPaidHistory($customer_id , $bill_no){

        if ($bill_no == "All" ){
         $receivings = PosSaleCashReceiving::where(['customer_id' => $customer_id])->whereNull('processor_id')->get();
         $paid = $receivings->whereNull('pos_sell_ids')->sum('cash_paid_added');
         $AllReceivings = false;
        }else{
          $query = PosSaleCashReceiving::where(['customer_id' => $customer_id])->whereNull('processor_id')->where('attach_to_bill_number' , $bill_no);
           $receivings = $query->get();
           $AllReceivings = PosSaleCashReceiving::where(['customer_id' => $customer_id])->whereNull('processor_id')->whereNull('pos_sell_ids')->get();

          if ($query->exists()){
             $paid = $query->whereNull('pos_sell_ids')->sum('cash_paid_added');
          }else{
            $paid = false;
            $receivings = false;
            $AllReceivings = false;
          }
          }
          $data['paid'] = $paid;
          $data['receivings'] = $receivings;
          $data['AllReceivings'] = $AllReceivings;
          
          return $data;

    }

   
    public function AddManualCash(Request $request){

       if (Auth::user()->role == 4 || Auth::user()->role == 7){
        $customer_id = $this->GetCustomerId();
        $OldReceivings  = $this->GetAllUserCashReceivingData();
        $old_cash_remaining = $OldReceivings->current_cash_remaining ?? 0;
        $cash_paid_added = -$request->amount;
        $current_cash_remaining = $old_cash_remaining + $cash_paid_added;
        $comments = $request->comments;
        if(Auth::user()->role == 7){
            $processor_id = Auth::id();
        }else{
            $processor_id = NULL;
        }
        $bill_no = $this->AttachBillNumber($customer_id);
         $this->CreatePosSaleCashReceiving(
            $customer_id,
            $processor_id = $processor_id,
            $pos_sell_ids = NULL,
            $old_cash_remaining,
            $cash_paid_added,
            $current_cash_remaining,
            $discounts  = NULL,
            $expenses  = NULL,
            $unconfirmed_expences  = NULL,
            $outside_payments  = NULL,
            $attach_to_bill_number = $bill_no,
            $comments
        );        
       }
       return redirect()->route('Get.Customer.Cash.Receivings')->with('success', 'Amount Received Successfully!');
    }
    public function AttachBillNumber($customer_id){

         $query = Order::where('customer_id' , $customer_id);
        if ($query->latest()->first()->exists()){
            $bill_no = Order::where('customer_id' , $customer_id)->pluck('id')->count();
        }else{
            $bill_no = 0;
        }
        return $bill_no;
    }
     public function AddPaymentToAdmin(Request $request){

       if (Auth::user()->role == 7 || Auth::user()->role == 3){
        if (Auth::user()->role == 3){
            $primaryManager = User::where(['seller_of' => $request->customer_id , 'role' => 7]);
            if (empty($primaryManager->exists())){
                $primaryManager =  $this->storePrimaryManager($request);
        }else{
            $primaryManager = $primaryManager->first();    
        }
        $customer_id = $primaryManager->id;
        }
        $old_cash_remaining = 0;
        $cash_paid_added = 0;
        $current_cash_remaining = 0;
        if(Auth::user()->role == 7){
            $processor_id = Auth::id();
             $customer_id = Auth::id();
             $comments = $request->comments;
             $bill_no = $this->AttachBillNumber($this->GetCustomerId());
        }elseif (Auth::user()->role == 3){
            $comments = $request->comments . ' Added By |' . Auth::user()->name . 'Seller|';
            $processor_id = $primaryManager->id;
            $bill_no = $this->AttachBillNumber($request->customer_id);
        }else{
            $processor_id = NULL;
        }
         $this->CreatePosSaleCashReceiving(
            $customer_id,
            $processor_id = $processor_id,
            $pos_sell_ids = NULL,
            $old_cash_remaining,
            $cash_paid_added,
            $current_cash_remaining,
            $discounts  = NULL,
            $expenses  = $request->expenses,
            $unconfirmed_expences  = 1,
            $outside_payments  = $request->payment,
            $attach_to_bill_number = $bill_no,
            $comments
        );

        return redirect()->back()->with('success', 'Amount Received Successfully!');        
       }
    }

    public function GetAllUserCashReceivingData(){
       
       $customer_id = $this->GetCustomerId();
       if (Auth::user()->role == 4 ){
            $data = PosSaleCashReceiving::where('customer_id' , $customer_id)->whereNull('processor_id')->latest('id')->first();
        }elseif(Auth::user()->role == 7){
            $AllManager = User::where(['seller_of' => $customer_id , 'role' => 7])->pluck('id')->toArray();
            $data      = PosSaleCashReceiving::whereIn('processor_id' , $AllManager)
            ->where('customer_id' , "!=" , Auth::id())->latest('id')->first();
        }
        return $data;
    }
    public function ConfirmCustomerExpnse(Request $request){

        try{
         DB::beginTransaction();
        $data = PosSaleCashReceiving::where(['id' => $request->id , 'unconfirmed_expences' => 1])->first();
        if(empty($data)){
            return response()->json(['message'=> 'Transection Already Confirmed']);
        }
        $username = User::where('id' , $data->customer_id)->first()->name;
        $amount = ($data->expenses ?? 0) + ($data->outside_payments ?? 0);
        //dd($amount);
         if (Auth::user()->role == 4 ){
        $customer_id = $this->GetCustomerId();
        $OldReceivings  = $this->GetAllUserCashReceivingData();
        $old_cash_remaining = $OldReceivings->current_cash_remaining;
        $cash_paid_added = -$amount;
        $current_cash_remaining = $old_cash_remaining + $cash_paid_added;
        $comments = "Confirmed Transection Which Added by $username";
         $this->CreatePosSaleCashReceiving(
            $customer_id,
            $processor_id = NULL,
            $pos_sell_ids = NULL,
            $old_cash_remaining,
            $cash_paid_added,
            $current_cash_remaining,
            $discounts  = NULL,
            $expenses  = $data->expenses ?? NULL,
            $unconfirmed_expences  = NULL,
            $outside_payments  = $data->outside_payments ?? NULL,
            $attach_to_bill_number = NULL,
            $comments
        );        
       }
       $data->unconfirmed_expences = NULL;
       $data->save();
           DB::commit();
           return response()->json(['message'=> 'Transection Confirmed']);
       }catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
           return response()->json(['message'=> 'Something Went Wrong!']);
        }

    }

    public function deleteCustomerTransection(Request $request){

        $data = PosSaleCashReceiving::where(['id' => $request->id , 'unconfirmed_expences' => 1])->delete();
        return response()->json(['message'=> 'Transection Deleted']);

    }

    public function storePrimaryManager($request){

            $customer = User::where('customer_id' , $request->customer_id)->first();
            $StoreUser = new User();   
            $StoreUser['role'] = 7; // Manager
            $StoreUser->name        = 'Primary Manager ' . $customer->name;
            $StoreUser->email       = $this->generateRandomString()."@scoops.com";
            $StoreUser->password    = 123456;
            $StoreUser->phone       = $request['phone'];
            $StoreUser->seller_of   = $customer->customer_id;
            $StoreUser->customer_id = $customer->id;
            
            $StoreUser->save();
            return $StoreUser; 
         
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

        public function addPosSale(Request $request){

            //dd($request->products_id);

            if(empty($request->products_id)){
                return response()->json(['message'=> 'At Least 1 Product is Required to Store Sale!']);
            }
            $data = $this->findPosSaleCustomerProduct($request);
            $data['order_date']      = date('Y-m-d');
            $data['location_url_ot'] = '31.5815886,74.3779746';
            $data['ot_id'] = Auth::id();
            $data['discount'] = 0;
            if(empty($request->received_amount)){
                $request->received_amount = 200;
            }
            $invData               = new Request(array_merge($request->all() , $data));
            $get_inv_class         = new OrderController();
            $order                 = $get_inv_class->storeOrderNew($invData);
            $order->selected_seller         = Auth::id();
            $order->is_confirmed_seller     = Auth::id();
            $order->seller_processed_order  = Auth::id();
            $order->is_confirmed_admin      = Auth::id();
            $order->save();
            //dd($order);
            if($order){
                 return response()->json(['message'=> 'Invoice Saved Successfully']);
            }else{
                return response()->json(['message'=> 'Something Went Wrong!']);
            }
        }

        public function findPosSaleCustomerProduct($request){

            $customer_id            = explode('-' , $request->customer_id);
            $customer               = Customer::where('id' , $customer_id)->first();

            $data['product_id']     = array_reverse(explode(',' , $request->products_id));
            foreach($data['product_id'] as $key => $product){
                if ($request->customer_id != 'defualt') {
                $CheckCustomPrice   = CustomPrice::where(['customer_id' => $customer->id , 'product_id' => $product]);
                if($CheckCustomPrice->exists() == true){
                    $price = $CheckCustomPrice->first()->price;
                }else{
                    $price = Product::where('id' , $product)->first()->price;
                }
            }else{
                $price = Product::where('id' , $product)->first()->price;
            }
            $data['price'][] = $price;
            $data['amount'][] = $request->unit[$key] * $price;
        }
            return $data;
        }


}
