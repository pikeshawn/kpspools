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
        Schema::create('str', function (Blueprint $table) {
            $table->id();
            $table->string('mcr')->nullable();
            $table->string('subdivision_name')->nullable();
            $table->string('section_township_range')->nullable();
            $table->string('ownership')->nullable();
            $table->string('apn')->nullable();
            $table->string('situs_address')->nullable();
            $table->string('situs_city')->nullable();
            $table->string('situs_zip')->nullable();
            $table->string('property_type')->nullable();
            $table->string('rental_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('str');
    }
};
