<?php
// app/Http/Controllers/GoogleMapController.php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use Illuminate\Http\Request;
use App\Models\GoogleMap;
use App\Models\WardModel;
use App\Models\BuildingModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GoogleMapController extends Controller
{
    /**
     * Display Google Map for any module with hierarchical context
     */
    public function showMap($moduleName, $moduleId, $moduleData = [])
    {
        try {
            Log::info("Google Map accessed with hierarchical context", [
                'module' => $moduleName,
                'module_id' => $moduleId,
                'user_id' => auth()->id()
            ]);

            // Get context-based coordinates and data
            $contextData = $this->getMapContext($moduleName, $moduleId, $moduleData);
            
            // Get existing drawings for this module
            $drawings = GoogleMap::getModuleDrawings($moduleName, $moduleId);
            
            // Get default drawing if exists
            $defaultDrawing = GoogleMap::getDefaultDrawing($moduleName, $moduleId);

            // Get ward boundary if building module
            $wardBoundary = null;
            $wardDefaultDrawing = null;
            
            if ($moduleName === 'building') {
                $building = BuildingModel::with('ward')->find($moduleId);
                if ($building && $building->ward) {
                    $wardDefaultDrawing = GoogleMap::getDefaultDrawing('ward', $building->ward->id);
                    if ($wardDefaultDrawing) {
                        $wardBoundary = $wardDefaultDrawing->drawing_data;
                    }
                }
            }

            return view('google-map.map', array_merge($contextData, [
                'drawings' => $drawings,
                'defaultDrawing' => $defaultDrawing,
                'wardBoundary' => $wardBoundary,
                'wardDefaultDrawing' => $wardDefaultDrawing
            ]));
            
        } catch (\Exception $e) {
            Log::error('Error loading Google Map with context:', [
                'module' => $moduleName,
                'module_id' => $moduleId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 
                'Unable to load Google Map. Please try again later.'
            );
        }
    }

    /**
     * Get context-based map data
     */
    private function getMapContext($moduleName, $moduleId, $moduleData = [])
    {
        $defaultLat = '19.0760'; // Mumbai
        $defaultLng = '72.8777';
        $defaultZoom = 12;

        switch ($moduleName) {
            case 'ward':
                return $this->getWardContext($moduleId, $moduleData, $defaultLat, $defaultLng, $defaultZoom);
                
            case 'building':
                return $this->getBuildingContext($moduleId, $moduleData, $defaultLat, $defaultLng, $defaultZoom);
                
            default:
                return [
                    'moduleName' => $moduleName,
                    'moduleId' => $moduleId,
                    'moduleTitle' => $moduleData['title'] ?? ucfirst($moduleName),
                    'moduleIdentifier' => $moduleData['identifier'] ?? 'N/A',
                    'lat' => $moduleData['latitude'] ?? $defaultLat,
                    'lng' => $moduleData['longitude'] ?? $defaultLng,
                    'zoom' => $moduleData['zoom_level'] ?? $defaultZoom,
                ];
        }
    }

    /**
     * Get ward-specific context
     */
  
/**
 * Get ward-specific context
 */
private function getWardContext($wardId, $moduleData, $defaultLat, $defaultLng, $defaultZoom)
{
    $ward = WardModel::find($wardId);
    
    // Always use moduleData first (from WardController), fallback to database
    $wardName = $moduleData['ward_name'] ?? 'N/A';
    $wardNumber = $moduleData['ward_number'] ?? 'N/A';
    $cityName = $moduleData['city_name'] ?? 'N/A';
    
    if ($ward) {
        // Override with database values if available
        $wardName = $ward->ward_name ?? $wardName;
        $wardNumber = $ward->ward_number ?? $wardNumber;
        $cityName = $ward->city_name ?? $cityName;
        
        // Try to get city coordinates for the ward
        $cityCoords = $this->getCityCoordinates($ward->city);
        
        return [
            'moduleName' => 'ward',
            'moduleId' => $wardId,
            'moduleTitle' => $wardName,
            'moduleIdentifier' => $ward->identifier ?? 'N/A',
            'lat' => $cityCoords['lat'] ?? $defaultLat,
            'lng' => $cityCoords['lng'] ?? $defaultLng,
            'zoom' => 13,
            'ward' => $ward,
            'city' => $ward->city,
            'ward_name' => $wardName,
            'ward_number' => $wardNumber,
            'city_name' => $cityName,
            'moduleData' => $moduleData // Pass the full moduleData
        ];
    }

    return [
        'moduleName' => 'ward',
        'moduleId' => $wardId,
        'moduleTitle' => $moduleData['title'] ?? 'Ward',
        'moduleIdentifier' => $moduleData['identifier'] ?? 'N/A',
        'lat' => $defaultLat,
        'lng' => $defaultLng,
        'zoom' => $defaultZoom,
        'ward_name' => $wardName,
        'ward_number' => $wardNumber,
        'city_name' => $cityName,
        'moduleData' => $moduleData // Pass the full moduleData
    ];
}

    /**
     * Get building-specific context with ward boundary
     */
   /**
 * Get building-specific context with ward boundary
 */
/**
 * Get building-specific context with ward boundary
 */
private function getBuildingContext($buildingId, $moduleData, $defaultLat, $defaultLng, $defaultZoom)
{
    $building = BuildingModel::with('ward')->find($buildingId);
    
    // Always use moduleData first (from BuildingController), fallback to database
    $buildingName = $moduleData['building_name'] ?? 'N/A';
    $wardName = $moduleData['ward_name'] ?? 'N/A';
    $wardNumber = $moduleData['ward_number'] ?? 'N/A';
    $cityName = $moduleData['city_name'] ?? 'N/A';
    
    if ($building && $building->ward) {
        // Override with database values if available
        $buildingName = $building->building_name ?? $buildingName;
        $wardName = $building->ward->ward_name ?? $wardName;
        $wardNumber = $building->ward->ward_number ?? $wardNumber;
        $cityName = $building->ward->city_name ?? $cityName;
        
        // Get ward's default drawing for center coordinates
        $wardDefaultDrawing = GoogleMap::getDefaultDrawing('ward', $building->ward->id);
        
        if ($wardDefaultDrawing) {
            return [
                'moduleName' => 'building',
                'moduleId' => $buildingId,
                'moduleTitle' => $buildingName,
                'moduleIdentifier' => $building->identifier ?? 'N/A',
                'lat' => $wardDefaultDrawing->center_lat ?? $defaultLat,
                'lng' => $wardDefaultDrawing->center_lng ?? $defaultLng,
                'zoom' => 15,
                'building' => $building,
                'ward' => $building->ward,
                'hasWardBoundary' => true,
                'building_name' => $buildingName,
                'ward_name' => $wardName,
                'ward_number' => $wardNumber,
                'city_name' => $cityName,
                'moduleData' => $moduleData // Pass the full moduleData
            ];
        }
    }
    
    // If no building found or no ward, return with moduleData
    return [
        'moduleName' => 'building',
        'moduleId' => $buildingId,
        'moduleTitle' => $buildingName,
        'moduleIdentifier' => 'N/A',
        'lat' => $defaultLat,
        'lng' => $defaultLng,
        'zoom' => $defaultZoom,
        'building_name' => $buildingName,
        'ward_name' => $wardName,
        'ward_number' => $wardNumber,
        'city_name' => $cityName,
        'moduleData' => $moduleData // Pass the full moduleData
    ];
}

    /**
     * Get coordinates for a city
     */
    private function getCityCoordinates($cityName)
    {
        $cityCoordinates = [
            'mumbai' => ['lat' => 19.0760, 'lng' => 72.8777],
            'delhi' => ['lat' => 28.6139, 'lng' => 77.2090],
            'bangalore' => ['lat' => 12.9716, 'lng' => 77.5946],
            'hyderabad' => ['lat' => 17.3850, 'lng' => 78.4867],
            'chennai' => ['lat' => 13.0827, 'lng' => 80.2707],
            'kolkata' => ['lat' => 22.5726, 'lng' => 88.3639],
            'pune' => ['lat' => 18.5204, 'lng' => 73.8567],
        ];

        $cityKey = strtolower(trim($cityName));
        return $cityCoordinates[$cityKey] ?? null;
    }

    /**
     * Save drawing to Google Maps table
     */
    public function saveDrawing(Request $request)
    {
        DB::beginTransaction();
        
        try {
            Log::info("Saving Google Map drawing with hierarchical context", [
                'module' => $request->module_name,
                'module_id' => $request->module_id,
                'user_id' => auth()->id()
            ]);

            // Validate required fields
            $request->validate([
                'module_name' => 'required|string|max:50',
                'module_id' => 'required|integer',
                'drawing_name' => 'required|string|max:255',
                'drawing_data' => 'required|string',
                'total_shapes' => 'required|integer|min:0',
                'center_lat' => 'required|numeric',
                'center_lng' => 'required|numeric',
                'zoom_level' => 'required|numeric|min:1|max:20'
            ]);

            $moduleName = $request->module_name;
            $moduleId = $request->module_id;
            $drawingName = $request->drawing_name;
            $drawingData = $request->drawing_data;
            $totalShapes = $request->total_shapes;
            $centerLat = $request->center_lat;
            $centerLng = $request->center_lng;
            $zoomLevel = $request->zoom_level;
            $imageData = $request->image_data;
            $isDefault = $request->boolean('is_default', false);
            $status = $request->status ?? 'active';

            // Calculate markers and areas
            $drawingArray = json_decode($drawingData, true);
            $totalMarkers = $this->countMarkers($drawingArray);
            $totalAreas = $this->countAreas($drawingArray);

            // Handle image saving
            $mapImage = null;
            $thumbnail = null;
            
            if ($imageData) {
                $mapImage = $this->saveMapImage($imageData, $moduleName, $moduleId);
                $thumbnail = $this->generateThumbnail($imageData, $moduleName, $moduleId);
            }

            // If this is set as default, remove default status from other drawings
            if ($isDefault) {
                GoogleMap::forModule($moduleName, $moduleId)
                    ->update(['is_default' => false]);
            }

            // Create the drawing record
            $googleMap = GoogleMap::create([
                'module_name' => $moduleName,
                'module_id' => $moduleId,
                'drawing_name' => $drawingName,
                'drawing_data' => $drawingData,
                'total_shapes' => $totalShapes,
                'total_markers' => $totalMarkers,
                'total_areas' => $totalAreas,
                'map_image' => $mapImage,
                'thumbnail' => $thumbnail,
                'center_lat' => $centerLat,
                'center_lng' => $centerLng,
                'zoom_level' => $zoomLevel,
                'status' => $status,
                'is_default' => $isDefault,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // If this is a ward drawing and it's set as default, update ward boundary
            if ($moduleName === 'ward' && $isDefault) {
                $this->updateWardBoundary($moduleId, $drawingData, $centerLat, $centerLng);
            }

            DB::commit();

            Log::info("Google Map drawing saved successfully with hierarchical context", [
                'drawing_id' => $googleMap->id,
                'module' => $moduleName,
                'module_id' => $moduleId
            ]);

            return response()->json([
                'success' => true,
                'drawing_id' => $googleMap->id,
                'drawing_name' => $googleMap->drawing_name,
                'message' => 'Drawing saved successfully!',
                'image_url' => $googleMap->image_url,
                'thumbnail_url' => $googleMap->thumbnail_url
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error saving Google Map drawing:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['image_data', 'drawing_data'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save drawing: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update ward boundary when default drawing is saved
     */
    private function updateWardBoundary($wardId, $drawingData, $centerLat, $centerLng)
    {
        try {
            $ward = WardModel::find($wardId);
            if ($ward) {
                $ward->update([
                    'boundary_data' => $drawingData,
                    'center_lat' => $centerLat,
                    'center_lng' => $centerLng
                ]);
                
                Log::info("Ward boundary updated from default drawing", [
                    'ward_id' => $wardId,
                    'center_lat' => $centerLat,
                    'center_lng' => $centerLng
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error updating ward boundary:', [
                'ward_id' => $wardId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get drawings for a specific module
     */
    public function getDrawings($moduleName, $moduleId)
    {
        try {
            $drawings = GoogleMap::getModuleDrawings($moduleName, $moduleId);

            return response()->json([
                'success' => true,
                'drawings' => $drawings->map(function ($drawing) {
                    return [
                        'id' => $drawing->id,
                        'drawing_name' => $drawing->drawing_name,
                        'total_shapes' => $drawing->total_shapes,
                        'total_markers' => $drawing->total_markers,
                        'total_areas' => $drawing->total_areas,
                        'map_image' => $drawing->map_image,
                        'image_url' => $drawing->image_url,
                        'thumbnail_url' => $drawing->thumbnail_url,
                        'center_lat' => $drawing->center_lat,
                        'center_lng' => $drawing->center_lng,
                        'zoom_level' => $drawing->zoom_level,
                        'status' => $drawing->status,
                        'is_default' => $drawing->is_default,
                        'created_at' => $drawing->created_at->format('M d, Y H:i'),
                        'created_by' => $drawing->creator->name ?? 'System',
                        'drawing_summary' => $drawing->getDrawingSummary()
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching Google Map drawings:', [
                'module' => $moduleName,
                'module_id' => $moduleId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching drawings'
            ], 500);
        }
    }

    /**
     * Get specific drawing data
     */
    public function getDrawing($drawingId)
    {
        try {
            $drawing = GoogleMap::findOrFail($drawingId);

            return response()->json([
                'success' => true,
                'drawing' => [
                    'id' => $drawing->id,
                    'drawing_name' => $drawing->drawing_name,
                    'drawing_data' => $drawing->drawing_data,
                    'total_shapes' => $drawing->total_shapes,
                    'total_markers' => $drawing->total_markers,
                    'total_areas' => $drawing->total_areas,
                    'map_image' => $drawing->map_image,
                    'image_url' => $drawing->image_url,
                    'center_lat' => $drawing->center_lat,
                    'center_lng' => $drawing->center_lng,
                    'zoom_level' => $drawing->zoom_level,
                    'status' => $drawing->status,
                    'is_default' => $drawing->is_default
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching Google Map drawing:', [
                'drawing_id' => $drawingId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Drawing not found'
            ], 404);
        }
    }

    /**
     * Delete a drawing
     */
    public function deleteDrawing($drawingId)
    {
        DB::beginTransaction();
        
        try {
            $drawing = GoogleMap::findOrFail($drawingId);

            // Delete associated files
            if ($drawing->map_image) {
                Storage::disk('public')->delete('map-images/' . $drawing->map_image);
            }
            if ($drawing->thumbnail) {
                Storage::disk('public')->delete('map-thumbnails/' . $drawing->thumbnail);
            }

            $drawingName = $drawing->drawing_name;
            $drawing->delete();

            DB::commit();

            Log::info("Google Map drawing deleted", [
                'drawing_id' => $drawingId,
                'drawing_name' => $drawingName,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Drawing deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error deleting Google Map drawing:', [
                'drawing_id' => $drawingId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error deleting drawing'
            ], 500);
        }
    }

    /**
     * Set drawing as default
     */
    public function setDefaultDrawing(Request $request, $drawingId)
    {
        DB::beginTransaction();
        
        try {
            $drawing = GoogleMap::findOrFail($drawingId);

            // Remove default status from other drawings in same module
            GoogleMap::forModule($drawing->module_name, $drawing->module_id)
                ->update(['is_default' => false]);

            // Set this drawing as default
            $drawing->update([
                'is_default' => true,
                'updated_by' => auth()->id()
            ]);

            // Update ward boundary if this is a ward drawing
            if ($drawing->module_name === 'ward') {
                $this->updateWardBoundary(
                    $drawing->module_id, 
                    $drawing->drawing_data, 
                    $drawing->center_lat, 
                    $drawing->center_lng
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Drawing set as default successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error setting default drawing:', [
                'drawing_id' => $drawingId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error setting default drawing'
            ], 500);
        }
    }

    /**
     * Update drawing status
     */
    public function updateStatus(Request $request, $drawingId)
    {
        try {
            $request->validate([
                'status' => 'required|in:active,inactive,draft'
            ]);

            $drawing = GoogleMap::findOrFail($drawingId);
            $drawing->update([
                'status' => $request->status,
                'updated_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Drawing status updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating drawing status:', [
                'drawing_id' => $drawingId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating drawing status'
            ], 500);
        }
    }

    /**
     * Count markers in drawing data
     */
    private function countMarkers($drawingData)
    {
        if (!is_array($drawingData)) return 0;

        return count(array_filter($drawingData, function ($shape) {
            return isset($shape['type']) && $shape['type'] === 'google.maps.Marker';
        }));
    }

    /**
     * Count areas (polygons, circles, rectangles) in drawing data
     */
    private function countAreas($drawingData)
    {
        if (!is_array($drawingData)) return 0;

        $areaTypes = ['google.maps.Polygon', 'google.maps.Circle', 'google.maps.Rectangle'];
        
        return count(array_filter($drawingData, function ($shape) use ($areaTypes) {
            return isset($shape['type']) && in_array($shape['type'], $areaTypes);
        }));
    }

    /**
     * Save map image to storage
     */
    private function saveMapImage($imageData, $moduleName, $moduleId)
    {
        try {
            $imageName = 'map_' . $moduleName . '_' . $moduleId . '_' . time() . '_' . Str::random(8) . '.png';
            
            // Remove data URL prefix
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            
            // Decode base64
            $imageBinary = base64_decode($imageData);
            
            if ($imageBinary === false) {
                throw new \Exception('Failed to decode base64 image');
            }
            
            // Ensure directory exists
            $directory = storage_path('app/public/map-images');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Save image file
            $filePath = $directory . '/' . $imageName;
            $saved = file_put_contents($filePath, $imageBinary);
            
            if ($saved === false) {
                throw new \Exception('Failed to save image file');
            }
            
            return $imageName;
            
        } catch (\Exception $e) {
            Log::error('Error saving map image:', [
                'module' => $moduleName,
                'module_id' => $moduleId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Generate thumbnail from image data
     */
    private function generateThumbnail($imageData, $moduleName, $moduleId)
    {
        try {
            // This would require intervention/image or similar package
            // For now, we'll return null and implement later
            return null;
            
        } catch (\Exception $e) {
            Log::error('Error generating thumbnail:', [
                'module' => $moduleName,
                'module_id' => $moduleId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}