@extends('layouts.app')
@section('contant')

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar mb-4">
    <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch">
        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
            <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Login History</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="<?php echo url('login-history');?>" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold"><i class="ki-outline ki-left fs-2"></i>Back</a>
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
                        <span class="fw-bold fs-6 text-gray-800">User Name 1</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Login Date-Time</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">31-03-2025 12:00 AM</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">IP Address</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">123.123.123.123</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Device Details</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">Android / IOS</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <label class="col-lg-4 fw-semibold text-muted">Browser Details</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-800">Google / Safari</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Navbar-->

@endsection