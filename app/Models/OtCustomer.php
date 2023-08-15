<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Auth;

class OtCustomer extends Model
{
    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }

    public function ot_customers(){
        return $this->where('ot_id', Auth::id())->belongsTo('App\Models\Customer','customer_id');
    }
}
