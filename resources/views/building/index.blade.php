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
                <a href="{{ url('building/add/') }}" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold">
                    <i class="ki-outline ki-plus fs-2"></i>Add
                </a>
            </div>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Navbar-->
<div class="card mb-5 mb-4">
    <div class="card-body pt-4 pb-0">
        <div class="d-row row-wrap row-sm-nowrap pb-4">
            <form id="search_from" name="search_from" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="GET" action="{{ route('building.index') }}">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="fs-6 fw-semibold mb-1 ms-1">State</label>
                        <select id="state_id" name="state_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Select">
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>{{ $state->state_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="fs-6 fw-semibold mb-1 ms-1">City</label>
                        <select id="city_id" name="city_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Select">
                            <option value="">Select City</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="fs-6 fw-semibold mb-1 ms-1">Ward</label>
                        <select id="ward_id" name="ward_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Select">
                            <option value="">Select Ward</option>
                            @foreach($wards as $ward)
                                <option value="{{ $ward->id }}" {{ request('ward_id') == $ward->id ? 'selected' : '' }}>{{ $ward->ward_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="fs-6 fw-semibold mb-1 ms-1">Landmark</label>
                        <select id="landmark_id" name="landmark_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Select">
                            <option value="">Select Landmark</option>
                            @if(!empty($landmarks))
                                @foreach($landmarks as $lm)
                                    <option value="{{ $lm->id }}" {{ request('landmark_id') == $lm->id ? 'selected' : '' }}>{{ $lm->landmark_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-1 ms-1">Building Name</label>
                        <div class="input-group">
                            <input type="text" id="building_name" name="building_name" class="form-control form-control-solid" value="{{ request('building_name') }}" placeholder="Enter building name to search..." />
                            <button type="button" id="clear_building_search" class="btn btn-icon btn-light" title="Clear building search">
                                <i class="ki-outline ki-cross fs-2"></i>
                            </button>
                        </div>
                        <!--<div class="form-text text-muted">Search by building name alone or combine with other filters</div>-->
                    </div>
                </div>

                <hr class="mt-4 mb-4">

                <div class="d-flex gap-2">
                    <button type="submit" id="search" name="search" class="btn btn-sm btn-primary">
                        <i class="ki-outline ki-filter-search"></i><span class="indicator-label">Search</span>
                    </button>
                    <button type="button" id="search_display_processing" name="search_display_processing" class="btn btn-sm btn-primary" style="display:none">
                        <span class="spinner-border spinner-border-sm align-middle"></span>
                        <span class="indicator-label ms-2">Please wait...</span>
                    </button>
                    <a href="{{ route('building.index') }}" class="btn btn-sm btn-secondary">
                        <i class="ki-outline ki-eraser"></i><span class="indicator-label">Clear All</span>
                    </a>
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
            <form id="limit_form" method="GET" action="{{ route('building.index') }}">
                <input type="hidden" name="state_id" value="{{ request('state_id') }}">
                <input type="hidden" name="city_id" value="{{ request('city_id') }}">
                <input type="hidden" name="ward_id" value="{{ request('ward_id') }}">
                <input type="hidden" name="landmark_id" value="{{ request('landmark_id') }}">
                <input type="hidden" name="building_name" value="{{ request('building_name') }}">
                <select class="form-select form-select-solid fw-bold" id="limit" name="limit" onchange="this.form.submit()">
                    <option value="10" {{ request('limit', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('limit', 10) == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ request('limit', 10) == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('limit', 10) == 100 ? 'selected' : '' }}>100</option>
                    <option value="200" {{ request('limit', 10) == 200 ? 'selected' : '' }}>200</option>
                </select>
            </form>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                <a href="javascript:void(0);" id="export_data" name="export_data" class="btn btn-light-primary"><i class="ki-outline ki-exit-up fs-2"></i>Export</a>
            </div>
        </div>
    </div>

    <div class="card-body py-4">
        @if(request('building_name') && empty($lists) && $total_count == 0)
        <div class="alert alert-warning d-flex align-items-center p-5 mb-10">
            <i class="ki-outline ki-information fs-2hx me-4 text-warning"></i>
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-warning">No buildings found</h4>
                <span>No buildings found matching "<strong>{{ request('building_name') }}</strong>". Try a different search term or check the spelling.</span>
            </div>
        </div>
        @endif

        <div class="table-responsive">
            <table id="table_content" name="table_content" class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 gs-0">
                        <th>Sr. No.</th>
                        <th>State Name</th>
                        <th>City Name</th>
                        <th>Ward Name</th>
                        <th>Landmark</th>
                        <th>Building Name</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold" id="table-content">
                @forelse($lists as $key => $list)
                    <tr>
                        <td>{{ $from + $key }}</td>
                        <td>{{ $list->state_name ?? 'N/A' }}</td>
                        <td>{{ $list->city_name ?? 'N/A' }}</td>
                        <td>{{ $list->ward_name ?? 'N/A' }}</td>
                        <td>{{ $list->landmark_name ?? 'N/A' }}</td>
                        <td>
                            <span class="text-gray-800 fw-bold">{{ $list->building_name ?? 'N/A' }}</span>
                            @if(request('building_name') && stripos($list->building_name, request('building_name')) !== false)
                                <!--<span class="badge badge-success ms-1">Match</span>-->
                            @endif
                        </td>
                        <td>{{ $list->latitude ?? 'N/A' }}</td>
                        <td>{{ $list->longitude ?? 'N/A' }}</td>
                        <td>
                            <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                <input type="checkbox" id="status_{{ $list->id }}" name="status" class="form-check-input" 
                                    value="{{ $list->status ?? 0 }}" 
                                    onchange="statusUpdate('{{ $list->status }}','{{ $list->id }}', this)" {{ isset($list->status) && $list->status == '0' ? 'checked' : '' }}>
                                <span class="form-check-label fw-semibold text-muted" for="status_{{ $list->id }}"></span>
                            </label>
                        </td>
                        <td>
                            <a href="{{ url('building/add/'.$list->id) }}" class="btn btn-icon btn-active-light-info w-30px h-30px me-3" title="Edit">
                                <i class="ki-outline ki-pencil text-info fs-3"></i>
                            </a>
                            <a href="{{ url('building/view/'.$list->id) }}" class="btn btn-icon btn-active-light-primary w-30px h-30px me-3" title="View">
                                <i class="ki-outline ki-eye text-primary fs-3"></i>
                            </a>
                            <a href="{{ route('building.map', $list->id) }}" class="btn btn-icon btn-active-light-success w-30px h-30px me-3" title="Map View" target="_blank">
                                <i class="ki-outline ki-location text-success fs-3"></i>
                            </a>
                            <button type="button" class="btn btn-icon btn-active-light-danger w-30px h-30px delete-btn" 
                                data-id="{{$list->id}}" 
                                data-name="{{$list->building_name}}"
                                title="Delete">
                                <i class="ki-outline ki-trash text-danger fs-3"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    @if(!request('building_name'))
                    <tr>
                        <td colspan="10" class="text-center text-danger py-10">
                            <div class="d-flex flex-column align-items-center">
                                <i class="ki-outline ki-search-list fs-2hx text-muted mb-2"></i>
                                <span class="fs-5 fw-semibold text-muted">No buildings found</span>
                            </div>
                        </td>
                    </tr>
                    @endif
                @endforelse
                </tbody>
            </table>
        </div>

        @if($total_count > 0)
        <div class="row mt-3">
            <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar">
                <label class="text-muted mt-1 m-b-0" id="showing">
                    Showing {{ $from }} to {{ $to }} of {{ $total_count }} records.
                    @if(request('building_name'))
                    <span class="text-primary">(Filtered by: "{{ request('building_name') }}")</span>
                    @endif
                </label>
            </div>
            <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-end">
                <div class="dt-paging paging_simple_numbers">
                    <nav id="pagination" aria-label="pagination">
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($current_page > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $current_page - 1, 'limit' => $per_page]) }}" aria-label="Previous">
                                        <i class="ki-outline ki-double-left fs-2"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="ki-outline ki-double-left fs-2"></i></span>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @for ($i = 1; $i <= $last_page; $i++)
                                @if ($i == 1 || $i == $last_page || ($i >= $current_page - 2 && $i <= $current_page + 2))
                                    <li class="page-item {{ $i == $current_page ? 'active' : '' }}">
                                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i, 'limit' => $per_page]) }}">{{ $i }}</a>
                                    </li>
                                @elseif ($i == $current_page - 3 || $i == $current_page + 3)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                            @endfor

                            {{-- Next Page Link --}}
                            @if ($current_page < $last_page)
                                <li class="page-item">
                                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $current_page + 1, 'limit' => $per_page]) }}" aria-label="Next">
                                        <i class="ki-outline ki-double-right fs-2"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="ki-outline ki-double-right fs-2"></i></span>
                                </li>
                            @endif
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
                <p>Are you sure you want to delete the building "<span id="deleteBuildingName"></span>"?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ url('public/validation/building.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Clear building search button
    const clearBuildingSearch = document.getElementById('clear_building_search');
    const buildingNameInput = document.getElementById('building_name');
    
    if (clearBuildingSearch && buildingNameInput) {
        clearBuildingSearch.addEventListener('click', function() {
            buildingNameInput.value = '';
            buildingNameInput.focus();
        });
    }

    // Enter key search in building name field
    if (buildingNameInput) {
        buildingNameInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('search_from').submit();
            }
        });
    }

    // Real-time search suggestions (optional enhancement)
    let searchTimeout;
    if (buildingNameInput) {
        buildingNameInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            // You can implement AJAX search suggestions here if needed
        });
    }
    
    // Delete functionality
    let deleteId = null;
    
    // Delete button click
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            deleteId = this.getAttribute('data-id');
            const buildingName = this.getAttribute('data-name');
            
            document.getElementById('deleteBuildingName').textContent = buildingName;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });
    });
    
    // Confirm delete
    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (!deleteId) return;
        
        const deleteButton = this;
        const originalText = deleteButton.innerHTML;
        
        // Disable button and show loading
        deleteButton.disabled = true;
        deleteButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Deleting...';
        
        fetch("{{ url('building/delete') }}/" + deleteId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Hide modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            modal.hide();
            
            if (data.status === 'success') {
                // Show success message
                showAlert(data.message, 'success');
                // Reload the page after 1.5 seconds
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                // Show error message
                showAlert(data.message || 'Error deleting building', 'danger');
                // Reset button
                deleteButton.disabled = false;
                deleteButton.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            // Hide modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            modal.hide();
            
            // Show error message
            showAlert('Network error occurred while deleting building', 'danger');
            
            // Reset button
            deleteButton.disabled = false;
            deleteButton.innerHTML = originalText;
        });
    });
});

function statusUpdate(status, id, element) {
    if (confirm('Are you sure you want to change the status?')) {
        const newStatus = status == '0' ? 1 : 0;
        
        fetch("{{ url('building/updateStatus') }}/" + newStatus + "/" + id, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Status updated successfully!');
                // Optionally reload the page to reflect changes
                window.location.reload();
            } else {
                alert('Error updating status: ' + data.message);
                // Revert the checkbox
                element.checked = !element.checked;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating status');
            // Revert the checkbox
            element.checked = !element.checked;
        });
    } else {
        // Revert the checkbox if user cancels
        element.checked = !element.checked;
    }
}

function showAlert(message, type) {
    // Remove existing alerts
    const existingAlert = document.querySelector('.custom-alert');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // Create alert element
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'warning' ? 'alert-warning' : 'alert-danger';
    const iconClass = type === 'success' ? 'ki-check-circle' : 
                     type === 'warning' ? 'ki-information' : 'ki-cross-circle';
    
    const alertHtml = `
        <div class="custom-alert alert ${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <div class="d-flex align-items-center">
                <i class="ki-outline ${iconClass} fs-2hx me-3"></i>
                <div class="d-flex flex-column">
                    <strong>${type === 'success' ? 'Success!' : type === 'warning' ? 'Warning!' : 'Error!'}</strong>
                    <span>${message}</span>
                </div>
                <button type="button" class="btn-close ms-3" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    // Add alert to page
    document.body.insertAdjacentHTML('beforeend', alertHtml);
    
    // Auto-remove after 5 seconds
    setTimeout(function() {
        const alert = document.querySelector('.custom-alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}

// AJAX filtering functionality (optional - for real-time search)
function performAjaxSearch() {
    const formData = new FormData(document.getElementById('search_from'));
    const searchButton = document.getElementById('search');
    const processingButton = document.getElementById('search_display_processing');
    
    searchButton.style.display = 'none';
    processingButton.style.display = 'inline-block';
    
    fetch("{{ route('building.getFiltering') }}", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Handle AJAX response and update table
        // This is optional - currently using server-side rendering
    })
    .catch(error => {
        console.error('Search error:', error);
    })
    .finally(() => {
        searchButton.style.display = 'inline-block';
        processingButton.style.display = 'none';
    });
}
</script>

<style>
.input-group .btn {
    border: 1px solid #E4E6EF;
    border-left: none;
}
.input-group .btn:hover {
    background-color: #F5F8FA;
}
.badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}
</style>

@endsection