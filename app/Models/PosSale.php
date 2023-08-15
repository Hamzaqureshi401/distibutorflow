<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosSale extends Model
{
    public function User(){
        return $this->belongsTo('App\Models\User' , 'sell_creater_id');
    }
}
