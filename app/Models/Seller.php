<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Auth;

class Seller extends Model
{
   public function sellerpaidhistory(){
        return $this->hasMany('App\Models\PaidSellerBenefit')->where('seller_id', Auth::id());
    }
}