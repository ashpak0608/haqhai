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
                <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">{{$permissions['sub_module_name']}}</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="<?php echo url('district');?>" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold"><i class="ki-outline ki-left fs-2"></i>Back</a>
            </div>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Navbar-->
<div class="card mb-4">
    <div class="card-body pt-4 pb-0">
        <div class="d-row row-wrap row-sm-nowrap pb-4">
            <form id="district_form" name="district_form" method="post" class="form fv-plugins-bootstrap5 fv-plugins-framework">
            @csrf
            <input type="hidden" id="id" name="id" value="{{isset($singleData['id']) ? $singleData['id'] : ''}}"/>
                <div class="row">
                    <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">State</label>
                        <select id="state_id" name="state_id" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select" required>
                            <option></option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" {{isset($singleData['state_id']) && $singleData['state_id'] == $state->id ? 'selected' : ''}}>{{ $state->state_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">District Name</label>
                        <input type="text" id="district_name" name="district_name" class="form-control form-control-solid" value="{{isset($singleData['district_name']) ? $singleData['district_name'] : ''}}" />
                    </div>
                </div>
                <hr>
                <div>
                    <button type="submit" id="submit_button" name="submit_button" class="btn btn-sm btn-primary">
                        <i class="ki-outline ki-save-2"></i><span class="indicator-label">Submit</span>
                    </button>
                    <button type="button" id="display_processing" name="display_processing" class="btn btn-sm btn-primary" style="display:none">
                        <span class="spinner-border spinner-border-sm align-middle"></span></span>
                        <span class="indicator-label ms-2">Please wait... 
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Navbar-->
<script src="{{ url('public/validation/district.js') }}"></script>
@endsection