<?php

namespace App\Models;
use App\Models\CustomerProductsStock;
use App\Models\PosSale;
use App\Models\PosSalesDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Database\Eloquent\Model;
use Auth;
class Product extends Model
{
    public function category(){
    	return $this->belongsTo('App\Models\Category');
    }
    public function productname($id){
        $users = Product::where('id', $id)->pluck('name')->first();
        return $users;
    }
    public function productprice($id){
        $users = Product::where('id', $id)->pluck('p_price')->first();
        return $users;
    }
     public function productsellprice($id , $pid){
         //dd($id , $pid);
        $data = CustomPrice::where(['customer_id' => $id , 'product_id' => $pid])->pluck('sell_price')->first();
        if($data == null){
            return Product::where('id' , $pid)->pluck('sell_price')->first();
        }
        else{
            return $data;
        }
    }
    public function getProductrecord($customer_id , $product_id){

        $data = CustomPrice::where(['customer_id' => $customer_id , 'product_id' => $product_id])->first();
        if(empty($data)){
            return Product::where('id' , $product_id)->first();
        }
        else{
            return $data;
        }   
    }
     public function GetProductStockRecord($customer_id , $product_id){

        return CustomerProductsStock::where(['customer_id' => $customer_id , 'product_id' => $product_id])->latest('id')->first();
    }
    public function GetSoldData($customer_id , $product_id){

         $sales_id = PosSale::where(['customer_id' => $customer_id])->whereNull('is_confirmed_admin')->pluck('id')->toArray();
          return PosSalesDetail::whereIn('pos_sale_id' , $sales_id)->where('product_id' , $product_id)->get();
    }
    public function GetIncomingData($customer_id , $product_id){

         $sales_id = Order::where(['customer_id' => $customer_id])->whereNull('is_confirmed_admin')->pluck('id')->toArray();

          return OrderDetail::whereIn('order_id' , $sales_id)->where('product_id' , $product_id)->get();
    }
    public function GetIncomingDataInv($customer_id , $product_id){        
         $sales_id = Invoice::where(['customer_id' => $customer_id])->whereNull('is_approved')->pluck('id')->toArray();

          return InvoiceDetail::whereIn('invoice_id' , $sales_id)->where('product_id' , $product_id)->get();
    }
    public function findProductLink($id){
        return Product::where('link_product' , $id)->get();
    }

    public function user(){
        return $this->belongsTo('App\Models\User' , 'user_id');
    }

    public function DefualtOrder(){
        return $this->belongsTo('App\Models\DefualtOrder' ,'id',  'product_id');
    }

    public function GetOrderData($order_id , $product_id){

          return OrderDetail::where('order_id' , $order_id)->where('product_id' , $product_id)->first() ?? "";
    }
    
}
