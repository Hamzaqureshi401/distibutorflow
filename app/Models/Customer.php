<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\PosSaleCashReceiving;
use App\Http\Controllers\OrderController;
use Auth;

class Customer extends Model
{
    protected $fillable = ['address' , 'phone' , 'freezer_model' , 'cnic' , 'other', 'area_id', 'allowed_products'];
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
     public function otordertakername(){
    	return $this->belongsTo('App\Models\User','created_by');
    }

    public function custom_prices(){
    	return $this->hasMany('App\Models\CustomPrice');
    }

    public function custom_Ot_benefit(){
    	return $this->hasMany('App\Models\CustomOTBenefit');
    }
    public function Ordertakers(){
    	return $this->hasMany('App\Models\OtCustomer');
    }

    public function invoices(){
    	return $this->hasMany('App\Models\Invoice');
    }
    public function PosSaleCashReceiving($id){

        return PosSaleCashReceiving::where('customer_id' , $id)->whereNull('processor_id')->orderBy('id' , 'desc')->get();
        
    }
    public function getOrders($id){

        return  Order::where('customer_id' , $id)->get();
        
    }

    public function scopeorder(){
    	return $this->hasMany('App\Models\Order','customer_id');
    }
    
    public function area(){
        return $this->belongsTo('App\Models\Area' , 'area_id');
    }
    
    public function ordertaker(){
    	return $this->belongsTo('App\Models\User','ot_id');
    }

    public function get_ot_creater(){
        return $this->belongsTo('App\Models\User','ot_id')->where('ot_of', Auth::id());
        // return $this->belongsTo('App\User','ot_id');
    }
    public function getamountleft(){
    	return $this->belongsTo('App\Models\Invoice','user_id');
    }
    public function getlastorder($id){
        $order = Order::where('customer_id' , $id);
        $order_existence = $order->orderBy('id' , 'desc')->pluck('is_confirmed_admin')->first();
        return $order_existence;
    }
    public function getlastorderdate($id){
        $order = Invoice::where('customer_id' , $id);
        $lastorder = $order->orderBy('id' , 'desc')->pluck('created_at')->first();
        return $lastorder;
    }
     public function getbalance($id){
        $order = Invoice::where('customer_id' , $id);
        $lastorder = $order->orderBy('id' , 'desc')->pluck('amount_left')->first();
        return $lastorder;
    }
    public function oldorder($id){
         $order = Order::where('customer_id' , $id);
        $lastorder = $order->orderBy('id' , 'desc')->pluck('created_at')->first();
        
        $now = \Carbon\Carbon::now()->toDateString();
        $a = strtotime($lastorder);
        $b = strtotime($now);
        return    $days_between = ceil(($b - $a) / 86400);
    }

}
