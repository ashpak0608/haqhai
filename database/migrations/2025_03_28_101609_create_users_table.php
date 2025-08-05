<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('email_id')->unique();
            $table->string('phone1');
            $table->string('password');
            $table->unsignedBigInteger('level_id');
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->date('dob')->nullable();
            $table->date('doa')->nullable();
            $table->date('last_passChanged_dt')->nullable();
            $table->string('status')->default('active');
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_dt')->useCurrent();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_dt')->nullable();
            $table->timestamps();
    
            $table->foreign('level_id')->references('id')->on('user_level');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
