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
        Schema::create('owners', function (Blueprint $table) {
            $table->id();
            $table->string('apn');
            $table->uuid('owner_id')->nullable(); // OwnerID stored as UUID
            $table->string('ownership')->nullable();
            $table->string('in_care_of')->nullable();
            $table->string('conto_ownership')->nullable();
            $table->string('mailing_address_1')->nullable();
            $table->string('mailing_address_2')->nullable();
            $table->string('mailing_city')->nullable();
            $table->string('mailing_state')->nullable();
            $table->string('mailing_zip')->nullable();
            $table->string('mailing_country')->nullable();
            $table->string('most_current_deed')->nullable();
            $table->date('deed_date')->nullable();
            $table->string('deed_type')->nullable();
            $table->decimal('sale_price', 15, 2)->nullable(); // Storing sale price with decimals
            $table->date('sale_date')->nullable();
            $table->boolean('redacted')->default(false);
            $table->text('full_mailing_address')->nullable();
            $table->string('deed_type_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};
