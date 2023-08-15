<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAttandenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_attandence', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->tinyInteger('user_active')->default('0');
            $table->tinyInteger('failed_attemptes')->nullable();
            $table->longText('images')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->dateTime('shift_will_end')->nullable();
            $table->float('distance_measure')->default('0');
            $table->string('cords')->default('0');
            $table->float('minutes_served')->default('0');
            $table->float('over_time_served')->default('0');
            $table->float('per_minute_sellary')->default('0');
            $table->float('over_time_per_minute_sellary')->default('0');
            
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
        Schema::dropIfExists('employee_attandence');
    }
}
