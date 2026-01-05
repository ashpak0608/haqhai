<?php
// database/migrations/2024_01_01_000000_create_google_maps_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleMapsTable extends Migration
{
    public function up()
    {
        Schema::create('google_maps', function (Blueprint $table) {
            $table->id();
            
            // Module identification
            $table->string('module_name', 50); // e.g., 'ward', 'building', 'area', 'location'
            $table->unsignedBigInteger('module_id'); // Foreign key to the specific module record
            
            // Drawing information
            $table->string('drawing_name', 255);
            $table->text('drawing_data')->nullable(); // JSON data of shapes, markers, paths
            $table->integer('total_shapes')->default(0);
            $table->integer('total_markers')->default(0);
            $table->integer('total_areas')->default(0);
            
            // Visual representation
            $table->string('map_image')->nullable(); // Saved map snapshot path
            $table->string('thumbnail')->nullable(); // Smaller version for listings
            
            // Location context
            $table->decimal('center_lat', 10, 8)->nullable(); // Map center latitude
            $table->decimal('center_lng', 11, 8)->nullable(); // Map center longitude
            $table->decimal('zoom_level', 3, 1)->default(12.0); // Map zoom level
            
            // Status and metadata
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->boolean('is_default')->default(false); // Default drawing for module
            
            // Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better performance
            $table->index(['module_name', 'module_id']);
            $table->index(['module_name', 'module_id', 'is_default']);
            $table->index('created_by');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('google_maps');
    }
}