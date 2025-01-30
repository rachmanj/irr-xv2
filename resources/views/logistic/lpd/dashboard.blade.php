@extends('layout.main')

@section('title_page')
    LPD
@endsection

@section('breadcrumb_title')
    <small>logistic / lpd / dashboard</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-log-lpd-links page='dashboard' />

            {{-- <div class="row">
                <div class="col-6">
                    @include('logistic.lpd.dashboard')
                </div>
            </div> --}}

        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card-header .active {
            color: black;
            text-transform: uppercase;
        }
    </style>
@endsection
