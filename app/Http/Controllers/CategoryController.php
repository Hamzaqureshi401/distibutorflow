<?php

namespace App\Http\Controllers;
use App\Repositories\Common;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use DB;

use Auth;

class CategoryController extends BaseController{
    
    public function index(){

        if (Auth::user()->role < 3){
            $categories = Category::where('user_id' , Auth::id())->get();
            return view('categories.all_categories' , compact('categories'));
        }elseif(Auth::user()->role == 4){
            $get_products = $this->getCustomerproduct(Auth::id());
            $category_id = array_unique(Product::whereIn('id' , $get_products)->pluck('category_id')->toArray());
            $categories = Category::where(['user_id' => Auth::id()])->orWhereIn('id' , $category_id)->get();
            return view('categories.all_categories' , compact('categories'));
        }
    }
    public function validation($request){

     $this->validate($request, [
            'name'        => 'required|max:30|unique:Categories',
        ]);
        // $validation['validation'] = $validator->errors()->first();
        // if ($validator->fails()) {
        //     $validation['error'] = true;
        // }else{
        //     $validation['error'] = false;
        // }
        // return $validation;
    }
    
    
    public function storeCategory(Request $request){

        if(Auth::user()->role < 3 || Auth::user()->role == 4){
             $validator = $this->validation($request);
        $category = new Category();
        $category->name = $request->name;
        $category->user_id = Auth::id();
        $category->save();
        if ($request->expectsJson()) {
        return response()->json([
            'code' => '200',
            'status' => 'success',
            'message' => 'Category Saved Successfully!',
            'data' => $category
        ]);
        }else{
            return Common::Message("Category" , 1);
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
    
    public function getCategory($id){
        $category = Category::where('id' , $id)->get();
        return Common::Data($category) ? Common::Data($category) : Common::Message("Category");
    }
    
    public function getCustomerproduct(){

       $get_customer_record = Customer::where('user_id' , Auth::id())->first();
       $products = explode('|' , $get_customer_record->allowed_products);
       return $products;
    }

    public function updateCategory(Request $request , $id){
        $category = Category::where(['id' => $id , 'user_id' => Auth::id()])->get();
        if(Common::Data($category)){
            Category::where(['id' => $id , 'user_id' => Auth::id()])->update(['name' => $request->name]);
            return Common::Message("Category" , 2);
        }
        else{
            return Common::Message("Category");
        }
    }
    
    public function deleteCategory($id){
        $category = Category::where(['id' => $id , 'user_id' => Auth::id()])->get();
        if(Common::Data($category) && Product::where('category_id' , $id)->pluck('category_id')->count() == 0){
           Category::where('id' , $id)->delete();
            return Common::Message("Category" , 3);
        }else{
            return redirect()->back()->with('error', 'Can Not Delete Category Some Product Is Linked!');
        }
    }

    public function getCategories()
    {

        $adminid = $this->findMyAdmin();
        $categories = Category::
        leftJoin('products', 'categories.id' , 'products.category_id')
            ->select('categories.id', 'categories.name', DB::raw('count(products.id) as product_count'), 'categories.created_at', 'categories.updated_at')
            ->where('categories.user_id', '=', $adminid)
            ->groupBy('categories.id', 'categories.name', 'categories.created_at', 'categories.updated_at')
            ->get();
        if ($categories->count() === 0) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'No categories found for the authenticated user.',
                'data' => null
            ], 404);
        }
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Categories retrieved successfully.',
            'data' => $categories
        ]);
    }
}