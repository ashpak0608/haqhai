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
                <a href="{{ route('building.index') }}" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold">
                    <i class="ki-outline ki-left fs-2"></i>Back
                </a>
            </div>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Navbar-->
<div class="card mb-4">
    <div class="card-body pt-4 pb-0">
        <div class="d-row row-wrap row-sm-nowrap pb-4">
            <!-- Toast Notification Container -->
            <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

            <!-- Inline Alert Messages -->
            <div id="alert-message" style="display: none;" class="alert alert-dismissible fade show mb-4" role="alert">
                <span id="alert-message-text"></span>
                <button type="button" class="btn-close" onclick="hideAlert()"></button>
            </div>

            <form id="building_form" name="building_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="id" name="id" value="{{ !empty($singleData['id']) ? $singleData['id'] : '' }}"/>

                <div class="row g-3">
                    <!-- State -->
                    <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">State</label>
                        <select id="state_id" name="state_id" class="form-select form-select-solid" data-control="select2" required>
                            <option value="">Select State</option>
                            @if(isset($states) && count($states) > 0)
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ (!empty($singleData['state_id']) && $singleData['state_id'] == $state->id) ? 'selected' : '' }}>
                                        {{ $state->state_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- City -->
                    <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">City</label>
                        <select id="city_id" name="city_id" class="form-select form-select-solid" data-control="select2" required>
                            <option value="">Select City</option>
                            @if(isset($cities) && count($cities) > 0)
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ (!empty($singleData['city_id']) && $singleData['city_id'] == $city->id) ? 'selected' : '' }} data-state="{{ $city->state_id }}">
                                        {{ $city->city_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Ward -->
                    <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">Ward</label>
                        <select id="ward_id" name="ward_id" class="form-select form-select-solid" data-control="select2" required>
                            <option value="">Select Ward</option>
                            @if(isset($wards) && count($wards) > 0)
                                @foreach($wards as $ward)
                                    <option value="{{ $ward->id }}" {{ (!empty($singleData['ward_id']) && $singleData['ward_id'] == $ward->id) ? 'selected' : '' }} data-city="{{ $ward->city_id }}">
                                        {{ $ward->ward_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="row g-3 mt-4">
                    <!-- Building Name -->
                    <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-1 ms-1">Building Name</label>
                        <input type="text" id="building_name" name="building_name" class="form-control form-control-solid" value="{{ !empty($singleData['building_name']) ? $singleData['building_name'] : '' }}" required />
                    </div>

                    <!-- Landmark -->
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-1 ms-1">Landmark</label>
                        <select id="landmark_id" name="landmark_id" class="form-select form-select-solid" data-control="select2">
                            <option value="">Select Landmark</option>
                            @if(isset($landmarks) && count($landmarks) > 0)
                                @foreach($landmarks as $lm)
                                    <option value="{{ $lm->id }}" {{ (!empty($singleData['landmark_id']) && $singleData['landmark_id'] == $lm->id) ? 'selected' : '' }}>
                                        {{ $lm->landmark_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <div class="form-text">Optional: Choose a nearby landmark</div>
                    </div>

                    <!-- Latitude -->
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-1 ms-1">Latitude</label>
                        <input type="text" id="latitude" name="latitude" class="form-control form-control-solid" value="{{ !empty($singleData['latitude']) ? $singleData['latitude'] : '' }}" placeholder="e.g., 19.0760" />
                        <div class="form-text">Optional: Enter latitude coordinate</div>
                    </div>

                    <!-- Longitude -->
                    <div class="col-md-4 mt-3">
                        <label class="fs-6 fw-semibold mb-1 ms-1">Longitude</label>
                        <input type="text" id="longitude" name="longitude" class="form-control form-control-solid" value="{{ !empty($singleData['longitude']) ? $singleData['longitude'] : '' }}" placeholder="e.g., 72.8777" />
                        <div class="form-text">Optional: Enter longitude coordinate</div>
                    </div>
                </div>

                <hr class="mt-4">

                <div>
                    <button type="button" id="submit_button" name="submit_button" class="btn btn-sm btn-primary">
                        <i class="ki-outline ki-save-2"></i><span class="indicator-label">Submit</span>
                    </button>
                    <button type="button" id="display_processing" name="display_processing" class="btn btn-sm btn-primary" style="display:none">
                        <span class="spinner-border spinner-border-sm align-middle"></span>
                        <span class="indicator-label ms-2">Please wait...</span>
                    </button>
                </div>

                @if(!empty($singleData['id']))
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card card-dashed">
                            <div class="card-header">
                                <h3 class="card-title">Google Map Integration</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="text-muted">Create map drawings and automatically update coordinates for this building.</p>
                                        <div class="d-flex align-items-center">
                                            <span class="text-muted me-3">Current Coordinates:</span>
                                            <span class="badge badge-light-primary">
                                                @if(!empty($singleData['latitude']) && !empty($singleData['longitude']))
                                                    {{ $singleData['latitude'] }}, {{ $singleData['longitude'] }}
                                                @else
                                                    Not set
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <a href="{{ route('building.map', $singleData['id']) }}" class="btn btn-primary" target="_blank">
                                            <i class="ki-outline ki-location fs-2 me-2"></i>Create New Drawing
                                        </a>
                                        <p class="text-muted mt-2 fs-7">Opens in new window</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>
<!--end::Navbar-->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Helper to fetch JSON POST with CSRF
    const postJSON = (url, data = {}) => {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        }).then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.json();
        });
    };

    const stateSelect = document.getElementById('state_id');
    const citySelect = document.getElementById('city_id');
    const wardSelect = document.getElementById('ward_id');
    const landmarkSelect = document.getElementById('landmark_id');

    const isEditMode = {{ !empty($singleData['id']) ? 'true' : 'false' }};
    const editCityId = "{{ !empty($singleData['city_id']) ? $singleData['city_id'] : '' }}";
    const editWardId = "{{ !empty($singleData['ward_id']) ? $singleData['ward_id'] : '' }}";
    const editLandmarkId = "{{ !empty($singleData['landmark_id']) ? $singleData['landmark_id'] : '' }}";

    // When state changes -> load cities
    stateSelect && stateSelect.addEventListener('change', function() {
        const stateId = this.value;
        citySelect.innerHTML = '<option value="">Loading cities...</option>';
        wardSelect.innerHTML = '<option value="">Select Ward</option>';
        landmarkSelect.innerHTML = '<option value="">Select Landmark</option>';

        if (!stateId) {
            citySelect.innerHTML = '<option value="">Select City</option>';
            return;
        }

        postJSON("{{ route('building.getCitiesByState') }}", { state_id: stateId })
            .then(data => {
                citySelect.innerHTML = '<option value="">Select City</option>';
                (data || []).forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id;
                    opt.textContent = c.city_name;
                    opt.dataset.state = c.state_id ?? '';
                    citySelect.appendChild(opt);
                });

                // If edit mode and city preset, select and dispatch change
                if (isEditMode && editCityId) {
                    const opt = citySelect.querySelector(`option[value="${editCityId}"]`);
                    if (opt) {
                        citySelect.value = editCityId;
                        citySelect.dispatchEvent(new Event('change'));
                    }
                }
            })
            .catch(err => {
                console.error('Error loading cities:', err);
                citySelect.innerHTML = '<option value="">Error loading cities</option>';
            });
    });

    // When city changes -> load wards
    citySelect && citySelect.addEventListener('change', function() {
        const cityId = this.value;
        wardSelect.innerHTML = '<option value="">Loading wards...</option>';
        landmarkSelect.innerHTML = '<option value="">Select Landmark</option>';

        if (!cityId) {
            wardSelect.innerHTML = '<option value="">Select Ward</option>';
            return;
        }

        postJSON("{{ route('building.getWardsByCity') }}", { city_id: cityId })
            .then(data => {
                wardSelect.innerHTML = '<option value="">Select Ward</option>';
                (data || []).forEach(w => {
                    const opt = document.createElement('option');
                    opt.value = w.id;
                    opt.textContent = w.ward_name;
                    opt.dataset.city = w.city_id ?? '';
                    wardSelect.appendChild(opt);
                });

                if (isEditMode && editWardId) {
                    const opt = wardSelect.querySelector(`option[value="${editWardId}"]`);
                    if (opt) {
                        wardSelect.value = editWardId;
                        wardSelect.dispatchEvent(new Event('change'));
                    }
                }
            })
            .catch(err => {
                console.error('Error loading wards:', err);
                wardSelect.innerHTML = '<option value="">Error loading wards</option>';
            });
    });

    // When ward changes -> load landmarks (prefer ward, fallback to city)
    wardSelect && wardSelect.addEventListener('change', function() {
        const wardId = this.value;
        const cityId = citySelect.value;
        landmarkSelect.innerHTML = '<option value="">Loading landmarks...</option>';

        postJSON("{{ route('building.getLandmarksByWard') }}", { ward_id: wardId, city_id: cityId })
            .then(data => {
                landmarkSelect.innerHTML = '<option value="">Select Landmark</option>';
                (data || []).forEach(l => {
                    const opt = document.createElement('option');
                    opt.value = l.id;
                    opt.textContent = l.landmark_name;
                    landmarkSelect.appendChild(opt);
                });

                if (isEditMode && editLandmarkId) {
                    const opt = landmarkSelect.querySelector(`option[value="${editLandmarkId}"]`);
                    if (opt) {
                        landmarkSelect.value = editLandmarkId;
                    }
                }
            })
            .catch(err => {
                console.error('Error loading landmarks:', err);
                landmarkSelect.innerHTML = '<option value="">Error loading landmarks</option>';
            });
    });

    // If page was rendered with pre-loaded city/ward (edit mode), trigger appropriate events
    setTimeout(() => {
        if (isEditMode) {
            // If controller preloaded cities for the state, attempt to initialize
            if (stateSelect && stateSelect.value) {
                stateSelect.dispatchEvent(new Event('change'));
            } else if (citySelect && citySelect.value) {
                citySelect.dispatchEvent(new Event('change'));
            } else if (wardSelect && wardSelect.value) {
                wardSelect.dispatchEvent(new Event('change'));
            }
        }
    }, 300);

    // Submit handler
    const submitButton = document.getElementById('submit_button');
    const processingBtn = document.getElementById('display_processing');
    submitButton && submitButton.addEventListener('click', function(e) {
        e.preventDefault();

        // Basic validation
        const stateId = stateSelect.value;
        const cityId = citySelect.value;
        const wardId = wardSelect.value;
        const buildingName = document.getElementById('building_name').value.trim();

        if (!stateId || !cityId || !wardId || !buildingName) {
            alert('Please fill all required fields (State, City, Ward, Building Name)');
            return;
        }

        const form = document.getElementById('building_form');
        const formData = new FormData(form);

        submitButton.style.display = 'none';
        processingBtn.style.display = 'inline-block';

        fetch("{{ route('building.save') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                // Show toast (simple)
                alert(data.message || 'Building saved successfully!');
                window.location.href = "{{ route('building.index') }}";
            } else if (data.status === 'exist') {
                alert(data.message || 'Record already exists.');
            } else {
                alert(data.message || 'Error occurred');
            }
        })
        .catch(err => {
            console.error('Save error:', err);
            alert('Network error: ' + err.message);
        })
        .finally(() => {
            submitButton.style.display = 'inline-block';
            processingBtn.style.display = 'none';
        });
    });
});
</script>

@endsection
