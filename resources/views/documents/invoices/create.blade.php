@extends('layout.main')

@section('title_page')
    INVOICES
@endsection

@section('breadcrumb_title')
    <small>documents / invoices / create</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-invoice-links page='create' />
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Invoice</h3>
                </div>

                <form id="invoiceForm" action="{{ route('documents.invoices.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="supplier_id">Select Vendor</label>
                                    <select name="supplier_id" id="supplier_id" class="form-control select2bs4">
                                        <option value="">Select Vendor</option>
                                        @foreach (App\Models\Supplier::where('type', 'vendor')->where('is_active', 1)->orderBy('name', 'asc')->get() as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name . ' | ' . $supplier->sap_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="invoice_type">Invoice Type</label>
                                    <select name="invoice_type" id="invoice_type" class="form-control select2bs4">
                                        <option value="">Select Type</option>
                                        @foreach (App\Models\InvoiceType::orderBy('type_name', 'asc')->get() as $invoiceType)
                                            <option value="{{ $invoiceType->id }}"
                                                {{ old('invoice_type') == $invoiceType->id ? 'selected' : '' }}>
                                                {{ $invoiceType->type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('invoice_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="form-group">
                                    <label for="invoice_number">Invoice Number</label>
                                    <input type="text" name="invoice_number" id="invoice_number"
                                        class="form-control @error('invoice_number') is-invalid @enderror"
                                        value="{{ old('invoice_number') }}">
                                    @error('invoice_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div id="invoice-number-error" class="text-danger" style="display: none;">
                                        Invoice number already exists for this supplier.
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="po_no">PO Number</label>
                                    <input type="text" name="po_no" id="po_no"
                                        class="form-control @error('po_no') is-invalid @enderror"
                                        value="{{ old('po_no') }}">
                                    @error('po_no')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="invoice_date">Invoice Date</label>
                                    <input type="date" name="invoice_date" id="invoice_date" class="form-control"
                                        value="{{ old('invoice_date') }}">
                                    @error('invoice_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="receive_date">Receive Date</label>
                                    <input type="date" name="receive_date" id="receive_date" class="form-control"
                                        value="{{ old('receive_date') }}">
                                    @error('receive_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="amount">Curr</label>
                                            <select name="currency" id="currency" class="form-control">
                                                <option value="IDR"
                                                    {{ old('currency', 'IDR') == 'IDR' ? 'selected' : '' }}>IDR
                                                </option>
                                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>
                                                    USD
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <div class="form-group">
                                            <label for="amount">Amount</label>
                                            <input type="text" name="amount" id="amount" class="form-control"
                                                value="{{ old('amount') }}" onkeyup="formatNumber(this)">
                                            @error('amount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <script>
                                            function formatNumber(input) {
                                                // Remove any non-digit characters except dots
                                                let value = input.value.replace(/[^\d.]/g, '');

                                                // Ensure only one decimal point
                                                let parts = value.split('.');
                                                if (parts.length > 2) {
                                                    parts = [parts[0], parts.slice(1).join('')];
                                                }

                                                // Add thousand separators
                                                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                                                // Join with decimal part if exists
                                                input.value = parts.join('.');
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="receive_project">Received in</label>
                                    <select name="receive_project" id="receive_project" class="form-control select2bs4">
                                        <option value="000H" {{ old('receive_project') == '000H' ? 'selected' : '' }}>
                                            000H</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->code }}"
                                                {{ old('receive_project') == $project->code ? 'selected' : '' }}>
                                                {{ $project->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="invoice_project">For Project</label>
                                    <select name="invoice_project" id="invoice_project" class="form-control select2bs4">
                                        <option value="000H" {{ old('invoice_project') == '000H' ? 'selected' : '' }}>
                                            000H</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->code }}"
                                                {{ old('invoice_project') == $project->code ? 'selected' : '' }}>
                                                {{ $project->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="payment_project">Payment in</label>
                                    <select name="payment_project" id="payment_project" class="form-control select2bs4">
                                        <option value="">Select Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->code }}"
                                                {{ old('payment_project') == $project->code ? 'selected' : '' }}>
                                                {{ $project->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('payment_project')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="remarks">Remarks</label>
                                    <textarea name="remarks" id="remarks" class="form-control">{{ old('remarks') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="similar-documents-card" style="display: none;">
                        <div class="row">
                            <div class="col-12">
                                <h5>Related Additional Documents</h5>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <td>#</td>
                                            <td>DocType</td>
                                            <td>DocNum</td>
                                            <td>DocDate</td>
                                            <td>checkbox</td>
                                        </tr>
                                    </thead>
                                    <tbody id="similar-documents-tbody">
                                        <!-- Dynamic content will be inserted here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-sm">Save Invoice</button>
                    </div>
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
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            $('#invoice_number, #supplier_id').on('blur', function() {
                var invoiceNumber = $('#invoice_number').val();
                var supplierId = $('#supplier_id').val();
                if (invoiceNumber && supplierId) {
                    $.ajax({
                        url: '{{ route('check.invoice.number') }}',
                        method: 'GET',
                        data: {
                            invoice_number: invoiceNumber,
                            supplier_id: supplierId
                        },
                        success: function(response) {
                            if (response.exists) {
                                $('#invoice-number-error').show();
                            } else {
                                $('#invoice-number-error').hide();
                            }
                        }
                    });
                }
            });

            $('#supplier_id').on('change', function() {
                var supplierId = $(this).val();
                if (supplierId) {
                    $.ajax({
                        url: '{{ route('get.payment.project') }}',
                        method: 'GET',
                        data: {
                            supplier_id: supplierId
                        },
                        success: function(response) {
                            $('#payment_project').empty();
                            $('#payment_project').append(
                                '<option value="">Select Payment Project</option>');
                            if (response.payment_project) {
                                $('#payment_project').append('<option value="' + response
                                    .payment_project + '" selected>' + response
                                    .payment_project + '</option>');
                            }
                            $.each(@json($projects), function(index, project) {
                                $('#payment_project').append('<option value="' + project
                                    .code + '">' + project.code + '</option>');
                            });
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                } else {
                    $('#payment_project').empty();
                    $('#payment_project').append('<option value="">Select Payment Project</option>');
                }
            });

            $('#po_no').on('blur', function() {
                var poNo = $(this).val();

                if (poNo) {
                    $.ajax({
                        url: '{{ route('search_addocs_by_po') }}',
                        method: 'GET',
                        data: {
                            po_no: poNo
                        },
                        success: function(response) {
                            $('#similar-documents-tbody').empty();
                            if (response.length > 0) {
                                $.each(response, function(index, document) {
                                    $('#similar-documents-tbody').append('<tr><td>' + (
                                            index + 1) + '</td><td>' + document
                                        .type.type_name + '</td><td>' +
                                        document.document_number + '</td><td>' +
                                        document.document_date +
                                        '</td><td><input type="checkbox" name="selected_documents[]" value="' +
                                        document.id + '"></td></tr>');
                                });
                                $('#similar-documents-card').show();
                            } else {
                                $('#similar-documents-tbody').append(
                                    '<tr><td colspan="5">No additional documents found</td></tr>'
                                );
                                $('#similar-documents-card').show();
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });

            // New AJAX form submission
            $('#invoiceForm').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        showSuccessNotification('Invoice created successfully');
                        // Reset form after successful save
                        $('#invoiceForm')[0].reset();
                        $('.select2bs4').val('').trigger('change');
                        $('#similar-documents-card').hide();
                    },
                    error: function(xhr) {
                        let errorMsg = 'Failed to save invoice';

                        // Extract validation errors if available
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errorList = '';
                            $.each(xhr.responseJSON.errors, function(field, errors) {
                                errorList += errors[0] + '<br>';
                            });
                            errorMsg = errorList;
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }

                        showErrorNotification(errorMsg);
                    }
                });
            });
        });
    </script>
@endsection
