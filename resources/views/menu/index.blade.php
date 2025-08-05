@extends('layouts.app')
@section('contant')

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar mb-4">
    <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch">
        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
            <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Menu</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="<?php echo url('menu/add/');?>" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold"><i class="ki-outline ki-plus fs-2"></i>Add</a>
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
                        <label class="fs-6 fw-semibold mb-1 ms-1">Menu Name</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid" />
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
                        <th>Menu Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold" id="table-content">
                    <tr>
                        <td>1</td>
                        <td>Menu Name 1</td>
                        <td>
                            <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                <input type="checkbox" id="status" name="status" class="form-check-input" value="1" checked="checked">
                                <span class="form-check-label fw-semibold text-muted" for="status"></span>
                            </label>
                        </td>
                        <td>
                            <a href="<?php echo url('menu/add/');?>" class="btn btn-icon btn-active-light-info w-30px h-30px me-3"><i class="ki-outline ki-pencil text-info fs-3"></i></a>
                            <a href="<?php echo url('menu/view/');?>" class="btn btn-icon btn-active-light-primary w-30px h-30px me-3"><i class="ki-outline ki-eye text-primary fs-3"></i></a>
                            <!-- <a class="btn btn-icon btn-active-light-danger w-30px h-30px delete-record"><i class="ki-outline ki-trash text-danger fs-3"></i></a> -->
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar"></div>
            <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                <div class="dt-paging paging_simple_numbers">
                    <nav aria-label="pagination">
                        <ul class="pagination">
                            <li class="dt-paging-button page-item disabled">
                                <a href="javascript:void(0);" class="page-link previous"><i class="previous"></i></a>
                            </li>
                            <li class="dt-paging-button page-item active">
                                <a href="javascript:void(0);" class="page-link">1</a>
                            </li>
                            <li class="dt-paging-button page-item">
                                <a href="javascript:void(0);" class="page-link">2</a>
                            </li>
                            <li class="dt-paging-button page-item">
                                <a href="javascript:void(0);" class="page-link">3</a>
                            </li>
                            <li class="dt-paging-button page-item">
                                <a href="javascript:void(0);" class="page-link next"><i class="next"></i></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Card-->

<script src="{{ url('public/validation/menu.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.delete-record').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                html: 'Are you sure? You want to delete it.',
                icon: 'info',
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: 'Ok, proceed!',
                cancelButtonText: 'Nope, cancel!',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-danger'
                }
            });
        });
    });
</script>

@endsection