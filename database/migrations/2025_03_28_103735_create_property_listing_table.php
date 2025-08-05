<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('property_listing', function (Blueprint $table) {
            $table->id();
            $table->text('property_description');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('property_type_id');
            $table->unsignedBigInteger('property_cat_id');
            $table->text('property_address');
            $table->string('developer_name')->nullable();
            $table->string('project_name')->nullable();
            $table->string('pincode');
            $table->string('property_status');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('country_id');
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_dt')->useCurrent();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_dt')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('property_type_id')->references('id')->on('property_type_master');
            $table->foreign('property_cat_id')->references('id')->on('property_category_master');
            $table->foreign('city_id')->references('id')->on('pin_location_master');
            $table->foreign('state_id')->references('id')->on('state_master');
            $table->foreign('country_id')->references('id')->on('country_master');
        });
    }

    public function down()
    {
        Schema::dropIfExists('property_listing');
    }
};
