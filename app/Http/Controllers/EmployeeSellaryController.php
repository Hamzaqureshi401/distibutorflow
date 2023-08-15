<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeSellarie;
use App\Http\Controllers\AttendanceController;


class EmployeeSellaryController extends Controller
{
    public function getEmployeeSellary($id){

        $query = EmployeeSellarie::where('user_id' , $id)->get();
        return view('employeesellery.get_employee_sellary' , compact('query' , 'id'));
    }

    public function paySellerSellary(Request $request){

       // dd($request->all());

        $AttendanceController = new AttendanceController();
        $AttendanceController->HandleSellary(-$request->amount , $request->comments , $request->id);

         return redirect()->back()->with('success', 'SuccessFully Paid!');

    }
}
