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
            <form id="view_from" name="view_from" class="form fv-plugins-bootstrap5 fv-plugins-framework">
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">User Name</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->user_name) ? $views->user_name : ''}}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Email Id</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->email_id) ? $views->email_id : ''}}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Phone 1</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->phone_1) ? $views->phone_1 : ''}}</span>
                    </div>
                </div>
                <!-- <hr> -->
                <!-- <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Phone 2</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800"></span>
                    </div>
                </div> -->
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">User Level</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->level_name) ? $views->level_name : ''}}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Gender</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->gender) ? $views->gender : ''}}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Marital Status</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->marital_status) ? $views->marital_status : ''}}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">DOB</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->dob) ? $views->dob : ''}}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">DOA</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->doa) ? $views->doa : ''}}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Aadhaar Card No.</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->aadhar_card_no) ? $views->aadhar_card_no : ''}}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">PAN Card No.</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->pan_card_no) ? $views->pan_card_no : ''}}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">District</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->district_name) ? $views->district_name : ''}}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Location</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->location_name) ? $views->location_name : ''}}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Latitude</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->latitude) ? $views->latitude : ''}}</span>
                    </div>
                </div>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Longitude</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->longitude) ? $views->longitude : ''}}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Address</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->user_name) ? $views->user_name : ''}}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">About Me</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->about_me) ? $views->about_me : ''}}</span>
                    </div>
                </div>
                <!-- <hr> -->
                <!-- <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">From year in Business</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800"></span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">RERA Registration No.</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800"></span>
                    </div>
                </div> -->
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
                        <span class="fw-bold fs-6 text-gray-800">{{ isset($views->updated_by) ? $views->updated_by : '' }}</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Navbar-->

@endsection