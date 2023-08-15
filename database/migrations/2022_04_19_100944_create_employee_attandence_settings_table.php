<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAttandenceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_attandence_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->tinyInteger('user_active')->default(0);
            $table->dateTime('start_time')->nullable();
            $table->dateTime('over_time_start')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->string('location_cords', 200)->nullable(); // Define varchar(200)
            $table->dateTime('over_time_end')->nullable();
            $table->float('distance_measure')->default(0);
            $table->float('per_minute_sellary')->default(0);
            $table->float('over_time_per_minute_sellary')->default(0);
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
        Schema::dropIfExists('employee_attandence_settings');
    }
}
