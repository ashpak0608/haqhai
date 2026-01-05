@extends('layouts.app')
@section('contant')

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar mb-4">
    <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch">
        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
            <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                    {{$permissions['sub_module_name']}} - View
                </h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ url('building') }}" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold">
                    <i class="ki-outline ki-left fs-2"></i>Back
                </a>
            </div>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Details Card-->
<div class="card mb-4">
    <div class="card-body pt-4 pb-0">
        <div class="d-row row-wrap row-sm-nowrap pb-4">
            <form id="view_from" name="view_from" class="form fv-plugins-bootstrap5 fv-plugins-framework">
                <div class="row mb-3">
                    <label class="col-lg-4 fw-semibold text-muted">State Name</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ $views->state_name ?? '' }}</span>
                    </div>
                </div>

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

                <!-- Landmark -->
                <div class="row mb-3">
                    <label class="col-lg-4 fw-semibold text-muted">Landmark</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ $views->landmark_name ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-lg-4 fw-semibold text-muted">Building Name</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ $views->building_name ?? '' }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-lg-4 fw-semibold text-muted">Latitude</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ $views->latitude ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-lg-4 fw-semibold text-muted">Longitude</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ $views->longitude ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-lg-4 fw-semibold text-muted">Status</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold text-gray-800 fs-6">
                            @if(isset($views->status) && $views->status == '0')
                                <span class="badge badge-light-success fs-7 fw-bold">Active</span>
                            @else
                                <span class="badge badge-light-danger fs-7 fw-bold">Inactive</span>
                            @endif
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-lg-4 fw-semibold text-muted">Created By</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ $views->created_by ?? '' }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-lg-4 fw-semibold text-muted">Created At</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ $views->created_at ?? '' }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-lg-4 fw-semibold text-muted">Modified By</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ $views->updated_by ?? '' }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-lg-4 fw-semibold text-muted">Modified At</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ $views->updated_at ?? '' }}</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Details Card-->

<!-- Separator -->
<div class="separator separator-dashed my-10"></div>

<!-- Drawings Header -->
<div class="d-flex justify-content-between align-items-center mb-6">
    <h3 class="card-label fw-bold fs-3">Google Map Drawings</h3>
    <div>
        <a href="{{ route('building.map', $views->id) }}" class="btn btn-primary" target="_blank">
            <i class="ki-outline ki-location fs-2 me-2"></i>Create New Drawing
        </a>
    </div>
</div>

<!-- Drawings List -->
@if(!empty($drawings) && count($drawings) > 0)
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
                            @if(!empty($drawing->is_default))
                                <span class="badge badge-light-success">Default</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body pt-1 pb-0">
                        <div class="d-flex flex-wrap gap-4 mb-5">
                            <div class="d-flex align-items-center">
                                <span class="svg-icon svg-icon-2 me-1">...svg...</span>
                                <span class="fs-7 fw-semibold text-gray-600">{{ $drawing->total_shapes ?? 0 }} Shapes</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="svg-icon svg-icon-2 me-1">...svg...</span>
                                <span class="fs-7 fw-semibold text-gray-600">{{ $drawing->total_markers ?? 0 }} Markers</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="svg-icon svg-icon-2 me-1">...svg...</span>
                                <span class="fs-7 fw-semibold text-gray-600">{{ $drawing->total_areas ?? 0 }} Areas</span>
                            </div>
                        </div>

                        @if(!empty($drawing->map_image))
                            @php
                                $imageUrl = asset('storage/map-images/' . $drawing->map_image);
                                $base = url('/');
                                $alternativeUrls = [
                                    asset('storage/map-images/' . $drawing->map_image),
                                    url('storage/map-images/' . $drawing->map_image),
                                    $base . '/storage/map-images/' . $drawing->map_image,
                                    $base . '/public/storage/map-images/' . $drawing->map_image,
                                ];
                            @endphp

                            <div class="map-preview-container position-relative rounded mb-4">
                                <img src="{{ $imageUrl }}"
                                     alt="{{ $drawing->drawing_name }}"
                                     class="img-fluid rounded w-100 dynamic-image"
                                     style="height: 180px; object-fit: cover; cursor: pointer;"
                                     data-filename="{{ $drawing->map_image }}"
                                     data-drawing-id="{{ $drawing->id }}"
                                     data-alternative-urls="{{ json_encode($alternativeUrls) }}"
                                     onclick="openImageModal(this.src, '{{ addslashes($drawing->drawing_name) }}')"
                                     onerror="handleImageError(this)"
                                     loading="lazy">
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge badge-light-primary fs-8">Google Map</span>
                                </div>
                            </div>
                        @else
                            <div class="text-center p-6 bg-light-warning rounded mb-4">
                                <i class="ki-outline ki-location text-warning fs-2hx mb-3"></i>
                                <p class="text-warning fw-semibold mb-1">No Image Preview</p>
                                <p class="text-muted fs-8">Drawing data saved but no image captured</p>
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
                                @if(!empty($drawing->map_image))
                                    <button class="btn btn-sm btn-icon btn-light-primary" onclick="openImageModal('{{ $imageUrl }}', '{{ addslashes($drawing->drawing_name) }}')" title="View Full Image">
                                        <i class="ki-outline ki-eye fs-2"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-light-info" onclick="downloadImage('{{ $drawing->map_image }}', '{{ addslashes($drawing->drawing_name) }}')" title="Download Image">
                                        <i class="ki-outline ki-down fs-2"></i>
                                    </button>
                                @endif
                                <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteDrawing({{ $drawing->id }}, '{{ addslashes($drawing->drawing_name) }}')" title="Delete Drawing">
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
            <div class="mb-8">
                <i class="ki-outline ki-location text-primary fs-6hx"></i>
            </div>
            <h4 class="text-gray-600 fw-semibold mb-4">No Google Map Drawings Found</h4>
            <p class="text-muted fs-6 mb-8">Create your first map drawing for this building</p>
            <a href="{{ route('building.map', $views->id) }}" class="btn btn-primary">
                <i class="ki-outline ki-location fs-2 me-2"></i>Create New Drawing
            </a>
        </div>
    </div>
@endif

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fw-bold" id="imageModalTitle">Map Drawing</h2>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body p-0">
                <img id="modalImage" src="" class="img-fluid w-100" alt="" style="max-height: 70vh; object-fit: contain;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="downloadModalImage">Download</button>
            </div>
        </div>
    </div>
</div>

<style>
.map-preview-container {
    border: 1px solid #E4E6EF;
    overflow: hidden;
    transition: all 0.3s ease;
}
.map-preview-container:hover {
    border-color: #009EF7;
    box-shadow: 0 4px 12px rgba(0, 158, 247, 0.12);
}
.map-preview-container img {
    transition: transform 0.3s ease;
}
.map-preview-container:hover img {
    transform: scale(1.02);
}
.image-loading {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}
.image-error {
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 14px;
}
@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>

<script>
let currentDownloadImage = '';
let currentDownloadName = '';

function handleImageError(imgElement) {
    const filename = imgElement.getAttribute('data-filename') || '';
    const alternativeUrls = JSON.parse(imgElement.getAttribute('data-alternative-urls') || '[]');

    let idx = 0;
    function tryNext() {
        if (idx >= alternativeUrls.length) {
            showImagePlaceholder(imgElement, filename);
            return;
        }
        const url = alternativeUrls[idx++] + '?t=' + new Date().getTime();
        const test = new Image();
        test.onload = function() { imgElement.src = url; };
        test.onerror = function() { tryNext(); };
        test.src = url;
    }
    tryNext();
}

function showImagePlaceholder(imgElement, filename) {
    imgElement.src = `data:image/svg+xml;base64,${btoa(`
        <svg width="400" height="180" xmlns="http://www.w3.org/2000/svg">
            <rect width="100%" height="100%" fill="#f8f9fa"/>
            <text x="50%" y="45%" font-family="Arial" font-size="14" fill="#6c757d" text-anchor="middle">Image Not Available</text>
            <text x="50%" y="60%" font-family="Arial" font-size="12" fill="#adb5bd" text-anchor="middle">${filename}</text>
        </svg>
    `)}`;
    imgElement.style.cursor = 'default';
    imgElement.classList.add('image-error');
    imgElement.onerror = null;
}

function openImageModal(imageUrl, title) {
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('imageModalTitle');

    modalTitle.textContent = title;
    currentDownloadImage = imageUrl;
    currentDownloadName = title;

    modalImage.className = 'img-fluid w-100 image-loading';
    modalImage.style.minHeight = '200px';
    modalImage.src = '';

    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();

    const test = new Image();
    test.onload = function() {
        modalImage.src = imageUrl;
        modalImage.className = 'img-fluid w-100';
        modalImage.style.minHeight = 'auto';
    };
    test.onerror = function() {
        modalImage.src = modalImage.src = `data:image/svg+xml;base64,${btoa(`
            <svg width="600" height="400" xmlns="http://www.w3.org/2000/svg">
                <rect width="100%" height="100%" fill="#f8f9fa"/>
                <text x="50%" y="45%" font-family="Arial" font-size="16" fill="#6c757d" text-anchor="middle">Image Not Available</text>
            </svg>
        `)}`;
        modalImage.className = 'img-fluid w-100';
        modalImage.style.minHeight = 'auto';
    };
    test.src = imageUrl;
}

function downloadImage(imageFilename, fileName) {
    if (!imageFilename) {
        showToast('error', 'No image available to download.');
        return;
    }

    const urlCandidates = [
        `{{ url('storage/map-images/') }}/${imageFilename}`,
        `{{ asset('storage/map-images/') }}/${imageFilename}`,
        `{{ url('/') }}/storage/map-images/${imageFilename}`,
        `{{ url('/') }}/public/storage/map-images/${imageFilename}`
    ];

    let i = 0;
    function tryDownload() {
        if (i >= urlCandidates.length) {
            showToast('error', 'Could not download the image.');
            return;
        }
        const url = urlCandidates[i++] + '?t=' + new Date().getTime();
        const img = new Image();
        img.onload = function() {
            const link = document.createElement('a');
            link.href = url;
            link.download = (fileName || 'map-drawing') + '.png';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            showToast('success', 'Download started.');
        };
        img.onerror = function() { tryDownload(); };
        img.src = url;
    }
    tryDownload();
}

function deleteDrawing(drawingId, drawingName) {
    if (!confirm(`Delete drawing "${drawingName}"? This cannot be undone.`)) return;

    const deleteUrl = "{{ route('google-map.deleteDrawing', '') }}/" + drawingId;
    const btn = event.target.closest('button');
    const orig = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    btn.disabled = true;

    fetch(deleteUrl, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Drawing deleted.');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast('error', data.message || 'Error deleting drawing.');
            btn.innerHTML = orig;
            btn.disabled = false;
        }
    })
    .catch(err => {
        console.error(err);
        showToast('error', 'Network error.');
        btn.innerHTML = orig;
        btn.disabled = false;
    });
}

function showToast(type, message) {
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    const id = 'toast-' + Date.now();
    const bg = (type === 'success') ? 'bg-success' : 'bg-danger';
    const html = `
        <div id="${id}" class="toast align-items-center text-white ${bg} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    toastContainer.insertAdjacentHTML('beforeend', html);
    const el = document.getElementById(id);
    const bsToast = new bootstrap.Toast(el, { delay: 3000 });
    bsToast.show();
    el.addEventListener('hidden.bs.toast', () => el.remove());
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('downloadModalImage')?.addEventListener('click', function() {
        if (currentDownloadImage && currentDownloadName) {
            const link = document.createElement('a');
            link.href = currentDownloadImage;
            link.download = currentDownloadName + '.png';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            showToast('success', 'Download started!');
        } else {
            showToast('error', 'No image to download.');
        }
    });

    // Warm up dynamic-image elements (let onerror handler do the rest)
    document.querySelectorAll('.dynamic-image').forEach(img => {
        const test = new Image();
        test.onerror = function() {};
        test.src = img.src;
    });
});
</script>

@endsection
