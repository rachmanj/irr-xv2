@extends('layout.main')

@section('title_page')
    ADDITIONAL DOCUMENTS
@endsection

@section('breadcrumb_title')
    <small>accounting / addocs / dashboard</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-additional-document-links page='dashboard' />

            <div class="row">
                <div class="col-6">
                    @include('documents.additional-documents.dashboard.outs')
                </div>

                <div class="col-6">
                    @include('documents.additional-documents.dashboard.bytype')
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
