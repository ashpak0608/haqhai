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
                <a href="<?php echo url('landmark/add/');?>" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold"><i class="ki-outline ki-plus fs-2"></i>Add</a>
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
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="fs-6 fw-semibold mb-1 ms-1">Area</label>
                            <select id="area_id" name="area_id" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select Area">
                                <option value=""></option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->area_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="fs-6 fw-semibold mb-1 ms-1">City</label>
                            <select id="city_id" name="city_id" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select City">
                                <option value=""></option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="fs-6 fw-semibold mb-1 ms-1">Ward</label>
                            <select id="ward_id" name="ward_id" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select Ward">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="fs-6 fw-semibold mb-1 ms-1">Landmark Name</label>
                            <input type="text" id="landmark_name" name="landmark_name" class="form-control form-control-solid" placeholder="Enter landmark name" />
                        </div>
                    </div>
                </div>
                
                <div class="row mt-2">
                    <div class="col-md-3">
                        <!--<div class="form-group">-->
                        <!--    <label class="fs-6 fw-semibold mb-1 ms-1">Status</label>-->
                        <!--    <select id="status" name="status" class="form-select form-select-solid form-select" aria-label="Select" data-control="select2" data-placeholder="Select Status">-->
                        <!--        <option value=""></option>-->
                        <!--        <option value="0">Active</option>-->
                        <!--        <option value="1">Inactive</option>-->
                        <!--    </select>-->
                        <!--</div>-->
                    </div>
                </div>
                
                <hr>
                
                <div class="d-flex gap-2">
                    <button type="button" id="search" name="search" class="btn btn-sm btn-primary">
                        <i class="ki-outline ki-filter-search"></i>
                        <span class="indicator-label">Search</span>
                    </button>
                    
                    <button type="button" id="search_display_processing" name="search_display_processing" class="btn btn-sm btn-primary" style="display:none">
                        <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                        <span class="indicator-label">Searching...</span>
                    </button>
                    
                    <button type="button" id="cancel" name="clear_button" class="btn btn-sm btn-secondary">
                        <i class="ki-outline ki-eraser"></i>
                        <span class="indicator-label">Clear</span>
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
            <select class="form-select form-select-solid fw-bold" id="search_limits" name="search_limits" data-kt-select2="true" data-placeholder="Select Limit" data-hide-search="true">
                <option value="10" selected>10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="200">200</option>
            </select>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                <a href="javascript:void(0);" id="export_data" name="export_data" class="btn btn-light-primary">
                    <i class="ki-outline ki-exit-up fs-2"></i>Export
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body py-4">
        <div class="table-responsive">
            <table id="table_content" name="table_content" class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 gs-0">
                        <th>Sr. No.</th>
                        <th>Area</th>
                        <th>City</th>
                        <th>Ward</th>
                        <th>Landmark Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold" id="table-content">
                    @if(count($lists) > 0)
                        @foreach($lists as $key => $list)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $list->area_name ?? 'N/A' }}</td>
                                <td>{{ $list->city_name ?? 'N/A' }}</td>
                                <td>{{ $list->ward_name ?? 'N/A' }}</td>
                                <td>{{ $list->landmark_name }}</td>
                                <td>
                                    <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                        <input type="checkbox" class="form-check-input status-toggle" 
                                            data-id="{{ $list->id }}" 
                                            data-status="{{ $list->status }}"
                                            {{ $list->status == 0 ? 'checked' : '' }}>
                                        <span class="form-check-label fw-semibold text-muted" for="status"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="<?php echo url('landmark/add/'.$list->id);?>" class="btn btn-icon btn-active-light-info w-30px h-30px me-2" title="Edit">
                                            <i class="ki-outline ki-pencil text-info fs-3"></i>
                                        </a>
                                        <a href="<?php echo url('landmark/view/'.$list->id);?>" class="btn btn-icon btn-active-light-primary w-30px h-30px me-2" title="View">
                                            <i class="ki-outline ki-eye text-primary fs-3"></i>
                                        </a>
                                        <button type="button" class="btn btn-icon btn-active-light-danger w-30px h-30px delete-btn" 
                                            data-id="{{ $list->id }}" 
                                            data-name="{{ $list->landmark_name }}"
                                            title="Delete">
                                            <i class="ki-outline ki-trash text-danger fs-3"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">No landmarks found</div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        @if($total_count > 0)
            <div class="row mt-4">
                <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar">
                    <div class="text-muted mt-1 m-b-0" id="showing">
                        Showing 1 to {{ min($total_count, 10) }} of {{ $total_count }} records.
                    </div>
                </div>
                <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-end">
                    <div class="dt-paging paging_simple_numbers">
                        <nav id="pagination" aria-label="pagination">
                            @php
                                $limit = count($lists); 
                                $total_page = ceil($total_count / $limit);
                            @endphp
                            <ul class="pagination">
                                <li class="dt-paging-button page-item strt filter" data-limit="{{ $limit }}" data-start="0">
                                    <a href="javascript:void(0);" class="page-link previous">
                                        <i class="ki-outline ki-double-left fs-2"></i>
                                    </a>
                                </li>
                                <li class="dt-paging-button page-item prev filter" data-limit="{{ $limit }}" data-start="0">
                                    <a href="javascript:void(0);" class="page-link previous">
                                        <i class="previous"></i>
                                    </a>
                                </li>
                                <li class="dt-paging-button page-item active pageActive">
                                    <a href="javascript:void(0);" class="page-link disp">1</a>
                                </li>
                                <li class="dt-paging-button page-item next filter" data-limit="{{ $limit }}" data-start="{{ ($total_page > 1) ? $limit : 0 }}">
                                    <a href="javascript:void(0);" class="page-link next">
                                        <i class="next"></i>
                                    </a>
                                </li>
                                <li class="dt-paging-button page-item last filter" data-limit="{{ $limit }}" data-start="{{ ($total_page - 1) * $limit }}">
                                    <a href="javascript:void(0);" class="page-link next">
                                        <i class="ki-outline ki-double-right fs-2"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<!--end::Card-->

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the landmark "<span id="deleteLandmarkName"></span>"?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('#area_id, #city_id, #ward_id, #status, #search_limits').select2({
        placeholder: function() {
            return $(this).data('placeholder');
        },
        allowClear: true
    });

    let deleteId = null;
    
    // Load wards when city changes in search form
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
    
    // Search functionality
    $('#search').click(function() {
        performSearch();
    });
    
    // Clear search
    $('#cancel').click(function() {
        $('#search_from')[0].reset();
        $('#area_id, #city_id, #ward_id, #status').val('').trigger('change');
        $('#ward_id').empty().append('<option value=""></option>').select2({
            placeholder: "Select Ward",
            allowClear: true
        });
        performSearch();
    });
    
    // Change limit
    $('#search_limits').change(function() {
        performSearch();
    });
    
    // Status toggle
    $(document).on('change', '.status-toggle', function() {
        const id = $(this).data('id');
        const currentStatus = $(this).data('status');
        const newStatus = currentStatus == 0 ? 1 : 0;
        const checkbox = $(this);
        
        $.ajax({
            url: "{{ url('landmark/status') }}/" + currentStatus + "/" + id,
            type: "GET",
            dataType: 'json',
            beforeSend: function() {
                checkbox.prop('disabled', true);
            },
            success: function(response) {
                if(response.status === 'success') {
                    // Update data-status attribute
                    checkbox.data('status', newStatus);
                    showAlert('Status updated successfully', 'success');
                } else {
                    // Revert checkbox if error
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    showAlert(response.message || 'Error updating status', 'danger');
                }
            },
            error: function(xhr, status, error) {
                // Revert checkbox on error
                checkbox.prop('checked', !checkbox.prop('checked'));
                showAlert('Error updating status', 'danger');
                console.error('Status update error:', error);
            },
            complete: function() {
                checkbox.prop('disabled', false);
            }
        });
    });
    
    // Delete button click
    $(document).on('click', '.delete-btn', function() {
        deleteId = $(this).data('id');
        const landmarkName = $(this).data('name');
        
        $('#deleteLandmarkName').text(landmarkName);
        $('#deleteModal').modal('show');
    });
    
    // Confirm delete
    $('#confirmDelete').click(function() {
        if(!deleteId) return;
        
        $.ajax({
            url: "{{ url('landmark/delete') }}/" + deleteId,
            type: "DELETE",
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#confirmDelete').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Deleting...');
            },
            success: function(response) {
                $('#deleteModal').modal('hide');
                if(response.status === 'success') {
                    showAlert(response.message, 'success');
                    // Reload the table
                    setTimeout(() => {
                        performSearch();
                    }, 1000);
                } else {
                    showAlert(response.message, 'warning');
                }
            },
            error: function(xhr, status, error) {
                $('#deleteModal').modal('hide');
                showAlert('Error deleting landmark', 'danger');
                console.error('Delete error:', error);
            },
            complete: function() {
                $('#confirmDelete').prop('disabled', false).html('Delete');
                deleteId = null;
            }
        });
    });
    
    // Export data
    $('#export_data').click(function() {
        showAlert('Export functionality will be implemented soon', 'info');
    });
    
    // Helper function to perform search
    function performSearch() {
        const searchData = {
            area_id: $('#area_id').val(),
            city_id: $('#city_id').val(),
            ward_id: $('#ward_id').val(),
            landmark_name: $('#landmark_name').val(),
            status: $('#status').val(),
            limit: $('#search_limits').val(),
            start: 0,
            _token: '{{ csrf_token() }}'
        };
        
        $('#search').hide();
        $('#search_display_processing').show();
        
        $.ajax({
            url: "{{ url('landmark/filter') }}",
            type: "POST",
            data: searchData,
            dataType: 'json',
            success: function(response) {
                $('#search').show();
                $('#search_display_processing').hide();
                
                if (response.status === 'success' || response.total_count > 0) {
                    updateTable(response);
                } else {
                    $('#table-content').html('<tr><td colspan="7" class="text-center py-5"><div class="text-muted">No landmarks found</div></td></tr>');
                    $('#showing').text('Showing 0 records.');
                }
            },
            error: function(xhr, status, error) {
                $('#search').show();
                $('#search_display_processing').hide();
                showAlert('Error searching landmarks', 'danger');
                console.error('Search error:', error);
            }
        });
    }
    
    // Helper function to update table
    function updateTable(response) {
        let tableHtml = '';
        
        if (response.lists && response.lists.length > 0) {
            response.lists.forEach((list, index) => {
                tableHtml += `
                    <tr>
                        <td>${list.serial_no || (index + 1)}</td>
                        <td>${list.area_name || 'N/A'}</td>
                        <td>${list.city_name || 'N/A'}</td>
                        <td>${list.ward_name || 'N/A'}</td>
                        <td>${list.landmark_name}</td>
                        <td>
                            <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                <input type="checkbox" class="form-check-input status-toggle" 
                                    data-id="${list.id}" 
                                    data-status="${list.status}"
                                    ${list.status == 0 ? 'checked' : ''}>
                                <span class="form-check-label fw-semibold text-muted"></span>
                            </label>
                        </td>
                        <td>
                            <div class="d-flex">
                                <a href="${baseUrl}/landmark/add/${list.id}" class="btn btn-icon btn-active-light-info w-30px h-30px me-2" title="Edit">
                                    <i class="ki-outline ki-pencil text-info fs-3"></i>
                                </a>
                                <a href="${baseUrl}/landmark/view/${list.id}" class="btn btn-icon btn-active-light-primary w-30px h-30px me-2" title="View">
                                    <i class="ki-outline ki-eye text-primary fs-3"></i>
                                </a>
                                <button type="button" class="btn btn-icon btn-active-light-danger w-30px h-30px delete-btn" 
                                    data-id="${list.id}" 
                                    data-name="${list.landmark_name}"
                                    title="Delete">
                                    <i class="ki-outline ki-trash text-danger fs-3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        } else {
            tableHtml = '<tr><td colspan="7" class="text-center py-5"><div class="text-muted">No landmarks found</div></td></tr>';
        }
        
        $('#table-content').html(tableHtml);
        $('#showing').text(response.message || '');
    }
    
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
    
    // Define base URL
    const baseUrl = "{{ url('') }}";
});
</script>
@endsection