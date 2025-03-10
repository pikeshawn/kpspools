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
        Schema::create('residential_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apn_id')->constrained('str')->onDelete('cascade'); // Foreign key reference to the str table
            $table->string('construction_year')->nullable();
            $table->string('original_construction_year')->nullable();
            $table->string('lot_size')->nullable();
            $table->string('livable_space')->nullable();
            $table->string('detached_livable_sqft')->nullable();
            $table->string('assessor_market')->nullable();
            $table->string('area_neighborhood')->nullable();
            $table->string('pool')->nullable();
            $table->string('pe_improvement_quality_grade')->nullable();
            $table->string('improvement_quality_grade')->nullable();
            $table->string('number_of_covered_patios')->nullable();
            $table->string('number_of_uncovered_patios')->nullable();
            $table->string('number_of_patios')->nullable();
            $table->string('patio_type')->nullable();
            $table->string('exterior_walls')->nullable();
            $table->string('roof_type')->nullable();
            $table->string('bath_fixtures')->nullable();
            $table->string('cooling')->nullable();
            $table->string('heating')->nullable();
            $table->string('number_of_garages')->nullable();
            $table->string('number_of_carports')->nullable();
            $table->json('parking_details')->nullable();
            $table->string('locational_factors')->nullable();
            $table->string('other_structures')->nullable();
            $table->string('parking_type')->nullable();
            $table->boolean('covered_parking')->nullable();
            $table->string('locational_characteristics')->nullable();
            $table->string('physical_condition')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residential_details');
    }
};
