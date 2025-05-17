@extends('layout.main')

@section('title_page')
    Document Distribution History
@endsection

@section('content')
    <x-distribution-links page="history" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        @if ($document)
                            @if ($document_type == 'App\Models\Invoice')
                                Invoice #{{ $document->invoice_number }}
                            @elseif($document_type == 'App\Models\AdditionalDocument')
                                {{ $document->document_type }} #{{ $document->document_number }}
                            @else
                                Document #{{ $document->id }}
                            @endif
                        @else
                            Document History
                        @endif
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('document-distributions.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($document)
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Document Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            @if ($document_type == 'App\Models\Invoice')
                                                <tr>
                                                    <th style="width: 30%">Invoice Number</th>
                                                    <td>{{ $document->invoice_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Supplier</th>
                                                    <td>{{ $document->supplier->name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>PO Number</th>
                                                    <td>{{ $document->po_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Amount</th>
                                                    <td>{{ number_format($document->amount, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Current Location</th>
                                                    <td>
                                                        @if ($document->cur_loc)
                                                            {{ $document->currentDepartment->department_name ?? $document->cur_loc }}
                                                        @else
                                                            Not specified
                                                        @endif
                                                    </td>
                                                </tr>
                                            @elseif($document_type == 'App\Models\AdditionalDocument')
                                                <tr>
                                                    <th style="width: 30%">Document Type</th>
                                                    <td>{{ $document->document_type }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Document Number</th>
                                                    <td>{{ $document->document_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Supplier</th>
                                                    <td>{{ $document->supplier->name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>PO Number</th>
                                                    <td>{{ $document->po_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Current Location</th>
                                                    <td>
                                                        @if ($document->cur_loc)
                                                            {{ $document->currentDepartment->department_name ?? $document->cur_loc }}
                                                        @else
                                                            Not specified
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-info">
                                        <h5 class="mb-0 text-white">Current Status</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-center align-items-center flex-column">
                                            <h4>
                                                @if ($latestDistribution && $latestDistribution->status == 'pending')
                                                    <span class="badge badge-warning p-3">Pending Distribution</span>
                                                @elseif($latestDistribution && $latestDistribution->status == 'in_transit')
                                                    <span class="badge badge-info p-3">In Transit</span>
                                                @elseif($latestDistribution && $latestDistribution->status == 'received')
                                                    <span class="badge badge-success p-3">Received by
                                                        {{ $latestDistribution->toDepartment->department_name ?? $latestDistribution->to_location_code }}</span>
                                                @elseif($latestDistribution && $latestDistribution->status == 'rejected')
                                                    <span class="badge badge-danger p-3">Rejected</span>
                                                @else
                                                    <span class="badge badge-secondary p-3">No Active Distribution</span>
                                                @endif
                                            </h4>

                                            @if ($latestDistribution)
                                                <p class="mt-3">Last updated:
                                                    {{ $latestDistribution->updated_at->format('Y-m-d H:i') }}</p>
                                            @endif

                                            <div class="mt-3">
                                                <a href="{{ route('document-distributions.create', ['document_type' => $document_type, 'document_id' => $document->id]) }}"
                                                    class="btn btn-primary">
                                                    <i class="fas fa-paper-plane"></i> Create New Distribution
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="timeline">
                        @forelse($distributions as $distribution)
                            <div class="time-label">
                                <span class="bg-secondary">{{ $distribution->created_at->format('Y-m-d') }}</span>
                            </div>
                            <div>
                                @if ($distribution->status == 'pending')
                                    <i class="fas fa-clock bg-warning"></i>
                                @elseif($distribution->status == 'in_transit')
                                    <i class="fas fa-paper-plane bg-info"></i>
                                @elseif($distribution->status == 'received')
                                    <i class="fas fa-check bg-success"></i>
                                @elseif($distribution->status == 'rejected')
                                    <i class="fas fa-times bg-danger"></i>
                                @else
                                    <i class="fas fa-dot-circle bg-secondary"></i>
                                @endif

                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i>
                                        {{ $distribution->created_at->format('H:i') }}</span>
                                    <h3 class="timeline-header">
                                        @if ($distribution->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($distribution->status == 'in_transit')
                                            <span class="badge badge-info">In Transit</span>
                                        @elseif($distribution->status == 'received')
                                            <span class="badge badge-success">Received</span>
                                        @elseif($distribution->status == 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $distribution->status }}</span>
                                        @endif
                                        Distribution #{{ $distribution->id }}
                                    </h3>

                                    <div class="timeline-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>From:</strong>
                                                {{ $distribution->fromDepartment->department_name ?? $distribution->from_location_code }}<br>
                                                <strong>To:</strong>
                                                {{ $distribution->toDepartment->department_name ?? $distribution->to_location_code }}<br>
                                                <strong>Sent By:</strong> {{ $distribution->sender->name ?? 'N/A' }}<br>
                                                <strong>Sent At:</strong>
                                                {{ $distribution->sent_at ? $distribution->sent_at->format('Y-m-d H:i') : 'N/A' }}
                                            </div>
                                            <div class="col-md-6">
                                                @if ($distribution->status == 'received' || $distribution->status == 'rejected')
                                                    <strong>Received/Rejected By:</strong>
                                                    {{ $distribution->receiver->name ?? 'N/A' }}<br>
                                                    <strong>Received/Rejected At:</strong>
                                                    {{ $distribution->received_at ? $distribution->received_at->format('Y-m-d H:i') : 'N/A' }}<br>
                                                @endif
                                                @if ($distribution->remarks)
                                                    <strong>Remarks:</strong> {{ $distribution->remarks }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="timeline-footer">
                                        <a href="{{ route('document-distributions.show', $distribution) }}"
                                            class="btn btn-primary btn-sm">View Details</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="time-label">
                                <span class="bg-secondary">No History</span>
                            </div>
                            <div>
                                <i class="fas fa-info bg-info"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header">No distribution history found for this document</h3>
                                    <div class="timeline-body">
                                        This document has not been distributed yet.
                                    </div>
                                    <div class="timeline-footer">
                                        @if ($document)
                                            <a href="{{ route('document-distributions.create', ['document_type' => $document_type, 'document_id' => $document->id]) }}"
                                                class="btn btn-primary btn-sm">
                                                Create Distribution
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforelse

                        <div>
                            <i class="fas fa-clock bg-gray"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @include('document-distributions.dashboard-widget', [
                'recentDistributions' => $recentDistributions,
            ])
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .timeline {
            position: relative;
            margin: 0 0 30px 0;
            padding: 0;
            list-style: none;
        }

        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #ddd;
            left: 31px;
            margin: 0;
            border-radius: 2px;
        }

        .timeline>div {
            position: relative;
            margin-right: 10px;
            margin-bottom: 15px;
        }

        .timeline>.time-label>span {
            font-weight: 600;
            padding: 5px;
            display: inline-block;
            background-color: #fff;
            border-radius: 4px;
        }

        .timeline>div>i {
            width: 30px;
            height: 30px;
            font-size: 15px;
            line-height: 30px;
            position: absolute;
            color: #fff;
            background: #d2d6de;
            border-radius: 50%;
            text-align: center;
            left: 18px;
            top: 0;
        }

        .timeline>div>.timeline-item {
            box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
            border-radius: 3px;
            margin-top: 0;
            background: #fff;
            color: #444;
            margin-left: 60px;
            margin-right: 15px;
            padding: 0;
            position: relative;
        }

        .timeline>div>.timeline-item>.time {
            color: #999;
            float: right;
            padding: 10px;
            font-size: 12px;
        }

        .timeline>div>.timeline-item>.timeline-header {
            margin: 0;
            color: #555;
            border-bottom: 1px solid rgba(0, 0, 0, .125);
            padding: 10px;
            font-size: 16px;
            line-height: 1.1;
        }

        .timeline>div>.timeline-item>.timeline-body,
        .timeline>div>.timeline-item>.timeline-footer {
            padding: 10px;
        }
    </style>
@endpush
