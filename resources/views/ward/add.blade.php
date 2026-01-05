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
                <a href="{{ route('ward.index') }}" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold"><i class="ki-outline ki-left fs-2"></i>Back</a>
            </div>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Navbar-->
<div class="card mb-4">
    <div class="card-body pt-4 pb-0">
        <div class="d-row row-wrap row-sm-nowrap pb-4">
            <!-- Toast Notification Container -->
            <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>
            
            <!-- Inline Alert Messages -->
            <div id="alert-message" style="display: none;" class="alert alert-dismissible fade show mb-4" role="alert">
                <span id="alert-message-text"></span>
                <button type="button" class="btn-close" onclick="hideAlert()"></button>
            </div>
            
            <!-- FIX: Change the button type to button and handle click instead of form submit -->
            <form id="ward_form" name="ward_form" class="form fv-plugins-bootstrap5 fv-plugins-framework">
                @csrf
                <input type="hidden" id="id" name="id" value="{{isset($singleData['id']) ? $singleData['id'] : ''}}"/>
                
                <div class="row">
                    <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">City</label>
                        <select id="city_id" name="city_id" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select" required>
                            <option value="">Select City</option>
                            @if(isset($cities) && count($cities) > 0)
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" 
                                        {{ (isset($singleData['city_id']) && $singleData['city_id'] == $city->id) ? 'selected' : '' }}>
                                        {{ $city->city_name }}
                                    </option>
                                @endforeach
                            @else
                                <option value="">No cities available</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">Ward Name</label>
                        <input type="text" id="ward_name" name="ward_name" class="form-control form-control-solid" value="{{isset($singleData['ward_name']) ? $singleData['ward_name'] : ''}}" required />
                    </div>
                    <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">Ward Number</label>
                        <input type="text" id="ward_number" name="ward_number" class="form-control form-control-solid" value="{{isset($singleData['ward_number']) ? $singleData['ward_number'] : ''}}" required />
                    </div>
                </div>

                <!-- Google Map Integration Section -->
                @if(isset($singleData['id']))
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card card-dashed">
                            <div class="card-header">
                                <h3 class="card-title">Google Map Integration</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="text-muted">Create map drawings to define the ward boundaries and areas.</p>
                                        <div class="d-flex align-items-center">
                                            <span class="text-muted me-3">Default Location:</span>
                                            <span class="badge badge-light-primary">
                                                Based on City Coordinates
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <a href="{{ route('ward.map', $singleData['id']) }}" class="btn btn-primary" target="_blank">
                                            <i class="ki-outline ki-location fs-2 me-2"></i>Create New Drawing
                                        </a>
                                        <p class="text-muted mt-2 fs-7">Opens in new window</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <hr>
                <div>
                    <!-- FIX: Changed type to button to prevent form submission -->
                    <button type="button" id="submit_button" name="submit_button" class="btn btn-sm btn-primary">
                        <i class="ki-outline ki-save-2"></i><span class="indicator-label">Submit</span>
                    </button>
                    <button type="button" id="display_processing" name="display_processing" class="btn btn-sm btn-primary" style="display:none">
                        <span class="spinner-border spinner-border-sm align-middle"></span>
                        <span class="indicator-label ms-2">Please wait...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Navbar-->

<script>
document.getElementById('submit_button').addEventListener('click', function(e) {
    e.preventDefault();
    
    const formData = new FormData(document.getElementById('ward_form'));
    
    fetch("{{ route('ward.save') }}", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Saved successfully!');
            window.location.href = "{{ route('ward.index') }}";
        } else {
            alert(data.message || 'Error occurred');
        }
    })
    .catch(error => {
        alert('Network error: ' + error.message);
    });
});
</script>

<!-- Load your existing validation file -->
<script src="{{ url('public/validation/ward.js') }}"></script>

@endsection