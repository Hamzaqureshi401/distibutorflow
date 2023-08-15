<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\User;
use App\Models\AdminSellTotal;
use Carbon\Carbon;
use Auth;
use App\Models\Product;
use App\Models\SellerSell;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
          public function dashboard()
    {
        return view('admin_home');
    }
    
    public function userDashboard(){
        if($this->IsBlocked()){
            Auth::logout();
            return redirect()->route('login')->with('error' , 'Your Account Has Been Blocked , Please Contact To Admin For More Information!');
        }
        $urole = Auth::user()->role;
        if($urole < 3){
            $ids =  User::where('ot_of', Auth::id())->pluck('id')->toArray();
            $myids = [Auth::id()];
            foreach(Auth::user()->mysellers as $seller){
                $myids[] = $seller->id;
            }
            array_push($ids , Auth::id());
            $tcustomers = sizeof(Customer::whereIn('created_by' , $ids)->get());
            $app_in = sizeof(Invoice::whereIn('user_id' , $myids)->where('is_approved' , 1)->get());
            $unapp_in = sizeof(Invoice::whereIn('user_id' , $myids)->where('is_approved' , null)->get());
            $today_in = Invoice::whereIn('user_id' , $myids)->whereDate('created_at', Carbon::today())->get();
            $total_sell = AdminSellTotal::where('user_id', Auth::id())->first()->total_amount ? AdminSellTotal::where('user_id', Auth::id())->first()->total_amount : 0;
            
            $customers = Customer::whereIn('created_by', $ids);
            $c_id = $customers->pluck('id')->toArray();
            foreach($c_id as $a){
            $balance[] = Invoice::where('customer_id', $a)->pluck('amount_left')->last();
            }
            $balance = array_sum($balance);
            
            $getseller = User::where('seller_of' , Auth::id())->pluck('id')->toArray();
     
             $week = \Carbon\Carbon::today()->subDays(7);
             $month = \Carbon\Carbon::today()->subDays(30);
    
              $week_expence =  SellerSell::where('created_at','>=',$week)->whereIn('seller_id' , $getseller)->whereIn('order_confirmed_by' , $getseller)->sum('expenses');
              $month_expence = SellerSell::where('created_at','>=',$month)->whereIn('seller_id' , $getseller)->whereIn('order_confirmed_by' , $getseller)->sum('expenses');
              $today_expence = SellerSell::whereDate('created_at',Carbon::today())->whereIn('seller_id' , $getseller)->whereIn('order_confirmed_by' , $getseller)->sum('expenses');
                         
            
            array_push($getseller,Auth::id());
            $date = \Carbon\Carbon::today()->subDays(7);
            $monthdata = \Carbon\Carbon::today()->subDays(30);
            
            //week data
            
            $sum_subtotal = Invoice::whereIn('user_id' , $getseller)->where('is_approved' , 1)->where('created_at','>=',$date)->get()->sum('subtotal');
            $sum_pampunt =  Invoice::whereIn('user_id' , $getseller)->where('is_approved' , 1)->where('created_at','>=',$date)->get()->sum('p_amount');
            $sum_discount = Invoice::whereIn('user_id' , $getseller)->where('is_approved' , 1)->where('created_at','>=',$date)->get()->sum('discount');
            $sum_received_amount = Invoice::whereIn('user_id' , $getseller)->where('is_approved' , 1)->where('created_at','>=',$date)->get()->sum('received_amount');
            $week_admin_benefit =  $sum_subtotal - $sum_pampunt - $sum_discount;
            $week_balance =  $sum_subtotal - $sum_received_amount;
            $week_admin_benefit_inhand =  $week_admin_benefit - $week_balance;
            
            //month data
            
            $sum_subtotal = Invoice::whereIn('user_id' , $getseller)->where('is_approved' , 1)->where('created_at','>=',$monthdata)->get()->sum('subtotal');
            $sum_pampunt =  Invoice::whereIn('user_id' , $getseller)->where('is_approved' , 1)->where('created_at','>=',$monthdata)->get()->sum('p_amount');
            $sum_discount = Invoice::whereIn('user_id' , $getseller)->where('is_approved' , 1)->where('created_at','>=',$monthdata)->get()->sum('discount');
            $sum_received_amount = Invoice::whereIn('user_id' , $getseller)->where('is_approved' , 1)->where('created_at','>=',$monthdata)->get()->sum('received_amount');
            $month_admin_benefit =  $sum_subtotal - $sum_pampunt - $sum_discount;
            $month_balance =  $sum_subtotal - $sum_received_amount;
            $month_admin_benefit_inhand =  $month_admin_benefit - $month_balance;
            
            
        }
        else if($urole == 3){
            $app_in = sizeof(Invoice::whereIn('user_id' , [Auth::id()])->where('is_approved' , 1)->get());
            $unapp_in = sizeof(Invoice::whereIn('user_id' , [Auth::id()])->where('is_approved' , null)->get());
            $today_in = Invoice::whereIn('user_id' , [Auth::id()])->whereDate('created_at', Carbon::today())->get();
            
            $total_sell = Invoice::whereIn('user_id' , [Auth::id()])->where('is_approved', 1)->get()->sum('amount');
            $balance = Invoice::whereIn('user_id' , [Auth::id()])->where('is_approved', 1)->get()->sum('amount_left');
            $customer_benefit = Invoice::whereIn('user_id' , [Auth::id()])->where('is_approved', 1)->get()->sum('c_benefit');
        }
        else if($urole == 4){
            $app_in = sizeof(Invoice::whereIn('customer_id' , [Auth::user()->customer_id])->where('is_approved' , 1)->get());
            $unapp_in = sizeof(Invoice::whereIn('customer_id' , [Auth::user()->customer_id])->where('is_approved' , null)->get());
            $today_in = Invoice::whereIn('customer_id' , [Auth::user()->customer_id])->whereDate('created_at', Carbon::today())->get();
            
            $total_sell = Invoice::whereIn('customer_id' , [Auth::user()->customer_id])->where('is_approved', 1)->get()->sum('amount');
            $balance = Invoice::whereIn('customer_id' , [Auth::user()->customer_id])->where('is_approved', 1)->get()->sum('amount_left');
            $customer_benefit = Invoice::whereIn('customer_id' , [Auth::user()->customer_id])->where('is_approved', 1)->get()->sum('c_benefit');
        }
        else if($urole == 5){
            return redirect()->route('ot_dashboard.orderstaker');
        }
        
        $products = Product::where('user_id' , Auth::id())->get();
        $product_report = [];
        $counter = 0;
        foreach($products as $p){
            $ppunit = 0;$ppamount = 0;
            $product_report[$counter]['id'] = $p->id;
            $product_report[$counter]['name'] = $p->name;
            foreach($today_in as $in){
                $idet =  $in->invoicedetail->where('product_id' , $p->id);
                $ppunit += $idet->sum('unit');
                $ppamount += $idet->sum('p_amount');
            }
            $product_report[$counter]['unit'] = $ppunit;
            $product_report[$counter]['amount'] = ($p->p_price * $ppunit);
            $product_report[$counter]['checks'] = $ppamount;
            
            $counter++;
        }
        
        
        return view('admin_home' , compact('tcustomers' , 'app_in' , 'unapp_in' , 'today_in' , 'total_sell' , 'balance' ,
        'week_balance' , 'month_balance' , 'week_admin_benefit' , 'week_admin_benefit_inhand' , 
        'month_admin_benefit' , 'month_admin_benefit_inhand' , 'customer_benefit', 'product_report' , 'conditiont' , 'week_expence'
        , 'month_expence' , 'today_expence'));
    }
    public function IsBlocked(){
        if(Auth::user()->role == 2){
            $blocked = false;
            $created_at = Auth::user()->created_at;
            if(Auth::user()->is_blocked == 1){
                $blocked = true;
            }
            if(time() - strtotime($created_at) >= 31536000){
                $user = Auth::user();
                $user->is_blocked = 1;
                $user->save();
                $blocked = true;
            }
            return $blocked;
        }
        if(Auth::user()->is_blocked == 1){
            return true;
        }
        return false;
    }
}
