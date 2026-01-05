@extends('layouts.app')
@section('contant')

<!-- Flash message -->
@if(session('message'))
    @php
        $alertType = session('status') === 'success' ? 'alert-success' : 
                    (session('status') === 'exist' ? 'alert-warning' : 'alert-danger');
        $icon = session('status') === 'success' ? 'ki-check-circle' : 
               (session('status') === 'exist' ? 'ki-information' : 'ki-cross-circle');
    @endphp
    <div class="container mt-3 flash-message">
        <div class="alert {{ $alertType }} alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="ki-outline {{ $icon }} fs-2hx me-3"></i>
            <div class="d-flex flex-column">
                <strong>{{ session('status') === 'success' ? 'Success!' : (session('status') === 'exist' ? 'Warning!' : 'Error!') }}</strong>
                <span>{{ session('message') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const flashMessage = document.querySelector('.flash-message');
                if (flashMessage) {
                    flashMessage.remove();
                }
            }, 3000);
        });
    </script>
@endif

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar mb-4">
    <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch">
        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
            <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Area</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="<?php echo url('areas');?>" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold"><i class="ki-outline ki-left fs-2"></i>Back</a>
            </div>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Navbar-->
<div class="card mb-4">
    <div class="card-body pt-4 pb-0">
        <div class="d-row row-wrap row-sm-nowrap pb-4">
            <form id="area_form" name="area_form" method="POST" action="{{ route('areas.save') }}" class="form fv-plugins-bootstrap5 fv-plugins-framework">
                @csrf
                <input type="hidden" id="id" name="id" value="{{isset($singleData['id']) ? $singleData['id'] : ''}}"/>
                
                <div class="row">
                    <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">Location</label>
                        <select id="location_id" name="location_id" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select" required>
                            <option value="">Select Location</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{isset($singleData['location_id']) && $singleData['location_id'] == $location->id ? 'selected' : (old('location_id') == $location->id ? 'selected' : '')}}>
                                    {{ $location->location_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">Area Name</label>
                        <input type="text" id="area_name" name="area_name" class="form-control form-control-solid" value="{{isset($singleData['area_name']) ? $singleData['area_name'] : old('area_name')}}" required />
                        @error('area_name')
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
                    <a href="{{ url('areas') }}" class="btn btn-sm btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Navbar-->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('area_form');
    const submitBtn = document.getElementById('submit_button');
    const processingBtn = document.getElementById('display_processing');

    // Basic form validation
    form.addEventListener('submit', function(e) {
        // Simple required field validation
        let isValid = true;
        
        // Validate location
        if (!$('#location_id').val()) {
            $('#location_id').addClass('is-invalid');
            isValid = false;
        } else {
            $('#location_id').removeClass('is-invalid');
        }
        
        // Validate area name
        const areaName = $('#area_name').val().trim();
        if (!areaName) {
            $('#area_name').addClass('is-invalid');
            isValid = false;
        } else {
            $('#area_name').removeClass('is-invalid');
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
    $('#location_id, #area_name').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
    
    // Auto-remove flash message after 3 seconds
    setTimeout(() => {
        const flashMessage = document.querySelector('.flash-message');
        if (flashMessage) {
            flashMessage.remove();
        }
    }, 3000);
});
</script>

@endsection