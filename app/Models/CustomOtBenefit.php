<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CustomOtBenefit extends Model
{
    public function product(){
        return $this->belongsTo('App\Models\Product');
    }
    
    public function ordertaker(){
        return $this->belongsTo('App\Models\Ordertaker');
    }
}
