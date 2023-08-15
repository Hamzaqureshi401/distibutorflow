<?php
// app/Http/Controllers/BaseController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;


use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected function getAdminOt(){
        $auth_id = Auth::id();
            if(Auth::user()->role < 3 ){
        $user = $auth_id;
        }
        $ots = User::where('ot_of' , $user)->orWhere('id' , $user)->pluck('id')->toArray();
         array_push($ots ,$auth_id);
         return array_unique($ots);
    }

    protected function findMyAdmin(){
        
        if(Auth::user()->role < 3 ){
            $user = Auth::id();
        }
        // elseif(Auth::user()->role == 4){
        //     $user = Auth::user()->seller_of;
        // }
         return $user;
    }
}
