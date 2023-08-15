<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Repositories\Common;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Seller;
use App\Models\PaidAmount;
use App\Models\SubAdmin;
use App\Models\AdminSellTotal;
use App\Models\AdminSellRecord;
use App\Models\EmployeeAttandenceSetting;
use Illuminate\Support\Facades\DB;


use App\OauthAccessToken;

use Auth;

class UserController extends Controller
{
    public $successStatus = 200;

    // public function loginUser(Request $request)
    // {
    //     Validator::make($request->all(), [
    //     'email' => 'required|email',
    //     'password' => 'required'])->validate();
        
    //     $email = $request->email;
    //     $password = $request->password;
        
    //     $client = new \GuzzleHttp\Client;
    //     try {
    //         $response = $client->post('http://oriaitsolution.com/scoops/oauth/token', [
    //             'form_params' => [
    //                 'client_id' => 2,
    //                 'client_secret' => 'jnHd1lW9q9wiADYNJXjPeZz0K81G03qTXhunRye1',
    //                 'grant_type' => 'password',
    //                 'username' => $email,
    //                 'password' => $password,
    //                 'scope' => '*',
    //             ]
    //         ]);
    
    //         $auth = json_decode( (string) $response->getBody() );
    //         $auth = (array)$auth;
    //         $auth['user'] = User::where('email' , $email)->first()->toArray();
            
    //         return ['messasge' => 'User logged in !' , 'access_token' => $auth['access_token'] , 'user' => $auth['user']];
            
    //     } catch (GuzzleHttp\Exception\BadResponseException $e) {
    //         echo "Unable to retrieve access token.";
    //     }
    // }
    
    public function logoutUser($user_id){
            OauthAccessToken::where('user_id' , $user_id)->delete();
            return response()->json(['message' => 'User Logged out']);
    }

    public function paidHistory(Request $request){
        $paid_amounts = PaidAmount::where('user_id' , Auth::id());
        if ($request->is('api/*')) {
    // API route logic
        return response()->json([
            'code' => '200',
            'status' => 'success',
            'message' => 'Data Fetch Successfully!',
            'data' => $paid_amounts->join('users' , 'users.id' , 'paid_amounts.user_id')->select('paid_amounts.*' , 'users.name')->get()
        ]);
    } else {
        $paid_amounts = $paid_amounts->get();
        return view('paid_amounts' , compact('paid_amounts'));
    }
    }
    
    public function payAmount(Request $request){
            
            try{
            DB::beginTransaction();
            $amountTotal = AdminSellTotal::where('user_id' , Auth::id())->first();
            $remaining= AdminSellRecord::where('user_id' , Auth::id())->sum('p_amount') - PaidAmount::where('user_id' , Auth::id())->get()->sum('paid');
            
            $paid = new PaidAmount();
            $paid->user_id = Auth::id();
            $paid->total_is = $amountTotal->total_p_amount ?? 0;
            $paid->remaining = $remaining;
            $paid->paid = $request->amount;
            $paid->comments = $request->comments;
            $paid->c_remaining= AdminSellRecord::where('user_id' , Auth::id())->sum('p_amount') - PaidAmount::where('user_id' , Auth::id())->get()->sum('paid') - $paid->paid;
            $paid->save();

            
            // if($now_amount == 0){
            //     $amountTotal->total_units = 0;
            //     //AdminSellRecord::where('user_id' , Auth::id())->delete();
            //     // PaidAmount::where('user_id' , Auth::id())->delete();
            // }
            if(!empty($amountTotal)){
                if(empty($amountTotal->total_p_amount)){
                $amountTotal->total_p_amount = 0 - $request->amount;    
            }else{
                $amountTotal->total_p_amount = $amountTotal->total_p_amount - $request->amount;
            
            }
            $amountTotal->save();
            }
            
            DB::commit();
            return Common::Message('Paid History' , 6);
            }catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

               return response()->json(['message' => 'Something Went Wrong!']);
        }
       
    }

    public function sellRecord(){
        $sell_records = AdminSellRecord::where('user_id' , Auth::id())->get();
        $remaining = AdminSellTotal::where('user_id' , Auth::id())->first();
        return view('sell_records' , compact('sell_records' , 'remaining'));
    }


    public function validatePin(Request $request){
        if(Auth::user()->role < 3){
            if(Auth::user()->pincode == $request->pin){
                session(['pin' => $request->pin]);
                return 1;
            }
            else{
                return "Invalid Pin Code !";
            }
        }
        else{
            return "Invalid Request";
        }
    }
    
    public function clearAll(){
        if(session('pin')){
            if(Auth::user()->role < 3){
                if(AdminSellRecord::where('user_id' , Auth::id())->sum('p_amount') - PaidAmount::where('user_id' , Auth::id())->get()->sum('paid') == 0){
                    AdminSellRecord::where('user_id' , Auth::id())->delete();
                    PaidAmount::where('user_id' , Auth::id())->delete();
                    return redirect()->back()->with('success' , 'Sell Record Clear');
                }
                else{
                    return redirect()->back()->with('error' , 'Record Cannot Be Cleared ( Pay Amount First )');
                }
            }
            else{
                return redirect()->back()->with('error' , 'Invalid Request !');
            }
        }
        else{
            return redirect()->back()->with('error' , 'Pin Code Validation Failed !');
        }
    }
    
    public function sellTotalClear(){
       if(session('pin')){
            if(sizeof(AdminSellRecord::where('user_id' , Auth::id())->get()) == 0){
            AdminSellTotal::where('user_id' , Auth::id())->delete();
            return redirect()->back()->with('success' , 'Sell Total Clear');
        }
        return redirect()->back()->with('error' , 'Canot Be Cleared ( Sell Record Exist )');
       }
    }

    public function suggestEmailForNewUser(Request $request){
        $email = $request->email;
        // Check If email is empty then return back false;
        if($email == ""){
            return "false";
        }

        // Return false if have email in db
        $checkDB = User::where('email', $email)->get();
        if(count($checkDB) > 0){
            return "false";
        }

        $email = explode('@', $email, 2)[0];
        $newemail1 = $email.rand(0, 1000)."@scoops.com";
        $newemail2 =  $email.rand(0, 1000)."@scoops.com";
        $html = "<li class='list-group-item'>$newemail1</li>";
        $html .= "<li class='list-group-item'>$newemail2</li>";

        // Return 2 resulted html to view;
        return $html;
    }

    public function FindUserEmail(Request $request){

        if(!empty($request->id)){
            $finduser = User::where(['email' => $request->email , 'id' => $request->id])->pluck('id')->count();
            if ($finduser == 1){
                $finduser = 0;
            }
        }else{
             $finduser = User::where('email' , $request->email)->pluck('id')->count();
        }

        return $finduser;
    }

    public function verifyuser(Request $request){

       $user= User::where('email', $request->email)->first();
        // print_r($data);
            if (!$user || !Hash::check($request->password, $user->password)) {
                     return response()->json(['message' => 'This Record do not match our records.']);
            }else{
                return 1;
            }
    }

    public function EmployeeAttandenceSettings($request , $user_id){

        $user = EmployeeAttandenceSetting::where('user_id' , $user_id);
        if($user->pluck('id')->count() == 0){
            $store = new EmployeeAttandenceSetting(); 
            $store->user_id = $user_id;
        }else{
            $store = $user->first();
        }
        if ($request->user_active == "on"){
            $store->user_active    = 1;
        }else{
            $store->user_active    = 0;
        }
        $store->over_time_start    = date("Y-m-d H:i:s",(strtotime($request->over_time_start))); 
        $store->end_time           = date("Y-m-d H:i:s",(strtotime($request->end_time)));
        $store->start_time         = date("Y-m-d H:i:s",(strtotime($request->start_time))); 
        $store->over_time_end      = date("Y-m-d H:i:s",(strtotime($request->over_time_end)));
        $store->distance_measure   = $request->distance_measure;
        $store->per_minute_sellary = $request->per_minute_sellary;
        $store->over_time_per_minute_sellary = $request->over_time_per_minute_sellary;
        $store->location_cords     = $request->location_cords;

        $store->save();

       // dd($store->start_time ,$store->end_time );
        
    }

        public function login(Request $request){ 
        //     $validator = Validator::make($request->all(), [ 
        //     'email' => 'required|email', 
        //     'password' => 'required',  
        // ]);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->remember_token; 
            $success['user'] = $user;
            return response()->json(['code' => $this->successStatus , 'status' => 'success', 'message' => 'User Found' , 'data' => $success], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['code' => 401 , 'status' => 'error', 'message' => 'User Not Found' ,], 401); 
        } 
    }
/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $input = $request->all(); 
        $input['password'] = $input['password']; 

        $user =  create($input); 
        $success['token'] =  $user->createToken('MyApp')-> remember_token; 
        $success['name'] =  $user->name;
return response()->json(['success'=>$success], $this-> successStatus); 
    }
/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json([
            'success' => 1,
            'User' => $user
        ]); 
    } 

    public function login2(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = md5(time()) . '.' . md5($request->email);
            $user->forcefill([
                'api_token' => $token
            ])->save();
            if(Auth::user()->role == 3){
                if(!empty(Seller::where('seller_id' , Auth::id())->first()->allowed_seller)){
                    $user->setAttribute('role', '10');
                }
            }
            return response()->json([
                'code' => '200' , 
                'status'=>'success' , 
                'message'=>'User Login Successfully!' , 
                'data' => $user
            ]);
        }
        return response()->json([
            'message' => 'Credentials Do no match'
        ]);
    }

    public function getEmployees(){

        if(Auth::user()->role == 3){
            $seller = Seller::where('seller_id' , Auth::id())->first();
            if(!empty($seller->allowed_seller)){
                $seller_ids = explode('|' , $seller->allowed_seller);
            }
            if (is_array($seller_ids)) {
                $queryset = 'orWhereIn';
                
            } else {
                $queryset = 'orWhere';
            }

        }elseif(Auth::user()->role < 3){
            $queryset = 'orWhere';
            $seller_ids = NULL;
        }else{
             return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'You are not allowed!',
                'data' => null
            ], 404);
        }

        //dd($queryset , $seller_ids);

        $data['orderTakers'] = User::join('ordertakers' , 'users.id' , 'ordertakers.user_id')->where('ot_of', '=', Auth::id())->get();
        $data['sellers']     = User::join('sellers' , 'users.id' , 'sellers.seller_id')->$queryset('users.id' , $seller_ids)->get();
        if ($data['orderTakers']->count() === 0 && $data['sellers']->count() === 0) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'No employees found for the authenticated user.',
                'data' => null
            ], 404);
        }
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Employees retrieved successfully.',
            'data' => $data
        ]);
    }

    public function registerNewSubadmin(Request $request){
        
        // try{
        // DB::beginTransaction();

         $request->validate([
            'email'     => 'required|unique:users',
            'name'      => 'required',
            'phone'     => 'required',
            'password'  => 'required|min:8|regex:/^(?=.*[A-Za-z])(?=.*[0-9]).{8,}$/',
            'cpassword' => 'required|min:8|regex:/^(?=.*[A-Za-z])(?=.*[0-9]).{8,}$/',
            'pin'       => 'required',
            'terms'     => 'required'
        ],[
            'password.regex' => 'Invalid format. It should contain at least one  alphabet, one digit',
            'cpassword.regex' => 'Invalid format. It should contain at least one  alphabet, one digit',
        ]);

         
        
        if($request->password != $request->cpassword){
            return response()->json(['message' => 'Password Did Not Matched!']);
        }
        $seller = new User() ;
       
        $seller->name       = $request->name;
        $seller->email      = $request->email;
        $seller->phone      = $request->phone;
        $seller->password   = $request->password;
        $seller->ot_of      = NULL;
        $seller->user_of    = 4;
        $seller->role       = 2;
        $seller->pincode    = $request->pin;

        $seller->save();

        $sv                 = new SubAdmin();
        $sv->sub_admin_id   = $seller->id;
        $sv->user_of        = 4;
        $sv->save();

        if ($seller && !$seller->hasVerifiedEmail()) {
            $seller->sendEmailVerificationNotification();
        }
        // DB::commit();
        //     return response()->json(["message" => 'User Added!']);
        // }catch (\Exception $e) {
        //     DB::rollBack();
        //     \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
        //        return response()->json(['message' => 'Something Went Wrong!']);
        // }
    }
}

