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
        Schema::create('valuations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apn_id')->constrained('str')->onDelete('cascade'); // Foreign key reference to the str table
            $table->string('tax_year');
            $table->decimal('full_cash_value', 15, 2)->nullable();
            $table->decimal('limited_property_value', 15, 2)->nullable();
            $table->string('legal_classification_code')->nullable();
            $table->string('legal_classification')->nullable();
            $table->decimal('assessment_ratio_percentage', 5, 4)->nullable();
            $table->string('assessed_fcv')->nullable(); // Could be 'na', keeping as string
            $table->string('assessed_lpv')->nullable();
            $table->string('valuation_source')->nullable();
            $table->string('pe_prop_use_desc')->nullable();
            $table->string('property_use_code')->nullable();
            $table->string('tax_area_code')->nullable();
            $table->string('notice_of_change_indc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valuations');
    }
};
