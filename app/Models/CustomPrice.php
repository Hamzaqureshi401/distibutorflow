<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CustomPrice extends Model
{
    public function product(){
        return $this->belongsTo('App\Models\Product');
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }
}
