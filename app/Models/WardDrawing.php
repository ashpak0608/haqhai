<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WardDrawing extends Model
{
    use HasFactory;

    protected $table = 'ward_drawings';
    
    protected $fillable = [
        'ward_id',
        'drawings_data',
        'total_shapes',
        'drawing_name',
        'map_image',
        'created_by'
    ];

    // Get full image URL
    public function getImageUrl()
    {
        if ($this->map_image) {
            return asset('storage/map-images/' . $this->map_image);
        }
        return null;
    }

    // Check if image exists
    public function hasImage()
    {
        return !empty($this->map_image);
    }
}