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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->boolean('terms_and_conditions')->default(false);
            $table->boolean('privacy_policy')->default(false);
            $table->dateTime('terms_accepted_date')->nullable();
            $table->dateTime('privacy_policy_accepted_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->boolean('terms_and_conditions')->default(false);
            $table->boolean('privacy_policy')->default(false);
            $table->dateTime('terms_accepted_date')->nullable();
            $table->dateTime('privacy_policy_accepted_date')->nullable();
        });
    }
};
