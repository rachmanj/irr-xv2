<!-- Start Generation Here -->
@extends('layout.main')

@section('title_page')
    Additional Document Details
@endsection

@section('breadcrumb_title')
    <small>accounting / additional documents / show</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><b>Document Details</b></h5>
                </div>
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#details" role="tab">Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#distribution" role="tab">Distribution</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#attachments" role="tab">Attachments</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="details" role="tabpanel">
                            @include('accounting.additional-documents.show.details')
                        </div>
                        <div class="tab-pane" id="distribution" role="tabpanel">
                            @include('accounting.additional-documents.show.distribution')
                        </div>
                        <div class="tab-pane" id="attachments" role="tabpanel">
                            @include('accounting.additional-documents.show.attachments')
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('accounting.additional-documents.edit', $additionalDocument->id) }}"
                        class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit Document
                    </a>
                    <a href="{{ route('accounting.additional-documents.index', ['page' => 'search']) }}"
                        class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/bootstrap/css/bootstrap.min.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@endsection
<!-- End Generation Here -->
