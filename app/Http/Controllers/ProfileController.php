<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Repositories\Common;
use App\Models\User;
use App\Models\Profile;
use Intervention\Image\Facades\Image;
use Auth;
use Validator;
class ProfileController extends Controller
{
    public function getuserprofile(){

    if (Auth::check()){
    $user = [User::find(Auth::id())];
    $profile = [Profile::where('user_id' , Auth::id())->first()];
        $result['user']   = $user[0];
        $result['profile']= $profile[0];
        if (Auth::user()->role == 1){
            $result['role']   = "Super Admin";
        }elseif(Auth::user()->role == 2){
            $result['role']   = "Admin";
        }elseif(Auth::user()->role == 3){
            $result['role']   = "Seller";
        }elseif(Auth::user()->role == 4){
            $result['role']   = "Customer";
        }elseif(Auth::user()->role == 5){
            $result['role']   = "Order Taker";
        }
    return $result;
    }
   }

    public function GetProfile(){
     
     return view('profile.user_profile');

   }
    public function EditProfile(){

    return view('profile.edit_user_profile');

   }

   public function UpdateProfile(Request $request){

        if ($request->img) {
        $data[] = $request->img;
        $validate = Validator::make($data, [
            'image' => 'image|mimes:jpeg,png,jpg,PNG',
        ]);
        }
        
        try{
            DB::beginTransaction();
        $user = User::find(Auth::id());
        $user->name = $request->name;
        $user->save();
        $getProfile = Profile::where('user_id' , Auth::id());
        if ((clone $getProfile)->pluck('id')->count() == 0){
            $store = new Profile();
            $store->user_id = Auth::id();
        }else{
        $store = (clone $getProfile)->first();
        }
        $store->address  = $request->address;
        $store->city  = $request->city;
        $store->state  = $request->state;
        $store->about  = $request->about;
        $store->hobbies  = $request->hobbies;
        $store->gender  = $request->gender;
        $store->date_of_birth  = $request->date_of_birth;
       // dd($request->all());
        if ($request->img) {
            $image = $request->file('img');
            $fileExtension   = strtolower($image->getClientOriginalExtension()); 
            $file_name       = sha1(uniqid().$image.uniqid()).'.'.$fileExtension;
            $destinationPath = 'images/';
                $img = Image::make($image->getRealPath());
                $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.$file_name);
            $store->img = $destinationPath.$file_name;
        }
        $store->save();
        DB::commit();
        return redirect()->route('edit.profile')->with(['message' => "Profile Updated!"]);
        }catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
           return response()->json(['message'=> "Line:" . $e->getLine() . $e->getMessage()]);
        }
   }

}
