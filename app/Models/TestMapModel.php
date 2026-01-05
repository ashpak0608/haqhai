<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TestMapModel extends Model
{
    use HasFactory;

    /**
     * Get all markers within a specified radius
     */
    public function getAllMarkers($lat, $lon, $radius = 10)
    {
        try {
            // Example implementation - adjust based on your needs
            $markers = DB::table('markers')
                ->select('*')
                ->whereRaw("
                    (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                    cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
                    sin(radians(latitude)))) < ?
                ", [$lat, $lon, $lat, $radius])
                ->get();

            return $markers->toArray();
            
        } catch (\Exception $e) {
            Log::error('Error fetching markers: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get detailed information for a specific marker
     */
    public function getMarkerInfo($lat, $lon)
    {
        try {
            // Example implementation - adjust based on your needs
            $markerInfo = DB::table('markers')
                ->where('latitude', $lat)
                ->where('longitude', $lon)
                ->first();

            return $markerInfo ? (array)$markerInfo : [];
            
        } catch (\Exception $e) {
            Log::error('Error fetching marker info: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Save ward drawings with optional image
     */
   public function saveWardDrawings($wardId, $drawings, $total_shapes, $drawingName = null, $imageData = null)
{
    try {
        $imagePath = null;
        
        // Save image if provided
        if ($imageData) {
            $imageName = 'map_' . time() . '_' . uniqid() . '.png';
            $imagePath = 'map-images/' . $imageName;
            
            // Decode base64 image and save
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageBinary = base64_decode($imageData);
            
            // Save to storage
            \Storage::disk('public')->put($imagePath, $imageBinary);
        }

        $data = [
            'ward_id' => $wardId,
            'drawings_data' => json_encode($drawings),
            'total_shapes' => $total_shapes,
            'drawing_name' => $drawingName,
            'map_image' => $imagePath ? basename($imagePath) : null,
            'image_data' => $imageData ? null : json_encode($drawings), // Store drawing data as fallback
            'created_by' => auth()->id() ?? null,
            'created_at' => now(),
            'updated_at' => now()
        ];

        // Use DB facade for direct insertion
        $drawing_id = DB::table('ward_drawings')->insertGetId($data);

        if ($drawing_id) {
            return [
                'success' => true, 
                'drawing_id' => $drawing_id, 
                'image_path' => $imagePath,
                'message' => 'Drawings saved successfully!'
            ];
        } else {
            return [
                'success' => false, 
                'message' => 'Failed to save drawings.'
            ];
        }
        
    } catch (\Exception $e) {
        \Log::error('Error saving ward drawings: ' . $e->getMessage());
        return [
            'success' => false, 
            'message' => 'Database error: ' . $e->getMessage()
        ];
    }
}

    /**
     * Get ward drawings by ward ID
     */
    public function getWardDrawings($wardId)
    {
        try {
            $drawings = DB::table('ward_drawings')
                ->where('ward_id', $wardId)
                ->orderBy('created_at', 'desc')
                ->get();

            return $drawings->map(function ($drawing) {
                return [
                    'id' => $drawing->id,
                    'drawing_name' => $drawing->drawing_name,
                    'total_shapes' => $drawing->total_shapes,
                    'map_image' => $drawing->map_image,
                    'created_at' => $drawing->created_at,
                    'created_by' => $drawing->created_by
                ];
            })->toArray();
            
        } catch (\Exception $e) {
            Log::error('Error fetching ward drawings: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get drawing details by ID
     */
    public function getDrawingById($drawingId)
    {
        try {
            $drawing = DB::table('ward_drawings')
                ->where('id', $drawingId)
                ->first();

            if ($drawing) {
                return [
                    'success' => true,
                    'drawing' => [
                        'id' => $drawing->id,
                        'ward_id' => $drawing->ward_id,
                        'drawings_data' => json_decode($drawing->drawings_data, true),
                        'drawing_name' => $drawing->drawing_name,
                        'total_shapes' => $drawing->total_shapes,
                        'map_image' => $drawing->map_image,
                        'image_data' => $drawing->image_data ? json_decode($drawing->image_data, true) : null,
                        'created_at' => $drawing->created_at,
                        'created_by' => $drawing->created_by
                    ]
                ];
            }

            return [
                'success' => false,
                'message' => 'Drawing not found'
            ];
            
        } catch (\Exception $e) {
            Log::error('Error fetching drawing: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error fetching drawing: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete ward drawing
     */
    public function deleteDrawing($drawingId)
    {
        try {
            // Get drawing first to delete associated image
            $drawing = DB::table('ward_drawings')->where('id', $drawingId)->first();
            
            if ($drawing && $drawing->map_image) {
                // Delete the associated image file
                Storage::disk('public')->delete('map-images/' . $drawing->map_image);
            }

            $deleted = DB::table('ward_drawings')->where('id', $drawingId)->delete();

            return [
                'success' => $deleted,
                'message' => $deleted ? 'Drawing deleted successfully!' : 'Drawing not found'
            ];
            
        } catch (\Exception $e) {
            Log::error('Error deleting drawing: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error deleting drawing: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Save base64 image to storage
     */
    private function saveBase64Image($base64Data)
    {
        try {
            $imageName = 'map_' . time() . '_' . uniqid() . '.png';
            $imagePath = 'map-images/' . $imageName;
            
            // Decode base64 image and save
            $imageData = str_replace('data:image/png;base64,', '', $base64Data);
            $imageData = str_replace(' ', '+', $imageData);
            $imageBinary = base64_decode($imageData);
            
            // Save to storage
            Storage::disk('public')->put($imagePath, $imageBinary);
            
            return $imagePath;
            
        } catch (\Exception $e) {
            Log::error('Error saving base64 image: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Validate base64 image string
     */
    private function isValidBase64Image($base64)
    {
        if (empty($base64)) {
            return false;
        }
        
        // Check if it's a valid base64 image string
        if (preg_match('/^data:image\/(png|jpeg|jpg|gif);base64,/', $base64)) {
            // Verify base64 decoding works
            $base64Data = str_replace(['data:image/png;base64,', 'data:image/jpeg;base64,', 'data:image/jpg;base64,', 'data:image/gif;base64,'], '', $base64);
            $base64Data = str_replace(' ', '+', $base64Data);
            
            if (base64_decode($base64Data, true) === false) {
                return false;
            }
            
            return true;
        }
        
        return false;
    }

    /**
     * Update drawing name
     */
    public function updateDrawingName($drawingId, $drawingName)
    {
        try {
            $updated = DB::table('ward_drawings')
                ->where('id', $drawingId)
                ->update([
                    'drawing_name' => $drawingName,
                    'updated_at' => now()
                ]);

            return [
                'success' => (bool)$updated,
                'message' => $updated ? 'Drawing name updated successfully!' : 'Drawing not found'
            ];
            
        } catch (\Exception $e) {
            Log::error('Error updating drawing name: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error updating drawing name: ' . $e->getMessage()
            ];
        }
    }
}