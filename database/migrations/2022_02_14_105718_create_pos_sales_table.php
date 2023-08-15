<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_sales', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('sell_creater_id')->nullable();
            $table->float('subtotal');
            $table->float('received_amount');
            $table->float('amount_left');
            $table->float('discount');
            $table->string('comments')->nullable();
            $table->integer('is_confirmed_manager')->nullable();
            $table->integer('is_confirmed_admin')->nullable();
            $table->date('approve_date');
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
        Schema::dropIfExists('pos_sales');
    }
}
