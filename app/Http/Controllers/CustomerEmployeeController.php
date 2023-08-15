<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\EmployeeAttandenceSetting;
use App\Models\EmployeeAttandence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserController;
use Illuminate\Validation\Rule;
use Auth;

class CustomerEmployeeController extends Controller
{
    public function AddCustomerEmployee(){

        return view('CustomerEmployee.add_customer_employee');
    }

    public function StoreUser(Request $request){

        try{

            $request->validate([
        'name' => 'required',
        'email' => ['required', 'email', Rule::unique('users')],
        'password' => 'required|min:8',
    ]);
            DB::beginTransaction();
            if ($request->id){
                $StoreUser = User::where('id' , $request->id)->first();
            }else{
                $StoreUser = new User();
                if ($request->type == 1){
                     $StoreUser->role = 6; // seller    
                }else{
                     $StoreUser->role = 7; // Manager
                }
            }
            $StoreUser->name        = $request->name;
            $StoreUser->email       = $request->email;
            $StoreUser->email_verified_at = date('Y-m-d H:i:s');
        
            $StoreUser->password    = $request->password;
            $StoreUser->phone       = $request->phone;
            $StoreUser->seller_of   = Auth::user()->customer_id;
            $StoreUser->customer_id = Auth::id();
            
            $StoreUser->save();
            $GetUserController      = new UserController();
            $GetUserController->EmployeeAttandenceSettings($request , $StoreUser->id);
            DB::commit();

            return response()->json(["message" => 'User Added!']); 

        }catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
           return response()->json(['message'=> 'Something Went Wrong!']);
        }  
    }
    public function GetCustomerSeller(){

        $GetCustomerSeller = User::where(['seller_of' => Auth::user()->customer_id , 'role' => 6])->get();
        return view('CustomerEmployee.get_customer_seller' , compact('GetCustomerSeller'));
    }
     public function GetCustomerManager(){
        $GetCustomerManager = User::where(['seller_of' => Auth::user()->customer_id , 'role' => 7])->get();
        return view('CustomerEmployee.get_customer_manager' , compact('GetCustomerManager'));
    }

    public function GetCustomerSellerToEdit($id){

        $GetCustomerSeller          = User::where(['seller_of' => Auth::user()->customer_id , 'role' => 6, 'id' => $id])->first();
        $EmployeeAttandenceSettings = EmployeeAttandenceSetting::where('user_id' , $id)->first();
        return view('CustomerEmployee.edit_customer_employee' , compact('GetCustomerSeller' , 'EmployeeAttandenceSettings'));

    }
    public function deleteCustomerEmplyee($id){

        User::where(['role' => 6, 'id' => $id])->delete();
        EmployeeAttandenceSetting::where('user_id' , $id)->delete();
        EmployeeAttandence::where('user_id' , $id)->delete();

        return redirect()->back()->with('success', 'Deleted successfully!');
    }

}
