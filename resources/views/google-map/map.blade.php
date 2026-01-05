<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Google Map - {{ $moduleTitle }} - HAQHAI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    
    <style>
        #map {
            height: 100vh;
            width: 100%;
        }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f8fa;
        }

        /* Main Sidebar Panel */
        .sidebar-panel {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 320px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 1000;
            border: 1px solid #e1e3ea;
            overflow: hidden;
        }

        .panel-header {
            background: linear-gradient(135deg, #3699FF 0%, #187DE4 100%);
            padding: 20px;
            color: white;
            border-radius: 12px 12px 0 0;
        }

        .panel-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 5px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .panel-subtitle {
            font-size: 12px;
            opacity: 0.9;
            margin: 0;
        }

        .panel-content {
            padding: 20px;
            max-height: 70vh;
            overflow-y: auto;
        }

        /* Info Sections */
        .info-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
            border: 1px solid #e1e3ea;
        }

        .info-title {
            font-size: 14px;
            font-weight: 600;
            color: #3699FF;
            margin: 0 0 12px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e1e3ea;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 12px;
            color: #7e8299;
            font-weight: 500;
        }

        .info-value {
            font-size: 12px;
            color: #3f4254;
            font-weight: 600;
            text-align: right;
        }

        /* Search Bar */
        .search-container {
            position: relative;
            margin-bottom: 16px;
        }

        .search-box {
            width: 100%;
            padding: 12px 45px 12px 16px;
            background: white;
            border: 1px solid #e1e3ea;
            border-radius: 8px;
            color: #3f4254;
            font-size: 13px;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-box:focus {
            border-color: #3699FF;
            box-shadow: 0 0 0 3px rgba(54, 153, 255, 0.1);
        }

        .search-box::placeholder {
            color: #a1a5b7;
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a1a5b7;
        }

        /* Map Controls */
        .map-controls {
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
        }

        .map-type-btn {
            flex: 1;
            background: white;
            color: #7e8299;
            border: 1px solid #e1e3ea;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .map-type-btn.active {
            background: #3699FF;
            color: white;
            border-color: #3699FF;
        }

        .map-type-btn:hover {
            background: #f1faff;
            color: #3699FF;
        }

        /* Drawing Tools */
        .tools-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 16px;
            border: 1px solid #e1e3ea;
        }

        .tools-title {
            font-size: 14px;
            font-weight: 600;
            color: #3699FF;
            margin: 0 0 12px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tools-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 12px;
        }

        .control-btn {
            background: white;
            color: #7e8299;
            border: 1px solid #e1e3ea;
            padding: 12px 8px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .control-btn:hover {
            background: #f1faff;
            color: #3699FF;
            border-color: #3699FF;
            transform: translateY(-1px);
        }

        .control-btn.active {
            background: #3699FF;
            color: white;
            border-color: #3699FF;
        }

        .control-btn.clear {
            background: #fff5f5;
            color: #f64e60;
            border-color: #f64e60;
        }

        .control-btn.save {
            background: #f0fff4;
            color: #0bb783;
            border-color: #0bb783;
        }

        .control-btn.export {
            background: #f8f5ff;
            color: #8950fc;
            border-color: #8950fc;
        }

        .btn-icon {
            font-size: 16px;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .location-btn {
            background: white;
            color: #7e8299;
            border: 1px solid #e1e3ea;
            padding: 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .location-btn:hover {
            background: #f1faff;
            color: #3699FF;
            border-color: #3699FF;
        }

        /* Drawing Name Input */
        .drawing-name-input {
            width: 100%;
            padding: 12px;
            background: white;
            border: 1px solid #e1e3ea;
            border-radius: 6px;
            color: #3f4254;
            font-size: 12px;
            margin: 12px 0;
            box-sizing: border-box;
            outline: none;
            transition: all 0.3s ease;
        }

        .drawing-name-input:focus {
            border-color: #3699FF;
            box-shadow: 0 0 0 3px rgba(54, 153, 255, 0.1);
        }

        .drawing-name-input::placeholder {
            color: #a1a5b7;
        }

        /* Default Drawing Checkbox */
        .default-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 12px 0;
            font-size: 12px;
            color: #7e8299;
        }

        .default-checkbox input[type="checkbox"] {
            accent-color: #3699FF;
        }

        /* Scrollbar Styling */
        .panel-content::-webkit-scrollbar {
            width: 6px;
        }

        .panel-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .panel-content::-webkit-scrollbar-thumb {
            background: #3699FF;
            border-radius: 3px;
        }

        .panel-content::-webkit-scrollbar-thumb:hover {
            background: #187DE4;
        }

        /* Error and Loading */
        .error-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background: #f8d7da;
            color: #721c24;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #f5c6cb;
            z-index: 1000;
            display: none;
        }

        .loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3699FF;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Autocomplete Dropdown */
        .autocomplete-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e1e3ea;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            max-height: 200px;
            overflow-y: auto;
            display: none;
            z-index: 1001;
        }

        .autocomplete-item {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #e1e3ea;
            font-size: 13px;
            color: #7e8299;
            transition: all 0.2s ease;
        }

        .autocomplete-item:hover {
            background: #f8f9fa;
            color: #3699FF;
        }

        .autocomplete-item:last-child {
            border-bottom: none;
        }

        .autocomplete-main-text {
            font-weight: 600;
            color: #3f4254;
            margin-bottom: 2px;
        }

        .autocomplete-secondary-text {
            font-size: 11px;
            color: #a1a5b7;
        }
        
        /* Coordinates Display */
        .coordinates-display {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
            margin: 12px 0;
            border: 1px solid #e1e3ea;
            font-family: monospace;
            font-size: 11px;
            color: #3f4254;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Main Sidebar Panel -->
    <div class="sidebar-panel">
        <div class="panel-header">
            <div class="panel-title">
                @if($moduleName === 'ward')
                🗺️ Ward Boundary Map
                @elseif($moduleName === 'building')
                🏢 Building Location Map
                @else
                🗺️ Google Map
                @endif
            </div>
            <div class="panel-subtitle">
                @if($moduleName === 'ward')
                Draw and save ward boundaries
                @elseif($moduleName === 'building')
                Set precise location for your building
                @else
                Create and manage map drawings
                @endif
            </div>
        </div>

        <div class="panel-content">
        <!-- Dynamic Information Section -->
<div class="info-section">
    <div class="info-title">
        @if($moduleName === 'ward')🗺️ Ward Details
        @elseif($moduleName === 'building')📋 Building Details
        @else📋 Module Details
        @endif
    </div>
    
    <!-- Show Building Name only for building module -->
    @if($moduleName === 'building')
    <div class="info-item">
        <span class="info-label">Building Name:</span>
        <span class="info-value">{{ $moduleData['building_name'] ?? ($building_name ?? 'N/A') }}</span>
    </div>
    @endif
    
    <!-- Show Ward Name for BOTH modules -->
    <div class="info-item">
        <span class="info-label">Ward Name:</span>
        <span class="info-value">{{ $moduleData['ward_name'] ?? ($ward_name ?? 'N/A') }}</span>
    </div>
    
    <!-- Show Ward Number for BOTH modules -->
    @if($moduleName === 'ward')
    <div class="info-item">
        <span class="info-label">Ward Number:</span>
        <span class="info-value">{{ $moduleData['ward_number'] ?? ($ward_number ?? 'N/A') }}</span>
    </div>
    @endif
    
    <!-- Show City for BOTH modules -->
    <div class="info-item">
        <span class="info-label">City:</span>
        <span class="info-value">{{ $moduleData['city_name'] ?? ($city_name ?? 'N/A') }}</span>
    </div>
    
    <!-- Show Coordinates for BOTH modules -->
    <div class="info-item">
        <span class="info-label">Coordinates:</span>
        <span class="info-value" id="current-coordinates">{{ $lat }}, {{ $lng }}</span>
    </div>
</div>

            <!-- Click Coordinates Display -->
            <!--<div class="coordinates-display" id="click-coordinates" style="display: none;">-->
            <!--    📍 Click Coordinates: <span id="click-coords-value"></span>-->
            <!--</div>-->

            <!-- Search Bar -->
            <div class="search-container">
                <input type="text" class="search-box" id="search-box" 
                    placeholder="@if($moduleName === 'ward')Search for ward location...@elseif($moduleName === 'building')Search for building location...@elseSearch for location...@endif">
                <div class="search-icon">🔍</div>
                <div class="autocomplete-dropdown" id="autocomplete-dropdown"></div>
            </div>

            <!-- Map Type Controls -->
            <div class="map-controls">
                <button class="map-type-btn" id="map-btn" onclick="setMapType('roadmap')">
                    🗺️ Map
                </button>
                <button class="map-type-btn active" id="satellite-btn" onclick="setMapType('hybrid')">
                    🛰️ Satellite
                </button>
            </div>

            <!-- Drawing Tools -->
            <div class="tools-section">
                <div class="tools-title">
                    @if($moduleName === 'ward')🗺️ Ward Drawing Tools
                    @elseif($moduleName === 'building')🏢 Building Location Tools
                    @else🛠️ Drawing Tools
                    @endif
                </div>
                
                <!-- Drawing Name Input -->
                <input type="text" class="drawing-name-input" id="drawing-name" 
                    placeholder="Drawing Name (Required)" 
                    value="@if($moduleName === 'ward'){{ $moduleData['ward_name'] ?? $moduleTitle }} Boundary @elseif($moduleName === 'building'){{ $moduleData['building_name'] ?? $moduleTitle }} Location @else{{ $moduleTitle }} Drawing @endif {{ date('Y-m-d H:i') }}">
                
                <!-- Default Drawing Checkbox -->
                <div class="default-checkbox">
                    <input type="checkbox" id="set-as-default" {{ $defaultDrawing ? '' : 'checked' }}>
                    <label for="set-as-default">Set as default drawing</label>
                </div>
                
                <div class="tools-grid">
                    <button class="control-btn" onclick="enableMarkerMode(this)">
                        <span class="btn-icon">📍</span>
                        <span>
                            @if($moduleName === 'building')Marker
                            @else Point
                            @endif
                        </span>
                    </button>
                    <button class="control-btn" onclick="enablePolylineMode(this)">
                        <span class="btn-icon">📏</span>
                        <span>Line</span>
                    </button>
                    <button class="control-btn" onclick="enablePolygonMode(this)">
                        <span class="btn-icon">🔷</span>
                        <span>
                            @if($moduleName === 'ward')Boundary
                            @else Area
                            @endif
                        </span>
                    </button>
                    <button class="control-btn" onclick="enableCircleMode(this)">
                        <span class="btn-icon">⭕</span>
                        <span>Circle</span>
                    </button>
                    <button class="control-btn" onclick="enableRectangleMode(this)">
                        <span class="btn-icon">📐</span>
                        <span>Rectangle</span>
                    </button>
                    <button class="control-btn clear" onclick="clearUserDrawings()">
                        <span class="btn-icon">🗑️</span>
                        <span>Clear Drawings</span>
                    </button>
                </div>
                
                <div class="action-buttons">
                    <button class="control-btn save" onclick="saveDrawing(this)">
                        <span class="btn-icon">💾</span>
                        <span>
                            @if($moduleName === 'building')
                            Save Building Location
                            @elseif($moduleName === 'ward')
                            Save Ward Boundary
                            @else
                            Save to Database
                            @endif
                        </span>
                    </button>
                    <button class="control-btn export" onclick="exportMapAsImage(this)">
                        <span class="btn-icon">📤</span>
                        <span>Export as Image</span>
                    </button>
                    <button class="location-btn" onclick="locateUser(this)">
                        <span class="btn-icon">📍</span>
                        <span>Find My Location</span>
                    </button>
                </div>
            </div>

            <!-- Back Button -->
            <div style="text-align: center; margin-top: 16px;">
                @if($moduleName === 'ward')
                    <a href="{{ route('ward.index') }}" style="color: #3699FF; text-decoration: none; font-size: 12px; font-weight: 600;">
                        ← Back to Ward List
                    </a>
                @elseif($moduleName === 'building')
                    <a href="{{ route('building.view', $moduleId) }}" style="color: #3699FF; text-decoration: none; font-size: 12px; font-weight: 600;">
                        ← Back to Building View
                    </a>
                @else
                    <a href="javascript:history.back()" style="color: #3699FF; text-decoration: none; font-size: 12px; font-weight: 600;">
                        ← Back
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div id="map"></div>
    <div id="error-message" class="error-message" style="display:none;"></div>

    <!-- Hidden canvas for image export -->
    <canvas id="map-canvas" style="display: none;"></canvas>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include html2canvas for image export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    
    @if(isset($moduleData['existingBuildings']))
    <script>
        window.wardBuildings = @json($moduleData['existingBuildings']);
    </script>
    @endif

    <script>
        // Global configuration with hierarchical context
        const GOOGLE_MAP_CONFIG = {
            moduleName: "{{ $moduleName }}",
            moduleId: "{{ $moduleId }}",
            center: { lat: {{ $lat }}, lng: {{ $lng }} },
            zoom: {{ $zoom }},
            apiKey: "AIzaSyATU-azg9UpokrIggfQT0AETzVFLSBaq9c",
            saveUrl: "{{ route('google-map.saveDrawing') }}",
            getDrawingsUrl: "{{ route('google-map.getDrawings', [$moduleName, $moduleId]) }}",
            csrfToken: "{{ csrf_token() }}",
            // Hierarchical context data
            ward: @json($ward ?? null),
            building: @json($building ?? null),
            city: @json($city ?? null),
            wardDefaultDrawing: @json($wardDefaultDrawing ?? null),
            hasWardBoundary: @json($hasWardBoundary ?? false),
            // Module-specific settings
            isWardModule: "{{ $moduleName }}" === "ward",
            isBuildingModule: "{{ $moduleName }}" === "building",
            // Module data for display
            moduleData: @json($moduleData ?? [])
        };

        let map;
        let infowindow = null;
        let markers = [];
        let existingBuildingMarkers = []; // Separate array for existing buildings
        let currentLocationMarker = null;
        let userLocation = null;
        let autocomplete;
        let placesService;
        let geocoder;
        
        // Drawing variables
        let drawingManager;
        let selectedShape;
        let drawingMode = null;
        let allShapes = [];
        let userDrawnShapes = []; // Separate array for user drawings

        /**
         * Enhanced initialization with hierarchical context
         */
        function initMap() {
            console.log('🚀 Initializing Google Maps with hierarchical context:', GOOGLE_MAP_CONFIG.moduleName, GOOGLE_MAP_CONFIG.moduleId);
            
            // Create the map with context-based settings - START WITH SATELLITE VIEW
            map = new google.maps.Map(document.getElementById("map"), {
                center: GOOGLE_MAP_CONFIG.center,
                zoom: GOOGLE_MAP_CONFIG.zoom,
                mapTypeId: 'hybrid', // Default to satellite view
                mapTypeControl: false,
                streetViewControl: true,
                fullscreenControl: true,
                styles: [
                    { "featureType": "poi.business", "stylers": [{ "visibility": "off" }] },
                    { "featureType": "poi.medical", "stylers": [{ "visibility": "off" }] },
                    { "featureType": "poi.school", "stylers": [{ "visibility": "off" }] },
                    { "featureType": "poi.place_of_worship", "stylers": [{ "visibility": "off" }] },
                    { "featureType": "poi.sports_complex", "stylers": [{ "visibility": "off" }] },
                    { "featureType": "poi.government", "stylers": [{ "visibility": "off" }] },
                    { "featureType": "transit.station", "stylers": [{ "visibility": "off" }] }
                ]
            });

            // Initialize services
            geocoder = new google.maps.Geocoder();
            placesService = new google.maps.places.PlacesService(map);

            // Initialize search with autocomplete
            initializeAutocomplete();

            // Initialize drawing tools
            initializeDrawingTools();

            // Load hierarchical data based on module type
            loadHierarchicalMapData();

            // Add click listener to update coordinates in panel (NO POPUP)
map.addListener('click', function(event) {
    if (drawingMode === 'marker') {
        return; // Let drawing manager handle marker placement
    }
    updateCoordinatesDisplay(event.latLng);
});

            // Add center change listener to update coordinates (for buildings)
            google.maps.event.addListener(map, 'center_changed', function() {
                const center = map.getCenter();
                if (GOOGLE_MAP_CONFIG.moduleName === 'building') {
                    updateBuildingCoordinates(center.lat(), center.lng());
                }
            });

            console.log('✅ Google Maps initialized with hierarchical context');
        }

        /**
         * Update coordinates display in panel (no popup)
         */
      /**
 * Update coordinates display in panel (no popup)
 */
function updateCoordinatesDisplay(latLng) {
    const lat = latLng.lat().toFixed(6);
    const lng = latLng.lng().toFixed(6);
    
    // Update main coordinates display ONLY
    document.getElementById('current-coordinates').textContent = lat + ', ' + lng;
    
    // REMOVED: Click coordinates display
    // const clickDisplay = document.getElementById('click-coordinates');
    // const clickValue = document.getElementById('click-coords-value');
    // clickValue.textContent = lat + ', ' + lng;
    // clickDisplay.style.display = 'block';
    
    console.log('📍 Coordinates updated:', lat, lng);
}

        /**
         * Load hierarchical map data based on module type
         */
        function loadHierarchicalMapData() {
            console.log(`📁 Loading ${GOOGLE_MAP_CONFIG.moduleName} context data...`);
            
            switch (GOOGLE_MAP_CONFIG.moduleName) {
                case 'ward':
                    loadWardContextData();
                    break;
                case 'building':
                    loadBuildingContextData();
                    break;
                default:
                    loadDefaultContextData();
            }
        }

        /**
         * Load default context data
         */
        function loadDefaultContextData() {
            renderServerDrawings();
        }

        /**
         * Load ward context data - simplified for ward module
         */
        function loadWardContextData() {
            console.log('🗺️ Loading ward context data...');
            
            // For ward module, just render existing drawings
            renderServerDrawings();
            
            // Center on ward coordinates if available
            if (GOOGLE_MAP_CONFIG.ward && GOOGLE_MAP_CONFIG.ward.latitude && GOOGLE_MAP_CONFIG.ward.longitude) {
                const wardPosition = new google.maps.LatLng(
                    parseFloat(GOOGLE_MAP_CONFIG.ward.latitude),
                    parseFloat(GOOGLE_MAP_CONFIG.ward.longitude)
                );
                map.setCenter(wardPosition);
                map.setZoom(15);
            }
            
            console.log('✅ Ward context loaded - ready for boundary drawing');
        }

        /**
         * Load building context data with ward boundary and existing buildings
         */
        function loadBuildingContextData() {
            console.log('🏢 Loading building context with ward boundary and existing buildings...');
            
            // First render ward boundary if available
            if (GOOGLE_MAP_CONFIG.wardDefaultDrawing) {
                renderWardBoundary(GOOGLE_MAP_CONFIG.wardDefaultDrawing);
            }
            
            // Load existing buildings in this ward (EXCLUDING CURRENT BUILDING)
            if (GOOGLE_MAP_CONFIG.ward && GOOGLE_MAP_CONFIG.ward.id) {
                loadExistingBuildingsInWard(GOOGLE_MAP_CONFIG.ward.id);
            }
            
            // Then render building-specific drawings
            renderServerDrawings();
            
            // If building has coordinates, center map on building and add CURRENT building marker
            if (GOOGLE_MAP_CONFIG.building && GOOGLE_MAP_CONFIG.building.latitude && GOOGLE_MAP_CONFIG.building.longitude) {
                const buildingPosition = new google.maps.LatLng(
                    parseFloat(GOOGLE_MAP_CONFIG.building.latitude),
                    parseFloat(GOOGLE_MAP_CONFIG.building.longitude)
                );
                map.setCenter(buildingPosition);
                map.setZoom(18);
                
                // Add CURRENT building marker with label (RED color) - this is user drawing
                addUserBuildingMarker(buildingPosition, GOOGLE_MAP_CONFIG.building.building_name, true);
            } else if (GOOGLE_MAP_CONFIG.wardDefaultDrawing && GOOGLE_MAP_CONFIG.wardDefaultDrawing.center_lat && GOOGLE_MAP_CONFIG.wardDefaultDrawing.center_lng) {
                // Center on ward if no building coordinates
                map.setCenter({
                    lat: parseFloat(GOOGLE_MAP_CONFIG.wardDefaultDrawing.center_lat),
                    lng: parseFloat(GOOGLE_MAP_CONFIG.wardDefaultDrawing.center_lng)
                });
                map.setZoom(15);
            }
        }

        /**
         * Render ward boundary on map with prominent styling
         */
        function renderWardBoundary(wardDrawing) {
            if (!wardDrawing || !wardDrawing.drawing_data) return;
            
            try {
                let boundaryData = wardDrawing.drawing_data;
                if (typeof boundaryData === 'string') {
                    boundaryData = JSON.parse(boundaryData);
                }
                
                boundaryData.forEach(shape => {
                    if (shape.type === 'google.maps.Polygon' && shape.path) {
                        // Use original colors from ward drawing or enhanced visibility
                        const polygon = new google.maps.Polygon({
                            paths: shape.path.map(p => ({ lat: parseFloat(p.lat), lng: parseFloat(p.lng) })),
                            strokeColor: "#FF0000", // Bright red for visibility
                            strokeOpacity: 0.8,
                            strokeWeight: 4,
                            fillColor: "#FF0000", // Bright red fill
                            fillOpacity: 0.15,
                            editable: false,
                            draggable: false,
                            zIndex: 1
                        });
                        
                        polygon.setMap(map);
                        allShapes.push(polygon);
                        console.log('🗺️ Ward boundary rendered successfully with enhanced visibility');
                    }
                });
                
            } catch (error) {
                console.error('Error rendering ward boundary:', error);
            }
        }

        /**
         * Load existing buildings in the same ward with error handling
         */
        function loadExistingBuildingsInWard(wardId) {
            if (!wardId) return;
            
            console.log('Loading buildings for ward:', wardId);
            
            // Include current building ID to exclude it from results
            const currentBuildingId = GOOGLE_MAP_CONFIG.building ? GOOGLE_MAP_CONFIG.building.id : null;
            const url = `/api/buildings-in-ward/${wardId}${currentBuildingId ? `?exclude_building=${currentBuildingId}` : ''}`;
            
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.buildings) {
                        console.log(`📍 Found ${data.buildings.length} existing buildings in ward`);
                        renderExistingBuildings(data.buildings);
                    } else {
                        console.warn('No buildings data received:', data);
                        // Fallback to window.wardBuildings if available
                        if (window.wardBuildings && Array.isArray(window.wardBuildings)) {
                            console.log('Using fallback building data');
                            renderExistingBuildings(window.wardBuildings);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading existing buildings:', error);
                    // Fallback to window.wardBuildings if available
                    if (window.wardBuildings && Array.isArray(window.wardBuildings)) {
                        console.log('Using fallback building data after error');
                        renderExistingBuildings(window.wardBuildings);
                    }
                });
        }

        /**
         * Render existing buildings in the ward with labels
         */
        function renderExistingBuildings(buildings) {
            if (!buildings || !Array.isArray(buildings)) return;
            
            buildings.forEach(building => {
                if (building.latitude && building.longitude && building.building_name) {
                    if (GOOGLE_MAP_CONFIG.building && building.id === GOOGLE_MAP_CONFIG.building.id) {
                        return;
                    }
                    
                    addExistingBuildingMarker(
                        new google.maps.LatLng(parseFloat(building.latitude), parseFloat(building.longitude)),
                        building.building_name
                    );
                }
            });
            
            console.log(`📍 Rendered ${buildings.length} existing buildings in ward`);
        }

        /**
         * Add EXISTING building marker (green, protected from clear)
         */
        function addExistingBuildingMarker(position, buildingName) {
            const markerColor = '#34A853';
            const markerSize = 8;

            // Create marker with custom icon
            const marker = new google.maps.Marker({
                position: position,
                map: map,
                title: buildingName, // This shows on hover as tooltip
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: markerSize,
                    fillColor: markerColor,
                    fillOpacity: 1,
                    strokeColor: '#FFFFFF',
                    strokeWeight: 2,
                },
                draggable: false,
                zIndex: 999
            });

            // Create label for the marker that shows ONLY on hover
            const label = new google.maps.InfoWindow({
                content: `
                    <div style="padding: 8px 12px; background: white; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); border: 2px solid ${markerColor}; font-family: Arial, sans-serif; min-width: 120px;">
                        <div style="font-weight: bold; color: #333; font-size: 12px; text-align: center;">${buildingName}</div>
                        <div style="color: #34A853; font-size: 10px; margin-top: 2px; text-align: center;">🏢 Existing Building</div>
                    </div>
                `,
                disableAutoPan: true
            });

            // Show label ONLY on hover
            marker.addListener('mouseover', function() {
                label.open(map, marker);
            });

            // Hide label when mouse leaves
            marker.addListener('mouseout', function() {
                label.close();
            });

            // Store in existing buildings array (protected from clear)
            existingBuildingMarkers.push({ marker, label, buildingName });
            allShapes.push(marker);
            
            console.log(`📍 Existing building marker added: ${buildingName}`);
        }

        /**
         * Add USER building marker (red, can be cleared)
         */
        function addUserBuildingMarker(position, buildingName, isCurrentBuilding = false) {
            const markerColor = '#EA4335';
            const markerSize = 10;
            const zIndex = 1000;

            // Create marker with custom icon
            const marker = new google.maps.Marker({
                position: position,
                map: map,
                title: buildingName,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: markerSize,
                    fillColor: markerColor,
                    fillOpacity: 1,
                    strokeColor: '#FFFFFF',
                    strokeWeight: 2,
                },
                draggable: isCurrentBuilding,
                zIndex: zIndex
            });

            // Create label for the marker
            const label = new google.maps.InfoWindow({
                content: `
                    <div style="padding: 8px 12px; background: white; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); border: 2px solid ${markerColor}; font-family: Arial, sans-serif; min-width: 120px;">
                        <div style="font-weight: bold; color: #333; font-size: 12px; text-align: center;">${buildingName}</div>
                        ${isCurrentBuilding ? 
                            '<div style="color: #EA4335; font-size: 10px; margin-top: 2px; text-align: center;">📍 Current Building</div>' : 
                            '<div style="color: #EA4335; font-size: 10px; margin-top: 2px; text-align: center;">📍 User Marker</div>'
                        }
                    </div>
                `,
                disableAutoPan: true
            });

            // Show label ONLY on hover
            marker.addListener('mouseover', function() {
                label.open(map, marker);
            });

            // Hide label when mouse leaves
            marker.addListener('mouseout', function() {
                label.close();
            });

            // For current building, also show label on click
            if (isCurrentBuilding) {
                marker.addListener('click', function() {
                    label.open(map, marker);
                });
                
                // Add drag event to update coordinates (only for current building)
                marker.addListener('dragend', function(event) {
                    updateBuildingCoordinates(event.latLng.lat(), event.latLng.lng());
                    label.setPosition(event.latLng);
                    updateCoordinatesDisplay(event.latLng);
                });
            }

            // Store in user drawings array (can be cleared)
            userDrawnShapes.push({ marker, label, buildingName, isCurrentBuilding });
            allShapes.push(marker);
            
            console.log(`📍 ${isCurrentBuilding ? 'Current' : 'User'} building marker added: ${buildingName}`);
        }

        // Initialize drawing tools
        function initializeDrawingTools() {
            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: null,
                drawingControl: false,
                markerOptions: {
                    draggable: true,
                    icon: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
                },
                polylineOptions: {
                    strokeColor: '#FF0000',
                    strokeOpacity: 1.0,
                    strokeWeight: 3,
                    editable: true
                },
                polygonOptions: {
                    fillColor: '#4285F4',
                    fillOpacity: 0.3,
                    strokeColor: '#4285F4',
                    strokeOpacity: 1.0,
                    strokeWeight: 2,
                    editable: true
                },
                circleOptions: {
                    fillColor: '#34A853',
                    fillOpacity: 0.3,
                    strokeColor: '#34A853',
                    strokeOpacity: 1.0,
                    strokeWeight: 2,
                    editable: true,
                    draggable: true
                },
                rectangleOptions: {
                    fillColor: '#FBBC05',
                    fillOpacity: 0.3,
                    strokeColor: '#FBBC05',
                    strokeOpacity: 1.0,
                    strokeWeight: 2,
                    editable: true,
                    draggable: true
                }
            });

            drawingManager.setMap(map);

            google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
                const shape = event.overlay;
                
                // Store shape in user drawings
                userDrawnShapes.push(shape);
                allShapes.push(shape);
                
                // Update coordinates when marker is placed (for buildings)
                if (event.type === 'marker' && GOOGLE_MAP_CONFIG.moduleName === 'building') {
                    const position = event.overlay.getPosition();
                    updateBuildingCoordinates(position.lat(), position.lng());
                    updateCoordinatesDisplay(position);
                }
                
                // NO INFO WINDOW ON CLICK - Only coordinates update
                google.maps.event.addListener(shape, 'click', function(clickEvent) {
                    if (shape instanceof google.maps.Marker) {
                        const position = shape.getPosition();
                        updateCoordinatesDisplay(position);
                    } else {
                        // For other shapes, get center or bounds
                        let position;
                        if (shape.getCenter) {
                            position = shape.getCenter();
                        } else if (shape.getBounds) {
                            position = shape.getBounds().getCenter();
                        }
                        if (position) {
                            updateCoordinatesDisplay(position);
                        }
                    }
                });
                
                disableDrawingMode();
            });
        }

        // Clear only user drawings (preserve existing buildings)
        function clearUserDrawings() {
            if (confirm('Are you sure you want to clear your drawings? (Existing buildings will be preserved)')) {
                userDrawnShapes.forEach(shape => {
                    if (shape.setMap) {
                        shape.setMap(null);
                    } else if (shape.marker && shape.marker.setMap) {
                        shape.marker.setMap(null);
                    }
                });
                userDrawnShapes = [];
                selectedShape = null;
                alert('Your drawings have been cleared! (Existing buildings preserved)');
            }
        }

        // Old clearAllDrawings function (keep for compatibility)
        function clearAllDrawings() {
            clearUserDrawings();
        }

        // [All other functions remain the same...]
        // Initialize Autocomplete, Search, Map Controls, etc.

        // Initialize Autocomplete for search
        function initializeAutocomplete() {
            const input = document.getElementById('search-box');
            const dropdown = document.getElementById('autocomplete-dropdown');
            
            // Create Autocomplete service
            autocomplete = new google.maps.places.AutocompleteService();
            
            input.addEventListener('input', function() {
                const query = input.value.trim();
                
                if (query.length < 3) {
                    dropdown.style.display = 'none';
                    return;
                }
                
                autocomplete.getPlacePredictions({
                    input: query,
                    componentRestrictions: { country: 'in' },
                    types: ['establishment', 'geocode', 'address']
                }, function(predictions, status) {
                    if (status !== google.maps.places.PlacesServiceStatus.OK || !predictions) {
                        dropdown.style.display = 'none';
                        return;
                    }
                    
                    dropdown.innerHTML = '';
                    predictions.slice(0, 5).forEach(prediction => {
                        const item = document.createElement('div');
                        item.className = 'autocomplete-item';
                        item.innerHTML = `
                            <div class="autocomplete-main-text">${prediction.structured_formatting.main_text}</div>
                            <div class="autocomplete-secondary-text">${prediction.structured_formatting.secondary_text}</div>
                        `;
                        
                        item.addEventListener('click', function() {
                            input.value = prediction.description;
                            dropdown.style.display = 'none';
                            selectPlaceFromAutocomplete(prediction.place_id);
                        });
                        
                        dropdown.appendChild(item);
                    });
                    
                    dropdown.style.display = 'block';
                });
            });
            
            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
            
            // Handle Enter key for direct search
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    dropdown.style.display = 'none';
                    performSearch(input.value.trim());
                }
            });
        }

        // Select place from autocomplete
        function selectPlaceFromAutocomplete(placeId) {
            const placesService = new google.maps.places.PlacesService(map);
            
            placesService.getDetails({
                placeId: placeId,
                fields: ['geometry', 'name', 'formatted_address', 'types']
            }, function(place, status) {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    map.setCenter(place.geometry.location);
                    map.setZoom(16);
                    // Update coordinates display instead of showing popup
                    updateCoordinatesDisplay(place.geometry.location);
                    // Update coordinates for building
                    if (GOOGLE_MAP_CONFIG.moduleName === 'building') {
                        updateBuildingCoordinates(place.geometry.location.lat(), place.geometry.location.lng());
                    }
                }
            });
        }

        // Perform accurate search
        function performSearch(query) {
            if (!query) return;

            // Check if it's coordinates
            const coordMatch = query.match(/^(-?\d+\.?\d*)[,\s]+(-?\d+\.?\d*)$/);
            if (coordMatch) {
                const lat = parseFloat(coordMatch[1]);
                const lng = parseFloat(coordMatch[2]);
                if (!isNaN(lat) && !isNaN(lng)) {
                    const position = new google.maps.LatLng(lat, lng);
                    map.setCenter(position);
                    map.setZoom(16);
                    updateCoordinatesDisplay(position);
                    if (GOOGLE_MAP_CONFIG.moduleName === 'building') {
                        updateBuildingCoordinates(lat, lng);
                    }
                    return;
                }
            }

            // Use Geocoding API
            geocoder.geocode({ 
                address: query,
                componentRestrictions: { country: 'IN' },
                bounds: map.getBounds()
            }, function(results, status) {
                if (status === 'OK' && results[0]) {
                    const location = results[0].geometry.location;
                    map.setCenter(location);
                    map.setZoom(16);
                    updateCoordinatesDisplay(location);
                    if (GOOGLE_MAP_CONFIG.moduleName === 'building') {
                        updateBuildingCoordinates(location.lat(), location.lng());
                    }
                } else {
                    alert('Location not found: ' + query + '\n\nPlease try:\n- Full building name with area\n- Complete address\n- Exact landmark name');
                }
            });
        }

        // Set map type (Roadmap or Hybrid Satellite with labels)
        function setMapType(type) {
            if (!map) {
                console.error('Map not initialized yet');
                return;
            }
            
            if (type === 'hybrid') {
                map.setMapTypeId(google.maps.MapTypeId.HYBRID);
            } else {
                map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
            }
            
            document.getElementById('map-btn').classList.toggle('active', type === 'roadmap');
            document.getElementById('satellite-btn').classList.toggle('active', type === 'hybrid');
        }

        // [Rest of the functions remain exactly the same...]
        // Get user's current location, Add current location marker, Export map as image, etc.
        
        // Get user's current location (centers map, doesn't always add marker)
        function getUserLocation() {
            if (!navigator.geolocation) {
                console.warn('Geolocation is not supported by this browser.');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    
                    console.log('User location found:', userLocation);
                    // Only center the map, don't add blue marker by default
                    map.setCenter(userLocation);
                    map.setZoom(16);
                    updateCoordinatesDisplay(new google.maps.LatLng(userLocation.lat, userLocation.lng));
                },
                (error) => {
                    console.error('Location error:', error);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 60000
                }
            );
        }

        // Manually trigger location detection - receives button element
        function locateUser(button) {
            const originalHTML = button.innerHTML;
            button.innerHTML = '<div class="loading"></div> Getting location...';
            button.disabled = true;

            getUserLocation();

            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.disabled = false;
            }, 2000);
        }

        // Drawing mode functions - each receives button element to toggle active class
        function enableMarkerMode(button) {
            disableDrawingMode();
            drawingManager.setDrawingMode(google.maps.drawing.OverlayType.MARKER);
            drawingMode = 'marker';
            updateActiveButton(button);
        }

        function enablePolylineMode(button) {
            disableDrawingMode();
            drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYLINE);
            drawingMode = 'polyline';
            updateActiveButton(button);
        }

        function enablePolygonMode(button) {
            disableDrawingMode();
            drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
            drawingMode = 'polygon';
            updateActiveButton(button);
        }

        function enableCircleMode(button) {
            disableDrawingMode();
            drawingManager.setDrawingMode(google.maps.drawing.OverlayType.CIRCLE);
            drawingMode = 'circle';
            updateActiveButton(button);
        }

        function enableRectangleMode(button) {
            disableDrawingMode();
            drawingManager.setDrawingMode(google.maps.drawing.OverlayType.RECTANGLE);
            drawingMode = 'rectangle';
            updateActiveButton(button);
        }

        function disableDrawingMode() {
            if (drawingManager) {
                drawingManager.setDrawingMode(null);
            }
            drawingMode = null;
            // Remove active class from all buttons
            document.querySelectorAll('.control-btn').forEach(btn => {
                btn.classList.remove('active');
            });
        }

        function updateActiveButton(button) {
            // Remove active class from all buttons
            document.querySelectorAll('.control-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            // Add active class to clicked button (if provided)
            if (button && button.classList) {
                button.classList.add('active');
            }
        }

        // Export map as image - receives button element
        function exportMapAsImage(button) {
            const mapElement = document.getElementById('map');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<div class="loading"></div> Exporting...';
            button.disabled = true;

            html2canvas(mapElement, {
                useCORS: true,
                allowTaint: true,
                scale: 2,
                logging: false,
                backgroundColor: null
            }).then(canvas => {
                const image = canvas.toDataURL('image/png');
                
                const link = document.createElement('a');
                link.download = `google-map-export-${new Date().toISOString().split('T')[0]}.png`;
                link.href = image;
                link.click();
                
                button.innerHTML = originalHTML;
                button.disabled = false;
            }).catch(error => {
                console.error('Error exporting map:', error);
                alert('Error exporting map as image. Please try again.');
                button.innerHTML = originalHTML;
                button.disabled = false;
            });
        }

        // Save drawing to Google Maps database
        function saveDrawing(button) {
            if (userDrawnShapes.length === 0) {
                alert('No drawings to save. Please create some drawings first.');
                return;
            }

            const drawingName = document.getElementById('drawing-name').value.trim();
            if (!drawingName) {
                alert('Please enter a drawing name.');
                return;
            }

            const originalHTML = button.innerHTML;
            button.innerHTML = '<div class="loading"></div> Saving...';
            button.disabled = true;

            // Capture map as image first
            const mapElement = document.getElementById('map');
            
            html2canvas(mapElement, {
                useCORS: true,
                allowTaint: true,
                scale: 1,
                logging: false,
                backgroundColor: null
            }).then(canvas => {
                const imageData = canvas.toDataURL('image/png');
                
                const drawingsData = userDrawnShapes.map(shape => {
                    let data = { type: 'Unknown' };
                    
                    // Handle marker objects
                    if (shape.marker && shape.marker instanceof google.maps.Marker) {
                        data.type = 'google.maps.Marker';
                        data.position = {
                            lat: shape.marker.getPosition().lat(),
                            lng: shape.marker.getPosition().lng()
                        };
                    } 
                    // Handle regular markers
                    else if (shape instanceof google.maps.Marker) {
                        data.type = 'google.maps.Marker';
                        data.position = {
                            lat: shape.getPosition().lat(),
                            lng: shape.getPosition().lng()
                        };
                    } else if (shape instanceof google.maps.Polyline) {
                        data.type = 'google.maps.Polyline';
                        data.path = shape.getPath().getArray().map(latLng => ({
                            lat: latLng.lat(),
                            lng: latLng.lng()
                        }));
                    } else if (shape instanceof google.maps.Polygon) {
                        data.type = 'google.maps.Polygon';
                        data.path = shape.getPath().getArray().map(latLng => ({
                            lat: latLng.lat(),
                            lng: latLng.lng()
                        }));
                    } else if (shape instanceof google.maps.Circle) {
                        data.type = 'google.maps.Circle';
                        data.center = {
                            lat: shape.getCenter().lat(),
                            lng: shape.getCenter().lng()
                        };
                        data.radius = shape.getRadius();
                    } else if (shape instanceof google.maps.Rectangle) {
                        data.type = 'google.maps.Rectangle';
                        const bounds = shape.getBounds();
                        data.bounds = {
                            north: bounds.getNorthEast().lat(),
                            south: bounds.getSouthWest().lat(),
                            east: bounds.getNorthEast().lng(),
                            west: bounds.getSouthWest().lng()
                        };
                    }
                    
                    return data;
                }).filter(data => data.type !== 'Unknown');

                const isDefault = document.getElementById('set-as-default').checked;

                // Prepare data for Google Map system
                const drawingData = {
                    module_name: GOOGLE_MAP_CONFIG.moduleName,
                    module_id: GOOGLE_MAP_CONFIG.moduleId,
                    drawing_name: drawingName,
                    drawing_data: JSON.stringify(drawingsData),
                    total_shapes: drawingsData.length,
                    center_lat: map.getCenter().lat(),
                    center_lng: map.getCenter().lng(),
                    zoom_level: map.getZoom(),
                    image_data: imageData,
                    is_default: isDefault,
                    _token: GOOGLE_MAP_CONFIG.csrfToken
                };

                // AJAX call to Google Map controller
                $.ajax({
                    type: "POST",
                    url: GOOGLE_MAP_CONFIG.saveUrl,
                    data: drawingData,
                    success: function(response) {
                        if (response.success) {
                            alert('✅ Google Map drawing saved successfully!');
                            
                            // Redirect based on module type
                            if (GOOGLE_MAP_CONFIG.moduleName === 'building' && GOOGLE_MAP_CONFIG.moduleId) {
                                // Small delay to show success message
                                setTimeout(function() {
                                    window.location.href = "{{ route('building.view', $moduleId) }}";
                                }, 1000);
                            } else if (GOOGLE_MAP_CONFIG.moduleName === 'ward' && GOOGLE_MAP_CONFIG.moduleId) {
                                setTimeout(function() {
                                    window.location.href = "{{ route('ward.view', $moduleId) }}";
                                }, 1000);
                            } else {
                                // For other modules, just clear the form
                                document.getElementById('drawing-name').value = '';
                                location.reload();
                            }
                        } else {
                            alert('❌ ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error saving drawing:', error);
                        alert('❌ Network error while saving drawing. Please try again.');
                    },
                    complete: function() {
                        button.innerHTML = originalHTML;
                        button.disabled = false;
                    }
                });
            }).catch(error => {
                console.error('Error capturing map image:', error);
                alert('Error capturing map image. Saving without image...');
                
                // Fallback: Save without image
                saveDrawingWithoutImage(button);
                button.innerHTML = originalHTML;
                button.disabled = false;
            });
        }

        // Fallback function to save without image
        function saveDrawingWithoutImage(button) {
            const drawingsData = userDrawnShapes.map(shape => {
                let data = { type: 'Unknown' };
                
                if (shape.marker && shape.marker instanceof google.maps.Marker) {
                    data.type = 'google.maps.Marker';
                    data.position = {
                        lat: shape.marker.getPosition().lat(),
                        lng: shape.marker.getPosition().lng()
                    };
                } else if (shape instanceof google.maps.Marker) {
                    data.type = 'google.maps.Marker';
                    data.position = {
                        lat: shape.getPosition().lat(),
                        lng: shape.getPosition().lng()
                    };
                } else if (shape instanceof google.maps.Polyline) {
                    data.type = 'google.maps.Polyline';
                    data.path = shape.getPath().getArray().map(latLng => ({
                        lat: latLng.lat(),
                        lng: latLng.lng()
                    }));
                } else if (shape instanceof google.maps.Polygon) {
                    data.type = 'google.maps.Polygon';
                    data.path = shape.getPath().getArray().map(latLng => ({
                        lat: latLng.lat(),
                        lng: latLng.lng()
                    }));
                } else if (shape instanceof google.maps.Circle) {
                    data.type = 'google.maps.Circle';
                    data.center = {
                        lat: shape.getCenter().lat(),
                        lng: shape.getCenter().lng()
                    };
                    data.radius = shape.getRadius();
                } else if (shape instanceof google.maps.Rectangle) {
                    data.type = 'google.maps.Rectangle';
                    const bounds = shape.getBounds();
                    data.bounds = {
                        north: bounds.getNorthEast().lat(),
                        south: bounds.getSouthWest().lat(),
                        east: bounds.getNorthEast().lng(),
                        west: bounds.getSouthWest().lng()
                    };
                }
                
                return data;
            }).filter(data => data.type !== 'Unknown');

            const drawingName = document.getElementById('drawing-name').value.trim();
            const isDefault = document.getElementById('set-as-default').checked;

            const drawingData = {
                module_name: GOOGLE_MAP_CONFIG.moduleName,
                module_id: GOOGLE_MAP_CONFIG.moduleId,
                drawing_name: drawingName,
                drawing_data: JSON.stringify(drawingsData),
                total_shapes: drawingsData.length,
                center_lat: map.getCenter().lat(),
                center_lng: map.getCenter().lng(),
                zoom_level: map.getZoom(),
                is_default: isDefault,
                _token: GOOGLE_MAP_CONFIG.csrfToken
            };

            $.ajax({
                type: "POST",
                url: GOOGLE_MAP_CONFIG.saveUrl,
                data: drawingData,
                success: function(response) {
                    if (response.success) {
                        alert('✅ Google Map drawing saved successfully! (without image)');
                        // Redirect based on module type
                        if (GOOGLE_MAP_CONFIG.moduleName === 'building' && GOOGLE_MAP_CONFIG.moduleId) {
                            setTimeout(function() {
                                window.location.href = "{{ route('building.view', $moduleId) }}";
                            }, 1000);
                        } else if (GOOGLE_MAP_CONFIG.moduleName === 'ward' && GOOGLE_MAP_CONFIG.moduleId) {
                            setTimeout(function() {
                                window.location.href = "{{ route('ward.view', $moduleId) }}";
                            }, 1000);
                        } else {
                            document.getElementById('drawing-name').value = '';
                            location.reload();
                        }
                    } else {
                        alert('❌ ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error saving drawing without image:', error);
                    alert('❌ Network error while saving drawing. Please try again.');
                }
            });
        }

        // Update building coordinates
        function updateBuildingCoordinates(lat, lng) {
            if (GOOGLE_MAP_CONFIG.moduleName !== 'building') {
                return;
            }

            const buildingId = GOOGLE_MAP_CONFIG.moduleId;
            
            if (!buildingId || buildingId === '') {
                return;
            }
            
            fetch("{{ route('building.updateCoordinates') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    building_id: buildingId,
                    latitude: lat,
                    longitude: lng
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Building coordinates updated successfully:', lat, lng);
                    // Update the coordinates display in header
                    const details = document.querySelectorAll('.info-value');
                    if (details && details.length >= 4) {
                        details[3].textContent = lat.toFixed(6) + ', ' + lng.toFixed(6);
                    }
                } else {
                    console.log('Coordinate update failed:', data.message);
                }
            })
            .catch(error => {
                console.error('Error updating coordinates:', error);
            });
        }

        // --- START: Render saved drawings (server-provided) ---
        /**
         * Convert saved drawing shape objects to map overlays
         */
        function renderSavedDrawingShapes(savedShapes, isInteractive = false) {
            if (!Array.isArray(savedShapes)) return;

            savedShapes.forEach(shapeObj => {
                if (!shapeObj) return;

                // Marker
                if (shapeObj.position) {
                    const pos = { lat: parseFloat(shapeObj.position.lat), lng: parseFloat(shapeObj.position.lng) };
                    const marker = new google.maps.Marker({
                        position: pos,
                        map: map,
                        draggable: isInteractive && GOOGLE_MAP_CONFIG.moduleName === 'building',
                    });
                    // on marker dragend update building coords if module is building
                    if (isInteractive && GOOGLE_MAP_CONFIG.moduleName === 'building') {
                        marker.addListener('dragend', function(evt) {
                            updateBuildingCoordinates(evt.latLng.lat(), evt.latLng.lng());
                        });
                    }
                    markers.push(marker);
                    userDrawnShapes.push(marker);
                }

                // Polyline / Polygon
                if (shapeObj.path && Array.isArray(shapeObj.path)) {
                    const path = shapeObj.path.map(p => ({ lat: parseFloat(p.lat), lng: parseFloat(p.lng) }));
                    // detect polygon by type or shapeObj.type content
                    const t = (shapeObj.type || '').toLowerCase();
                    if (t.indexOf('polygon') !== -1) {
                        const polygon = new google.maps.Polygon({
                            paths: path,
                            map: map,
                            editable: isInteractive,
                            draggable: false,
                            fillColor: '#4285F4',
                            fillOpacity: 0.2,
                            strokeColor: '#4285F4'
                        });
                        userDrawnShapes.push(polygon);
                    } else {
                        const polyline = new google.maps.Polyline({
                            path: path,
                            map: map,
                            editable: isInteractive,
                            strokeColor: '#FF0000'
                        });
                        userDrawnShapes.push(polyline);
                    }
                }

                // Circle
                if (shapeObj.center && shapeObj.radius) {
                    const circle = new google.maps.Circle({
                        center: { lat: parseFloat(shapeObj.center.lat), lng: parseFloat(shapeObj.center.lng) },
                        radius: parseFloat(shapeObj.radius),
                        map: map,
                        editable: isInteractive,
                        fillColor: '#34A853',
                        fillOpacity: 0.2,
                        strokeColor: '#34A853'
                    });
                    userDrawnShapes.push(circle);
                }

                // Rectangle (bounds)
                if (shapeObj.bounds) {
                    const bounds = new google.maps.LatLngBounds(
                        { lat: parseFloat(shapeObj.bounds.south), lng: parseFloat(shapeObj.bounds.west) },
                        { lat: parseFloat(shapeObj.bounds.north), lng: parseFloat(shapeObj.bounds.east) }
                    );
                    const rectangle = new google.maps.Rectangle({
                        bounds: bounds,
                        map: map,
                        editable: isInteractive,
                        fillColor: '#FBBC05',
                        fillOpacity: 0.2,
                        strokeColor: '#FBBC05'
                    });
                    userDrawnShapes.push(rectangle);
                }
            });
        }

        /**
         * Render server-provided drawings
         */
        function renderServerDrawings() {
            try {
                // Blade-injected lists (safe JSON)
                const serverDrawings = @json($drawings ?? []);
                const defaultDrawing = @json($defaultDrawing ?? null);

                // If no ward default selected, render all server drawings for this module
                if (serverDrawings && serverDrawings.length > 0) {
                    serverDrawings.forEach(d => {
                        let shapes = d.drawing_data;
                        if (typeof shapes === 'string') {
                            try { shapes = JSON.parse(shapes); } catch(e) { /* ignore */ }
                        }
                        renderSavedDrawingShapes(shapes, false);
                    });

                    // If there is an explicit default for the module, center on it
                    if (defaultDrawing && defaultDrawing.center_lat && defaultDrawing.center_lng) {
                        map.setCenter({ lat: parseFloat(defaultDrawing.center_lat), lng: parseFloat(defaultDrawing.center_lng) });
                        if (defaultDrawing.zoom_level) {
                            map.setZoom(parseInt(defaultDrawing.zoom_level));
                        }
                    }
                }
            } catch (err) {
                console.error('Error rendering server drawings:', err);
            }
        }
        // --- END: Render saved drawings ---

        function showError(message) {
            console.error('Error:', message);
            const errorDiv = document.getElementById('error-message');
            if (errorDiv) {
                errorDiv.innerHTML = '<strong>Error:</strong> ' + message;
                errorDiv.style.display = 'block';
            }
        }

        window.gm_authFailure = function() {
            showError('Google Maps authentication failed. Please check your API key and billing.');
        };

    </script>

    <!-- Google Maps API with Drawing and Places libraries -->
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATU-azg9UpokrIggfQT0AETzVFLSBaq9c&libraries=drawing,places&callback=initMap">
    </script>
</body>
</html>