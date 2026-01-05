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
                <a href="{{ url('road') }}" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold">
                    <i class="ki-outline ki-left fs-2"></i>Back
                </a>
            </div>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Navbar-->
<div class="card mb-4">
    <div class="card-body pt-4 pb-0">
        <div class="d-row row-wrap row-sm-nowrap pb-4">
            <form id="road_form" name="road_form" method="post" action="{{ url('road/save') }}" class="form fv-plugins-bootstrap5 fv-plugins-framework">
                @csrf
                <input type="hidden" id="id" name="id" value="{{ isset($singleData['id']) ? $singleData['id'] : '' }}"/>
                
                <div class="row">
                    <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">District</label>
                        <select id="district_id" name="district_id" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select" required>
                            <option value="">Select District</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" {{ (isset($singleData['district_id']) && $singleData['district_id'] == $district->id) ? 'selected' : (old('district_id') == $district->id ? 'selected' : '') }}>
                                    {{ $district->district_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('district_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">road Name</label>
                        <input type="text" id="road_name" name="road_name" class="form-control form-control-solid" value="{{ old('road_name', isset($singleData['road_name']) ? $singleData['road_name'] : '') }}" required />
                        @error('road_name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <hr>
                <div>
                    <button type="submit" id="submit_button" name="submit_button" class="btn btn-sm btn-primary">
                        <i class="ki-outline ki-save-2"></i><span class="indicator-label">Submit</span>
                    </button>
                    <button type="button" id="display_processing" name="display_processing" class="btn btn-sm btn-primary" style="display:none">
                        <span class="spinner-border spinner-border-sm align-middle"></span>
                        <span class="indicator-label ms-2">Please wait...</span>
                    </button>
                    <a href="{{ url('road') }}" class="btn btn-sm btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Navbar-->

<script src="{{ url('public/validation/road.js') }}"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('#district_id').select2({
        placeholder: "Select District",
        allowClear: true
    });

    // Basic form validation
    $('#road_form').on('submit', function(e) {
        // Simple required field validation
        let isValid = true;
        
        // Validate district
        if (!$('#district_id').val()) {
            $('#district_id').addClass('is-invalid');
            isValid = false;
        } else {
            $('#district_id').removeClass('is-invalid');
        }
        
        // Validate road name
        const roadName = $('#road_name').val().trim();
        if (!roadName) {
            $('#road_name').addClass('is-invalid');
            isValid = false;
        } else {
            $('#road_name').removeClass('is-invalid');
        }
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
        
        // Show processing indicator
        $('#submit_button').hide();
        $('#display_processing').show();
        
        // Allow form to submit normally
        return true;
    });
    
    // Remove validation classes on input
    $('#district_id, #road_name').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>

@endsection