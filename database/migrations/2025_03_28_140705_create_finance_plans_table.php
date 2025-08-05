<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('finance_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name');
            $table->text('plan_description');
            $table->decimal('plan_amount', 10, 2);
            $table->integer('validity_days');
            $table->string('applicable_user_type');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_plans');
    }
};
