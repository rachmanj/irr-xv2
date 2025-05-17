@extends('layout.main')

@section('title_page')
    Create Document Distribution
@endsection

@section('content')
    <x-distribution-links page="create" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Distribution</h3>
                    <div class="card-tools">
                        <a href="{{ route('document-distributions.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('document-distributions.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Document Selection</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="document_type">Document Type <span
                                                    class="text-danger">*</span></label>
                                            <select name="document_type" id="document_type"
                                                class="form-control @error('document_type') is-invalid @enderror" required>
                                                <option value="">Select Document Type</option>
                                                <option value="App\Models\Invoice"
                                                    {{ old('document_type', request('document_type')) == 'App\Models\Invoice' ? 'selected' : '' }}>
                                                    Invoice</option>
                                                <option value="App\Models\AdditionalDocument"
                                                    {{ old('document_type', request('document_type')) == 'App\Models\AdditionalDocument' ? 'selected' : '' }}>
                                                    Additional Document</option>
                                            </select>
                                            @error('document_type')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group invoice-fields"
                                            style="{{ old('document_type', request('document_type')) != 'App\Models\Invoice' ? 'display: none;' : '' }}">
                                            <label for="invoice_search">Invoice Search</label>
                                            <div class="input-group">
                                                <input type="text" id="invoice_search" class="form-control"
                                                    placeholder="Search by invoice number, PO number...">
                                                <div class="input-group-append">
                                                    <button type="button" id="search_invoices" class="btn btn-info">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group additional-doc-fields"
                                            style="{{ old('document_type', request('document_type')) != 'App\Models\AdditionalDocument' ? 'display: none;' : '' }}">
                                            <label for="document_search">Document Search</label>
                                            <div class="input-group">
                                                <input type="text" id="document_search" class="form-control"
                                                    placeholder="Search by document number, type...">
                                                <div class="input-group-append">
                                                    <button type="button" id="search_documents" class="btn btn-info">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="document_id">Document <span class="text-danger">*</span></label>
                                            <select name="document_id" id="document_id"
                                                class="form-control @error('document_id') is-invalid @enderror" required>
                                                <option value="">Select Document</option>
                                                @if (old('document_id', request('document_id')))
                                                    <option value="{{ old('document_id', request('document_id')) }}"
                                                        selected>
                                                        {{ old('document_id', request('document_id')) }}
                                                        @if (isset($document))
                                                            -
                                                            {{ $document instanceof \App\Models\Invoice ? $document->invoice_number : $document->document_number }}
                                                        @endif
                                                    </option>
                                                @endif
                                            </select>
                                            @error('document_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Distribution Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="from_location_code">From Location <span
                                                    class="text-danger">*</span></label>
                                            <select name="from_location_code" id="from_location_code"
                                                class="form-control @error('from_location_code') is-invalid @enderror"
                                                required>
                                                <option value="">Select Location</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->location_code }}"
                                                        {{ old('from_location_code') == $department->location_code ? 'selected' : '' }}>
                                                        {{ $department->department_name }}
                                                        ({{ $department->location_code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('from_location_code')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="to_location_code">To Location <span
                                                    class="text-danger">*</span></label>
                                            <select name="to_location_code" id="to_location_code"
                                                class="form-control @error('to_location_code') is-invalid @enderror"
                                                required>
                                                <option value="">Select Location</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->location_code }}"
                                                        {{ old('to_location_code') == $department->location_code ? 'selected' : '' }}>
                                                        {{ $department->department_name }}
                                                        ({{ $department->location_code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('to_location_code')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="remarks">Remarks</label>
                                            <textarea name="remarks" id="remarks" class="form-control @error('remarks') is-invalid @enderror" rows="3">{{ old('remarks') }}</textarea>
                                            @error('remarks')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane"></i> Create Distribution
                                </button>
                                <a href="{{ route('document-distributions.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
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
            // Handle document type change
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

                // Clear document selection
                $('#document_id').empty().append('<option value="">Select Document</option>');
            });

            // Search invoices
            $('#search_invoices').click(function() {
                var query = $('#invoice_search').val();
                if (!query) {
                    alert('Please enter a search term');
                    return;
                }

                $.ajax({
                    url: '{{ route('api.distributions.search-documents') }}',
                    data: {
                        document_type: 'App\\Models\\Invoice',
                        query: query
                    },
                    success: function(data) {
                        var select = $('#document_id');
                        select.empty().append('<option value="">Select Document</option>');

                        if (data.length === 0) {
                            alert('No invoices found matching your search');
                            return;
                        }

                        $.each(data, function(i, item) {
                            select.append($('<option>', {
                                value: item.id,
                                text: item.invoice_number + ' - ' + (item
                                        .supplier_name || 'N/A') + ' - ' +
                                    item.po_number
                            }));
                        });
                    },
                    error: function() {
                        alert('Error searching for invoices');
                    }
                });
            });

            // Search additional documents
            $('#search_documents').click(function() {
                var query = $('#document_search').val();
                if (!query) {
                    alert('Please enter a search term');
                    return;
                }

                $.ajax({
                    url: '{{ route('api.distributions.search-documents') }}',
                    data: {
                        document_type: 'App\\Models\\AdditionalDocument',
                        query: query
                    },
                    success: function(data) {
                        var select = $('#document_id');
                        select.empty().append('<option value="">Select Document</option>');

                        if (data.length === 0) {
                            alert('No documents found matching your search');
                            return;
                        }

                        $.each(data, function(i, item) {
                            select.append($('<option>', {
                                value: item.id,
                                text: item.document_type + ' - ' + item
                                    .document_number + ' - ' + (item
                                        .supplier_name || 'N/A')
                            }));
                        });
                    },
                    error: function() {
                        alert('Error searching for documents');
                    }
                });
            });

            // Initialize select2 for dropdowns
            $('#from_location_code, #to_location_code, #document_id').select2({
                placeholder: 'Select an option',
                allowClear: true
            });
        });
    </script>
@endpush
