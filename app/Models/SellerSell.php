<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SellerSell extends Model
{
     public function admin(){
        return $this->belongsTo('App\Models\User' , 'order_confirmed_by');
    }
}