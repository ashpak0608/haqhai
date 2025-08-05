<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('state_master', function (Blueprint $table) {
            $table->id();
            $table->string('state_name');
            $table->unsignedBigInteger('country_id');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_dt')->useCurrent();
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('country_master');
        });
    }

    public function down()
    {
        Schema::dropIfExists('state_master');
    }
};
