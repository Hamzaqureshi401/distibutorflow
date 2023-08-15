<?php

namespace App\Models;
use  App\Models\Customer;


use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
	public function findcustomer($id){
        return Customer::where('area_id' , $id)->pluck('id')->toArray();
    }
}