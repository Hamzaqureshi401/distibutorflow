<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    
    public function invoice(){
        return $this->belongsTo('App\Models\Invoice');
    }
     public function order(){
        return $this->belongsTo('App\Models\Order');
    }
}
