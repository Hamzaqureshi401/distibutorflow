<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\User;
use App\Models\ProfitMail;
use App\Models\Product;
use Auth;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;



class SendProfitInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'command:name';

    protected $signature = 'cron:send-profit-info';

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
     
     $get_user = User::where('email_subscription' , 1)->pluck('id')->toArray();
     //dd($get_user);
 
     foreach($get_user as $user){
         
         $this_suser = User::where('id' , $user)->first();
         $email = $this_suser->email;
         $password = $this_suser->password;
         
         $get_customer = Customer::where('user_id' , $user)->first();
         $user_inv_link = $get_customer->id;
         $suggestion = $get_customer->suggestion_in_email;
         $get_profit_mail = ProfitMail::where('user_id' , $user)->orderBy('id' , 'desc')->first();
         $old_profit = $get_profit_mail->profit;
         $get_user_invoices = Invoice::where('customer_id' , $get_customer->id)->where('emailed' , NULL); // set date
         //$get_user_invoices = Invoice::where('customer_id' , $get_customer->id); // set date
        
             $get_invoices_id        = (clone $get_user_invoices)->pluck('id')->toArray();
             $get_invoices_data      = (clone $get_user_invoices)->get();
             $get_invoices_subtotal  = (clone $get_user_invoices)->sum('subtotal');
             $get_invoices_total     = (clone $get_user_invoices)->sum('amount');
             $get_invoices_receiving = (clone $get_user_invoices)->sum('received_amount');
             $get_invoices_balance   = (clone $get_user_invoices)->orderBy('id' , 'desc')->pluck('amount_left')->first();
             $total_bill             = count($get_invoices_id);
             $first_bill             = (clone $get_user_invoices)->pluck('created_at')->first();
             $last_bill              = (clone $get_user_invoices)->orderBy('id' , 'desc')->pluck('created_at')->first();
             //dd($last_bill , $first_bill);
             foreach($get_invoices_id as $SetEmild){
                 $SetEmild = Invoice::where('id' , $SetEmild)->update(['emailed' => 1]);
                 //$SetEmild = Invoice::where('id' , $SetEmild)->update(['emailed' => NULL]);
             }
             $now = \Carbon\Carbon::now()->toDateString();
             $a = strtotime($first_bill);
             $b = strtotime($now);
             $days_between = ceil(($b - $a) / 86400);
             if ($get_invoices_id != [] && $days_between >= 30){
            
             //dd($days_between);

             // get invoice detail calculation
             
             $inv_detail   = InvoiceDetail::whereIn('invoice_id' , $get_invoices_id);
             $get_p_ids    = $inv_detail->pluck('product_id')->toArray();
             $unique_p_ids = array_unique($get_p_ids);
             
            //  foreach($unique_p_ids as $p_id){
            //     $unit[] = (clone $inv_detail)->where('product_id' , $p_id)->sum('unit');
            //  }
             
             
            $products = Product::whereIn('id', $unique_p_ids)->get();
            $table = [];
            $counter = 0;
            foreach ($products as $p) {
            $ppunit = 0;
            $subtotal = 0;
            $table[$counter]['id'] = $p->id;
            $table[$counter]['name'] = $p->name;
            foreach ($get_invoices_data as $in) {
                $idet =  $in->invoicedetail->where('product_id', $p->id);
                $ppunit += $idet->sum('unit');
                $subtotal += $idet->sum('amount');
            }
            $table[$counter]['sellprice'] = round($p->productsellprice($get_customer->id , $p->id), 1);
            $table[$counter]['unit'] = round($ppunit, 1);
            $table[$counter]['subtotal'] = round($subtotal, 1);
            $table[$counter]['price'] =  round(($subtotal / $ppunit) , 1);
            $table[$counter]['profit'] =  round(($p->productsellprice($get_customer->id , $p->id) - ($subtotal / $ppunit)) * $ppunit, 1);
            $table[$counter]['t_sell'] =  round(($p->productsellprice($get_customer->id , $p->id)) * $ppunit, 1);
            $counter++;
        }
             $sumArray = array();
              foreach ($table as $k=>$subArray) {
              foreach ($subArray as $id=>$value) {
                $sumArray+=$value;
              }
            }
            //dd(1);
            $user_info = array(
            'sum_profit' => $sumArray['profit'],
            'sum_subtotal' => $sumArray['subtotal'],
            'sum_sell' => $sumArray['t_sell'],
            'no_of_days' => $days_between,
            'total_bill' => $total_bill,
            'first_bill' => $first_bill->format('m/d/Y'),
            'name' => $get_customer->user->name,
            'current_date' => $now,
            'email' => $email,
            'password' => $password,
            'user_inv_link' => $user_inv_link,
            'over_all_profit' => $old_profit + $sumArray['profit'],
            'suggestion' => $suggestion
            );    
            if ($sumArray['profit'] > 5000){
            $blade = "profit-mail";
            Mail::to($email)->send(new SendMail($table = null ,$user_info , $blade));
            Mail::to('mail.scoopscreamery@gmail.com')->send(new SendMail($table = null ,$user_info , $blade));
           
            $save_profit_mail = new ProfitMail();
            $save_profit_mail->user_id = $user;
            $save_profit_mail->subtotal = $sumArray['subtotal'];
            $save_profit_mail->received_amount = $get_invoices_receiving;
            $save_profit_mail->amount_left = $get_invoices_balance;
            $save_profit_mail->profit = $sumArray['profit'] + $old_profit;
            $save_profit_mail->comments  = $suggestion;
            $save_profit_mail->save();
            }
     
    }
   
        
    
}
// dd($table , $user_info , $sumArray);
}
}
