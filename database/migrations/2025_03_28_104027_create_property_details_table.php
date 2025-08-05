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
        Schema::create('property_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('bedrooms');
            $table->unsignedBigInteger('property_area');
            $table->integer('total_floors');
            $table->integer('property_floor_no');
            $table->boolean('lifts')->default(false);
            $table->decimal('property_price', 12, 2);
            $table->decimal('registration_cost', 12, 2)->nullable();
            $table->decimal('booking_amount', 12, 2)->nullable();
            $table->boolean('existing_bank_loan')->default(false);
            $table->boolean('loan_possible')->default(false);
            $table->timestamps();
            
            $table->foreign('property_id')->references('id')->on('property_listing');
            $table->foreign('bedrooms')->references('id')->on('bedrooms_master');
            $table->foreign('property_area')->references('id')->on('property_area_unit_master');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_details');
    }
};
