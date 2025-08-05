<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('customer_category', function (Blueprint $table) {
            $table->id();
            $table->string('category_name')->comment('Broker, Builder, Custom');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by');
            $table->integer('password_expirydays');
            $table->timestamp('created_dt')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_category');
    }
};
