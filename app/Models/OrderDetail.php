<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{    
    public function order(){
        return $this->belongsTo('App\Models\Order');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }
    public function ordertaker(){
    	return $this->belongsTo('App\Models\User','ot_id');
    }

    public function get_ot_creater(){
        return $this->belongsTo('App\Models\User','ot_id')->where('ot_of', Auth::id());
        // return $this->belongsTo('App\User','ot_id');
    }
}
