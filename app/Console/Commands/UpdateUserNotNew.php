<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

use App\Models\Customer;

use Illuminate\Support\Facades\Artisan;


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
       
        $dayAgo = 2; // Days ago
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
        //Artisan::call('cron:create-automatic-order'); 
    }
}
