<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_stops', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('address_id');
            $table->float('ph_level');
            $table->float('chlorine_level');
            $table->float('powder_chlorine');
            $table->float('tabs_whole_mine')->default(0);
            $table->float('tabs_crushed_mine')->default(0);
            $table->float('tabs_whole_thiers')->default(0);
            $table->float('tabs_crushed_thiers')->default(0);
            $table->float('liquid_chlorine')->default(0);
            $table->float('liquid_acid')->default(0);
            $table->dateTime('time_in');
            $table->dateTime('time_out');
            $table->time('service_time')->default('00:00:00');
            $table->boolean('vacuum')->default(false);
            $table->boolean('brush')->default(true);
            $table->boolean('backwash')->default(true);
            $table->longText('notes')->nullable();
            $table->boolean('empty_baskets')->default(true);
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
        Schema::dropIfExists('service_stops');
    }
}
