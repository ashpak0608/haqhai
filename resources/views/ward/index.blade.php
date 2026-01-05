@extends('layouts.app')
@section('contant')

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 my-0">{{$permissions['sub_module_name']}}</h1>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <a href="<?php echo url('ward/add/');?>" class="btn btn-flex btn-primary">
                <i class="ki-outline ki-plus fs-2"></i>Add Ward
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Navbar-->
<div class="card mb-5 mb-xl-8">
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold fs-3 mb-1">Filters</span>
        </h3>
    </div>
    <div class="card-body pt-0 pb-5">
        <form id="search_from" name="search_from" class="form fv-plugins-bootstrap5 fv-plugins-framework">
            <div class="row g-5">
                <div class="col-md-4 fv-row">
                    <label class="fs-6 fw-semibold form-label mb-2">City</label>
                    <select id="city_id" name="city_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Select City">
                        <option></option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                        @endforeach
                    </select>
                    <span class="error text-danger" id="city_id_error"></span>
                </div>
                <div class="col-md-4 fv-row">
                    <label class="fs-6 fw-semibold form-label mb-2">Ward Name</label>
                    <input type="text" id="ward_name" name="ward_name" class="form-control form-control-solid" placeholder="Enter ward name" />
                    <span class="error text-danger" id="ward_name_error"></span>
                </div>
                <div class="col-md-4 fv-row">
                    <label class="fs-6 fw-semibold form-label mb-2">Status</label>
                    <select id="status" name="status" class="form-select form-select-solid" data-control="select2" data-placeholder="Select Status">
                        <option></option>
                        <option value="0">Active</option>
                        <option value="1">Inactive</option>
                    </select>
                    <span class="error text-danger" id="status_error"></span>
                </div>
            </div>
            <div class="separator separator-dashed my-6"></div>
            <div class="d-flex justify-content-start">
                <button type="button" id="search" name="search" class="btn btn-primary me-3">
                    <i class="ki-outline ki-magnifier fs-2"></i>
                    <span class="indicator-label">Search</span>
                </button>
                <button type="button" id="search_display_processing" name="search_display_processing" class="btn btn-primary me-3" style="display:none">
                    <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                    <span class="indicator-label">Please wait...</span>
                </button>
                <button type="button" id="cancel" name="clear_button" class="btn btn-light btn-active-light-primary">
                    <i class="ki-outline ki-reset fs-2"></i>
                    <span class="indicator-label">Clear</span>
                </button>
            </div>
        </form>
    </div>
</div>
<!--end::Navbar-->

<!--begin::Card-->
<div class="card">
    <div class="card-header border-0 pt-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative">
                <span class="svg-icon svg-icon-1 position-absolute ms-4">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                    </svg>
                </span>
                <select class="form-select form-select-solid w-150px ps-12" data-kt-select2="true" data-placeholder="Select Limit" id="search_limits" name="search_limits">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                </select>
            </div>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                <button type="button" id="export_data" name="export_data" class="btn btn-light-primary">
                    <i class="ki-outline ki-exit-up fs-2"></i>Export
                </button>
            </div>
        </div>
    </div>
    <div class="card-body py-4">
        <div class="table-responsive">
            <table id="table_content" name="table_content" class="table table-row-bordered table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                        <th class="min-w-50px">Sr. No.</th>
                        <th class="min-w-150px">City Name</th>
                        <th class="min-w-150px">Ward Name</th>
                        <th class="min-w-100px">Ward Number</th>
                        <th class="min-w-100px">Status</th>
                        <th class="min-w-150px text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold" id="table-content">
                @foreach($lists as $key => $list)
                    @php
                        // Get drawings count for this ward
                        $drawingsCount = \App\Models\GoogleMap::where('module_name', 'ward')
                            ->where('module_id', $list->id)
                            ->count();
                    @endphp
                    <tr>
                        <td>
                            <span class="text-gray-800 fw-bold">{{$key+1}}</span>
                        </td>
                        <td>
                            <span class="text-gray-800 fw-bold d-block">{{$list->city_name}}</span>
                        </td>
                        <td>
                            <span class="text-gray-800 fw-bold d-block">{{$list->ward_name}}</span>
                        </td>
                        <td>
                            <span class="text-gray-600">{{$list->ward_number}}</span>
                        </td>
                        <td>
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input type="checkbox" id="status_{{ $list->id }}" name="status" class="form-check-input" 
                                    value="{{ isset($list->status) ? $list->status : 0}}" 
                                    onchange="statusUpdate('{{$list->status}}','{{$list->id}}',this)" {{ isset($list->status) && $list->status == '0' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="status_{{ $list->id }}">
                                    {{ isset($list->status) && $list->status == '0' ? 'Active' : 'Inactive' }}
                                </label>
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end">
                                <!-- Edit Button -->
                                <a href="<?php echo url('ward/add/'.$list->id);?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-2" title="Edit">
                                    <i class="ki-outline ki-pencil fs-2"></i>
                                </a>
                                
                                <!-- View Button -->
                                <a href="<?php echo url('ward/view/'.$list->id);?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-2" title="View">
                                    <i class="ki-outline ki-eye fs-2"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($total_count > 0)
        <div class="row mt-8">
            <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                <div class="dataTables_length" id="kt_table_length">
                    <label class="text-muted mt-1 m-b-0" id="showing">
                        Showing 1 to {{ ($total_count > 10) ? 10 : $total_count }} of {{ $total_count }} records.
                    </label>
                </div>
            </div>
            <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                <div class="dataTables_paginate paging_simple_numbers" id="kt_table_paginate">
                    <ul class="pagination">
                        <li class="paginate_button page-item previous disabled" id="kt_table_previous">
                            <a href="#" aria-controls="kt_table" data-dt-idx="0" tabindex="0" class="page-link">
                                <i class="previous"></i>
                            </a>
                        </li>
                        <li class="paginate_button page-item active">
                            <a href="#" aria-controls="kt_table" data-dt-idx="1" tabindex="0" class="page-link">1</a>
                        </li>
                        <li class="paginate_button page-item next" id="kt_table_next">
                            <a href="#" aria-controls="kt_table" data-dt-idx="2" tabindex="0" class="page-link">
                                <i class="next"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-10">
            <div class="text-gray-600 fw-semibold fs-6">No records found.</div>
        </div>
        @endif
    </div>
</div>
<!--end::Card-->

<script src="{{ url('public/validation/ward.js') }}"></script>

<script>
// Additional JavaScript for enhanced functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Enhanced search functionality
    $('#search').on('click', function() {
        performSearch();
    });

    $('#cancel').on('click', function() {
        clearSearch();
    });

    // Enter key support for search
    $('#ward_name').on('keypress', function(e) {
        if (e.which === 13) {
            performSearch();
        }
    });

    // Limit change handler
    $('#search_limits').on('change', function() {
        performSearch();
    });
});

function performSearch() {
    // Your existing search logic here
    console.log('Performing search...');
    // Add your AJAX search implementation
}

function clearSearch() {
    $('#city_id').val('').trigger('change');
    $('#ward_name').val('');
    $('#status').val('').trigger('change');
    performSearch();
}

function statusUpdate(oldStatus, id, element) {
    // Your existing status update logic here
    console.log('Updating status for ID:', id);
    // Add your AJAX status update implementation
}

// Export functionality
$('#export_data').on('click', function() {
    // Your existing export logic here
    console.log('Exporting data...');
    // Add your export implementation
});
</script>

@endsection