<?php

namespace App\Http\Controllers;

use App\Repositories\Common;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use resources\views\layouts\app;
use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Notification;
use App\Models\AdminSellRecord;
use App\Models\AdminSellTotal;
use App\Models\CustomPrice;
use App\Models\PaidAmount;
use App\Models\Admin_purchase_historie;
use App\Models\SubAdmin;
use App\Models\Order;
use App\Models\Seller;
use App\Http\Controllers\ProductController;
use Auth;
use PDF;
use Illuminate\Support\Facades\Artisan;
class InvoiceController extends Controller
{

    public function getAllSeller(){

        $ids = [Auth::id()];
            foreach (Auth::user()->mysellers as $value) {
                $ids[] = $value->id;
            }
            $ids[] = Auth::user()->seller_of;
            return $ids;
    }
    public function index($get_unapproved = "", $from = "", $to = "")
    {

        $cond = $get_unapproved == "" ? ['is_approved'  => 1] : ['is_approved'  => null];
            
        if (Auth::user()->role <= 3) {
           $ids = $this->getAllSeller();
           $query= Invoice::where('user_id', Auth::id())->whereNotNull('customer_id')->where($cond); 
        
            if ($from != "") {
                $invoices = $query->whereBetween('created_at', array($from, $to))->paginate(500);
            }
        }
        $key_counted = [];
        $keys_generated = []; 
        foreach (array_reverse($query->get()->toArray(), true) as $key => $row) {
            if (!in_array($row['customer_id'], $key_counted)) {
                $key_counted[$key] = $row['customer_id'];
                $keys_generated[] = $key;
            }
        }
        $product_report = $this->getProductReport($query);
        $invoices = $query->paginate(500);
         if ($get_unapproved == "") {
           // $invoices = Invoice::whereIn('user_id', $ids)->where($cond)->paginate(10);
            return view('invoice.approved_invoices', compact('invoices', 'product_report', 'keys_generated'));
        } else {
            return view('invoice.pending_invoices', compact('invoices', 'product_report', 'keys_generated'));
        }
    }

    public function unApprovedStockInvoices($from = "", $to = ""){
        
        // Artisan::call('config:cache');
        // Artisan::call('config:clear');
        //  Artisan::call('cache:clear');

        if (Auth::user()->role <= 3) {
           $ids = $this->getAllSeller();
           $query= Invoice::whereIn('user_id', $ids)->whereNull('customer_id')->whereNull('is_approved'); 
           //dd($query->get() , $ids , Auth::id());
        
            if ($from != "") {
                $invoices = $query->whereBetween('created_at', array($from, $to))->paginate(500);
            }
        }
        $key_counted = [];
        $keys_generated = []; 
        foreach (array_reverse($query->take(500)->get()->toArray(), true) as $key => $row) {
            if (!in_array($row['customer_id'], $key_counted)) {
                $key_counted[$key] = $row['customer_id'];
                $keys_generated[] = $key;
            }
        }
        $product_report = $this->getProductReport($query);
        $invoices = $query->paginate(500);

         return view('invoice.unconfirmed_Stock_Invoices', compact('invoices', 'product_report', 'keys_generated'));

    }
    public function getProductReport($query){

        $products = Product::where('user_id', Auth::id())->get();
        $product_report = [];
        $counter = 0;
        foreach ($products as $p) {
            $ppunit = 0;
            $ppamount = 0;
            $product_report[$counter]['id'] = $p->id;
            $product_report[$counter]['name'] = $p->name;
            $product_report[$counter]['p_price'] = $p->p_price;
            foreach ($query->take(500)->get() as $in) {
                $idet =  $in->invoicedetail->where('product_id', $p->id);
                $ppunit += $idet->sum('unit');
                $ppamount += $idet->sum('p_amount');
            }
            $product_report[$counter]['unit'] = $ppunit;
            $product_report[$counter]['amount'] = ($p->p_price * $ppunit);
            $product_report[$counter]['checks'] = $ppamount;
            $product_report[$counter]['remaining'] = AdminSellRecord::where('user_id', Auth::id())->sum('p_amount') - PaidAmount::where('user_id', Auth::id())->get()->sum('paid');

            $product_report[$counter]['checkunit'] = $query->get()->sum('unit');

            $counter++;
        }

        return $product_report;

    }
    public function GetStockInvoices (){

        if(Auth::user()->role < 3){
            $adminid = Auth::id();

        }elseif(Auth::user()->role == 3){
            $adminid = Auth::user()->seller_of;
        }
        $subadmin = SubAdmin::where('product_link' , $adminid)->pluck('sub_admin_id')->toArray();
     
     $invoices = Invoice::where('stock_adder_id' ,"!=" , null)->whereIn('user_id' , [$adminid , Auth::id() , $subadmin])->get();   
     $key_counted = [];
        $keys_generated = []; 
        foreach (array_reverse($invoices->toArray(), true) as $key => $row) {
            if (!in_array($row['customer_id'], $key_counted)) {
                $key_counted[$key] = $row['customer_id'];
                $keys_generated[] = $key;
            }
        }
        $products = Product::where('user_id', Auth::id())->get();
        $product_report = [];
        $counter = 0;
        foreach ($products as $p) {
            $ppunit = 0;
            $ppamount = 0;
            $product_report[$counter]['id'] = $p->id;
            $product_report[$counter]['name'] = $p->name;
            foreach ($invoices as $in) {
                $idet =  $in->invoicedetail->where('product_id', $p->id);
                $ppunit += $idet->sum('unit');
                $ppamount += $idet->sum('p_amount');
            }
            $product_report[$counter]['unit'] = $ppunit;
            $product_report[$counter]['amount'] = ($p->p_price * $ppunit);
            $product_report[$counter]['checks'] = $ppamount;
            $product_report[$counter]['remaining'] = AdminSellRecord::where('user_id', Auth::id())->sum('p_amount') - PaidAmount::where('user_id', Auth::id())->get()->sum('paid');

            $product_report[$counter]['checkunit'] = $invoices->sum('unit');

            $counter++;
        }
         return view('invoice.stock_invoices', compact('invoices', 'product_report', 'keys_generated'));
    }

    public function indexAll($admin_id)
    {
        $ids = [];
        $sub_admin = User::find($admin_id);
        foreach ($sub_admin->mysellers as $value) {
            $ids[] = $value->id;
        }
        $invoices = Invoice::whereIn('user_id', $ids)->get();
        return view('subadmin_invoices', compact('invoices', 'sub_admin'));
    }

    public function newInvoice()
    {
        //if something happen uncomment or allow seller to add invoice
        // $ids = [Auth::id()];
        // if(Auth::user()->role == 3){
        //     $ids[] = User::find(Auth::user()->seller_of)->id;
        // }

        $ids = [Auth::id()];
        if (Auth::user()->role <= 3) {
            $ids = array_merge($ids, User::where('ot_of', Auth::id())->pluck('id')->toArray());
            
            //$ids[] = User::find(Auth::user()->seller_of)->id;
        }
        

        $customers = Customer::whereIn('created_by', $ids)->with('user')->get()->sortBy('user.name'); //}
        if(empty($customers->first())){
             return redirect()->route('add.customer')->with('error', 'No Customer Found! Please Create Customer First');
        }
        $products = Product::where('user_id', Auth::id())->get();
        return view('invoice.add_invoice', compact('customers', 'products'));
    }
    
    public function manageStock(){
        
        if (Auth::user()->role <= 3){
        $id = Auth::id();
        $allowed_user = SubAdmin::where('sub_admin_id' , Auth::id())->pluck('product_link')->first();
         if (Auth::user()->role == 3){
        $get_seller = Seller::where('seller_id', Auth::id());
        $allowed_user = $get_seller->pluck('chk_add_remove_stock')->first();
         }
        $allowed_ids = [$id, $allowed_user];
        $users = User::whereIn('id' , $allowed_ids)->get();
        $stock_type = 1;
        return view('invoice.manage_stock', compact('users' , 'stock_type' , 'allowed_user'));
        }
        else
        return redirect()->back()->with('error', 'Access Denied');
   
    }

    public function storeInvoice(Request $request, $update = false){

        //dd($request->all());
         if ($request->stock_type == 1 && Auth::user()->role <= 3) {
            $this->addremovestock($request);
             return redirect()->back()->with('success', 'Stock Added');
      }  
      else{
        $invoice                    = new Invoice();

        $cus_det                    = explode("-", $request->customer_id);

        $inv = Invoice::where('customer_id' , $cus_det[0])->orderBy('id' , 'desc');
        if($inv->exists() == true){
            $old_balance = $inv->first()->amount_left;
        }else{
            $old_balance = 0;
        }
        $tt_amount                  = array_sum($request->amount) + $old_balance;
        $invoice->customer_id       = $cus_det[0];
        $invoice->user_id           = Auth::id();
        $invoice->unit              = array_sum($request->unit);
        $invoice->amount            = $tt_amount;
        $invoice->subtotal          = array_sum($request->amount);
        $invoice->received_amount   = $request->received_amount;
        $invoice->discount          = $request->discount;
        $invoice->comments          = $request->comments;
        
        if ($tt_amount > $request->received_amount) {
            $invoice->amount_left   = $tt_amount -  $request->received_amount - $request->discount; 
        } else {
            $invoice->advance       = $request->received_amount - $tt_amount;
            $invoice->amount_left   = $tt_amount -  $request->received_amount - $request->discount;
        }

        $invoice->save();

        $invoice_id                 = $invoice->id;

        $invoiceData                = $request->all();
        $total_admin_benefit        = 0;
        $total_customer_benefit     = 0;
        $p_amount                   = 0;
        for ($counter = 0; $counter < sizeof($request->amount); $counter++) {
            if ($invoiceData['amount'][$counter] != '') {
                $invoiceDetails = new InvoiceDetail();
                $invoiceDetails->invoice_id = $invoice_id;
                $is_custom_price = CustomPrice::where(['customer_id' => $request->customer_id, 'product_id' => $invoiceData['product_id'][$counter]])->first();
                $is_default_price = Product::find($invoiceData['product_id'][$counter]);
                if (!empty($is_custom_price)) {
                    $invoiceDetails->a_benefit = $is_custom_price->a_benefit * $invoiceData['unit'][$counter];
                    $invoiceDetails->c_benefit = $is_custom_price->c_benefit * $invoiceData['unit'][$counter];
                    $invoiceDetails->sell_price = $is_custom_price->sell_price * $invoiceData['unit'][$counter];
                    $invoiceDetails->p_amount = $is_custom_price->product->p_price * $invoiceData['unit'][$counter];
                   
                } else {
                    $invoiceDetails->a_benefit = $is_default_price->a_benefit * $invoiceData['unit'][$counter];
                    $invoiceDetails->c_benefit = $is_default_price->c_benefit * $invoiceData['unit'][$counter];
                    $invoiceDetails->sell_price = $is_default_price->sell_price * $invoiceData['unit'][$counter];
                    $invoiceDetails->p_amount = $is_default_price->p_price * $invoiceData['unit'][$counter];
                }
                $invoiceDetails->product_id = $invoiceData['product_id'][$counter];
                $invoiceDetails->unit = $invoiceData['unit'][$counter];
                $invoiceDetails->amount = $invoiceData['amount'][$counter];
                $invoiceDetails->save();
            }
        }

        Invoice::where(['id' => $invoice_id])->update(['a_benefit' => $invoice->invoicedetail->sum('a_benefit'), 'c_benefit' => $invoice->invoicedetail->sum('c_benefit'), 'p_amount' => $invoice->invoicedetail->sum('p_amount'), 'sell_price' => $invoice->invoicedetail->sum('sell_price')]);

        if ($this->getAdminId(Auth::id()) != Auth::id()) {
            $this->notifyAdmin($invoice_id);
        }

        return redirect()->route('invoices', 'unapproved')->with('success', 'Invoice Created');
      }
    }
    
     protected function addremovestock(Request $request){
        
        $invoice = new Invoice();
        $cus_det = explode("-", $request->customer_id);
        $invoice->stock_adder_id = Auth::id();
        if ($request->user_id != NULL){
            $invoice->user_id = $request->user_id;
        }
        else{
        $invoice->user_id = Auth::id();
            
        }
        $invoice->unit = array_sum($request->unit);
        $invoice->comments  = $request->comments;
        $invoice->stock_type = 1;
        if(Auth::user()->role == 3){
        $invoice->is_approved_stock = 0;
        }
        
        $invoice->amount = 0;
        $invoice->subtotal = 0;
        $invoice->received_amount = 0;
        $invoice->discount = 0;
        $invoice->amount_left = 0; 
        $invoice->advance = 0;
        
        $invoice->save();

        $invoice_id = $invoice->id;

        $invoiceData = $request->all();
        $total_admin_benefit = 0;
        $total_customer_benefit = 0;
        $p_amount = 0;
        for ($counter = 0; $counter < sizeof($request->unit); $counter++) {
            if ($invoiceData['unit'][$counter] != '') {
                $invoiceDetails = new InvoiceDetail();
                $invoiceDetails->invoice_id = $invoice_id;
                $is_default_price = Product::find($invoiceData['product_id'][$counter]);
                
                    $invoiceDetails->a_benefit = 0;
                    $invoiceDetails->c_benefit = 0;
                    $invoiceDetails->sell_price = 0;
                    $invoiceDetails->p_amount   = 0;
                    $unit = $invoiceData['unit'][$counter];
                    $product_id = $invoiceData['product_id'][$counter];
                    $find_prouct = Product::where('id' , $product_id);
                    $old_stock = $find_prouct->pluck('remaining_stock')->first();
                    if(Auth::user()->role < 3){
                    $update_stock = $old_stock + $unit;
                    }
                    else{
                        $update_stock = $old_stock;
                    }
                     
                    
                    
                    Product::where('id' , $product_id)->update(['remaining_stock' => $update_stock]);
                    $invoiceDetails->old_stock = $old_stock;

                }
                $product_id = $invoiceData['product_id'][$counter];
                
                $invoiceDetails->product_id = $product_id;
                
                $invoiceDetails->unit = $invoiceData['unit'][$counter];
                $invoiceDetails->amount = 0;
                $invoiceDetails->save();
            }
            //return back()->with('success' , 'Stock Added Successfully');
           
    }

    public function getInvoice($id)
    {
        $parent = Auth::id();
        $added = [];
        $invoice = Invoice::find($id);
        $customPrices = CustomPrice::where('customer_id', $invoice->customer_id)->get();
        $customer = Customer::find($invoice->customer_id);
        if (sizeof($customPrices)) {
            foreach ($invoice->invoicedetail as $d) {
                foreach ($customPrices as $custom) {
                    if ($custom->product_id == $d->product_id) {
                        $d->product->price = $custom->price;
                        $d->product->c_benefit = $custom->c_benefit;
                        $d->product->sell_price = $custom->sell_price;
                    }
                }
                $added[] = $d->product_id;
            }
        } 
         $products = Product::orderBy('category_id', 'asc')
                    ->whereIn('id', explode('|', $customer->final_allowed_products))->orWhere('allow_to_all_customer' , 1)->where('user_id', $parent)->get();
        //$products = Product::whereNotIn('id', $added)->where('user_id', Auth::id())->get();
        foreach ($products as $product) {
            foreach ($customPrices as $custom) {
                if ($custom->product_id == $product->id) {
                    $product->price = $custom->price;
                    $product->c_benefit = $custom->c_benefit;
                    $product->sell_price = $custom->sell_price;
                }
            }
        }
        $old_invoices = Invoice::where('customer_id', $invoice->customer_id)->orderBy('id', 'desc')->get();
        $old_balance = 0;
        if (sizeof($old_invoices) > 1) {
            $old_balance = $old_invoices[1]->amount_left;
        }
        return view('invoice.edit_invoice', compact('invoice', 'products', 'old_balance'));
    }
    
    public function getdatedInvoice($date)
    {
        $date = date('Y-m-d', strtotime($date));
        $invoices = Invoice::where('user_id' , Auth::id())->where('is_approved' , 1)->whereDate('approve_date' , $date)->paginate(500);
         $ids = [Auth::id()];
            foreach (Auth::user()->mysellers as $value) {
                $ids[] = $value->id;
            }
            $available_dates = array_unique(Invoice::whereIn('user_id', $ids)->where('is_approved' , 1)->whereNotNull('approve_date')->orderBy('id', 'desc')->pluck('approve_date')->toArray());
        $products = Product::where('user_id', Auth::id())->get();
        $product_report = [];
        $counter = 0;
        $invoices_ID = $invoices->pluck('id')->toArray();
       
        foreach ($products as $p) {
            $ppunit = 0;
            $ppamount = 0;
            $product_report[$counter]['id'] = $p->id;
            $product_report[$counter]['name'] = $p->name;
            $invoices_sum_p_amount =  InvoiceDetail::whereIn('invoice_id' , $invoices_ID)->where('product_id' , $p->id)->sum('p_amount');

            foreach ($invoices as $in) {
               
                $idet =  $in->invoicedetail->where('product_id', $p->id);
                $ppunit += $idet->sum('unit');
                $ppamount += $idet->sum('p_amount');
            }

            $product_report[$counter]['unit'] = $ppunit;
            $product_report[$counter]['amount'] = ($p->p_price * $ppunit);
            $product_report[$counter]['checks'] = $ppamount;
            $product_report[$counter]['invoices_sum_p_amount'] = $invoices_sum_p_amount;
            $product_report[$counter]['remaining'] = AdminSellRecord::where('user_id', Auth::id())->sum('p_amount') - PaidAmount::where('user_id', Auth::id())->get()->sum('paid');

            $product_report[$counter]['checkunit'] = $invoices->sum('unit');

            $counter++;
        }
        //dd($invoices);
        //dd($available_dates);
        return view('get_dated_invoice', compact('invoices' , 'product_report' , 'available_dates'));
    }

    public function customerInvoices($customer_id, $from = "", $to = "")
    {
        error_reporting(0);
        if ($from != "") {
            $invoices = Invoice::where(['customer_id' => $customer_id])->whereBetween('created_at', array($from, $to))->get();
        } else {
            $invoices = Invoice::where(['customer_id' => $customer_id])->orderBy('id' , "desc")->get();
        }
        $customer = Customer::find($customer_id);
        $products = Product::where('user_id', Auth::id())->get();
        if(empty($products->first()->id)){
            $products = Product::whereIn('id', (explode('|' , $customer->final_allowed_products)))->get();
        }
        $product_report = [];
        $counter = 0;
        foreach ($products as $p) {
            $ppunit = 0;
            $ppamount = 0;
            $product_report[$counter]['id'] = $p->id;
            $product_report[$counter]['name'] = $p->name;
            foreach ($invoices as $in) {
                $idet =  $in->invoicedetail->where('product_id', $p->id);
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
            return view('invoice.customer_invoices', compact('invoices', 'customer', 'product_report'));
        // } else {
        //     return redirect()->back()->with('error', 'Invalid Request');
        // }
    }

    public function updateInvoice(Request $request, $id)
    {
           try{

            DB::beginTransaction();
        $conditions = ['id' => $id];
        $invoice = Invoice::where($conditions)->get();

        $adminSellTotal = AdminSellTotal::where('user_id', Auth::id())->first();
        if (sizeof($invoice)) {
            if ($request->amount_left_input) {
                $conditions['is_approved'] = 1;
                $amount_left = $invoice[0]->amount_left;
                $invoice = Invoice::where($conditions)->update(['amount_left' => $amount_left - $request->amount_left_input, 'received_amount' => $invoice[0]->received_amount + $request->amount_left_input]);
                DB::commit();
                return Common::Message("Invoice", 2);
            } else {
                if ($this->getAdminId(Auth::id()) == Auth::id() || $invoice[0]->is_approved == null) {

                    $invoiceDetail = $invoice[0]->invoicedetail->toArray();

                    if ($invoice[0]->is_approved == 1) {
                        foreach ($invoice[0]->invoicedetail as $detail) {
                            $this->updateSellRecord($detail, true);
                        }
                        AdminSellTotal::where('user_id', $this->getAdminId(Auth::id()))->update(['total_amount' => $adminSellTotal->total_amount - $invoice[0]->amount, 'total_units' => $adminSellTotal->total_units - $invoice[0]->unit, 'a_benefit' => $invoice[0]->a_benefit, 'total_p_amount' => $adminSellTotal->total_p_amount - $invoice[0]->p_amount]);
                    }

                    $this->deleteInvoice($id);

                    $this->storeInvoice($request, $invoice[0]->id);
                    if ($invoice[0]->is_approved == 1) {
                        $this->approveInvoice($invoice[0]->id, true);
                    }
                    DB::commit();
                    return redirect()->route('invoices', 'unapproved')->with('success', 'Invoice Updated');
                } else {
                    DB::commit();
                    return Common::Message("Invoice", 5);
                }
            }
        } else {
            DB::commit();
            return Common::Message("Invoice");
        }
        }catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

               return response()->json(['message' => 'Something Went Wrong!']);
        }
    }

    public function deleteInvoice($id)
    {
        $invoice = Invoice::find($id);
        if (!empty($invoice) && Auth::user()->role < 3) {
            Invoice::where('id', $invoice->id)->delete();
            InvoiceDetail::where('invoice_id', $id)->delete();
            return Common::Message("Invoice", 3);
        } else {
            return Common::Message("Invoice");
        }
    }
    
    public function SetInvoiceZero($id)
    {
        $invoice = Invoice::find($id);
        if (!empty($invoice)) {
            Invoice::where('id', $invoice->id)->update(['amount_left' => 0]);
            
            return Common::Message("Invoice", 2);
        } else {
            return Common::Message("Invoice");
        }
    }
    
    

    public function equalInvoice($id)
    {
        $invoice = Invoice::find($id);
        $get_ordr = Order::where('customer_id' , $invoice->customer_id)->orderBy('id', 'desc')->first();
        if($get_ordr != null){
        $get_ordr->received_amount = $get_ordr->subtotal;
        $get_ordr->amount_left = $get_ordr->amount_left - $get_ordr->received_amount;
        $get_ordr->save();
        }
        
        $invoice->received_amount = $invoice->subtotal;
        $invoice->amount_left = $invoice->amount_left - $invoice->received_amount;
        $invoice->save();
        return Common::Message("Invoice", 2);
    }
    
     public function updateReceivingInvoice(Request $request , $id){
        $invoice = Invoice::find($id);
        Invoice::where('id', $invoice->id)->get();
            $invoice->amount_left = $invoice->amount_left + $invoice->received_amount - $request->received_amount;
            $invoice->received_amount = $request->received_amount;
            $invoice->save();
            return Common::Message("Invoice" , 2);
       
           
        
    }
    
    // public function updateReceivingInvoice(Request $request , $id)
    // {
    //     $invoice = Invoice::find($id);
    //     Invoice::where('id', $invoice->id)->get();
    //     $invoice->received_amount = $request->received_amount;
    //     $invoice->amount_left = $invoice->amount_left - $invoice->received_amount;
    //     $invoice->save();
    //     return Common::Message("Invoice", 2);
    // }

    public function updateStatus(Request $request)
    {
        $invoice = Invoice::findOrFail($request->user_id);
        $invoice->i_status = $request->i_status;
        $invoice->save();

        return response()->json(['message' => 'User status updated successfully.']);
    }
    
    public function storeadminpurchasehistory($sum_p_amount , $sum_profit , $t_inv )
    {
         try{
            DB::beginTransaction();
        $data = PaidAmount::where('user_id' , Auth::id())->orderBy('id', 'desc')->first();
        $name = User::where('id' , Auth::id())->pluck('name')->first();
        $total_is =  $data->total_is;
        if ($total_is == null){
            $total_is = 0;
            
        }
        $remaining = $data->c_remaining;
        $paid =      $data->paid;
        $c_remaining = $data->c_remaining;
        $admin_history = new PaidAmount();
        $admin_history->user_id = Auth::id();
        $admin_history->t_invoices = $t_inv;
        $admin_history->purchases = $sum_p_amount;
        $admin_history->profit = $sum_profit;
        $admin_history->total_is = $total_is;
        $admin_history->remaining = $remaining;
        $admin_history->c_remaining = $c_remaining + $sum_p_amount;
        $admin_history->paid = 0;
        $admin_history->comments = "Approved by $name!";
        $admin_history->save();
        DB::commit();
         }catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

               return response()->json(['message' => 'Something Went Wrong!']);
        }
    }    
    
    public function approveInvoiceMult(Request $req)
    {
         try{
            DB::beginTransaction();
        if (Auth::user()->role < 3) {
        $multiple = array_unique($req->approve_to);
        $sum_p_amount = Invoice::whereIn('id', $multiple)->where('is_approved' , null)->sum('p_amount');
        $sum_subtotal = Invoice::whereIn('id', $multiple)->where('is_approved' , null)->sum('subtotal');
        $t_inv = Invoice::whereIn('id', $multiple)->where('is_approved' , null)->pluck('id')->count();
        $sum_discount = Invoice::whereIn('id', $multiple)->where('is_approved' , null)->sum('discount');
        $sum_profit = $sum_subtotal - $sum_p_amount - $sum_discount;
        $check = 1;
        foreach ($multiple as $single) {
            $this->approveInvoice($single, true , $check);
        }
        $this->storeadminpurchasehistory($sum_p_amount , $sum_profit , $t_inv);
        DB::commit();
        return Common::Message("Invoices", 4);
        }
         }catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

               return response()->json(['message' => 'Something Went Wrong!']);
        }
    }

    public function approveInvoice($id, $is_multiple_or_edited = false , $check = 0)
    {
         try{
            DB::beginTransaction();
        if (Auth::user()->role < 3) {

        if (!$is_multiple_or_edited) {
            if ($this->validPin() !== 1) {
                return $this->validPin();
            }
        }
        $invoiceDetails = InvoiceDetail::where('invoice_id', $id)->get();
        $invoice = Invoice::where('id', $id)->first();
        $sum_p_amount =  $invoice->p_amount;
        $sum_subtotal = $invoice->subtotal;
        $sum_discount = $invoice->discount;
        $sum_profit = $sum_subtotal - $sum_p_amount - $sum_discount;
        $t_inv = 1;
        if($check != 1){
        $this->storeadminpurchasehistory($sum_p_amount , $sum_profit , $t_inv);
        }
       if($invoice->is_approved == null){
         $getproductcontroller = new ProductController();
            $transection_id = $getproductcontroller->GetUniqueTransectionId(); 
            $idetails = Invoice::find($id)->invoicedetail;
           
        $adminSellTotal = AdminSellTotal::where('user_id', Auth::id())->first();
        if (sizeof($invoiceDetails)) {

            foreach ($invoiceDetails as $invoiceDetail) {
                $this->updateSellRecord($invoiceDetail);
                if ($invoice->customer->is_active_for_product == 1)
                    {
                 $getproductcontroller->SetProductStock(
                        $invoiceDetail->product_id , 
                        $invoice->customer->user_id , // product k stock k liyay user table ki id leni hy
                        $invoiceDetail->unit , 
                        $comments = NULL , 
                        $invoice_type = "stock_added" , 
                        $stock_adder_id = Auth::id(),
                        $transection_id 
                    ); 
                 }
            }

            if (!empty($adminSellTotal)) {

                AdminSellTotal::where('user_id', Auth::id())->update(['total_amount' => $adminSellTotal->total_amount + (float) $invoice->amount, 'total_units' => $adminSellTotal->total_units + (float) $invoice->unit, 'a_benefit' => $invoice->a_benefit, 'total_p_amount' => $adminSellTotal->total_p_amount + $invoice->p_amount]);
            } else {
                $newSell = new AdminSellTotal();
                $newSell->user_id = Auth::id();
                $newSell->total_amount = $invoice->amount;
                $newSell->total_p_amount = $invoice->p_amount;
                $newSell->total_units = $invoice->unit;
                $newSell->a_benefit = $invoice->a_benefit;
                $newSell->save();
            }

           Invoice::where('id' , $id)->update(['is_approved' => 1 , 'approve_date' => date('Y-m-d H:i')]);
           DB::commit();
            return Common::Message("Invoice", 4);
        }
        }
        }else {
            DB::commit();
            return Common::Message("Invoice");
        }
        }catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

               return response()->json(['message' => 'Something Went Wrong!']);
        }
    }


    public function notifyAdmin($invoice_id)
    {
        $notify = new Notification();
        $notify->user_id = Auth::id();
        $notify->notify_to = Auth::user()->seller_of;
        $notify->invoice_id = $invoice_id;
        $notify->save();
    }

    public function updateSellRecord($detail, $undo_changes = false)
    {
         try{
            DB::beginTransaction();
        $adminOldRecord = AdminSellRecord::where(['user_id' => Auth::id(), 'product_id' => $detail->product_id])->first();
        if (!empty($adminOldRecord)) {
            if (!$undo_changes) {
                $adminUpdateData = ['unit' => $adminOldRecord->unit + $detail->unit, 'amount' => $adminOldRecord->amount + $detail->amount, 'p_amount' => $adminOldRecord->p_amount + $detail->p_amount, 'a_benefit' => $adminOldRecord->a_benefit + $detail->a_benefit, 'c_benefit' => $adminOldRecord->c_benefit + $detail->c_benefit];
            } else {
                $adminUpdateData = ['unit' => $adminOldRecord->unit - $detail->unit, 'amount' => $adminOldRecord->amount - $detail->amount, 'p_amount' => $adminOldRecord->p_amount - $detail->p_amount, 'a_benefit' => $adminOldRecord->a_benefit - $detail->a_benefit, 'c_benefit' => $adminOldRecord->c_benefit - $detail->c_benefit];
            }
            AdminSellRecord::where(['user_id' => Auth::id(), 'product_id' => $detail->product_id])->update($adminUpdateData);
        } else {
            $adminSellRecord = new AdminSellRecord();
            $adminSellRecord->user_id = Auth::id();
            $adminSellRecord->product_id = $detail->product_id;
            $adminSellRecord->unit = $detail->unit;
            $adminSellRecord->amount = $detail->amount;
            $adminSellRecord->p_amount = $detail->p_amount;
            $adminSellRecord->c_benefit = $detail->c_benefit;
            $adminSellRecord->a_benefit = $detail->a_benefit;
            $adminSellRecord->save();
        }
         DB::commit();
         }catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

               return response()->json(['message' => 'Something Went Wrong!']);
        }
    }

    public function getAdminId($user_id)
    {
        $check = User::where('id', $user_id)->first();
        if ($check->seller_of == null) {
            $admin_id = $user_id;
        } else {
            $admin_id = User::where('id', $check->seller_of)->first()->id;
        }
        return $admin_id;
    }

    public function validPin()
    {
        if (!session('pin')) {
            return redirect()->back()->with('error', 'Pin Code Validation Failed');
        }
        session(['pin' => '']);
        return 1;
    }


    //AJAX function for invoice details
    public function getinvoiceDetail($id)
    {

        $invoice = Invoice::find($id);
        $idetails = Invoice::find($id)->invoicedetail;
        $prev_invoices = Invoice::where('id', '<', $id)->where('customer_id', $invoice->customer_id)->orderBy('id', 'desc');
        if($prev_invoices->pluck('id')->count() > 0){
            
            $prev_invoice = Invoice::where('customer_id', $invoice->customer_id)->orderBy('id', 'desc')->skip(1)->first();
             $bill_no = $prev_invoices->pluck('id')->count();
             
        }else{
             $bill_no = 0;
             $prev_invoice = NULL;
        }
        return view('ajax.invoice_detail', compact('idetails', 'invoice', 'prev_invoice', 'bill_no'));
    }
    
     public function getStockDetail($id)
    {

        $invoice = Invoice::find($id);
        $idetails = Invoice::find($id)->invoicedetail;
        $prev_invoices = Invoice::where('id', '<', $id)->where('user_id', $invoice->user_id)->where('stock_type', 1)->orderBy('id', 'desc')->get();
        $prev_invoice = $prev_invoices[0];
        $bill_no = count($prev_invoices);
        $name = array_values(Invoice::where('id' , $id)->select('user_id' , 'customer_id')->first()->toArray());
        
        
        return view('ajax.stock_detail', compact('idetails' , 'name' , 'invoice' , 'bill_no'));
    }

    public function getinvoiceDetailMultiples(Request $request)
    {
        $ids = explode(':', $request->ids);

        $data = Invoice::whereIn('id', $ids)->get();

        foreach ($data as $key => $row) {
            $data[$key]['invoice_details'] = Invoice::find($row->id)->invoicedetail;

            $prev_invoices = Invoice::where('id', '<', $row->id)->where('customer_id', $row->customer_id)->orderBy('id', 'desc')->get();
            $data[$key]['prev_invoices_details'] = $prev_invoices[0];
            $data[$key]['bill_no'] = count($prev_invoices);
        }

        return view('ajax.invoice_detail_ajax', compact('data'));
    }

    // Date Filter
    public function dateFilter(Request $request, $unapproved = "")
    {
        $from = str_replace("/", "-", $request->from);
        $to = str_replace("/", "-", $request->to);
        if ($to == null) {
            $to = date('Y-m-d');
        }
        if ($from == null) {
            $from = date('Y-m-d');
        }
        if ($request->customer_id) {
            return $this->customerInvoices($request->customer_id, $from, $to);
        }
        return $this->index($unapproved, $from, $to);
    }


    public function printInvoice($id)
    {
        error_reporting(0);
        $invoice = Invoice::find($id);
        if (empty($invoice))
            return redirect()->back()->with('error', 'Not Found!');

        $prev_invoices = Invoice::where('id', '<', $id)->where('customer_id', $invoice->customer_id)->orderBy('id', 'desc')->get();
        $prev_invoice = $prev_invoices[0];
        $bill_no = count($prev_invoices);
        $pdf = PDF::loadView('print_invoice', compact('invoice', 'prev_invoice', 'bill_no'));
        return $pdf->download($invoice->customer->user->name . date('d/m/Y') . '.pdf');
    }

    public function updation()
    {
        $ins = InvoiceDetail::all();
        foreach ($ins as $i) {
            $p = Product::find($i->product_id);
            $i->p_amount = $p->p_price * $i->unit;
            $i->save();
        }
    }
    
    public function approveSellerStock(Request $req){
        
        
        if (Auth::user()->role < 3) {
        
        $inv = Invoice::where('id' , $req->inv_id);
        $chekin = $inv->pluck('is_approved_stock')->first();
        if($chekin == 0)
          {
        $name = User::where('id' , Auth::id())->pluck('name')->first();
        $inv->update(['is_approved_stock' =>  1 , 'is_approved' => 1 , 'comments' => "Confirmed By $name"]);
        $invdata = InvoiceDetail::where('invoice_id' , $req->inv_id);
        $product_id = $invdata->pluck('product_id')->toArray();
       // dd($product_id);
        foreach($product_id as $p_ids){
            $unit = InvoiceDetail::where(['invoice_id' => $req->inv_id , 'product_id' => $p_ids])->pluck('unit')->first();
            $old_stock = Product::where('id' , $p_ids)->pluck('remaining_stock')->first();
            
            $update_stock = $old_stock + $unit;
            //dd($update_stock);
            $p = Product::find($p_ids);
            $p->remaining_stock = $update_stock;
            $p->save();
        }
           return response()->json(['success'=> true, 'message' => 'Stock Approved Successfully']);
              }
              else{
                 return response()->json(['error'=> true, 'message' => 'Already Hnadeled This Stock']); 
              }
    }
    }
     
}
