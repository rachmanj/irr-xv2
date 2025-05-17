@extends('layout.main')

@section('title_page')
    Track Document
@endsection

@section('content')
    <x-distribution-links page="search" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Track Document Location</h3>
                    <div class="card-tools">
                        <a href="{{ route('document-distributions.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="card">
                                <div class="card-header bg-primary">
                                    <h5 class="card-title text-white">Search Document</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('document-distributions.search') }}" method="GET">
                                        <div class="form-group">
                                            <label for="document_type">Document Type</label>
                                            <select name="document_type" id="document_type" class="form-control" required>
                                                <option value="">Select Document Type</option>
                                                <option value="App\Models\Invoice"
                                                    {{ request('document_type') == 'App\Models\Invoice' ? 'selected' : '' }}>
                                                    Invoice</option>
                                                <option value="App\Models\AdditionalDocument"
                                                    {{ request('document_type') == 'App\Models\AdditionalDocument' ? 'selected' : '' }}>
                                                    Additional Document</option>
                                            </select>
                                        </div>

                                        <div class="form-group invoice-fields"
                                            style="{{ request('document_type') != 'App\Models\Invoice' ? 'display: none;' : '' }}">
                                            <label for="invoice_number">Invoice Number</label>
                                            <input type="text" name="invoice_number" id="invoice_number"
                                                class="form-control" value="{{ request('invoice_number') }}">
                                        </div>

                                        <div class="form-group additional-doc-fields"
                                            style="{{ request('document_type') != 'App\Models\AdditionalDocument' ? 'display: none;' : '' }}">
                                            <label for="document_number">Document Number</label>
                                            <input type="text" name="document_number" id="document_number"
                                                class="form-control" value="{{ request('document_number') }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="supplier_id">Supplier</label>
                                            <select name="supplier_id" id="supplier_id" class="form-control">
                                                <option value="">Select Supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="po_number">PO Number</label>
                                            <input type="text" name="po_number" id="po_number" class="form-control"
                                                value="{{ request('po_number') }}">
                                        </div>

                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Search
                                        </button>
                                        <a href="{{ route('document-distributions.search-history') }}"
                                            class="btn btn-secondary">
                                            <i class="fas fa-redo"></i> Reset
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (isset($results) && $results->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-success">
                                        <h5 class="card-title text-white">Search Results</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Document Type</th>
                                                        <th>Document Number</th>
                                                        <th>Supplier</th>
                                                        <th>PO Number</th>
                                                        <th>Current Location</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($results as $result)
                                                        <tr>
                                                            <td>{{ $result->id }}</td>
                                                            <td>
                                                                @if (get_class($result) == 'App\Models\Invoice')
                                                                    Invoice
                                                                @elseif(get_class($result) == 'App\Models\AdditionalDocument')
                                                                    {{ $result->document_type }}
                                                                @else
                                                                    {{ class_basename($result) }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if (get_class($result) == 'App\Models\Invoice')
                                                                    {{ $result->invoice_number }}
                                                                @elseif(get_class($result) == 'App\Models\AdditionalDocument')
                                                                    {{ $result->document_number }}
                                                                @endif
                                                            </td>
                                                            <td>{{ $result->supplier->name ?? 'N/A' }}</td>
                                                            <td>{{ $result->po_number ?? 'N/A' }}</td>
                                                            <td>
                                                                @if ($result->cur_loc)
                                                                    {{ $result->currentDepartment->department_name ?? $result->cur_loc }}
                                                                @else
                                                                    Not specified
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('document-distributions.history', ['document_type' => get_class($result), 'document_id' => $result->id]) }}"
                                                                    class="btn btn-info btn-sm">
                                                                    <i class="fas fa-history"></i> View History
                                                                </a>
                                                                <a href="{{ route('document-distributions.create', ['document_type' => get_class($result), 'document_id' => $result->id]) }}"
                                                                    class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-paper-plane"></i> Distribute
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(isset($results))
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No documents found matching your search
                                    criteria.
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

@push('scripts')
    <script>
        $(function() {
            $('#document_type').change(function() {
                var docType = $(this).val();
                if (docType === 'App\\Models\\Invoice') {
                    $('.invoice-fields').show();
                    $('.additional-doc-fields').hide();
                } else if (docType === 'App\\Models\\AdditionalDocument') {
                    $('.invoice-fields').hide();
                    $('.additional-doc-fields').show();
                } else {
                    $('.invoice-fields').hide();
                    $('.additional-doc-fields').hide();
                }
            });

            // Initialize select2 for supplier dropdown
            $('#supplier_id').select2({
                placeholder: 'Select Supplier',
                allowClear: true
            });
        });
    </script>
@endpush
