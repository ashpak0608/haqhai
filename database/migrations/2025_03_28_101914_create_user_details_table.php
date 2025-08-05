<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('aadhar_no')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('phone2')->nullable();
            $table->text('address')->nullable();
            $table->string('pincode')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('landmark')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_dt')->useCurrent();
            $table->timestamps();
    
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('city_id')->references('id')->on('pin_location_master');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_details');
    }
};
