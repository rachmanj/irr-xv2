@extends('layout.main')

@section('title_page')
    View LPD Details
@endsection

@section('breadcrumb_title')
    <small>accounting / deliveries / view</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-log-lpd-links page='list' />

            <!-- LPD Info Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>
                        LPD Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 140px">LPD Number</th>
                                    <td>: {{ $lpd->nomor }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>: {{ $lpd->date ? \Carbon\Carbon::parse($lpd->date)->format('d M Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Origin</th>
                                    <td>: {{ $lpd->origin }}</td>
                                </tr>
                                <tr>
                                    <th>Destination</th>
                                    <td>: {{ $lpd->destination }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 140px">Status</th>
                                    <td>: <span
                                            class="badge {{ $lpd->status === 'draft' ? 'badge-warning' : ($lpd->status === 'sent' ? 'badge-success' : 'badge-info') }}">{{ $lpd->status }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Attention Person</th>
                                    <td>: {{ $lpd->attention_person }}</td>
                                </tr>
                                <tr>
                                    <th>Created By</th>
                                    <td>: {{ $lpd->createdBy->name }}</td>
                                </tr>
                                <tr>
                                    <th>Notes</th>
                                    <td>: {{ $lpd->notes ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt mr-2"></i>
                        Documents
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Document Type</th>
                                <th>Document Number</th>
                                <th>Document Date</th>
                                <th>Invoice Number</th>
                                <th>Supplier</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lpd->documents as $index => $document)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $document->type->type_name }}</td>
                                    <td>{{ $document->document_number }}</td>
                                    <td>{{ $document->document_date ? \Carbon\Carbon::parse($document->document_date)->format('d M Y') : '-' }}
                                    </td>
                                    <td>{{ $document->invoice?->invoice_number ?? '-' }}</td>
                                    <td>{{ $document->invoice?->supplier?->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Back Button -->
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('logistic.lpd.index', ['page' => 'list']) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Back to List
                    </a>

                    <a href="{{ route('logistic.lpd.print', $lpd->id) }}" class="btn btn-primary float-right"
                        target="_blank">
                        <i class="fas fa-print mr-1"></i> Print
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
