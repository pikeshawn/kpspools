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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->boolean('active')->nullable();
            $table->timestamps();
            $table->string('type')->nullable();
            $table->string('plan')->nullable();
            $table->string('service_day')->nullable();
            $table->integer('order')->nullable();
            $table->string('plan_duration')->nullable();
            $table->float('plan_price')->nullable();
            $table->tinyInteger('chemicals_included')->nullable();
            $table->string('assigned_serviceman')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('terms')->nullable();
            $table->integer('jemmson_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
