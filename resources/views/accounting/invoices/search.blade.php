@extends('layout.main')

@section('title_page')
    INVOICES
@endsection

@section('breadcrumb_title')
    accounting / invoices / search
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-acc-invoice-links page='search' />
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
