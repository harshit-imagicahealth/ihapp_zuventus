@extends('Admin.layouts.main')
@push('title')
    Edit Employee
@endpush
@section('main')
    <div class="main_content_box mt-5">

        <form id="userForm" action="{{ route('admin.users.update', ['id' => $user->id]) }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="parent_employee_code" class="form-label">Parent Employee Code</label>
                        <input type="text" class="form-control" id="parent_employee_code" name="parent_employee_code"
                            value="{{ old('parent_employee_code', $user->parent_employee_code) }}"
                            placeholder="Enter parent employee code">
                    </div>
                    <div class="col-md-6">
                        <label for="employee_code" class="form-label">Employee Code *</label>
                        <input type="text" class="form-control" id="employee_code" name="employee_code"
                            value="{{ old('employee_code', $user->employee_code) }}" placeholder="Enter employee code">
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label for="employee_pos_code" class="form-label">Employee Position *</label>
                        <select class="form-control" id="employee_pos_code" name="employee_pos_code">
                            <option value="">Select Position</option>
                            <option value="TM"
                                {{ old('employee_pos_code', $user->employee_pos_code) == 'TM' ? 'selected' : '' }}>
                                TM</option>
                            <option value="ASM"
                                {{ old('employee_pos_code', $user->employee_pos_code) == 'ASM' ? 'selected' : '' }}>
                                ASM</option>
                            <option value="RSM"
                                {{ old('employee_pos_code', $user->employee_pos_code) == 'RSM' ? 'selected' : '' }}>
                                RSM</option>
                            <option value="SM"
                                {{ old('employee_pos_code', $user->employee_pos_code) == 'SM' ? 'selected' : '' }}>
                                SM</option>
                            <option value="ME"
                                {{ old('employee_pos_code', $user->employee_pos_code) == 'ME' ? 'selected' : '' }}>
                                ME</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="designation" class="form-label">Designation *</label>
                        <input type="text" class="form-control" id="designation" name="designation"
                            value="{{ old('designation', $user->designation) }}" placeholder="Enter Designation">
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $user->name) }}" placeholder="Enter full name">
                    </div>
                    <div class="col-md-6">
                        <label for="mobile_no" class="form-label">Mobile Number *</label>
                        <input type="text" class="form-control" id="mobile_no" name="mobile_no"
                            value="{{ old('mobile_no', $user->mobile_no) }}" placeholder="Enter mobile number">
                    </div>
                </div>

                <div class="mt-3">
                    <label for="emailid" class="form-label">Email Address *</label>
                    <input type="email" class="form-control" id="emailid" name="emailid"
                        value="{{ old('emailid', $user->emailid) }}" placeholder="Enter email address">
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <label for="hq" class="form-label">HQ</label>
                        <input type="text" class="form-control" id="hq" name="hq"
                            value="{{ old('hq', $user->hq) }}" placeholder="Enter HQ">
                    </div>
                    <div class="col-md-4">
                        <label for="region" class="form-label">Region</label>
                        <input type="text" class="form-control" id="region" name="region"
                            value="{{ old('region', $user->region) }}" placeholder="Enter region">
                    </div>
                    <div class="col-md-4">
                        <label for="zone" class="form-label">Zone</label>
                        <input type="text" class="form-control" id="zone" name="zone"
                            value="{{ old('zone', $user->zone) }}" placeholder="Enter zone">
                    </div>
                </div>
                <div class="row g-2 mt-2">
                    <div class="col-md-6">
                        <label for="unique_id" class="form-label">Unique ID *</label>
                        <input type="text" class="form-control" id="unique_id" name="unique_id"
                            value="{{ old('unique_id', $user->unique_id) }}" placeholder="Enter unique ID">
                    </div>
                    <div class="col-md-6">
                        <label for="target_enrollment" class="form-label">Target Enrollment *</label>
                        <input type="text" class="form-control" id="target_enrollment" name="target_enrollment"
                            value="{{ old('target_enrollment', $user->target_enrollment) }}"
                            placeholder="Enter target enrollment">
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn_custom px-4">Submit</button>
            </div>
        </form>

    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('userForm');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const requiredFields = ['employee_code', 'employee_pos_code', 'designation', 'name',
                    'mobile_no',
                    'emailid', 'unique_id'
                ];
                let errors = [];

                requiredFields.forEach(function(field) {
                    const el = document.getElementById(field);
                    if (!el.value.trim()) {
                        errors.push(el.previousElementSibling.innerText + ' is required');
                    }
                });

                if (errors.length > 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: errors.join('<br>')
                    });
                } else {
                    form.submit();
                }
            });
        });
    </script>
@endpush
