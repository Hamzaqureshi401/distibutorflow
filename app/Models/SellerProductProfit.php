<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SellerProductProfit extends Model
{
     public function product(){
        return $this->belongsTo('App\Models\Product');
    }
    
    public function seller(){
        return $this->belongsTo('App\Models\Seller');
    }
}