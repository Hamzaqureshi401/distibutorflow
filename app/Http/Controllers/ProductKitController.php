<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use App\Models\ItemKit;
use App\Models\ItemKitDetail;
use Illuminate\Http\Request;
use Auth;
use Image;
use File;

class ProductKitController extends Controller
{
    public function addProductKit(){

        $categories = Category::where('user_id' , Auth::id())->get();

        $products   = Product::where(['user_id' => Auth::id() , 'type' => 'single_product'])->orderBy('category_id', 'asc')->get();
        //dd($categories);
         if(empty($categories->first())){
            return redirect()->route('all.categories')->with('error', 'Please Add Category First!');
        }
        return view('ProductKit.addProductKit' , compact('categories' , 'products'));
    }

    public function storeKit(Request $request){

        //dd($request->all());
        //ItemKit::truncate();
        $ItemKit = new Product();
        $ItemKit->category_id = $request->category_id;
        $ItemKit->name     = $request->kitname;
        $ItemKit->price = $request->trade_price;
        $ItemKit->a_benefit       = $request->a_ben;
        $ItemKit->type        = 'item_kit';
        $ItemKit->user_id     = Auth::id();
        $img = $this->resizeImage($request , $destinationPath = public_path().'/productKit/');
        $ItemKit->img         = $img;
         
        if(empty($request->status)){
            return response()->json(["message" => "Kit Product Required!" , "type" => "error" , 'title' => 'Error']);

        }
        $ItemKit->save();

        $this->storekitdetail($request , $ItemKit->id);

        return response()->json(["message" => "Kit Stored Successfullly" , "type" => "success" , 'title' => 'Success']);
        
        
        
    }

    public function storekitdetail($request , $ItemKitId){

        if(!empty($request->status)){
        for ($key=0; $key < sizeof($request->status) ; $key++) {

            $ky               = $request->status[$key];
            $ItemKitDetail    = new ItemKitDetail();
            
            $ItemKitDetail->kit_id      = $ItemKitId;
            $ItemKitDetail->product_id  = $request->product_id[$ky];
            $ItemKitDetail->quantity    = $request->quantity[$ky];
            $ItemKitDetail->save();
        
    }
    }
    }

    public function allProductKit(){

        $ItemKits = Product::where(['user_id' => Auth::id() , 'type' => 'item_kit'])->get();
        //dd($ItemKits);
        return view('ProductKit.all_productKit' , compact('ItemKits'));
    }
    public function resizeImage(Request $request , $path)
    {
        $this->validate($request, [
            "file" => "required|image|mimes:jpg,jpeg,png,gif,svg|max:90480",
        ]);
        $image = $request->file("file");
        $input["file"] = time() . "." . $image->getClientOriginalExtension();

        $destinationPath = $path;
            if (!File::exists($destinationPath)) {
                 File::makeDirectory($destinationPath, 0755, true);
            }
        $imgFile = Image::make($image->getRealPath());
        $imgFile
            ->resize(32, 52, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($destinationPath . "/" . $input["file"]);

        return '/productKit/'.$input["file"];
    }
    public function productKitDetail($id){

        $ItemKitDetails = ItemKitDetail::where('kit_id' , $id)->get();
        return view('ProductKit.ItemKitDetail' , compact('ItemKitDetails')); 

    }
     public function editKit($id){

        $categories     = Category::where('user_id' , Auth::id())->get();
        
        $ItemKit        = Product::where('id' , $id)->first();
        $ItemKitDetails = ItemKitDetail::where('kit_id' , $id)->get();
        $arr_pr         = $ItemKitDetails->pluck('product_id')->toArray();

        $products       = Product::where(['user_id' => Auth::id()])->whereNotIn('id' , $arr_pr)->orderBy('category_id', 'asc')->get();
        
        return 
            view('ProductKit.editKit' , 
                compact(
                    'ItemKitDetails' , 
                    'categories' , 
                    'products' , 
                    'ItemKit'
                )); 

    }

    public function updateProductKit(Request $request){

        //dd($request->all());

        Product::where('id' , $request->id)->update([
            'category_id' => $request->category_id ,
            'name'        => $request->kitname,
            'price'       => $request->price,
            'a_benefit'   => $request->a_ben
        ]);
        
        for ($key=0; $key < sizeof($request->statusold) ; $key++) {
            $ky = $request->statusold[$key];
            $pr[] = $request->product_idold[$ky];
            ItemKitDetail::where([
                'product_id' => $request->product_idold[$ky] , 
                'kit_id' => $request->id 
            ])->update(['quantity' => $request->quantityold[$ky]]);
        }
        ItemKitDetail::whereNotIn('product_id' , $pr)->delete();
        $this->storekitdetail($request , $request->id);
         return response()->json(["message" => "Updated" , "type" => "success" , 'title' => 'Error']);
    }
}
