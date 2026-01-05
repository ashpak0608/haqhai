@extends('layouts.app')
@section('contant')

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar mb-4">
    <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch">
        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
            <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">{{$permissions['sub_module_name']}}</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="<?php echo url('ward');?>" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold"><i class="ki-outline ki-left fs-2"></i>Back</a>
            </div>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Navbar-->
<div class="card mb-4">
    <div class="card-body pt-4 pb-0">
        <div class="d-row row-wrap row-sm-nowrap pb-4">
            <form id="view_from" name="view_from" class="form fv-plugins-bootstrap5 fv-plugins-framework">
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">City Name</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->city_name) ? $views->city_name : '' }}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Ward Name</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->ward_name) ? $views->ward_name : '' }}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Ward Number</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->ward_number) ? $views->ward_number : '' }}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Status</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">
                            @if($views->status == '0')
                            <span class="badge badge-light-success fs-7 fw-bold">Active</span>
                            @else
                            <span class="badge badge-light-danger fs-7 fw-bold">Inactive</span>
                            @endif
                        </span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Created By</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->created_by) ? $views->created_by : '' }}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Created At</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->created_at) ? $views->created_at : '' }}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Modified By</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->updated_by) ? $views->updated_by : '' }}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Modified At</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->updated_at) ? $views->updated_at : '' }}</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Saved Map Drawings Section -->
<div class="separator separator-dashed my-10"></div>

<!--<div class="d-flex justify-content-between align-items-center mb-6">-->
<!--    <h3 class="card-label fw-bold fs-3">Saved Map Drawings</h3>-->
<!--    <a href="{{ route('ward.map', $views->id) }}" class="btn btn-primary">-->
<!--        <i class="ki-outline ki-location fs-2"></i>Create New Drawing-->
<!--    </a>-->
<!--</div>-->

@if($drawings && count($drawings) > 0)
    <div class="row">
        @foreach($drawings as $drawing)
        <div class="col-md-6 col-lg-4 mb-6">
            <div class="card card-flush h-100">
                <div class="card-header">
                    <div class="card-title">
                        <h4 class="fw-bold">{{ $drawing->drawing_name }}</h4>
                    </div>
                    <div class="card-toolbar">
                        <span class="badge badge-light-primary">{{ $drawing->total_shapes }} shapes</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($drawing->map_image)
                        @php
                            // Use direct image route instead of asset()
                            $imageUrl = url('/image/' . $drawing->map_image);
                        @endphp
                        <div class="map-preview-container">
                            <img src="{{ $imageUrl }}" 
                                 alt="{{ $drawing->drawing_name }}" 
                                 class="img-fluid rounded-top w-100"
                                 style="max-height: 200px; object-fit: cover;"
                                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjM0Y0MjU0Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iI0ZGRkZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk1hcCBJbWFnZTwvdGV4dD48dGV4dCB4PSI1MCUiIHk9IjY1JSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjEyIiBmaWxsPSIjRkZGRkZGIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+UmVhZHkgdG8gVmlldzwvdGV4dD48L3N2Zz4='">
                        </div>
                    @else
                        <div class="text-center p-10 bg-light-warning rounded-top">
                            <i class="ki-outline ki-location text-warning fs-2hx mb-4"></i>
                            <p class="text-warning fw-semibold">No Image Preview Available</p>
                            <p class="text-muted fs-7">Drawing data is saved but no image was captured</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($drawing->created_at)->format('M d, Y h:i A') }}
                        </small>
                        <div class="d-flex gap-2">
                            @if($drawing->map_image)
                                <a href="{{ $imageUrl }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-icon btn-light-primary"
                                   title="View Full Image">
                                    <i class="ki-outline ki-eye fs-2"></i>
                                </a>
                                <button class="btn btn-sm btn-icon btn-light-info" 
                                        onclick="downloadImage('{{ $drawing->map_image }}', '{{ $drawing->drawing_name }}')"
                                        title="Download Image">
                                    <i class="ki-outline ki-down fs-2"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="text-center py-10">
        <i class="ki-outline ki-location text-primary fs-4hx mb-4"></i>
        <h4 class="text-gray-600 fw-semibold">No Saved Drawings Found</h4>
        <p class="text-muted fs-6">Get started by creating your first map drawing</p>
        <a href="{{ route('ward.map', $views->id) }}" class="btn btn-primary mt-4">
            <i class="ki-outline ki-location fs-2"></i>Create New Drawing
        </a>
    </div>
@endif

<style>
.map-preview-container {
    border-bottom: 1px solid #E4E6EF;
    overflow: hidden;
}
.map-preview-container img {
    transition: transform 0.3s ease;
}
.map-preview-container img:hover {
    transform: scale(1.05);
}
</style>

<script>
function downloadImage(imageFilename, fileName) {
    if (!imageFilename) {
        alert('No image available to download.');
        return;
    }
    
    const imageUrl = "{{ url('/image/') }}/" + imageFilename;
    const link = document.createElement('a');
    link.href = imageUrl;
    link.download = fileName + '.png';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection