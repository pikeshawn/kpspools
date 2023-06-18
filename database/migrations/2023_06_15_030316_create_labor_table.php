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
        Schema::create('labor', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->nullable();
            $table->float('amount')->nullable();
            $table->date('date')->nullable();
            $table->string('type')->nullable();
            $table->integer('check_number')->nullable();
            $table->integer('account')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labor');
    }
};
