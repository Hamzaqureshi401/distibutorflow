<?php

namespace App\Http\Controllers;
use App\Models\ReceiptSetting;
use Auth;
use Image;
use File;
use Illuminate\Http\Request;

class ReceiptSettingsController extends Controller
{
    public function receiptSettings(){

        $receipt = ReceiptSetting::where('user_id' , Auth::id())->first();
        return view('Receipts.receipt_Settings' , compact('receipt'));
    }

    public function storeReceipt(Request $request){

     $this->validate($request, [
                "headerImg" => "image|mimes:jpg,jpeg,png,gif,svg|max:90480",
                "footerImg" => "image|mimes:jpg,jpeg,png,gif,svg|max:90480",
            ]);

         if($request->headerImg){
            $headerImg = $this->resizeImage($request , $destinationPath = public_path().'/receipt/' , $folder ='/receipt/' , $request->file("headerImg"));    
        }else{
            $headerImg = null;
        }
        if($request->footerImg){
            $footerImg = $this->resizeImage($request , $destinationPath = public_path().'/receipt/' , $folder ='/receipt/' , $request->file("footerImg"));    
        }else{
            $footerImg = null;
        }
        $query = ReceiptSetting::where('user_id' , Auth::id());
        if($query->exists()){
            $ReceiptSetting = $query->first();
        }else{
            $ReceiptSetting = new ReceiptSetting();
        }
        $ReceiptSetting->user_id              = Auth::id();
        $ReceiptSetting->headerImg            = $headerImg ?? $ReceiptSetting->headerImg;
        $ReceiptSetting->footerImg            = $footerImg ?? $ReceiptSetting->footerImg;
        $ReceiptSetting->company_name         = $request->company_name;
        $ReceiptSetting->address              = $request->address;
        $ReceiptSetting->phone                = $request->phone;
        $ReceiptSetting->email                = $request->email;
        $ReceiptSetting->website              = $request->website;
        $ReceiptSetting->save();
        return redirect()->back()->with('success' , "Receip Set");

    }
     public function resizeImage(Request $request , $path , $folder , $image)
    {
        // $this->validate($request, [
        //     "img" => "required|image|mimes:jpg,jpeg,png,gif,svg|max:90480",
        // ]);
        
        $input["img"] = time() . "." . $image->getClientOriginalExtension();

        $destinationPath = $path;
            if (!File::exists($destinationPath)) {
                 File::makeDirectory($destinationPath, 0755, true);
            }
        $imgFile = Image::make($image->getRealPath());
        $imgFile
            ->resize(400, 200, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($destinationPath . "/" . $input["img"]);
        // $destinationPath = public_path("/uploads");
        // $image->move($destinationPath, $input["file"]);

        return $folder.$input["img"];
    }
}
