@extends('layout.main')

@section('title_page')
    INVOICES
@endsection

@section('breadcrumb_title')
    <small>accounting / invoices / show</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="#details" data-toggle="tab">
                                <i class="fas fa-info-circle"></i> Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#additional-docs" data-toggle="tab">
                                <i class="fas fa-file-alt"></i> Additional Docs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#distribution" data-toggle="tab">
                                <i class="fas fa-random"></i> Distribution
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#attachments" data-toggle="tab">
                                <i class="fas fa-paperclip"></i> Attachments
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        @include('accounting.invoices.show.details')
                        @include('accounting.invoices.show.additional_docs')
                        @include('accounting.invoices.show.distribution')
                        @include('accounting.invoices.show.attachments')
                    </div>
                </div>

                <div class="card-footer">
                    <div>
                        <a href="{{ route('accounting.invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit Invoice
                        </a>
                        <a href="{{ route('accounting.invoices.index', ['page' => 'search']) }}"
                            class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .nav-pills .nav-link:not(.active):hover {
            color: #007bff;
        }
    </style>
@endsection
