<?php

namespace App\Http\Controllers;

use App\Repositories\Common;

use Illuminate\Http\Request;

use App\Models\SubAdmin;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

class SubAdminController extends Controller
{
    public function getSubAdmin($id){
        
        $seller = User::find($id);
        return view('edit_subadmin' , compact('seller'));
    }
    public function deleteSuAdmin($id){
        
        $user = SubAdmin::where('sub_admin_id' , $id)->delete();
        $user = User::where('id' , $id)->delete();
        return Common::Message("Sub Admin" , 3);
    }
    public function getallSubAdmin(){
        
        $sub_admins = User::where('user_of' , Auth::id())->has('subadmin')->with('subadmin')->get();
        return view('sub_admins' , compact('sub_admins'));
        
    }
    public function updateSubadmin(Request $request , $id ){
        //dd($request->all());
        
         $subadmin = SubAdmin::where('sub_admin_id' , $id)->first();

         
                    if(!empty($request->assign_products)){
                        $subadmin->assign_products = $request->assign_products;    
                    }
                    if(!empty($request->assign_products)){
                        $subadmin->assign_products = $request->assign_products;    
                    }
                    $subadmin->final_allowed_products = $request->final_allowed_products;
                    $subadmin->save();
            
            if($request->product_link){
                $product_link = Auth::id();
            }else{
                $product_link = NULL;
            }
            if($request->password){
                $password = $request->password;
            }else{
                $password = $subadmin->user->password;
            }
            $updateData = [
            'name'          => $request->name , 
            'password'      => $request->password , 
            'phone'         => $request->phone
            ];
            if(empty(User::where('email' , $request->email)->first()->name)){
                $updateData['email'] = $request->email;
            }
            User::where(['id' => $id ])->update($updateData);
            SubAdmin::where('sub_admin_id' , $id)->update(['product_link' => $product_link]);
            
            return Common::Message("Subadmin" , 2);
        
    }
    
    

}