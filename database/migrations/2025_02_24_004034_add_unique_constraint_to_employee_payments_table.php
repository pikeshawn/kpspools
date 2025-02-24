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
        Schema::table('employee_payments', function (Blueprint $table) {
            //
            $table->unique(['serviceman_id', 'service_stop_id'], 'unique_serviceman_service_stop')
                ->whereNotNull('service_stop_id');

            $table->unique(['serviceman_id', 'task_id'], 'unique_serviceman_task')
                ->whereNotNull('task_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_payments', function (Blueprint $table) {
            //
            $table->dropUnique('unique_serviceman_service_stop');
            $table->dropUnique('unique_serviceman_task');
        });
    }
};
