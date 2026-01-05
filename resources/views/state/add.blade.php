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
@endif

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar mb-4">
    <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch">
        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
            <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                    {{ isset($singleData['id']) ? 'Edit State' : 'Add State' }} || HAQHAI
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('state') }}" class="text-muted text-hover-primary">State</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">{{ isset($singleData['id']) ? 'Edit' : 'Add' }}</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ url('state') }}" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold">
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
            <form id="states_form" name="states_form" method="post" action="{{ route('state.save') }}" class="form fv-plugins-bootstrap5 fv-plugins-framework">
                @csrf
                <input type="hidden" id="id" name="id" value="{{ $singleData['id'] ?? '' }}"/>
                <div class="row">
                    <div class="col-md-6">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">State Name</label>
                        <input type="text" id="state_name" name="state_name" class="form-control form-control-solid" 
                               value="{{ old('state_name', $singleData['state_name'] ?? '') }}" 
                               placeholder="Enter state name" required />
                        @if($errors->has('state_name'))
                            <div class="text-danger small mt-1">{{ $errors->first('state_name') }}</div>
                        @endif
                        <div class="text-muted fs-7 mt-1">Enter the name of the state</div>
                    </div>
                </div>
                <hr>
                <div class="d-flex gap-3">
                    <button type="submit" id="submit_button" name="submit_button" class="btn btn-sm btn-primary">
                        <i class="ki-outline ki-save-2"></i>
                        <span class="indicator-label">{{ isset($singleData['id']) ? 'Update' : 'Submit' }}</span>
                    </button>
                    <button type="button" id="display_processing" name="display_processing" class="btn btn-sm btn-primary" style="display:none">
                        <span class="spinner-border spinner-border-sm align-middle"></span>
                        <span class="indicator-label ms-2">Please wait...</span>
                    </button>
                    <a href="{{ url('state') }}" class="btn btn-sm btn-secondary">
                        <i class="ki-outline ki-cross"></i>
                        <span class="indicator-label">Cancel</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Navbar-->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('states_form');
    const submitBtn = document.getElementById('submit_button');
    const processingBtn = document.getElementById('display_processing');

    // Regular form submission (non-AJAX) for better error handling
    form.addEventListener('submit', function(e) {
        // Basic validation
        const stateName = document.getElementById('state_name').value.trim();
        if (!stateName) {
            e.preventDefault();
            showAlert('Please enter state name', 'warning');
            document.getElementById('state_name').focus();
            return false;
        }
        
        // Show processing state
        submitBtn.style.display = 'none';
        processingBtn.style.display = 'inline-block';
        
        // Let the form submit normally - this will show Laravel validation errors if any
        return true;
    });

    // Alternative: AJAX submission (uncomment if you prefer AJAX)
    /*
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Basic validation
        const stateName = document.getElementById('state_name').value.trim();
        if (!stateName) {
            showAlert('Please enter state name', 'warning');
            document.getElementById('state_name').focus();
            return;
        }
        
        // Show processing state
        submitBtn.style.display = 'none';
        processingBtn.style.display = 'inline-block';
        
        // Submit form via AJAX
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.status === 'success') {
                showAlert(data.message || 'State saved successfully!', 'success');
                // Redirect to index page after success
                setTimeout(() => {
                    window.location.href = "{{ route('state.index') }}";
                }, 1500);
            } else if (data.status === 'exist') {
                showAlert(data.message || 'State already exists!', 'warning');
                submitBtn.style.display = 'inline-block';
                processingBtn.style.display = 'none';
            } else {
                showAlert(data.message || 'Something went wrong!', 'danger');
                submitBtn.style.display = 'inline-block';
                processingBtn.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Network error: ' + error.message, 'danger');
            submitBtn.style.display = 'inline-block';
            processingBtn.style.display = 'none';
        });
    });
    */

    function showAlert(message, type) {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.custom-alert');
        existingAlerts.forEach(alert => alert.remove());
        
        const wrapper = document.createElement('div');
        wrapper.className = `custom-alert alert alert-${type} alert-dismissible fade show`;
        wrapper.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="ki-outline ${type === 'success' ? 'ki-check-circle' : type === 'warning' ? 'ki-information' : 'ki-cross-circle'} fs-2hx me-3"></i>
                <div class="d-flex flex-column">
                    <strong>${type === 'success' ? 'Success!' : type === 'warning' ? 'Warning!' : 'Error!'}</strong>
                    <span>${message}</span>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        const container = document.querySelector('.container') || document.body;
        container.prepend(wrapper);
        
        setTimeout(() => {
            if (wrapper.parentNode) {
                wrapper.remove();
            }
        }, 5000);
    }

    // Auto-remove existing flash messages after 3 seconds
    setTimeout(() => {
        const flashMessages = document.querySelectorAll('.flash-message');
        flashMessages.forEach(message => {
            message.remove();
        });
    }, 3000);
});
</script>

@endsection