@extends('layout.main')

@section('title_page')
    SUPPLIERS
@endsection

@section('breadcrumb_title')
    <small>master / suppliers / sync</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-master-supplier-links page='list' />
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
