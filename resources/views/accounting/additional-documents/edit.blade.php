@extends('layout.main')

@section('title_page')
    Additional Document
@endsection

@section('breadcrumb_title')
    <small>accounting / addocs / edit</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Additional Document</h3>
                    <a href="{{ route('accounting.additional-documents.index', ['page' => 'list']) }}"
                        class="btn btn-sm btn-primary float-right">
                        <i class="fas fa-arrow-left"></i> Back to Document List
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('accounting.additional-documents.update', $additionalDocument->id) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="type_id">Document Type</label>
                                    <select name="type_id" id="type_id"
                                        class="form-control @error('type_id') is-invalid @enderror">
                                        <option value="">Select Document Type</option>
                                        @foreach ($additionalDocumentTypes as $documentType)
                                            <option value="{{ $documentType->id }}"
                                                {{ old('type_id', $additionalDocument->type_id) == $documentType->id ? 'selected' : '' }}>
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
                                        value="{{ old('document_number', $additionalDocument->document_number) }}" required>
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
                                        value="{{ old('document_date', $additionalDocument->document_date) }}" required>
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
                                        value="{{ old('receive_date', $additionalDocument->receive_date) }}">
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
                                        value="{{ old('po_no', $additionalDocument->po_no) }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="invoice_id">Select Invoice</label>
                                    <select name="invoice_id" id="invoice_id" class="form-control select2bs4">
                                        <option value="">Select Invoice</option>
                                        @foreach ($invoices as $invoice)
                                            <option value="{{ $invoice->id }}"
                                                {{ old('invoice_id', $additionalDocument->invoice_id) == $invoice->id ? 'selected' : '' }}>
                                                {{ $invoice->supplier->name . ' | ' . $invoice->invoice_number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="remarks">Remarks</label>
                                    <textarea name="remarks" id="remarks" class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks', $additionalDocument->remarks) }}</textarea>
                                    @error('remarks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        <a href="{{ route('accounting.additional-documents.index') }}"
                            class="btn btn-secondary btn-sm">Cancel</a>
                    </form>
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
                                        .id + '">' + invoice.supplier.name + ' | ' +
                                        invoice.invoice_number + '</option>');
                                });
                            } else {
                                $('#invoice_id').append(
                                    '<option value="">No related invoices found</option>');
                                $('#invoice_id').prop('disabled', true);
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
