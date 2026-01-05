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
        Schema::create('ward_drawings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ward_id');
            $table->text('drawings_data')->nullable();
            $table->integer('total_shapes')->default(0);
            $table->string('drawing_name')->nullable();
            $table->string('map_image')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            // Add index for better performance
            $table->index('ward_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ward_drawings');
    }
};