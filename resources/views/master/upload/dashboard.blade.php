@extends('layout.main')

@section('title_page')
    UPLOAD
@endsection

@section('breadcrumb_title')
    <small>master / upload / ito</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-master-upload-links page='dashboard' />

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
