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
        Schema::table('service_stops', function (Blueprint $table) {
            //
            $table->integer('super_black_algaecide')->nullable();
            $table->integer('no_phos')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_stops', function (Blueprint $table) {
            //
            $table->dropColumn('super_black_algaecide', 'no_phos');
        });
    }
};
