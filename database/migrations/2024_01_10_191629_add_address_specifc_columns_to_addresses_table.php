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
        Schema::table('addresses', function (Blueprint $table) {
            //
            $table->boolean('active')->nullable();
            $table->string('type')->nullable();
            $table->string('plan')->nullable();
            $table->string('plan_duration')->nullable();
            $table->float('plan_price')->nullable();
            $table->tinyInteger('chemicals_included')->nullable();
            $table->string('assigned_serviceman')->nullable();
            $table->string('terms')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            //
            $table->boolean('active')->nullable();
            $table->string('type')->nullable();
            $table->string('plan')->nullable();
            $table->string('plan_duration')->nullable();
            $table->float('plan_price')->nullable();
            $table->tinyInteger('chemicals_included')->nullable();
            $table->string('assigned_serviceman')->nullable();
            $table->string('terms')->nullable();
        });
    }
};
