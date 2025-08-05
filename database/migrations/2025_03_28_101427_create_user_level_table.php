<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_level', function (Blueprint $table) {
            $table->id();
            $table->string('level_name')->comment('Entry, Silver, Gold, Platinum');
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_dt')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_level');
    }
};
