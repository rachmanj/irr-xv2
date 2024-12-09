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
            <x-acc-invoice-links page='dashboard' />
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
