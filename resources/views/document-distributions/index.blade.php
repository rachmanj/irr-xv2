@extends('layout.main')

@section('title_page')
    Document Distributions
@endsection

@section('content')
    <x-distribution-links page="index" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Document Distributions</h3>
                    <div class="card-tools">
                        <a href="{{ route('document-distributions.search-history') }}" class="btn btn-info btn-sm mr-2">
                            <i class="fas fa-search"></i> Track Document
                        </a>
                        <a href="{{ route('document-distributions.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> New Distribution
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <form action="{{ route('document-distributions.index') }}" method="GET" class="row">
                            <div class="col-md-3">
                                <select name="document_type" class="form-control">
                                    <option value="">All Document Types</option>
                                    <option value="App\Models\Invoice"
                                        {{ request('document_type') == 'App\Models\Invoice' ? 'selected' : '' }}>Invoice
                                    </option>
                                    <option value="App\Models\AdditionalDocument"
                                        {{ request('document_type') == 'App\Models\AdditionalDocument' ? 'selected' : '' }}>
                                        Additional Document</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>In
                                        Transit</option>
                                    <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>
                                        Received</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="location_code" class="form-control" placeholder="Location Code"
                                    value="{{ request('location_code') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('document-distributions.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Document Type</th>
                                    <th>Document ID</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Sent By</th>
                                    <th>Sent At</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($distributions as $distribution)
                                    <tr>
                                        <td>{{ $distribution->id }}</td>
                                        <td>
                                            @if ($distribution->document_type == 'App\Models\Invoice')
                                                Invoice
                                            @elseif($distribution->document_type == 'App\Models\AdditionalDocument')
                                                Additional Document
                                            @else
                                                {{ $distribution->document_type }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $distribution->document_id }}
                                            <a href="{{ route('document-distributions.history', ['document_type' => $distribution->document_type, 'document_id' => $distribution->document_id]) }}"
                                                class="btn btn-xs btn-info" title="View History">
                                                <i class="fas fa-history"></i>
                                            </a>
                                        </td>
                                        <td>{{ $distribution->fromDepartment->department_name ?? $distribution->from_location_code }}
                                        </td>
                                        <td>{{ $distribution->toDepartment->department_name ?? $distribution->to_location_code }}
                                        </td>
                                        <td>{{ $distribution->sender->name ?? 'N/A' }}</td>
                                        <td>{{ $distribution->sent_at ? $distribution->sent_at->format('Y-m-d H:i') : 'N/A' }}
                                        </td>
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
                                        <td>
                                            <a href="{{ route('document-distributions.show', $distribution) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if ($distribution->status == 'in_transit')
                                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                                    data-target="#receiveModal{{ $distribution->id }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                                    data-target="#rejectModal{{ $distribution->id }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Receive Modal -->
                                    <div class="modal fade" id="receiveModal{{ $distribution->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="receiveModalLabel{{ $distribution->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('document-distributions.receive', $distribution) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="receiveModalLabel{{ $distribution->id }}">Receive Document
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="remarks">Remarks</label>
                                                            <textarea name="remarks" id="remarks" class="form-control" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-success">Mark as
                                                            Received</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $distribution->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="rejectModalLabel{{ $distribution->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('document-distributions.reject', $distribution) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="rejectModalLabel{{ $distribution->id }}">Reject Document
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="remarks">Reason for Rejection <span
                                                                    class="text-danger">*</span></label>
                                                            <textarea name="remarks" id="remarks" class="form-control" rows="3" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-danger">Reject
                                                            Distribution</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No document distributions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $distributions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @include('document-distributions.dashboard-widget')
        </div>
    </div>
@endsection
