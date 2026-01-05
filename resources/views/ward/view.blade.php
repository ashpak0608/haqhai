@extends('layouts.app')
@section('contant')

<div id="kt_app_toolbar" class="app-toolbar mb-4">
    <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch">
        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
            <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                    {{$permissions['sub_module_name']}} - View
                </h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ url('ward') }}" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold">
                    <i class="ki-outline ki-left fs-2"></i>Back
                </a>
            </div>
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-body pt-4 pb-0">
        <div class="d-row row-wrap row-sm-nowrap pb-4">
            <form class="form">
                <div class="row mb-3">
                    <label class="col-lg-4 fw-semibold text-muted">City Name</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ $views->city_name ?? '' }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-4 fw-semibold text-muted">Ward Name</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ $views->ward_name ?? '' }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-4 fw-semibold text-muted">Ward Number</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ $views->ward_number ?? '' }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-4 fw-semibold text-muted">Status</label>
                    <div class="col-lg-8">
                        @if(isset($views->status) && $views->status == '0')
                            <span class="badge badge-light-success fs-7 fw-bold">Active</span>
                        @else
                            <span class="badge badge-light-danger fs-7 fw-bold">Inactive</span>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="separator separator-dashed my-10"></div>

<div class="d-flex justify-content-between align-items-center mb-6">
    <h3 class="card-label fw-bold fs-3">Google Map Drawings</h3>
    <a href="{{ route('ward.map', $views->id) }}" class="btn btn-primary">
        <i class="ki-outline ki-location fs-2"></i>Create New Drawing
    </a>
</div>

@if($drawings && count($drawings) > 0)
    <div class="row g-6">
        @foreach($drawings as $drawing)
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card card-flush h-100">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-4 fw-bold text-gray-900 text-hover-primary mb-1">{{ $drawing->drawing_name }}</span>
                        <span class="fs-7 text-muted">Created: {{ \Carbon\Carbon::parse($drawing->created_at)->format('M d, Y h:i A') }}</span>
                    </div>
                    <div class="card-toolbar">
                        @if($drawing->is_default)
                        <span class="badge badge-light-success">Default</span>
                        @endif
                    </div>
                </div>
                
                <div class="card-body pt-1 pb-0">
                    <div class="d-flex flex-wrap gap-4 mb-5">
                        <div class="d-flex align-items-center">
                            <i class="ki-outline ki-abstract-26 fs-2 me-1"></i>
                            <span class="fs-7 fw-semibold text-gray-600">{{ $drawing->total_shapes }} Shapes</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="ki-outline ki-geolocation fs-2 me-1 text-success"></i>
                            <span class="fs-7 fw-semibold text-gray-600">{{ $drawing->total_markers }} Markers</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="ki-outline ki-frame fs-2 me-1 text-warning"></i>
                            <span class="fs-7 fw-semibold text-gray-600">{{ $drawing->total_areas }} Areas</span>
                        </div>
                    </div>
                    
                    @if($drawing->map_image)
                        @php
                            $imageUrl = asset('storage/map-images/' . $drawing->map_image);
                            // Bypass finfo error using native PHP file_exists
                            $filePath = public_path('storage/map-images/' . $drawing->map_image);
                            $imageExists = file_exists($filePath);
                        @endphp
                        
                        <div class="map-preview-container position-relative rounded mb-4">
                            <img src="{{ $imageExists ? $imageUrl : asset('images/map-placeholder.jpg') }}" 
                                 alt="{{ $drawing->drawing_name }}" 
                                 class="img-fluid rounded w-100 dynamic-image"
                                 style="height: 180px; object-fit: cover; cursor: pointer;"
                                 data-filename="{{ $drawing->map_image }}"
                                 onclick="openImageModal('{{ $imageUrl }}', '{{ addslashes($drawing->drawing_name) }}')"
                                 onerror="handleImageError(this)"
                                 loading="lazy">
                        </div>
                    @else
                        <div class="text-center p-6 bg-light-warning rounded mb-4">
                            <i class="ki-outline ki-location text-warning fs-2hx mb-3"></i>
                            <p class="text-warning fw-semibold mb-1">No Image Preview</p>
                        </div>
                    @endif
                </div>
                
                <div class="card-footer pt-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column">
                            <span class="fs-7 text-muted">Created By</span>
                            <span class="fs-7 fw-semibold text-gray-800">{{ $drawing->creator->name ?? 'System' }}</span>
                        </div>
                        <div class="d-flex gap-2">
                            @if($drawing->map_image)
                                <button class="btn btn-sm btn-icon btn-light-primary" onclick="openImageModal('{{ $imageUrl }}', '{{ addslashes($drawing->drawing_name) }}')">
                                    <i class="ki-outline ki-eye fs-2"></i>
                                </button>
                            @endif
                            <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteDrawing({{ $drawing->id }}, '{{ addslashes($drawing->drawing_name) }}')">
                                <i class="ki-outline ki-trash fs-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-20">
            <i class="ki-outline ki-location text-primary fs-6hx mb-8"></i>
            <h4 class="text-gray-600 fw-semibold mb-4">No Google Map Drawings Found</h4>
            <a href="{{ route('ward.map', $views->id) }}" class="btn btn-primary">Create New Drawing</a>
        </div>
    </div>
@endif

<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fw-bold" id="imageModalTitle">Map Drawing</h2>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body p-0">
                <img id="modalImage" src="" class="img-fluid w-100" style="max-height: 70vh; object-fit: contain;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="downloadModalImage">Download</button>
            </div>
        </div>
    </div>
</div>

<style>
.map-preview-container { border: 1px solid #E4E6EF; overflow: hidden; }
.map-preview-container img { transition: transform 0.3s ease; }
.map-preview-container:hover img { transform: scale(1.05); }
.image-error { background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #6c757d; }
</style>

<script>
function handleImageError(imgElement) {
    imgElement.src = `data:image/svg+xml;base64,${btoa(`<svg width="400" height="180" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#f8f9fa"/><text x="50%" y="50%" font-family="Arial" font-size="14" fill="#6c757d" text-anchor="middle">Image Not Available</text></svg>`)}`;
    imgElement.onerror = null;
}

let currentDownloadImage = '';
let currentDownloadName = '';

function openImageModal(imageUrl, title) {
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('imageModalTitle');
    modalImage.src = imageUrl;
    modalTitle.textContent = title;
    currentDownloadImage = imageUrl;
    currentDownloadName = title;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

function deleteDrawing(drawingId, drawingName) {
    if (!confirm(`Delete "${drawingName}"?`)) return;
    fetch("{{ route('google-map.deleteDrawing', '') }}/" + drawingId, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}", 'Content-Type': 'application/json' }
    }).then(res => res.json()).then(data => {
        if (data.success) location.reload();
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('downloadModalImage')?.addEventListener('click', function() {
        const link = document.createElement('a');
        link.href = currentDownloadImage;
        link.download = currentDownloadName + '.png';
        link.click();
    });
});
</script>

@endsection