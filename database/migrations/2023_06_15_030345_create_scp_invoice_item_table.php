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
        Schema::create('scp_invoice_item', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->nullable();
            $table->string('units_sold')->nullable();
            $table->string('product_number')->nullable();
            $table->string('model_num')->nullable();
            $table->string('description')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('notes')->nullable();
            $table->string('uom')->nullable();
            $table->string('cost')->nullable();
            $table->string('ext_cost')->nullable();
            $table->string('ord_num')->nullable();
            $table->string('line_item_num')->nullable();
            $table->string('bo_qty')->nullable();
            $table->string('ship_qty')->nullable();
            $table->string('line_item_weight_ext')->nullable();
            $table->string('ship_whse_num')->nullable();
            $table->string('line_ship_via')->nullable();
            $table->string('gl_code')->nullable();
            $table->string('serial_nums')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scp_invoice_item');
    }
};
