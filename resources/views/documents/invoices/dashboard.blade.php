@extends('layout.main')

@section('title_page')
    INVOICES
@endsection

@section('breadcrumb_title')
    <small>accounting / invoices / dashboard</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-invoice-links page='dashboard' />

            <div class="row">
                <div class="col-12">
                    @include('documents.invoices.dashboard.summary')
                </div>
            </div>

            <div class="row">
                {{-- <div class="col-6">
                    @include('documents.invoices.dashboard.dashb1')
                </div> --}}
                <div class="col-6">
                    @include('documents.invoices.dashboard.10oldest')
                </div>
            </div>

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
