<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_stops', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('customer_id');
            $table->integer('address_id');
            $table->float('ph_level');
            $table->float('chlorine_level');
            $table->float('tabs_whole_mine')->default(0);
            $table->float('tabs_crushed_mine')->default(0);
            $table->float('tabs_whole_theirs')->default(0);
            $table->float('tabs_crushed_theirs')->default(0);
            $table->float('liquid_chlorine')->default(0);
            $table->float('liquid_acid')->default(0);
            $table->dateTime('time_in');
            $table->dateTime('time_out');
            $table->time('service_time')->default('00:00:00');
            $table->boolean('vacuum')->default(false);
            $table->boolean('brush')->default(true);
            $table->boolean('empty_baskets')->default(true);
            $table->timestamps();
            $table->boolean('backwash')->default(true);
            $table->float('powder_chlorine');
            $table->longText('notes')->nullable();
            $table->string('service_type');
            $table->string('salt_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_stops');
    }
};
