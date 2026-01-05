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
                <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">{{ $permissions['sub_module_name'] ?? 'State' }}</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ url('state/add/') }}" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold">
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
            <form id="search_from" name="search_from" class="form fv-plugins-bootstrap5 fv-plugins-framework" onsubmit="return false;">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-1 ms-1">State Name</label>
                        <input type="text" id="state_name" name="state_name" class="form-control form-control-solid" />
                        <span class="error" id="state_name_error"></span>
                    </div>
                </div>
                <hr>
                <div>
                    <button type="button" id="search" name="search" class="btn btn-sm btn-primary">
                        <i class="ki-outline ki-filter-search"></i><span class="indicator-label">Search</span>
                    </button>
                    <button type="button" id="search_display_processing" name="search_display_processing" class="btn btn-sm btn-primary" style="display:none">
                        <span class="spinner-border spinner-border-sm align-middle"></span>
                        <span class="indicator-label ms-2">Please wait...</span>
                    </button>
                    <button type="button" id="cancel" name="clear_button" class="btn btn-sm btn-secondary">
                        <i class="ki-outline ki-eraser"></i><span class="indicator-label">Clear</span>
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
            <select class="form-select form-select-solid fw-bold" id="search_limits" name="search_limits">
                <option value="10">10</option>
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
                        <th>State Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold" id="table-content">
                @forelse($lists as $key => $list)
                    <tr id="row-{{ $list->id }}">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ e($list->state_name) }}</td>
                        <td>
                            <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                @php
                                    $isChecked = isset($list->status) && (int)$list->status === 0 ? 'checked' : '';
                                    $statusValue = isset($list->status) ? (int)$list->status : 0;
                                    $statusText = $statusValue === 0 ? 'Active' : 'Inactive';
                                    $newStatus = $statusValue === 0 ? 1 : 0;
                                    $statusClass = $statusValue === 0 ? 'text-success' : 'text-warning';
                                @endphp
                                <input type="checkbox"
                                       id="status_{{ $list->id }}"
                                       name="status"
                                       class="form-check-input status-toggle"
                                       value="{{ $statusValue }}"
                                       data-id="{{ $list->id }}"
                                       data-current-status="{{ $statusValue }}"
                                       data-state-name="{{ e($list->state_name) }}"
                                       {{ $isChecked }}>
                                <span class="form-check-label fw-semibold {{ $statusClass }}" for="status_{{ $list->id }}">
                                    {{ $statusText }}
                                </span>
                            </label>
                        </td>
                        <td>
                            <a href="{{ url('state/add/'.$list->id) }}" class="btn btn-icon btn-active-light-info w-30px h-30px me-2" title="Edit">
                                <i class="ki-outline ki-pencil text-info fs-3"></i>
                            </a>
                            <a href="{{ url('state/view/'.$list->id) }}" class="btn btn-icon btn-active-light-primary w-30px h-30px me-2" title="View">
                                <i class="ki-outline ki-eye text-primary fs-3"></i>
                            </a>

                            <!-- Delete button triggers modal -->
                            <button type="button"
                                    class="btn btn-icon btn-active-light-danger w-30px h-30px me-2 btn-delete"
                                    data-id="{{ $list->id }}"
                                    data-name="{{ e($list->state_name) }}"
                                    title="Delete">
                                <i class="ki-outline ki-trash text-danger fs-3"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No records found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($total_count > 0)
        <div class="row">
            <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar">
                <label class="text-muted mt-1 m-b-0" id="showing">
                    Showing 1 to {{ min(count($lists), $total_count) }} of {{ $total_count }} records.
                </label>
            </div>
            <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-end">
                <div class="dt-paging paging_simple_numbers">
                    <nav id="pagination" aria-label="pagination">
                        @php
                            $limit = count($lists) ?: 10;
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
                            @for($i = 1; $i <= $total_page; $i++)
                                <li class="dt-paging-button page-item {{ $i == 1 ? 'active' : '' }} pageActive" data-start="{{ ($i-1)*$limit }}">
                                    <a href="javascript:void(0);" class="page-link disp">{{ $i }}</a>
                                </li>
                            @endfor
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
        @endif

    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p id="deleteModalBody">Are you sure you want to delete this record?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
          </div>
        </div>
      </div>
    </div>

</div>
<!--end::Card-->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    let deleteModal = null;
    let currentDeleteId = null;
    let currentStart = 0;
    let currentLimit = parseInt(document.getElementById('search_limits').value);
    let totalCount = {{ $total_count }};
    
    // Initialize Bootstrap modal
    const deleteModalElement = document.getElementById('deleteModal');
    if (deleteModalElement) {
        deleteModal = new bootstrap.Modal(deleteModalElement);
    }
    
    // Status toggle functionality
    document.querySelectorAll('.status-toggle').forEach(checkbox => {
        checkbox.addEventListener('change', function(e) {
            const stateId = this.getAttribute('data-id');
            const stateName = this.getAttribute('data-state-name');
            const currentStatus = parseInt(this.getAttribute('data-current-status'));
            const newStatus = currentStatus === 0 ? 1 : 0;
            const statusText = newStatus === 0 ? 'Active' : 'Inactive';
            
            if (confirm(`Are you sure you want to change the status of "${stateName}" to "${statusText}"?`)) {
                this.disabled = true;
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                
                fetch(`/state/status/${currentStatus}/${stateId}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        this.setAttribute('data-current-status', newStatus);
                        const statusLabel = this.nextElementSibling;
                        statusLabel.textContent = statusText;
                        statusLabel.className = `form-check-label fw-semibold ${newStatus === 0 ? 'text-success' : 'text-warning'}`;
                        showAlert(`Status updated to ${statusText} successfully`, 'success');
                    } else {
                        this.checked = !this.checked;
                        showAlert(data.message || 'Failed to update status', 'danger');
                    }
                })
                .catch(error => {
                    this.checked = !this.checked;
                    showAlert('Error updating status: ' + error.message, 'danger');
                })
                .finally(() => {
                    this.disabled = false;
                });
            } else {
                this.checked = !this.checked;
            }
        });
    });

    // Delete button functionality
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name') || 'this record';
            
            currentDeleteId = id;
            
            // Update modal content
            const deleteModalBody = document.getElementById('deleteModalBody');
            if (deleteModalBody) {
                deleteModalBody.innerHTML = `
                    <div class="text-center">
                        <i class="ki-outline ki-trash fs-2hx text-danger mb-3"></i>
                        <h6 class="fw-bold">Are you sure you want to delete?</h6>
                        <p class="text-muted">"${name}" will be permanently removed.</p>
                    </div>
                `;
            }
            
            // Show modal
            if (deleteModal) {
                deleteModal.show();
            }
        });
    });

   // Confirm delete button
const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener('click', function() {
        if (!currentDeleteId) {
            showAlert('Invalid ID for delete', 'danger');
            return;
        }

        const originalText = this.innerHTML;
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Deleting...';

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        // Use POST method with _method=DELETE (Laravel method spoofing)
        const deleteUrl = `/state/delete/${currentDeleteId}`;
        
        fetch(deleteUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest' // Important for Laravel to detect AJAX
            },
            body: JSON.stringify({
                _method: 'DELETE' // Laravel method spoofing
            })
        })
        .then(response => {
            // Check if response is a redirect (302)
            if (response.redirected) {
                // If it's a redirect, follow it but handle it as JSON
                return fetch(response.url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
            }
            return response.json();
        })
        .then(data => {
            this.disabled = false;
            this.innerHTML = originalText;

            // Check if data is valid JSON
            if (data && typeof data === 'object' && data.status) {
                if (data.status === 'success') {
                    if (deleteModal) {
                        deleteModal.hide();
                    }
                    removeRowFromTable(currentDeleteId);
                    showAlert(data.message || 'Deleted successfully', 'success');
                    // Reload data to fix pagination
                    setTimeout(() => {
                        loadTableData(currentStart, currentLimit);
                    }, 500);
                } else {
                    if (deleteModal) {
                        deleteModal.hide();
                    }
                    showAlert(data.message || 'Unable to delete', 'danger');
                }
            } else {
                // If we got HTML or redirect, reload the page
                if (deleteModal) {
                    deleteModal.hide();
                }
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            this.disabled = false;
            this.innerHTML = originalText;
            if (deleteModal) {
                deleteModal.hide();
            }
            
            // Check if it's a JSON parse error (might be HTML response)
            if (error instanceof SyntaxError) {
                // Probably got HTML instead of JSON, reload page
                window.location.reload();
            } else {
                showAlert('Network error: ' + error.message, 'danger');
            }
        });
    });
}

    function removeRowFromTable(id) {
        const row = document.getElementById(`row-${id}`);
        if (row) {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '0';
            setTimeout(() => {
                row.remove();
            }, 300);
        }
    }

    // Search functionality
    document.getElementById('search').addEventListener('click', function() {
        currentStart = 0;
        loadTableData(currentStart, currentLimit);
    });

    // Clear functionality
    document.getElementById('cancel').addEventListener('click', function() {
        document.getElementById('state_name').value = '';
        currentStart = 0;
        loadTableData(currentStart, currentLimit);
    });

    // Limit change functionality
    document.getElementById('search_limits').addEventListener('change', function() {
        currentLimit = parseInt(this.value);
        currentStart = 0;
        loadTableData(currentStart, currentLimit);
    });

    // Pagination click handlers
    document.querySelectorAll('.pagination .page-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.classList.contains('active')) return;
            
            const start = parseInt(this.getAttribute('data-start')) || 0;
            const limit = parseInt(this.getAttribute('data-limit')) || currentLimit;
            
            currentStart = start;
            loadTableData(start, limit);
        });
    });

    function loadTableData(start, limit) {
        const searchBtn = document.getElementById('search');
        const processingBtn = document.getElementById('search_display_processing');
        const stateName = document.getElementById('state_name').value;
        
        // Show processing state
        searchBtn.style.display = 'none';
        processingBtn.style.display = 'inline-block';
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        fetch('/state/filter', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                start: start,
                limit: limit,
                state_name: stateName
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            searchBtn.style.display = 'inline-block';
            processingBtn.style.display = 'none';
            
            if (data.status === 'success') {
                updateTable(data.lists);
                updatePagination(start, limit, data.total_count);
                updateShowingText(data.showing || `Showing ${start + 1} to ${Math.min(start + data.lists.length, data.total_count)} of ${data.total_count} records.`);
            } else {
                updateTable([]);
                updatePagination(0, limit, 0);
                updateShowingText('No records found.');
                showAlert(data.message || 'No data found', 'warning');
            }
        })
        .catch(error => {
            searchBtn.style.display = 'inline-block';
            processingBtn.style.display = 'none';
            showAlert('Error loading data: ' + error.message, 'danger');
            console.error('Error:', error);
        });
    }

    function updateTable(lists) {
        const tbody = document.getElementById('table-content');
        
        if (lists.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No records found.</td></tr>';
            return;
        }
        
        let html = '';
        lists.forEach((list, index) => {
            const serialNo = currentStart + index + 1;
            const statusText = list.status === 0 ? 'Active' : 'Inactive';
            const statusClass = list.status === 0 ? 'text-success' : 'text-warning';
            const isChecked = list.status === 0 ? 'checked' : '';
            
            html += `
                <tr id="row-${list.id}">
                    <td>${serialNo}</td>
                    <td>${escapeHtml(list.state_name)}</td>
                    <td>
                        <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                            <input type="checkbox"
                                   id="status_${list.id}"
                                   name="status"
                                   class="form-check-input status-toggle"
                                   value="${list.status}"
                                   data-id="${list.id}"
                                   data-current-status="${list.status}"
                                   data-state-name="${escapeHtml(list.state_name)}"
                                   ${isChecked}>
                            <span class="form-check-label fw-semibold ${statusClass}" for="status_${list.id}">
                                ${statusText}
                            </span>
                        </label>
                    </td>
                    <td>
                        <a href="/state/add/${list.id}" class="btn btn-icon btn-active-light-info w-30px h-30px me-2" title="Edit">
                            <i class="ki-outline ki-pencil text-info fs-3"></i>
                        </a>
                        <a href="/state/view/${list.id}" class="btn btn-icon btn-active-light-primary w-30px h-30px me-2" title="View">
                            <i class="ki-outline ki-eye text-primary fs-3"></i>
                        </a>
                        <button type="button"
                                class="btn btn-icon btn-active-light-danger w-30px h-30px me-2 btn-delete"
                                data-id="${list.id}"
                                data-name="${escapeHtml(list.state_name)}"
                                title="Delete">
                            <i class="ki-outline ki-trash text-danger fs-3"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
        
        // Reattach event listeners to new status toggles
        document.querySelectorAll('.status-toggle').forEach(checkbox => {
            checkbox.addEventListener('change', function(e) {
                const stateId = this.getAttribute('data-id');
                const stateName = this.getAttribute('data-state-name');
                const currentStatus = parseInt(this.getAttribute('data-current-status'));
                const newStatus = currentStatus === 0 ? 1 : 0;
                const statusText = newStatus === 0 ? 'Active' : 'Inactive';
                
                if (confirm(`Are you sure you want to change the status of "${stateName}" to "${statusText}"?`)) {
                    this.disabled = true;
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    
                    fetch(`/state/status/${currentStatus}/${stateId}`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            this.setAttribute('data-current-status', newStatus);
                            const statusLabel = this.nextElementSibling;
                            statusLabel.textContent = statusText;
                            statusLabel.className = `form-check-label fw-semibold ${newStatus === 0 ? 'text-success' : 'text-warning'}`;
                            showAlert(`Status updated to ${statusText} successfully`, 'success');
                        } else {
                            this.checked = !this.checked;
                            showAlert(data.message || 'Failed to update status', 'danger');
                        }
                    })
                    .catch(error => {
                        this.checked = !this.checked;
                        showAlert('Error updating status: ' + error.message, 'danger');
                    })
                    .finally(() => {
                        this.disabled = false;
                    });
                } else {
                    this.checked = !this.checked;
                }
            });
        });
        
        // Reattach event listeners to delete buttons
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name') || 'this record';
                
                currentDeleteId = id;
                
                const deleteModalBody = document.getElementById('deleteModalBody');
                if (deleteModalBody) {
                    deleteModalBody.innerHTML = `
                        <div class="text-center">
                            <i class="ki-outline ki-trash fs-2hx text-danger mb-3"></i>
                            <h6 class="fw-bold">Are you sure you want to delete?</h6>
                            <p class="text-muted">"${name}" will be permanently removed.</p>
                        </div>
                    `;
                }
                
                if (deleteModal) {
                    deleteModal.show();
                }
            });
        });
    }

    function updatePagination(start, limit, totalCount) {
        const pagination = document.querySelector('#pagination .pagination');
        if (!pagination) return;
        
        const totalPages = Math.ceil(totalCount / limit);
        const currentPage = Math.floor(start / limit) + 1;
        
        let paginationHtml = `
            <li class="dt-paging-button page-item strt filter" data-limit="${limit}" data-start="0">
                <a href="javascript:void(0);" class="page-link previous"><i class="ki-outline ki-double-left fs-2"></i></a>
            </li>
            <li class="dt-paging-button page-item prev filter" data-limit="${limit}" data-start="${Math.max(0, (currentPage - 2) * limit)}">
                <a href="javascript:void(0);" class="page-link previous"><i class="previous"></i></a>
            </li>
        `;
        
        // Calculate start and end page numbers for pagination
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);
        
        // Adjust if we're near the end
        if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const pageStart = (i - 1) * limit;
            paginationHtml += `
                <li class="dt-paging-button page-item ${i === currentPage ? 'active' : ''}" data-start="${pageStart}">
                    <a href="javascript:void(0);" class="page-link disp">${i}</a>
                </li>
            `;
        }
        
        paginationHtml += `
            <li class="dt-paging-button page-item next filter" data-limit="${limit}" data-start="${Math.min((totalPages - 1) * limit, currentPage * limit)}">
                <a href="javascript:void(0);" class="page-link next"><i class="next"></i></a>
            </li>
            <li class="dt-paging-button page-item last filter" data-limit="${limit}" data-start="${(totalPages - 1) * limit}">
                <a href="javascript:void(0);" class="page-link next"><i class="ki-outline ki-double-right fs-2"></i></a>
            </li>
        `;
        
        pagination.innerHTML = paginationHtml;
        
        // Reattach event listeners to new pagination buttons
        document.querySelectorAll('.pagination .page-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                if (this.classList.contains('active')) return;
                
                const start = parseInt(this.getAttribute('data-start')) || 0;
                const limit = parseInt(this.getAttribute('data-limit')) || currentLimit;
                
                currentStart = start;
                loadTableData(start, limit);
            });
        });
    }

    function updateShowingText(text) {
        const showingElement = document.getElementById('showing');
        if (showingElement) {
            showingElement.textContent = text;
        }
    }

    function showAlert(message, type) {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.custom-alert');
        existingAlerts.forEach(alert => alert.remove());
        
        const wrapper = document.createElement('div');
        wrapper.className = `custom-alert alert alert-${type} alert-dismissible fade show`;
        wrapper.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="ki-outline ${type === 'success' ? 'ki-check-circle' : 'ki-cross-circle'} fs-2hx me-3"></i>
                <div class="d-flex flex-column">
                    <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong>
                    <span>${message}</span>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        const container = document.querySelector('.container') || document.body;
        container.prepend(wrapper);
        
        setTimeout(() => {
            if (wrapper.parentNode) {
                wrapper.remove();
            }
        }, 3000);
    }

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Initialize on page load
    currentStart = 0;
    currentLimit = parseInt(document.getElementById('search_limits').value);
});
</script>

@endsection