@extends('layout.main')

@section('title_page')
    Dashboard
@endsection

@section('breadcrumb_title')
    dashboard
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Welcome to Document Distribution System</div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h4>Welcome, {{ $user->name }}!</h4>
                        <p>You are logged in from: {{ $user->department->location_code }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @include('document-distributions.dashboard-widget')
        </div>
    </div>
@endsection

@section('styles')
    <!-- Add any additional styles here -->
@endsection

@section('scripts')
    <!-- Add any additional scripts here -->
@endsection
