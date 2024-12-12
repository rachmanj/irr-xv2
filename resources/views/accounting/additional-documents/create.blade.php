@extends('layout.main')

@section('title_page')
    ADDITIONAL DOCUMENTS
@endsection

@section('breadcrumb_title')
    <small>accounting / addocs / create</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-acc-addoc-links page='create' />
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Additional Document</h3>
                </div>

                <form action="{{ route('accounting.additional-documents.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="type_id">Document Type</label>
                                    <select name="type_id" id="type_id"
                                        class="form-control @error('type_id') is-invalid @enderror">
                                        <option value="">Select Document Type</option>
                                        @foreach ($additionalDocumentTypes as $documentType)
                                            <option value="{{ $documentType->id }}"
                                                {{ old('type_id') == $documentType->id ? 'selected' : '' }}>
                                                {{ $documentType->type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="document_number">Document Number</label>
                                    <input type="text" name="document_number" id="document_number"
                                        class="form-control @error('document_number') is-invalid @enderror"
                                        value="{{ old('document_number') }}" required>
                                    @error('document_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="document-number-error" class="invalid-feedback" style="display: none;">Dokumen
                                        dengan type yang sama sudah ada</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="document_date">Document Date</label>
                                    <input type="date" name="document_date" id="document_date"
                                        class="form-control @error('document_date') is-invalid @enderror"
                                        value="{{ old('document_date') }}" required>
                                    @error('document_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="receive_date">Receive Date</label>
                                    <input type="date" name="receive_date" id="receive_date"
                                        class="form-control @error('receive_date') is-invalid @enderror"
                                        value="{{ old('receive_date') }}">
                                    @error('receive_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="po_no">PO Number</label>
                                    <input type="text" name="po_no" id="po_no" class="form-control"
                                        value="{{ old('po_no') }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="invoice_id">Select Invoice</label>
                                    <select name="invoice_id" id="invoice_id"
                                        class="form-control select2bs4 @error('invoice_id') is-invalid @enderror">
                                        <option value="">Select Invoice</option>
                                        @foreach ($invoices as $invoice)
                                            <option value="{{ $invoice->id }}"
                                                {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}>
                                                {{ $invoice->invoice_number }}</option>
                                        @endforeach
                                    </select>
                                    @error('invoice_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="remarks">Remarks</label>
                                    <textarea name="remarks" id="remarks" class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks') }}</textarea>
                                    @error('remarks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                        <a href="{{ route('accounting.additional-documents.index') }}"
                            class="btn btn-secondary btn-sm">Cancel</a>
                    </div>
                </form>
            </div>

            <div class="card" id="similar-documents-card" style="display: none;">
                <div class="card-header p-1">
                    <h5 class="card-title">Documents with similar PO No</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>DocNum</th>
                                <th>PO</th>
                            </tr>
                        </thead>
                        <tbody id="similar-documents-tbody">
                            <!-- Dynamic content will be inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
    </div>
@endsection

@section('styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .card-header .active {
            color: black;
            text-transform: uppercase;
        }
    </style>
@endsection

@section('scripts')
    <!-- Select2 -->
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#document_number').on('blur', function() {
                var documentTypeId = $('#type_id').val();
                var documentNumber = $(this).val();

                if (documentTypeId && documentNumber) {
                    $.ajax({
                        url: '{{ route('check.addoc.combination') }}',
                        method: 'GET',
                        data: {
                            type_id: documentTypeId,
                            document_number: documentNumber
                        },
                        success: function(response) {
                            if (response.exists) {
                                $('#document-number-error').show();
                            } else {
                                $('#document-number-error').hide();
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });

            $('#po_no').on('blur', function() {
                var poNo = $(this).val();

                if (poNo) {
                    $.ajax({
                        url: '{{ route('search.invoices.by.po') }}',
                        method: 'GET',
                        data: {
                            po_no: poNo
                        },
                        success: function(response) {
                            $('#invoice_id').empty();
                            if (response.invoices.length > 0) {
                                $('#invoice_id').append(
                                    '<option value="">Select Invoice</option>');
                                $.each(response.invoices, function(index, invoice) {
                                    $('#invoice_id').append('<option value="' + invoice
                                        .id + '">' + invoice.invoice_number +
                                        '</option>');
                                });
                            } else {
                                $('#invoice_id').append(
                                    '<option value="">No related invoices found</option>');
                                $('#invoice_id').prop('disabled', true);
                            }

                            if (response.documents.length > 0) {
                                $('#similar-documents-tbody').empty();
                                $.each(response.documents, function(index, document) {
                                    $('#similar-documents-tbody').append('<tr><td>' + (
                                            index + 1) + '</td><td>' + document
                                        .document_type.type_name + '</td><td>' +
                                        document
                                        .document_number + '</td><td>' + document
                                        .po_no + '</td></tr>');
                                });
                                $('#similar-documents-card').show();
                            } else {
                                $('#similar-documents-card').hide();
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });

            // initialize select2
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
@endsection
