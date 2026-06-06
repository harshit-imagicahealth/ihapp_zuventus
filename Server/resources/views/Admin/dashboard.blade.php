@extends('Admin.layouts.main')
@push('title')
    Dashboard Overview
@endpush
@section('main')

        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body stat-card">
                        <div class="stat-icon users">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="stat-number">{{$employeecount}}</h3>
                        <p class="stat-label">Total Employee</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body stat-card">
                        <div class="stat-icon users">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="stat-number">{{$totalRequests}}</h3>
                        <p class="stat-label">Total Request</p>
                    </div>
                </div>
            </div>
        </div>

@endsection

@push('script')

@endpush
