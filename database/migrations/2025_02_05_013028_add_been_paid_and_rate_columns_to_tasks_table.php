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
        Schema::table('tasks', function (Blueprint $table) {
            //
            $table->boolean('been_paid')->default(false);
            $table->integer('quantity')->default(0);
            $table->float('rate')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            //
            $table->boolean('been_paid')->default(false);
            $table->float('rate')->default(0);
            $table->integer('quantity')->default(0);
        });
    }
};
