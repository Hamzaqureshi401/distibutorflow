<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Area;

use App\Models\User;
use App\Models\Seller;

use App\Models\Customer;
use App\Models\Ordertaker;

use Illuminate\Support\Facades\Auth;

class AreaController extends BaseController
{
    // public function list()
    // {
    //     $ids = [Auth::id()];
    //     $otowns = User::findOrFail(Auth::id());
    //     $otowner = $otowns->ot_of;
    //     $otid = $otowns->id;
    //     $adminotarea = [$otowns->ot_of , $otowns->id];
    //     if (Auth::user()->role < 3) {
    //          $adminotarea = array_merge($ids, User::where('ot_of', Auth::id())->pluck('id')->toArray());
    //          $areas = Area::whereIn('created_by' , $adminotarea)->get();
    //      }
    //      else{
    //         $areas = Area::whereIn('created_by' , $adminotarea)->get();     
    //      }
        
    //     return view('list_area', compact('areas'));
    // }

     public function list(Request $request)
    {
        $ids = [Auth::id()];
        $otowns = User::findOrFail(Auth::id());
        $otowner = $otowns->ot_of;
        $otid = $otowns->id;
        
        if(Auth::user()->role < 3) {
             $adminotarea = $this->getAdminOt();
             $allowed_areas = [];   
         }
         elseif(Auth::user()->role == 5){
            $adminotarea = Auth::id(); 
            $ordertaker = Ordertaker::where('user_id' , Auth::id())->first();  
            $allowed_areas = explode('|' , $ordertaker->allowed_areas);
            if(empty($ordertaker->allowed_areas)){
                $adminotarea = explode('|' , $ordertaker->ot_customer_allowed);
            }  
           // dd($allowed_areas ,$adminotarea);
         }elseif (Auth::user()->role == 3){
            $seller = Seller::where('seller_id' , Auth::id())->first();
            if(!empty($seller->allowed_seller)){
                $adminotarea = $this->getAdminOt();
                $allowed_areas = [];     
            }else{
                return response()->json([
                'code' => '404',
                'status' => 'Failed',
                'message' => 'Not Allowed!',
                'data' => ''
            ]);
            }
        }
            if (is_array($adminotarea)) {
                $queryset = 'whereIn';
            } else {
                $queryset = 'where';
            }

         $areas = Area::$queryset('created_by' , $adminotarea)->orWhereIn('id' , $allowed_areas)->get();

         // $areas = Area::whereIn('created_by' , $adminotarea)->orWhereIn('id' , $allowed_areas)->get();
        
        if ($request->expectsJson()) {
        return response()->json([
            'code' => '200',
            'status' => 'success',
            'message' => 'Data Fetch Successfully!',
            'data' => $areas
        ]);
    } else{
        return view('list_area', compact('areas'));
    }
    }
    
    public function add(Request $req)
    {
        return view('add_area');
    }
    
    public function edit($id)
    {
        $area = Area::find($id);
        //$area = $area->where('id' , $area->id)->get();
        $name = $area->name;
        
        return view('edit_area', compact('area' , 'name'));
    }
    
    public function save(Request $req, $id = null){

    if(Auth::user()->role < 3 || Auth::user()->role == 5){
        $req->validate([
            'name' => 'required|max:191',
        ]);
        
        $area = is_null($id) ? new Area() : Area::find($id);
        $area->name = $req->name;
        $area->created_by = Auth::id();
        $area->save();

        if ($req->expectsJson()) {
        return response()->json([
            'code' => '200',
            'status' => 'success',
            'message' => 'Category Saved Successfully!',
            'data' => $area
        ]);
        }else{
            return back()->with('success', 'Area saved successfully');
        }
    }else{
         return response()->json([
            'code' => '404',
            'status' => 'error',
            'message' => 'Not Allowede',
            'data' => ""
        ]);
    }
        
        
    }
    
     public function update(Request $req, $id)
    {
        
        $req->validate([
            'name' => 'required|max:191',
        ]);
        
        $area = Area::find($id);
        //Area::where('id' , $area->id)->get();
        $area = $req->name;
        Area::where('id' , $id)->update(['name' => $area]);
        return back()->with('success', 'Area saved successfully');
    }

    public function saveAjax(Request $req, $id = null){
        $req->validate([
            'name' => 'required|max:191',
        ]);
        
        $area = is_null($id) ? new Area() : Area::find($id);
        $area->name = $req->name;
        $area->created_by = Auth::id();
        $area->save();
        
        $allAreas = Area::all();
        $options = "<option value=''>Select Area</option>";
        foreach($allAreas as $row){
            $selected = "";
            if($row->id == $area->id){
                $selected = "selected='selected'";
            }
            $options .= "<option $selected value='$row->id'>$row->name</option>";
        }
        return $options;

    }
    
    public function delete($id)
    {
        $no_of_cst = Customer::where('area_id' , $id)->pluck('id')->count();
        if ($no_of_cst == 0){
        Area::find($id)->delete();
        return back()->with('success', 'Area deleted successfully');
            
        }
        else{
            return back()->with('error', 'Customers Are linked with this Area so it can not be deleted!');
        }
        
    }
}