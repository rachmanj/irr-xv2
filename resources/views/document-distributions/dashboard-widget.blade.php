<div class="card">
    <div class="card-header">
        <h3 class="card-title">Recent Document Distributions</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Document</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentDistributions as $distribution)
                        <tr>
                            <td>{{ $distribution->id }}</td>
                            <td>
                                @if ($distribution->document_type == 'App\Models\Invoice')
                                    Invoice
                                    #{{ optional($distribution->document)->invoice_number ?? $distribution->document_id }}
                                @elseif($distribution->document_type == 'App\Models\AdditionalDocument')
                                    {{ optional($distribution->document)->document_type ?? 'Document' }}
                                    #{{ optional($distribution->document)->document_number ?? $distribution->document_id }}
                                @else
                                    {{ class_basename($distribution->document_type) }} #{{ $distribution->document_id }}
                                @endif
                            </td>
                            <td>{{ $distribution->fromDepartment->department_name ?? $distribution->from_location_code }}
                            </td>
                            <td>{{ $distribution->toDepartment->department_name ?? $distribution->to_location_code }}
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
                            <td>{{ $distribution->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('document-distributions.show', $distribution) }}"
                                    class="btn btn-xs btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('document-distributions.history', ['document_type' => $distribution->document_type, 'document_id' => $distribution->document_id]) }}"
                                    class="btn btn-xs btn-primary">
                                    <i class="fas fa-history"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No recent distributions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('document-distributions.index') }}" class="btn btn-sm btn-primary">
            View All Distributions
        </a>
        <a href="{{ route('document-distributions.create') }}" class="btn btn-sm btn-success float-right">
            <i class="fas fa-plus"></i> New Distribution
        </a>
    </div>
</div>
