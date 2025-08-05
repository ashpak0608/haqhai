<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('login_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->timestamp('login_date_time')->useCurrent();
            $table->string('ip_address');
            $table->string('device_details')->comment('Web/Mob/iPAD');
            $table->string('browser_details');
            $table->timestamps();
    
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('login_history');
    }
};
