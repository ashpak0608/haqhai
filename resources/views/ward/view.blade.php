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
<!--end::Navbar-->

@endsection