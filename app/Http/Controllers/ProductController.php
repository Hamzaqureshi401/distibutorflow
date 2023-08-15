<?php
namespace App\Http\Controllers;
use App\Http\Requests;

use Illuminate\Http\Request;
use App\Repositories\Common;
use App\Models\CustomOtBenefit;
use App\Models\Customer;
use App\Models\Product;
use App\Models\CustomPrice;
use App\Models\Category;
use App\Models\SubAdmin;
use App\Models\Ordertaker;
use App\Models\User;
use App\Models\Area;
use App\Models\CustomerProductsStock;
use App\Models\AdminSellRecord;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Image;
use File;
use App\Models\OtCustomer;

use Auth;

class ProductController extends Controller
{
    public function index($customer_id = "")
    {
        $route = "updateProduct/";
        $getEditRoute = "getProduct";
        $modalTitle = "Edit Product";

        $products = Product::where(["user_id" => Auth::id()])
            ->orderBy("id", "desc")
            ->get();
        if (Auth::user()->role == 2) {
            $check_link = SubAdmin::where("sub_admin_id", Auth::id())->first()
                ->product_link;
            $findadminname = User::where("id", $check_link)
                ->pluck("name")
                ->first();
            $findadminproducts = Product::where(["user_id" => $check_link])
                ->orderBy("id", "desc")
                ->get();
            return view(
                "products.all_products",
                compact(
                    "products",
                    "findadminproducts",
                    "findadminname",
                    "route",
                    "getEditRoute",
                    "modalTitle"
                )
            );
        } elseif (Auth::user()->role == 4) {
            
            $get_products = $this->getCustomerproduct(Auth::id());
            $products = Product::whereIn("id", $get_products)
                ->orWhere("user_id", Auth::id())
                ->get();
            return view(
                "products.CustomerProduct.all_customer_product",
                compact(
                    "products",
                    "route",
                    "getEditRoute",
                    "modalTitle"
                )
            );
        }

        // Artisan::call('backup:run');
        // dd(Artisan::output());
        // dd(date('Y-m-d-H-i-s').".zip");
        // $path = storage_path('app/laravel-backup/*');
        // $latest_ctime = 0;
        // $latest_filename = '';
        // $files = glob($path);
        // foreach($files as $file)
        // {
        //         if (is_file($file) && filectime($file) > $latest_ctime)
        //         {
        //                 $latest_ctime = filectime($file);
        //                 $latest_filename = $file;
        //         }
        // }
        //  dd($latest_filename);
        return view("products.all_products", 
            compact(
                "products",
                "route",
                    "getEditRoute",
                    "modalTitle"
            ));
    }




    public function getCustomerproduct($customer_user_id)
    {
        $get_customer_record = Customer::where(
            "user_id",
            $customer_user_id
        )->first();
        $products = explode("|", $get_customer_record->final_allowed_products);
        return $products;
    }

    public function download()
    {
        if (Auth::user()->role < 3) {
            Artisan::call("config:cache");
            Artisan::call("config:clear");
            Artisan::call("cache:clear");
            Artisan::call("view:clear");
            // Artisan::call('cron:send-profit-info');
            // dd(Artisan::output());
            //  //$file = date('Y-m-d-H-i-s').".zip";
            //   $path = storage_path('app/*');
            // $latest_ctime = 0;
            // $latest_filename = '';
            // $files = glob($path);
            // foreach($files as $file)
            // {
            //         if (is_file($file) && filectime($file) > $latest_ctime)
            //         {
            //                 $latest_ctime = filectime($file);
            //                 $latest_filename = $file;
            //         }
            // }
            // //dd(file($latest_filename));
            // //Storage::disk('google')->put('test1.txt', $success);
            // return response()->download($latest_filename);
        }
    }

    public function myProducts($user_id)
    {
        $products = Product::where("created_by", $user_id)
            ->with("User")
            ->get();
        return Common::Data($products)
            ? Common::Data($products)
            : Common::Message("Product");
    }

    public function mergeCustomer(){

      $adminid = 228;
      $changeId = 4;
      $ordertaker = 231;
      $ot =  User::where('ot_of' , $adminid)->pluck('id')->toArray();
      User::where('ot_of' , $adminid)->update(['ot_of' => $changeId]);
      CustomOtBenefit::whereIn('ot_id' , $ot)->delete();
      $defualt_product = CustomOtBenefit::where('ot_id' , $ordertaker)->pluck('product_id')->toArray();
      foreach($ot as $oti){
      foreach($defualt_product as $p_id){
          $allow_product = new CustomOtBenefit();
          $allow_product->ot_id = $oti;
          $allow_product->product_id = $p_id;
          $allow_product->ot_benefit = 0;
          $allow_product->save();
      }
  }
      $allowed_products = '23|24|25|39|62';
      $final_allowed_products = '23|24|25|39|62|27|28|29|30|31|32';
      Customer::whereIn('created_by' , $ot)->update(['allowed_products' => $allowed_products , 'final_allowed_products' => $final_allowed_products]);

      array_push($ot , $adminid);
      $area = Area::whereIn('created_by' , $ot)->update(['created_by' => $changeId]);//



      //merge admin customer
      OtCustomer::where('ot_id' , $adminid)->update(['ot_id' => $changeId]);
      Customer::where('created_by' , $adminid)->update(['allowed_products' => $allowed_products , 'final_allowed_products' => $final_allowed_products , 'created_by' => $changeId]);

    }

    public function addProduct(){

        //$this->mergeCustomer(); //dangerous

        if (Auth::user()->role < 3) {
            $categories = Category::where(["user_id" => Auth::id()])->get();
        } elseif (Auth::user()->role == 4) {
            $get_products = $this->getCustomerproduct(Auth::id());
            $category_id = array_unique(
                Product::whereIn("id", $get_products)
                    ->pluck("category_id")
                    ->toArray()
            );
            $categories = Category::where(["user_id" => Auth::id()])
                ->orWhereIn("id", $category_id)
                ->get();
            return view(
                "products.CustomerProduct.add_customer_product",
                compact("categories")
            );
        }
        
        if(empty($categories->first())){
            return redirect()->route('all.categories')->with('error', 'Please Add Category First!');
        }

        return view("products.add_product", compact("categories"));
    }

    public function storeProduct(Request $request)
    {
        try {
            DB::beginTransaction();
            $product = new Product();

            if (!empty($request->new_category_name)) {
                $store_categoy = new Category();
                $store_categoy->name = $request->new_category_name;
                $store_categoy->user_id = Auth::id();
                $store_categoy->save();
                $product->category_id = $store_categoy->id;
            } else {
                $product->category_id = $request->category_id;
            }

            $product->user_id = Auth::id();
            $product->name = $request->name;
            $product->price = $request->price ?? "0";
            $product->c_benefit = $request->c_benefit ?? "0";
            $product->ot_benefit = $request->ot_benefit ?? "0";
            $product->p_price = $request->p_price ?? "0";
            $product->a_benefit = $request->price - $request->p_price ?? "0";
            $product->sell_price = $request->sell_price ?? "0";
            $product->save();
            if ($request->add_stock != 0) {
                $transection_id = $this->GetUniqueTransectionId();
                $this->SetProductStock(
                    $product->id,
                    Auth::id(),
                    $request->add_stock,
                    $comments = null,
                    $invoice_type = "stock_added",
                    $stock_adder_id = false,
                    $transection_id
                );
            }
            DB::commit();
            return response()->json(["message" => "Product Saved!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency(
                "File:" .
                    $e->getFile() .
                    "Line:" .
                    $e->getLine() .
                    "Message:" .
                    $e->getMessage()
            );

            $output = [
                "success" => 0,
                "msg" => __("messages.something_went_wrong"),
            ];

            return response()->json(["message" => "Something Went Wrong!"]);
        }
    }

    // public function getProduct($id)
    // {
    //     if (Auth::user()->role < 3) {
    //         $product = Product::find($id);
    //         $categories = Category::where(["user_id" => Auth::id()])->get();
    //         $button = "Update Product";
    //         $title = "Edit Product";
    //         $route = "/updateProduct";

    //         return view(
    //             "products.edit_product",
    //             compact(
    //                 "product", 
    //                 "categories",
    //                 'button',
    //                 'title',
    //                 'route'
    //             )
    //         );
    //     } elseif (Auth::user()->role == 4) {
    //         $product = Product::find($id);
    //         $get_products = $this->getCustomerproduct(Auth::id());
    //         $category_id = array_unique(
    //             Product::whereIn("id", $get_products)
    //                 ->pluck("category_id")
    //                 ->toArray()
    //         );
    //         $categories = Category::where(["user_id" => Auth::id()])
    //             ->orWhereIn("id", $category_id)
    //             ->get();

    //         return view(
    //             "products.CustomerProduct.edit_customer_product",
    //             compact("product", "categories")
    //         );
    //     }
    // }

    public function getProduct($id)
    {
        if (Auth::user()->role < 3) {
            $product = Product::find($id);
            $categories = Category::where(["user_id" => Auth::id()])->get();
            $button = "Update Product";
            $title = "Edit Product";
            $route = "/updateProduct";

            return view(
                "products.edit_product",
                compact(
                    "product", 
                    "categories",
                    'button',
                    'title',
                    'route'
                )
            );
        } elseif (Auth::user()->role == 4) {
            $product = Product::find($id);
            $get_products = $this->getCustomerproduct(Auth::id());
            $category_id = array_unique(
                Product::whereIn("id", $get_products)
                    ->pluck("category_id")
                    ->toArray()
            );
            $categories = Category::where(["user_id" => Auth::id()])
                ->orWhereIn("id", $category_id)
                ->get();

            return view(
                "products.CustomerProduct.edit_customer_product",
                compact("product", "categories")
            );
        }
    }

   

    public function deleteProduct($id)
    {
        if (Auth::user()->role < 3) {
            $product = Product::where([
                "id" => $id,
                "user_id" => Auth::id(),
            ])->get();
            if (Common::Data($product)) {
                if (
                    sizeof(AdminSellRecord::where("product_id", $id)->get()) ==
                    0
                ) {
                    CustomOtBenefit::where("product_id", $id)->delete();
                    CustomPrice::where("product_id", $id)->delete();
                    Product::where("id", $id)->delete();
                    return response()->json([
                        "success" => true,
                        "message" => "Product Deleted!",
                    ]);
                }
                return response()->json([
                    "error" => true,
                    "message" =>
                        "Product Cannot Be Deleted ( Exist In Sell Record )!",
                ]);
            } else {
                return response()->json([
                    "error" => true,
                    "message" => "Something Went Wrong!",
                ]);
            }
        } else {
            Product::where(["id" => $id, "user_id" => Auth::id()])->delete();
            CustomerProductsStock::where([
                "product_id" => $id,
                "customer_id" => Auth::id(),
            ])->delete();
            return response()->json([
                "success" => true,
                "message" => "Product Deleted!",
            ]);
        }
    }

    //update Poduct allow status
    public function updateStatusAllow(Request $request)
    {
        $product = Product::findOrFail($request->user_id);
        $product->allow_status = $request->allow_status;
        $product->save();

        return response()->json([
            "message" => "User status updated successfully.",
        ]);
    }
    public function AllowToAllCustomer(Request $request)
    {
        $product = Product::findOrFail($request->user_id);
        $product->allow_to_all_customer = $request->allow_status;
        $product->save();

        return response()->json([
            "message" => "User status updated successfully.",
        ]);
    }
    public function ShowInPos(Request $request)
    {
        $product = Product::find($request->p_id);
        $product->show_in_pos = $request->show_in_pos;
        $product->save();

        return response()->json([
            "message" => "User status updated successfully.",
        ]);
    }

    //update Product show status
    public function updateStatusShow(Request $request)
    {
        $product = Product::findOrFail($request->user_id);
        $product->show_status = $request->show_status;
        $product->save();

        return response()->json([
            "message" => "User status updated successfully.",
        ]);
    }
    public function savelinkproduct(Request $request)
    {
        if (Auth::user()->role < 3) {
            if ($request->maping_product != null) {
                $product = Product::findOrFail($request->user_product);
                $product->link_product = $request->maping_product;
                $product->save();
                return response()->json([
                    "success" => true,
                    "message" => "Product Linked successfully.",
                ]);
            } else {
                return response()->json([
                    "error" => true,
                    "message" => "Link Failed",
                ]);
            }
        }
    }
    public function deletelinkproduct(Request $request)
    {
        if (Auth::user()->role < 3) {
            if ($request->user_product != null) {
                $product = Product::findOrFail($request->user_product);
                $product->link_product = null;
                $product->save();
                return response()->json([
                    "success" => true,
                    "message" => "Product Deleted successfully.",
                ]);
            } else {
                return response()->json([
                    "error" => true,
                    "message" => "Error Failed",
                ]);
            }
        }
    }
    public function checkstock()
    {
        if (Auth::user()->role == 3) {
            $user_of = User::where("id", Auth::id())
                ->pluck("seller_of")
                ->first();
        } else {
            $user_of = Auth::id();
        }
        $products = Product::where(["user_id" => $user_of])
            ->where("remaining_stock", "!=", 0)
            ->get();
        if (Auth::user()->role == 2) {
            $check_link = SubAdmin::where("sub_admin_id", Auth::id())
                ->pluck("product_link")
                ->first();
            $findadminname = User::where("id", $check_link)
                ->pluck("name")
                ->first();
            $findadminproducts = Product::where([
                "user_id" => $check_link,
            ])->get();
            return view(
                "products.stock_check",
                compact("products", "findadminproducts", "findadminname")
            );
        } else {
            return view("products.stock_check", compact("products"));
        }
    }

    public function GetStock()
    {
        $users = User::where("id", Auth::id())->get();
        return view(
            "products.CustomerProduct.add_customer_product_stock",
            compact("users")
        );
    }
    public function findStockProduct($allowed_ids)
    {
        $get_products = $this->getCustomerproduct(Auth::id());
        $stock_products = Product::whereIn("id", $get_products)
            ->orWhere("user_id", Auth::id())
            ->get();

        return view(
            "products.CustomerProduct.ajax_product_stock",
            compact("stock_products")
        );
    }
    public function SetProductStock(
        $product_id,
        $customer_id,
        $unit,
        $comments,
        $invoice_type,
        $stock_adder_id,
        $transection_id
    ) {
        $query = CustomerProductsStock::where([
            "product_id" => $product_id,
            "customer_id" => $customer_id,
        ]);
        $CheckProductExist = $query->pluck("id")->count();
        if ($CheckProductExist != 0) {
            $getproductrecord = $query->latest("id")->first();
            $remaining_stock = $getproductrecord->remaining_stock + $unit;
            $old_stok = $getproductrecord->remaining_stock;
            $stock_added = 1;
        } else {
            $remaining_stock = $unit;
            $old_stok = 0;
            $stock_added = 1;
        }
        $AddNewRecord = new CustomerProductsStock();
        $AddNewRecord->customer_id = $customer_id;
        $AddNewRecord->product_id = $product_id;
        $AddNewRecord->remaining_stock = $remaining_stock;
        $AddNewRecord->old_stock = $old_stok;
        $AddNewRecord->comments = $comments;
        $AddNewRecord->transection_id = $transection_id;

        if ($invoice_type == "stock_added") {
            $AddNewRecord->stock_added = 1;
        } elseif ($invoice_type == "sell_added") {
            $AddNewRecord->sell_added = 1;
        }
        if ($stock_adder_id != false) {
            $AddNewRecord->stock_adder_user_id = $stock_adder_id;
        }
        $AddNewRecord->save();
    }

   
    public function GetUniqueTransectionId()
    {
        $new_id = CustomerProductsStock::latest("id")->first();
        if (!empty($new_id)) {
            $transection_id = 1 + $new_id->id;
        } else {
            $transection_id = 1;
        }
        return $transection_id;
    }
    public function GetCustomerStockInvoices()
    {
        $get_products = $this->getCustomerproduct(Auth::id());
        $products_ids = Product::whereIn("id", $get_products)
            ->orWhere("user_id", Auth::id())
            ->pluck("id")
            ->toArray();
        $products = array_unique(
            CustomerProductsStock::whereIn("product_id", $products_ids)
                ->where("customer_id", Auth::id())
                ->pluck("transection_id")
                ->toArray()
        );
        $model = new CustomerProductsStock();
        return view(
            "products.CustomerProduct.customer_stock_added_invoices",
            compact("products", "model")
        );
    }
    public function GetTransectionRecord($id)
    {
        $transection = CustomerProductsStock::where("transection_id", $id);
        $data["transection"] = $transection->get();
        return view(
            "products.CustomerProduct.stock_add_remove_history",
            compact("data")
        );
    }

    public function getProducts()
    {
        if(Auth::user()->role == 3){
            $adminid = Auth::user()->seller_of;
            $ot_products = [];
        }elseif(Auth::user()->role == 5)
        {
            $adminid = NULL;
            $ot_products = CustomOtBenefit::where('ot_id' , Auth::id())->pluck('product_id')->toArray();
        }else{
            $adminid = Auth::id();
            $ot_products = [];
        }

        $products = Product::join('categories', 'products.category_id', '=', 'categories.id')
    ->where(function ($query) use ($adminid, $ot_products) {
        $query->where('products.user_id', $adminid)
            ->orWhereIn('products.id', $ot_products);
    })
    ->select('products.*', 'categories.name as cat_name')
    ->get();

        if ($products->count() === 0) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'No products found for the authenticated user.',
                'data' => null
            ], 404);
        }
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Products retrieved successfully.',
            'data' => $products
        ]);
    }
}
