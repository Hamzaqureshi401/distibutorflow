<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

use App\Customer;

use App\Order;

use App\Invoice;

use App\User;

class CreateAutomaticOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'command:name';

    protected $signature = 'cron:create-automatic-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';



    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        // here check if order exist donot create new order else create and set order exist 1 to customer  
        $customer_ids = Customer::where('automatic_create_order' , "!=" , 0)->pluck('id')->toArray();
        
        foreach($customer_ids as $customer_id){
        $create_order = new Order();
        $now = \Carbon\Carbon::now()->toDateString();
        $order = Invoice::where('customer_id' , $customer_id);
        $lastorder = (clone $order)->orderBy('id' , 'desc')->pluck('created_at')->first();
        $order_exist = Order::where('customer_id' , $customer_id);
        $order_existence = $order_exist->orderBy('id' , 'desc')->pluck('is_confirmed_admin')->first();
        $a = strtotime($lastorder);
        $b = strtotime($now);
        $days_between = ceil(($b - $a) / 86400);
        $days = Customer::where('id' , $customer_id)->pluck('automatic_create_order')->first();
          if ($days_between >= $days && $order_existence != NULL)             
        {             
        $customer = Customer::where('id' , $customer_id)->pluck('created_by')->first();
        $admin = User::where('id' , $customer)->pluck('ot_of')->first();
        Customer::where('id' , $customer_id)->update(['order_exist' => 1]);
        $old_order_balance = Order::where('customer_id' , $customer_id)->orderBy('id' , 'desc')->pluck('amount_left')->first();
        $create_order->ot_id = $admin; // need to set id
        $create_order->user_id = $admin;
        $create_order->customer_id = $customer_id;
        $create_order->unit = 0;
        $create_order->amount = 0;
        $create_order->subtotal = 0;
        $create_order->p_amount = 0;
        $create_order->received_amount = 0;
        $create_order->amount_left = $old_order_balance;
        $create_order->ot_benefit = 0;
        $create_order->c_benefit = 0;
        $create_order->order_comments = "System Generated Order!";
        $create_order->cancel_status = 1;
        $create_order->order_date = date('Y-m-d H:i');
        $create_order->allow_next_order = 0;
        $create_order->location_url_ot = "31.5815886,74.3779746";
        $create_order->ot_customer_distance = 0;
        $create_order->chk_ord_vst = 0;
        $create_order->save();
        }
    }
    }

}
