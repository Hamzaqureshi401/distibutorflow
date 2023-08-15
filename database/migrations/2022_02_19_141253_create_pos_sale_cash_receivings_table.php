<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosSaleCashReceivingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_sale_cash_receivings', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id'); // step 1 customer_id lo 
            $table->integer('processor_id')->nullable();
            $table->longtext('pos_sell_ids')->nullable();
            $table->float('old_cash_remaining')->nullable();
            $table->float('cash_paid_added')->nullable();
            $table->float('current_cash_remaining')->nullable();
            $table->float('discounts')->nullable();
            $table->float('expenses')->nullable();
            $table->float('unconfirmed_expences')->nullable();
            $table->float('outside_payments')->nullable();
            $table->string('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pos_sale_cash_receivings');
    }
}
