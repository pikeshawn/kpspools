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
        Schema::create('scp_invoice', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable();
            $table->string('release_number')->nullable();
            $table->string('customer_po')->nullable();
            $table->string('status')->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('order_date')->nullable();
            $table->float('sales_order_amount')->nullable();
            $table->float('invoice_amount')->nullable();
            $table->float('merch_amount')->nullable();
            $table->float('tax_amount')->nullable();
            $table->float('freight')->nullable();
            $table->float('other')->nullable();
            $table->date('invoice_due_date')->nullable();
            $table->string('ship_to_number')->nullable();
            $table->string('ship_to_name')->nullable();
            $table->string('ship_to_address_1')->nullable();
            $table->string('ship_to_address_2')->nullable();
            $table->string('ship_to_address_3')->nullable();
            $table->string('ship_to_city')->nullable();
            $table->string('ship_to_state')->nullable();
            $table->string('ship_to_country')->nullable();
            $table->date('order_entry_date')->nullable();
            $table->string('ship_via')->nullable();
            $table->string('ar_term_code')->nullable();
            $table->integer('total_qty')->nullable();
            $table->float('total_weight')->nullable();
            $table->string('tracking_numbers')->nullable();
            $table->string('inv_status')->nullable();
            $table->float('inv_bal_due')->nullable();
            $table->string('preferred_carrier')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scp_invoice');
    }
};
