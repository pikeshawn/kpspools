<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        //        DB::statement('
        //            DELETE t1 FROM service_stops t1
        //            INNER JOIN service_stops t2
        //            WHERE t1.id > t2.id
        //              AND t1.user_id = t2.user_id
        //              AND t1.customer_id = t2.customer_id
        //              AND t1.address_id = t2.address_id
        //              AND t1.time_in = t2.time_in
        //              AND t1.time_out = t2.time_out
        //              AND t1.service_type = t2.service_type
        //            ');

        Schema::table('service_stops', function (Blueprint $table) {

            $table->unique([
                'user_id',
                'customer_id',
                'address_id',
                'service_type',
                'time_in',
                'time_out'], 'unique_service_stop');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_stops', function (Blueprint $table) {
            //
            $table->dropUnique('unique_service_stop');
        });
    }
};
