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
        Schema::create('property_type_plan_mapping', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_type_id');
            $table->unsignedBigInteger('plan_id');
            $table->integer('no_of_properties');
            $table->timestamps();
            
            $table->foreign('property_type_id')->references('id')->on('property_type_master');
            $table->foreign('plan_id')->references('id')->on('finance_plans');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_type_plan_mapping');
    }
};
