<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('district_master', function (Blueprint $table) {
            $table->id();
            $table->string('district_name');
            $table->unsignedBigInteger('state_id');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_dt')->useCurrent();
            $table->timestamps();

            $table->foreign('state_id')->references('id')->on('state_master');
        });
    }

    public function down()
    {
        Schema::dropIfExists('district_master');
    }
};
