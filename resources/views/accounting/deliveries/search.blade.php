@extends('layout.main')

@section('title_page')
    Deliveries
@endsection

@section('breadcrumb_title')
    <small>accounting / deliveries / dashboard</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-acc-delivery-links page="dashboard" />


            {{-- <div class="row">
                <div class="col-12">
                    @include('accounting.spi.dashboard.summary')
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    @include('accounting.invoices.dashboard.dashb1')
                </div>
                <div class="col-6">
                    @include('accounting.invoices.dashboard.dashb2')
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
