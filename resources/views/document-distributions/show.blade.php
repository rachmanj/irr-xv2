@extends('layout.main')

@section('title_page')
    Document Distribution Details
@endsection

@section('content')
    <x-distribution-links page="show" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Distribution #{{ $distribution->id }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('document-distributions.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <a href="{{ route('document-distributions.history', ['document_type' => $distribution->document_type, 'document_id' => $distribution->document_id]) }}"
                            class="btn btn-info btn-sm">
                            <i class="fas fa-history"></i> View History
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Distribution Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 30%">Status</th>
                                            <td>
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
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>From</th>
                                            <td>{{ $distribution->fromDepartment->department_name ?? $distribution->from_location_code }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>To</th>
                                            <td>{{ $distribution->toDepartment->department_name ?? $distribution->to_location_code }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Sent By</th>
                                            <td>{{ $distribution->sender->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Sent At</th>
                                            <td>{{ $distribution->sent_at ? $distribution->sent_at->format('Y-m-d H:i') : 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Received By</th>
                                            <td>{{ $distribution->receiver->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Received At</th>
                                            <td>{{ $distribution->received_at ? $distribution->received_at->format('Y-m-d H:i') : 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Remarks</th>
                                            <td>{{ $distribution->remarks ?? 'No remarks' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Document Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 30%">Document Type</th>
                                            <td>
                                                @if ($distribution->document_type == 'App\Models\Invoice')
                                                    Invoice
                                                @elseif($distribution->document_type == 'App\Models\AdditionalDocument')
                                                    Additional Document
                                                @else
                                                    {{ $distribution->document_type }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Document ID</th>
                                            <td>{{ $distribution->document_id }}</td>
                                        </tr>
                                        @if ($document)
                                            @if ($distribution->document_type == 'App\Models\Invoice')
                                                <tr>
                                                    <th>Invoice Number</th>
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
                                            @elseif($distribution->document_type == 'App\Models\AdditionalDocument')
                                                <tr>
                                                    <th>Document Type</th>
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
                                            @endif
                                        @else
                                            <tr>
                                                <td colspan="2" class="text-center">Document details not available</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($distribution->status == 'in_transit')
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-success">
                                        <h5 class="mb-0 text-white">Receive Document</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('document-distributions.receive', $distribution) }}"
                                            method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="remarks">Remarks</label>
                                                <textarea name="remarks" id="remarks" class="form-control" rows="3"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-success">Mark as Received</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-danger">
                                        <h5 class="mb-0 text-white">Reject Document</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('document-distributions.reject', $distribution) }}"
                                            method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="remarks">Reason for Rejection <span
                                                        class="text-danger">*</span></label>
                                                <textarea name="remarks" id="remarks" class="form-control" rows="3" required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-danger">Reject Distribution</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
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
