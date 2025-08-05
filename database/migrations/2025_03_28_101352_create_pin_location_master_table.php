<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pin_location_master', function (Blueprint $table) {
            $table->id();
            $table->string('location_name');
            $table->string('pincode');
            $table->unsignedBigInteger('district_id');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_dt')->useCurrent();
            $table->timestamps();

            $table->foreign('district_id')->references('id')->on('district_master');
            $table->unique(['location_name', 'pincode']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pin_location_master');
    }
};
