<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerProductsStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_products_stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('transection_id');
            $table->integer('stock_adder_user_id')->nullable();
            $table->integer('product_id');
            $table->integer('old_stock');
            $table->integer('remaining_stock');
            $table->integer('stock_added')->nullable();
            $table->integer('sell_added')->nullable();
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
        Schema::dropIfExists('customer_products_stocks');
    }
}
