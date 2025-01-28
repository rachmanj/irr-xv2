@extends('layout.main')

@section('title_page')
    Additional Document
@endsection

@section('breadcrumb_title')
    <small>logistic / additional document / dashboard</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-log-addoc-links page='dashboard' />

            {{-- <div class="row">
                <div class="col-12">
                    @include('accounting.lpd.dashboard.summary')
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    @include('accounting.lpd.dashboard.dashb1')
                </div>
                <div class="col-6">
                    @include('accounting.lpd.dashboard.dashb2')
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
