<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

use App\Customer;

class UpdateUserNotNew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'command:name';

    protected $signature = 'cron:update-user-not-new';

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
        $dayAgo = 7; // Days ago
        $dayToCheck = \Carbon\Carbon::now()->subDays($dayAgo)->format('Y-m-d');
        $dayToChecktodonotshow = \Carbon\Carbon::now()->subDays(1)->format('Y-m-d');
        Customer::whereDate('visit_date', '<=', $dayToCheck)
        ->update([
            'visit_clear' => 0
        ]);
        Customer::whereDate('do_not_show_date', '<=', $dayToChecktodonotshow)
        ->update([
            'do_not_show' => 0
        ]);
    }
    
    public function CreateAutomaticOrder(){
        $create_order = new Order();
        
        // here check if order exist donot create new order else create and set order exist 1 to customer  
        Customer::where('id' , $cus_det[0])->update(['order_exist' => 1]);
        $old_order = Order::where('customer_id' , $order->customer_id)->orderBy('id' , 'desc')->get();
  
        $create_order->ot_id = $admin_id; // need to set id
        $create_order->user_id = $admin_id;
        $create_order->customer_id = $customer_id;
        $create_order->unit = 0;
        $create_order->amount = 0;
        $create_order->subtotal = 0;
        $create_order->p_amount = 0;
        $create_order->received_amount = 0;
        $create_order->amount_left = $old_balance;
        $create_order->ot_benefit = 0;
        $create_order->c_benefit = 0;
        $create_order->order_comment = "System Generated Order!";
        $create_order->cancel_ststus = 1;
        $create_order->order_date = date('Y-m-d H:i');
        $create_order->allow_next_order = 0;
        $create_order->location_url_ot = "31.5815886,74.3779746";
        $create_order->ot_customer_distance = 0;
        $create_order->chk_ord_vst = $get_old_visit;
        $create_order->save();
           
                $orderDetails = new OrderDetail();
                $orderDetails->save();
    
    
      
        
    
}
