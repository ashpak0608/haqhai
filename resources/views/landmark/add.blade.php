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
                <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">{{$permissions['sub_module_name']}} - {{isset($singleData['id']) ? 'Edit' : 'Add'}}</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="<?php echo url('landmark');?>" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold"><i class="ki-outline ki-left fs-2"></i>Back</a>
            </div>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Navbar-->
<div class="card mb-4">
    <div class="card-body pt-4 pb-0">
        <div class="d-row row-wrap row-sm-nowrap pb-4">
            <form id="landmark_form" name="landmark_form" method="post" action="{{ url('landmark/save') }}" class="form fv-plugins-bootstrap5 fv-plugins-framework">
                @csrf
                <input type="hidden" id="id" name="id" value="{{ isset($singleData['id']) ? $singleData['id'] : '' }}"/>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required fs-6 fw-semibold mb-1 ms-1">Area</label>
                            <select id="area_id" name="area_id" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select Area" required>
                                <option value=""></option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" 
                                        {{ (isset($singleData['area_id']) && $singleData['area_id'] == $area->id) ? 'selected' : '' }}>
                                        {{ $area->area_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please select an area</div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required fs-6 fw-semibold mb-1 ms-1">City</label>
                            <select id="city_id" name="city_id" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select City" required>
                                <option value=""></option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" 
                                        {{ (isset($singleData['city_id']) && $singleData['city_id'] == $city->id) ? 'selected' : '' }}>
                                        {{ $city->city_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please select a city</div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="fs-6 fw-semibold mb-1 ms-1">Ward</label>
                            <select id="ward_id" name="ward_id" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select Ward">
                                <option value=""></option>
                                @if(isset($singleData['ward_id']) && $singleData['ward_id'] && isset($wards) && count($wards) > 0)
                                    @foreach($wards as $ward)
                                        <option value="{{ $ward->id }}" 
                                            {{ (isset($singleData['ward_id']) && $singleData['ward_id'] == $ward->id) ? 'selected' : '' }}>
                                            {{ $ward->ward_name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required fs-6 fw-semibold mb-1 ms-1">Landmark Name</label>
                            <input type="text" id="landmark_name" name="landmark_name" class="form-control form-control-solid" 
                                value="{{ isset($singleData['landmark_name']) ? $singleData['landmark_name'] : '' }}" 
                                placeholder="Enter landmark name" />
                            <div class="invalid-feedback">Please enter landmark name</div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-flex gap-2">
                    <button type="submit" id="submit_button" name="submit_button" class="btn btn-sm btn-primary">
                        <i class="ki-outline ki-save-2"></i>
                        <span class="indicator-label">Submit</span>
                    </button>
                    
                    <button type="button" id="display_processing" name="display_processing" class="btn btn-sm btn-primary" style="display:none">
                        <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                        <span class="indicator-label">Please wait...</span>
                    </button>
                    
                    <a href="<?php echo url('landmark');?>" class="btn btn-sm btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Navbar-->

<script>
$(document).ready(function() {
    // Initialize Select2
    $('#area_id, #city_id, #ward_id').select2({
        placeholder: function() {
            return $(this).data('placeholder');
        },
        allowClear: true
    });

    // Load wards when city changes
    $('#city_id').on('change', function() {
        var cityId = $(this).val();
        var wardSelect = $('#ward_id');
        
        if (cityId) {
            $.ajax({
                url: "{{ url('landmark/get-wards-by-city') }}",
                type: "GET",
                data: { 
                    'city_id': cityId,
                    '_token': '{{ csrf_token() }}'
                },
                dataType: 'json',
                beforeSend: function() {
                    wardSelect.prop('disabled', true);
                    wardSelect.html('<option value="">Loading wards...</option>');
                    wardSelect.trigger('change');
                },
                success: function(response) {
                    wardSelect.prop('disabled', false);
                    wardSelect.empty();
                    wardSelect.append('<option value=""></option>');
                    
                    if (response && response.length > 0) {
                        $.each(response, function(index, ward) {
                            wardSelect.append('<option value="' + ward.id + '">' + ward.ward_name + '</option>');
                        });
                        
                        // If editing and city matches, select the saved ward
                        @if(isset($singleData['ward_id']) && isset($singleData['city_id']))
                            if (cityId == '{{ $singleData["city_id"] }}') {
                                setTimeout(function() {
                                    wardSelect.val('{{ $singleData["ward_id"] }}').trigger('change');
                                }, 100);
                            }
                        @endif
                    } else {
                        wardSelect.append('<option value="">No wards found</option>');
                    }
                    
                    // Reinitialize Select2
                    wardSelect.select2({
                        placeholder: "Select Ward",
                        allowClear: true
                    });
                },
                error: function(xhr, status, error) {
                    wardSelect.prop('disabled', false);
                    wardSelect.empty();
                    wardSelect.append('<option value="">Error loading wards</option>');
                    wardSelect.select2({
                        placeholder: "Select Ward",
                        allowClear: true
                    });
                    console.error('Error loading wards:', error);
                }
            });
        } else {
            wardSelect.empty();
            wardSelect.append('<option value=""></option>');
            wardSelect.val('').trigger('change');
            wardSelect.select2({
                placeholder: "Select Ward",
                allowClear: true
            });
        }
    });

    // Form validation and submission
    $('#landmark_form').validate({
        rules: {
            area_id: {
                required: true
            },
            city_id: {
                required: true
            },
            landmark_name: {
                required: true,
                maxlength: 255
            }
        },
        messages: {
            area_id: {
                required: "Please select an area"
            },
            city_id: {
                required: "Please select a city"
            },
            landmark_name: {
                required: "Please enter landmark name",
                maxlength: "Landmark name cannot exceed 255 characters"
            }
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            // Show loading state
            $('#submit_button').hide();
            $('#display_processing').show();
            
            // Submit the form via AJAX
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: $(form).serialize(),
                dataType: 'json',
                success: function(response) {
                    // Hide loading state
                    $('#submit_button').show();
                    $('#display_processing').hide();
                    
                    if (response.status === 'success') {
                        // Show success message and redirect
                        showAlert(response.message, 'success');
                        setTimeout(function() {
                            window.location.href = "{{ url('landmark') }}";
                        }, 1500);
                    } else if (response.status === 'exist') {
                        // Show warning for duplicate entry
                        showAlert(response.message, 'warning');
                        if (response.unique_field) {
                            for (let field in response.unique_field) {
                                $('[name="' + field + '"]').addClass('is-invalid');
                                $('[name="' + field + '"]').after('<div class="invalid-feedback">' + response.message + '</div>');
                            }
                        }
                    } else {
                        // Show validation errors
                        if (response.errors) {
                            for (let field in response.errors) {
                                let errorElement = $('[name="' + field + '"]');
                                errorElement.addClass('is-invalid');
                                errorElement.after('<div class="invalid-feedback">' + response.errors[field][0] + '</div>');
                            }
                        } else {
                            showAlert(response.message || 'Something went wrong', 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    // Hide loading state
                    $('#submit_button').show();
                    $('#display_processing').hide();
                    
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Show validation errors
                        for (let field in xhr.responseJSON.errors) {
                            let errorElement = $('[name="' + field + '"]');
                            errorElement.addClass('is-invalid');
                            errorElement.after('<div class="invalid-feedback">' + xhr.responseJSON.errors[field][0] + '</div>');
                        }
                    } else {
                        showAlert('An error occurred. Please try again.', 'danger');
                    }
                }
            });
            
            return false; // Prevent normal form submission
        }
    });

    // Helper function to show alerts
    function showAlert(message, type) {
        // Remove existing alerts
        $('.custom-alert').remove();
        
        // Create alert element
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'warning' ? 'alert-warning' : 'alert-danger';
        const iconClass = type === 'success' ? 'ki-check-circle' : 
                         type === 'warning' ? 'ki-information' : 'ki-cross-circle';
        
        const alertHtml = `
            <div class="custom-alert alert ${alertClass} alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ki-outline ${iconClass} fs-2hx me-3"></i>
                    <div class="d-flex flex-column">
                        <strong>${type === 'success' ? 'Success!' : type === 'warning' ? 'Warning!' : 'Error!'}</strong>
                        <span>${message}</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Add alert to page
        $('.card-body').prepend(alertHtml);
        
        // Auto-remove after 5 seconds
        setTimeout(function() {
            $('.custom-alert').alert('close');
        }, 5000);
    }

    // Trigger city change on page load if city is selected (for edit mode)
    @if(isset($singleData['city_id']) && $singleData['city_id'])
        setTimeout(function() {
            $('#city_id').trigger('change');
        }, 300);
    @endif
});
</script>
@endsection