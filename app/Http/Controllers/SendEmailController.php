<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Models\OrderDetail;
use App\Models\User;
class SendEmailController extends Controller
{
    function index()
    {
     return view('send_email');
    }

    function send(Request $request)
    {
     $this->validate($request, [
      'name'     =>  'required',
      'email'  =>  'required|email',
      'message' =>  'required'
     ]);

        $data = array(
            'name'      =>  $request->name,
            'message'   =>   $request->message
        );
        
     $get_order = OrderDetail::where('order_id' , 112)->get();

     Mail::to('hamzaqureshi401@gmail.com')->send(new SendMail($data , $get_order));
     return back()->with('success', 'Thanks for contacting us!');

    }
     public function EnableProfitGuru(Request $request){
            
            $a = "a";
            $customer =  User::find($request->user_id);
            $data =  array(
                'name' =>     $customer->name
                );
            if ($customer->email_subscription == 0){
            
            $customer->email_subscription = 1;
            $customer->save();
            $blade = "welcome-profit-guru";
            
            //$customer->email;
            //$m = "hamzaqureshi401@gmail.com";
            Mail::to($customer->email)->send(new SendMail($data ,$a , $blade));
            Mail::to('mail.scoopscreamery@gmail.com')->send(new SendMail($data ,$a , $blade));
            return response()->json(['message' => 'Profit Guru Mail Sent Successfully!']);
            }
            
            else{
            
            $blade = "disable-profit-guru";
            $customer->email_subscription = 0;
            $customer->save();
            if ($request->status === "yes"){
            Mail::to($customer->email)->send(new SendMail($data ,$a , $blade));
            Mail::to('mail.scoopscreamery@gmail.com')->send(new SendMail($data ,$a , $blade));
            }
             return response()->json(['message' => 'Profit Guru Disabled Successfully!']);
            }
            
            if (Mail::failures()) {
              Mail::to('mail.scoopscreamery@gmail.com')->subject('Mail Not Sent');
    }
            
            
        
    }
}

?>