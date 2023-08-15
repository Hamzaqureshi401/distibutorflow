<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Auth;

class Ordertaker extends Model
{
    public function orders(){
        return $this->hasMany('App\Models\Orders');
    }

    public function ot_seller_match(){
        $users = User::where('ot_of', Auth::user()->seller_of)->get();
        return $users;
    }
    
    public function vistorders(){
        return $this->hasMany('App\Models\Order','user_id');
    }
    public function customOtbenefit(){
        return $this->hasMany('App\Models\CustomOtBenefit','id' ,'ot_id');
    }
}
