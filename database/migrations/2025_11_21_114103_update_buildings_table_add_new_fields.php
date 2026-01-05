<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
            // Remove building_number
            $table->dropColumn('building_number');
            
            // Add new fields
            $table->unsignedBigInteger('state_id')->nullable()->after('id');
            $table->unsignedBigInteger('ward_id')->nullable()->after('city_id');
            $table->decimal('latitude', 10, 8)->nullable()->after('ward_id');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            
            // Add foreign key constraints
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('ward_id')->references('id')->on('wards')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->string('building_number')->nullable();
            
            $table->dropForeign(['state_id']);
            $table->dropForeign(['ward_id']);
            
            $table->dropColumn(['state_id', 'ward_id', 'latitude', 'longitude']);
        });
    }
};