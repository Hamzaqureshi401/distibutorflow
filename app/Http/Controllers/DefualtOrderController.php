<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DefualtOrder;
use App\Models\Product;
use Auth;


class DefualtOrderController extends Controller
{
    public function getProductForDefualtOrder(){

        $user = Auth::id();
        if(Auth::user()->role == 5){
            $user = Auth::user()->ot_of;
        }

        $product = Product::where('user_id' , $user)->get();

        return view('DefualtOrder.getProductForDefualtOrder' , compact('product'));

    }

    public function setDefualtOrder(Request $request){
        
        $user = Auth::id();
        if(Auth::user()->role == 5){
            $user = Auth::user()->ot_of;
        }
        DefualtOrder::where('user_id' , $user)->delete();
        foreach($request->product_id as $key => $id){
            $DefualtOrder = new DefualtOrder();
            $DefualtOrder->user_id = $user; 
            $DefualtOrder->product_id = $id; 
            $DefualtOrder->unit = $request->unit[$key]; 
            if($request->unit[$key] != 0){
                $DefualtOrder->save();
            }

        }
         return redirect()->back()->with('success', 'Default value set successfully!');        
       
        
    }
}
