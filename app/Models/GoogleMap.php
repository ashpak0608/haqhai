<?php
// app/Models/GoogleMap.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoogleMap extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'google_maps';
    
    protected $fillable = [
        'module_name',
        'module_id',
        'drawing_name',
        'drawing_data',
        'total_shapes',
        'total_markers',
        'total_areas',
        'map_image',
        'thumbnail',
        'center_lat',
        'center_lng',
        'zoom_level',
        'status',
        'is_default',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'drawing_data' => 'array',
        'center_lat' => 'decimal:8',
        'center_lng' => 'decimal:8',
        'zoom_level' => 'decimal:1',
        'total_shapes' => 'integer',
        'total_markers' => 'integer',
        'total_areas' => 'integer',
        'is_default' => 'boolean'
    ];

    protected $attributes = [
        'status' => 'active',
        'zoom_level' => 12.0
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForModule($query, $moduleName, $moduleId = null)
    {
        $query = $query->where('module_name', $moduleName);
        
        if ($moduleId) {
            $query = $query->where('module_id', $moduleId);
        }
        
        return $query;
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeWithCoordinates($query)
    {
        return $query->whereNotNull('center_lat')->whereNotNull('center_lng');
    }

    // Accessors & Mutators
    public function getImageUrlAttribute()
    {
        if ($this->map_image) {
            return asset('storage/map-images/' . $this->map_image);
        }
        return null;
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('storage/map-thumbnails/' . $this->thumbnail);
        }
        return $this->image_url;
    }

    public function getCenterAttribute()
    {
        if ($this->center_lat && $this->center_lng) {
            return [
                'lat' => (float)$this->center_lat,
                'lng' => (float)$this->center_lng
            ];
        }
        return null;
    }

    public function getDrawingStatsAttribute()
    {
        return [
            'total_shapes' => $this->total_shapes,
            'total_markers' => $this->total_markers,
            'total_areas' => $this->total_areas,
            'has_image' => !empty($this->map_image)
        ];
    }

    // Business Logic Methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function hasImage()
    {
        return !empty($this->map_image);
    }

    public function hasDrawingData()
    {
        return !empty($this->drawing_data) && is_array($this->drawing_data);
    }

    public function getDrawingSummary()
    {
        if (!$this->hasDrawingData()) {
            return 'No drawing data';
        }

        $types = [];
        if ($this->total_markers > 0) $types[] = "{$this->total_markers} markers";
        if ($this->total_areas > 0) $types[] = "{$this->total_areas} areas";
        if ($this->total_shapes > 0 && $this->total_shapes > ($this->total_markers + $this->total_areas)) {
            $types[] = "{$this->total_shapes} shapes";
        }

        return implode(', ', $types) ?: 'Empty drawing';
    }

    // Get boundary data for ward (polygon data)
    public function getBoundaryData()
    {
        if (!$this->hasDrawingData()) {
            return null;
        }

        $boundaryData = [];
        foreach ($this->drawing_data as $shape) {
            if (isset($shape['type']) && $shape['type'] === 'google.maps.Polygon' && isset($shape['path'])) {
                $boundaryData = $shape['path'];
                break;
            }
        }

        return $boundaryData;
    }

    // Check if this drawing contains a ward boundary
    public function hasWardBoundary()
    {
        if (!$this->hasDrawingData()) {
            return false;
        }

        foreach ($this->drawing_data as $shape) {
            if (isset($shape['type']) && $shape['type'] === 'google.maps.Polygon') {
                return true;
            }
        }

        return false;
    }

    // Static Methods
    public static function getModuleDrawings($moduleName, $moduleId, $activeOnly = true)
    {
        $query = self::forModule($moduleName, $moduleId);
        
        if ($activeOnly) {
            $query->active();
        }
        
        return $query->orderBy('is_default', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

    public static function getDefaultDrawing($moduleName, $moduleId)
    {
        return self::forModule($moduleName, $moduleId)
                    ->active()
                    ->default()
                    ->first();
    }

    public static function createDefaultDrawing($moduleName, $moduleId, $data)
    {
        // Remove any existing default
        self::forModule($moduleName, $moduleId)
            ->update(['is_default' => false]);

        // Create new default
        return self::create(array_merge($data, [
            'module_name' => $moduleName,
            'module_id' => $moduleId,
            'is_default' => true,
            'status' => 'active'
        ]));
    }

    // Get ward boundary drawing for a building
    public static function getWardBoundaryForBuilding($buildingId)
    {
        $building = \App\Models\Building::with('ward')->find($buildingId);
        
        if ($building && $building->ward) {
            return self::getDefaultDrawing('ward', $building->ward->id);
        }
        
        return null;
    }
}