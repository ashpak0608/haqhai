@extends('layouts.app')
@section('contant')

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar mb-4">
    <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch">
        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
            <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Login History</h1>
            </div>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Navbar-->
<div class="card mb-5 mb-4">
    <div class="card-body pt-4 pb-0">
        <div class="d-row row-wrap row-sm-nowrap pb-4">
            <form id="search_from" name="search_from" class="form fv-plugins-bootstrap5 fv-plugins-framework">
                <div class="row">
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-1 ms-1">User</label>
                        <select id="user_id" name="user_id" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select">
                            <option></option>
                        </select>
                    </div>
                </div>
                <hr>
                <div>
                    <button type="button" id="search_button" name="search_button" class="btn btn-sm btn-primary">
                        <i class="ki-outline ki-filter-search"></i><span class="indicator-label">Search</span>
                    </button>
                    <button type="button" id="display_processing" name="display_processing" class="btn btn-sm btn-primary" style="display:none">
                        <span class="spinner-border spinner-border-sm align-middle"></span></span>
                        <span class="indicator-label ms-2">Please wait... 
                    </button>
                    <button type="button" id="cancel" name="clear_button" class="btn btn-sm btn-secondary">
                        <i class="ki-outline ki-eraser"></i><span class="indicator-label">clear</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Navbar-->

<!--begin::Card-->
<div class="card">
    <div class="card-header border-0 pt-1">
        <div class="card-title">
            <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Select Limit" data-kt-user-table-filter="role" data-hide-search="true" id="search_limits" name="search_limits">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="200">200</option>
            </select>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                <a href="javascript:void(0);" id="export_data" name="export_data" class="btn btn-light-primary"><i class="ki-outline ki-exit-up fs-2"></i>Export</a>
            </div>
        </div>
    </div>
    <div class="card-body py-4">
        <div class="table-responsive">
            <table id="table_content" name="table_content" class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 gs-0">
                        <th>Sr. No.</th>
                        <th>User</th>
                        <th>Login Date-Time</th>
                        <th>IP Address</th>
                        <th>Device Details</th>
                        <th>Browser Details</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold" id="table-content">
                @foreach($lists as $key => $list)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$list->full_name}}</td>
                        <td>{{$list->login_date_time}}</td>
                        <td>{{$list->ip_address}}</td>
                        <td>{{$list->platform}}</td>
                        <td>{{$list->browser}}</td>
                        <td>
                            <a href="<?php echo url('login-history/view/'.$list->id);?>" class="btn btn-icon btn-active-light-primary w-30px h-30px me-3"><i class="ki-outline ki-eye text-primary fs-3"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @if($total_count > 0)
        <div class="row">
            <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar">
                <label class="text-muted mt-1 m-b-0" id="showing">
                    Showing 1 to {{ ($total_count > 10) ? 10 : $total_count }} of {{ $total_count }} records.
                </label>
            </div>
            <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-end">
                <div class="dt-paging paging_simple_numbers">
                    <nav id="pagination" aria-label="pagination">
                        @php
                            $limit = count($lists); 
                            $remaining = $total_count % $limit;
                            $total_page = ($remaining > 0) ? (int)($total_count / $limit) + 1 : (int)($total_count / $limit);
                        @endphp
                        <ul class="pagination">
                            <li class="dt-paging-button page-item strt filter" data-limit="{{ $limit }}" data-start="0">
                                <a href="javascript:void(0);" class="page-link previous"><i class="ki-outline ki-double-left fs-2"></i></a>
                            </li>
                            <li class="dt-paging-button page-item prev filter" data-limit="{{ $limit }}" data-start="0">
                                <a href="javascript:void(0);" class="page-link previous"><i class="previous"></i></a>
                            </li>
                            <li class="dt-paging-button page-item active pageActive">
                                <a href="javascript:void(0);" class="page-link disp">1</a>
                            </li>
                            <li class="dt-paging-button page-item next filter" data-limit="{{ $limit }}" data-start="{{ ($total_page > 1) ? $limit : 0 }}">
                                <a href="javascript:void(0);" class="page-link next"><i class="next"></i></a>
                            </li>
                            <li class="dt-paging-button page-item last filter" data-limit="{{ $limit }}" data-start="{{ ($total_page - 1) * $limit }}">
                                <a href="javascript:void(0);" class="page-link next"><i class="ki-outline ki-double-right fs-2"></i></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-12 text-center">
                <label class="text-danger mt-2 mb-2">No records found.</label>
            </div>
        </div>
        @endif
    </div>
</div>
<!--end::Card-->
<script src="{{ url('public/validation/login_history.js') }}"></script>
@endsection