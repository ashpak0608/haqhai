<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {

        Schema::create('developer_project_master', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('developer_id');
            $table->string('project_name');
            $table->string('location');
            $table->string('pincode');
            $table->timestamps();
            
            $table->foreign('developer_id')->references('id')->on('developer_master');
        });
    }

    public function down()
    {
        Schema::dropIfExists('developer_project_master');
    }
};
