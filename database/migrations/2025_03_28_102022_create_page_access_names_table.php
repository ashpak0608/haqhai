<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('page_access_names', function (Blueprint $table) {
            $table->id();
            $table->string('page_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('page_access_names');
    }
};
