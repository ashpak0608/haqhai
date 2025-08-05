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
                <a href="<?php echo url('user');?>" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold"><i class="ki-outline ki-left fs-2"></i>Back</a>
            </div>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Navbar-->
<div class="card mb-4">
    <div class="card-body pt-4 pb-0">
        <div class="d-row row-wrap row-sm-nowrap pb-4">
            <form id="user_form" name="user_form" method="post" class="form fv-plugins-bootstrap5 fv-plugins-framework">
            @csrf
            <input type="hidden" id="id" name="id" value="{{isset($singleData['id']) ? $singleData['id'] : ''}}"/>
                <div class="row">
                    <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">User Name</label>
                        <input type="text" id="user_name" name="user_name" class="form-control form-control-solid" value="{{ isset($singleData['user_name']) ? $singleData['user_name'] : ''}}" />
                    </div>
                    <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">Email Id</label>
                        <input type="text" id="email_id" name="email_id" class="form-control form-control-solid" value="{{ isset($singleData['email_id']) ? $singleData['email_id'] : ''}}" />
                    </div>
                    <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">Phone 1</label>
                        <input type="text" id="phone_1" name="phone_1" class="form-control form-control-solid" minlength="10" maxlength="10" value="{{ isset($singleData['phone_1']) ? $singleData['phone_1'] : ''}}" />
                    </div>
                </div>
                <div class="row mt-3">
                    <!-- <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-1 ms-1">Phone 2</label>
                        <input type="text" id="phone_2" name="phone_2" class="form-control form-control-solid" minlength="10" maxlength="10" value="{{ isset($singleData['phone_2']) ? $singleData['phone_2'] : ''}}"/>
                    </div> -->
                    <!-- <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">User Level</label>
                        <select id="level_id" name="level_id" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select" required >
                            <option></option>
                            @foreach($userLevels as $levels)
                                <option value="{{ $levels->id }}" {{isset($singleData['level_id']) && $singleData['level_id'] == $levels->id ? 'selected' : ''}}>{{ $levels->level_name }}</option>
                            @endforeach
                        </select>
                    </div> -->
                    <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">User Role</label>
                        <select id="role_id" name="role_id" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select" required >
                            <option></option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{isset($singleData['role_id']) && $singleData['role_id'] == $role->id ? 'selected' : ''}}>{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-1 ms-1">Gender</label>
                        <select id="gender" name="gender" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select">
                            <option></option>
                            <option value="1" {{isset($singleData['gender']) && $singleData['gender'] == '1' ? 'selected' : ''}}>Male</option>
                            <option value="1" {{isset($singleData['gender']) && $singleData['gender'] == '2' ? 'selected' : ''}}>Female</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-1 ms-1">Marital Status</label>
                        <select id="marital_status" name="marital_status" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select">
                            <option></option>
                            <option value="1" {{isset($singleData['marital_status']) && $singleData['marital_status'] == '1' ? 'selected' : ''}}>Yes</option>
                            <option value="2" {{isset($singleData['marital_status']) && $singleData['marital_status'] == '2' ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-1 ms-1">DOB</label>
                        <input type="date" id="dob" name="dob" class="form-control form-control-solid" value="{{ isset($singleData['dob']) ? $singleData['dob'] : ''}}"  max="{{ date('Y-m-d', strtotime('-18 years')) }}"/>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-1 ms-1">DOA</label>
                        <input type="date" id="doa" name="doa" class="form-control form-control-solid" value="{{ isset($singleData['doa']) ? $singleData['doa'] : ''}}" max="{{ date('Y-m-d') }}"/>
                    </div>
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-1 ms-1">Aadhar Card No.</label>
                        <input type="text" id="aadhar_card_no" name="aadhar_card_no" class="form-control form-control-solid" value="{{ isset($singleData['aadhar_card_no']) ? $singleData['aadhar_card_no'] : ''}}" maxlength="12"
                        pattern="\d{12}" title="Aadhar must be exactly 12 digits" />
                    </div>
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-1 ms-1">PAN Card No.</label>
                        <input type="text" id="pan_card_no" name="pan_card_no" class="form-control form-control-solid" value="{{ isset($singleData['pan_card_no']) ? $singleData['pan_card_no'] : ''}}" maxlength="10"
                        pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}"
                        title="PAN must be 5 letters, 4 numbers, 1 letter (e.g., ABCDE1234F)"/>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-1 ms-1">District</label>
                        <select id="district_id" name="district_id" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select" onChange="getLocation(this.value)" required>
                            <option></option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" {{isset($singleData['district_id']) && $singleData['district_id'] == $district->id ? 'selected' : ''}}>{{ $district->district_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-1 ms-1">Location</label>
                        <select id="location_id" name="location_id" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select">
                            @if(isset($singleData['district_id']))
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{isset($singleData['location_id']) && $singleData['location_id'] == $location->id ? 'selected' : ''}}>{{ $location->location_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-1 ms-1">Latitude</label>
                        <input type="text" id="latitude" name="latitude" class="form-control form-control-solid" value="{{ isset($singleData['latitude']) ? $singleData['latitude'] : ''}}"/>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-1 ms-1">Longitude</label>
                        <input type="text" id="longitude" name="longitude" class="form-control form-control-solid" value="{{ isset($singleData['longitude']) ? $singleData['longitude'] : ''}}"/>
                    </div>
                    <div class="col-md-8">
                        <label class="fs-6 fw-semibold mb-1 ms-1">Address</label>
                        <textarea id="address" name="address" class="form-control form-control-solid">{{ isset($singleData['address']) ? $singleData['address'] : ''}}</textarea>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <label class="fs-6 fw-semibold mb-1 ms-1">About Me</label>
                        <textarea id="about_me" name="about_me" class="form-control form-control-solid">{{ isset($singleData['about_me']) ? $singleData['about_me'] : ''}}</textarea>
                    </div>
                </div>
                <!-- <div class="row mt-3">
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-1 ms-1">From year in Business</label>
                        <select id="from_year_in_business" name="from_year_in_business" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select">
                            <option></option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-1 ms-1">RERA Registration No.</label>
                        <input type="text" id="rera_registration_no" name="rera_registration_no" class="form-control form-control-solid" value="{{ isset($singleData['email_id']) ? $singleData['email_id'] : ''}}"/>
                    </div>
                </div> -->
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
<script src="{{ url('public/validation/user.js') }}"></script>
@endsection