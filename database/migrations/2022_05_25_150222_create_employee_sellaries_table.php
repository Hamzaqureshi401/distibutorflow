<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeSellariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_sellaries', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->float('old_cash_remaining')->default('0');
            $table->float('cash_paid_added')->default('0');
            $table->float('current_cash_remaining')->default('0');
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
        Schema::dropIfExists('employee_sellaries');
    }
}
