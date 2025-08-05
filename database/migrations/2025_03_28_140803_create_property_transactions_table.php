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
        Schema::create('property_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('user_id');
            $table->string('transaction_no');
            $table->string('payment_mode');
            $table->decimal('trx_amount', 12, 2);
            $table->timestamp('txn_dt')->useCurrent();
            $table->string('coupon_code')->nullable();
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->string('payment_status')->comment('success, failed');
            $table->timestamps();
            
            $table->foreign('property_id')->references('id')->on('property_listing');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('plan_id')->references('id')->on('finance_plans');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_transactions');
    }
};
