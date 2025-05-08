@extends('layout.main')

@section('title_page')
    INVOICES
@endsection

@section('breadcrumb_title')
    <small>accounting / invoices / edit</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Invoice</h3>
                    <a href="{{ route('accounting.invoices.index', ['page' => 'search']) }}"
                        class="btn btn-sm btn-primary float-right">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>

                <form action="{{ route('accounting.invoices.update', $invoice->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Tabs Navigation --}}
                    <ul class="nav nav-tabs" id="documentTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="invoice-tab" data-toggle="tab" href="#invoice" role="tab">
                                Invoice Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="addocs-tab" data-toggle="tab" href="#addocs" role="tab">
                                Additional Documents
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="attachments-tab" data-toggle="tab" href="#attachments" role="tab">
                                Attachments
                            </a>
                        </li>
                    </ul>

                    {{-- Tabs Content --}}
                    <div class="tab-content" id="documentTabsContent">
                        {{-- Invoice Tab --}}
                        <div class="tab-pane fade show active" id="invoice" role="tabpanel">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="supplier_id">Select Vendor <span
                                                    class="text-danger">*</span></label>
                                            <select name="supplier_id" id="supplier_id" class="form-control select2bs4">
                                                <option value="">Select Vendor</option>
                                                @foreach (App\Models\Supplier::where('type', 'vendor')->where('is_active', 1)->orderBy('name', 'asc')->get() as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ $invoice->supplier_id == $supplier->id ? 'selected' : '' }}>
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
                                            <label for="invoice_type">Invoice Type <span
                                                    class="text-danger">*</span></label>
                                            <select name="invoice_type" id="invoice_type" class="form-control select2bs4">
                                                <option value="">Select Type</option>
                                                @foreach (App\Models\InvoiceType::orderBy('type_name', 'asc')->get() as $invoiceType)
                                                    <option value="{{ $invoiceType->id }}"
                                                        {{ $invoice->type_id == $invoiceType->id ? 'selected' : '' }}>
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
                                            <label for="invoice_number">Invoice Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="invoice_number" id="invoice_number"
                                                class="form-control @error('invoice_number') is-invalid @enderror"
                                                value="{{ old('invoice_number', $invoice->invoice_number) }}">
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
                                                value="{{ old('po_no', $invoice->po_no) }}">
                                            @error('po_no')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="invoice_date">Invoice Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="invoice_date" id="invoice_date" class="form-control"
                                                value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}">
                                            @error('invoice_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="receive_date">Receive Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="receive_date" id="receive_date" class="form-control"
                                                value="{{ old('receive_date', $invoice->receive_date->format('Y-m-d')) }}">
                                            @error('receive_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <label for="currency">Curr <span class="text-danger">*</span></label>
                                                    <select name="currency" id="currency" class="form-control">
                                                        <option value="IDR"
                                                            {{ old('currency', $invoice->currency) == 'IDR' ? 'selected' : '' }}>
                                                            IDR
                                                        </option>
                                                        <option value="USD"
                                                            {{ old('currency', $invoice->currency) == 'USD' ? 'selected' : '' }}>
                                                            USD
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-9">
                                                <div class="form-group">
                                                    <label for="amount">Amount <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="amount" id="amount"
                                                        class="form-control"
                                                        value="{{ old('amount', number_format($invoice->amount, 2, '.', ',')) }}"
                                                        onkeyup="formatNumber(this)">
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
                                            <label for="receive_project">Received in <span
                                                    class="text-danger">*</span></label>
                                            <select name="receive_project" id="receive_project"
                                                class="form-control select2bs4">
                                                <option value="000H"
                                                    {{ old('receive_project', $invoice->receive_project) == '000H' ? 'selected' : '' }}>
                                                    000H</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->code }}"
                                                        {{ old('receive_project', $invoice->receive_project) == $project->code ? 'selected' : '' }}>
                                                        {{ $project->code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="invoice_project">For Project <span
                                                    class="text-danger">*</span></label>
                                            <select name="invoice_project" id="invoice_project"
                                                class="form-control select2bs4">
                                                <option value="000H"
                                                    {{ old('invoice_project', $invoice->invoice_project) == '000H' ? 'selected' : '' }}>
                                                    000H</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->code }}"
                                                        {{ old('invoice_project', $invoice->invoice_project) == $project->code ? 'selected' : '' }}>
                                                        {{ $project->code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="payment_project">Payment in <span
                                                    class="text-danger">*</span></label>
                                            <select name="payment_project" id="payment_project"
                                                class="form-control select2bs4">
                                                <option value="">Select Project</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->code }}"
                                                        {{ old('payment_project', $invoice->payment_project) == $project->code ? 'selected' : '' }}>
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
                                            <input type="text" name="remarks" id="remarks" class="form-control"
                                                value="{{ old('remarks', $invoice->remarks) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Additional Documents Tab --}}
                        <div class="tab-pane fade" id="addocs" role="tabpanel">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-sm btn-success float-right mb-3"
                                            data-toggle="modal" data-target="#addDocumentModal">
                                            <i class="fas fa-plus"></i> Add New Document
                                        </button>
                                        <table class="table table-sm">
                                            <thead style="background-color: #343a40; color: white;">
                                                <tr>
                                                    <td class="py-1">#</td>
                                                    <td class="py-1">DocType</td>
                                                    <td class="py-1">DocNum</td>
                                                    <td class="py-1">DocDate</td>
                                                    <td class="py-1">ReceiveDate</td>
                                                    <td class="py-1">PO No</td>
                                                    <td class="py-1">checkbox</td>
                                                </tr>
                                            </thead>
                                            <tbody id="similar-documents-tbody">
                                                @foreach ($additionalDocuments as $additionalDocument)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $additionalDocument->type ? $additionalDocument->type->type_name : 'N/A' }}
                                                        </td>
                                                        <td>{{ $additionalDocument->document_number }}</td>
                                                        <td>{{ $additionalDocument->document_date }}</td>
                                                        <td>
                                                            <input type="date"
                                                                class="form-control form-control-sm edit-receive-date"
                                                                data-document-id="{{ $additionalDocument->id }}"
                                                                value="{{ $additionalDocument->receive_date }}"
                                                                style="width: 140px;">
                                                        </td>
                                                        <td>{{ $additionalDocument->po_no ?? '-' }}</td>
                                                        <td>
                                                            <input type="checkbox" name="selected_documents[]"
                                                                value="{{ $additionalDocument->id }}"
                                                                {{ in_array($additionalDocument->id, $connectedDocumentIds) ? 'checked' : '' }}>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Attachments Tab --}}
                        <div class="tab-pane fade" id="attachments" role="tabpanel">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <button type="button" class="btn btn-sm btn-success float-right"
                                            data-toggle="modal" data-target="#uploadAttachmentsModal">
                                            <i class="fas fa-upload"></i> Upload Attachments
                                        </button>
                                    </div>
                                </div>
                                <div class="row" id="attachments-container">
                                    @foreach ($invoice->attachments as $attachment)
                                        <div class="col-md-3 col-sm-4 mb-3">
                                            <div class="card h-100">
                                                <div class="card-body p-2">
                                                    <div class="text-center mb-2">
                                                        @if (in_array(strtolower(pathinfo($attachment->file_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                                            <img src="{{ asset('storage/' . $attachment->file_path) }}"
                                                                class="img-fluid" style="max-height: 100px;"
                                                                alt="Attachment preview">
                                                        @else
                                                            <i class="fas fa-file fa-3x text-secondary"></i>
                                                        @endif
                                                    </div>
                                                    <p class="small text-muted mb-1 text-truncate"
                                                        title="{{ $attachment->original_name }}">
                                                        {{ $attachment->original_name }}
                                                    </p>
                                                    <div class="btn-group btn-group-sm w-100">
                                                        <a href="{{ asset('storage/' . $attachment->file_path) }}"
                                                            class="btn btn-info" target="_blank">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-danger delete-attachment"
                                                            data-attachment-id="{{ $attachment->id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success btn-sm">Update Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('accounting.invoices.edit._add_document_modal')
    @include('accounting.invoices.edit._upload_attachments_modal')
@endsection

@section('styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"> --}}
    <style>
        .nav-tabs .nav-link.active {
            font-weight: bold;
            color: #343a40;
        }

        .card-header .active {
            color: black;
            text-transform: uppercase;
        }

        .tab-content {
            padding-top: 20px;
        }
    </style>
@endsection

@section('scripts')
    <script>
        const asset_url = "{{ url('') }}";
    </script>
    <!-- Select2 -->
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>
    <script>
        // Add this at the start of your scripts
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize toastr options if needed
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
        };

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

            $('#addDocumentForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('accounting.additional-documents.store') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            console.log(response);
                            var newRow = `
                                <tr>
                                    <td>${$('#similar-documents-tbody tr').length + 1}</td>
                                    <td>${response.data.document_type}</td>
                                    <td>${response.data.document_number}</td>
                                    <td>${response.data.document_date}</td>
                                    <td>
                                        <input type="date"
                                            class="form-control form-control-sm edit-receive-date"
                                            data-document-id="${response.data.id}"
                                            value="${response.data.receive_date || ''}"
                                            style="width: 140px;">
                                    </td>
                                    <td>${response.data.po_no ? response.data.po_no : '-'}</td>
                                    <td>
                                        <input type="checkbox" name="selected_documents[]" 
                                            value="${response.data.id}" checked>
                                    </td>
                                </tr>
                            `;
                            $('#similar-documents-tbody').append(newRow);

                            // Close modal and reset form
                            $('#addDocumentModal').modal('hide');
                            $('#addDocumentForm')[0].reset();

                            // Show success message
                            toastr.success('Document added successfully');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error adding document');
                    }
                });
            });

            // Function to refresh attachments content
            function refreshAttachmentsTab(attachments) {
                const container = $('#attachments-container');
                container.empty();

                attachments.forEach(attachment => {
                    const isImage = ['jpg', 'jpeg', 'png', 'gif'].includes(
                        attachment.file_path.split('.').pop().toLowerCase()
                    );

                    const preview = isImage ?
                        `<img src="${asset_url}/storage/${attachment.file_path}" 
                              class="img-fluid" style="max-height: 100px;" 
                              alt="Attachment preview">` :
                        `<i class="fas fa-file fa-3x text-secondary"></i>`;

                    const attachmentHtml = `
                        <div class="col-md-3 col-sm-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body p-2">
                                    <div class="text-center mb-2">
                                        ${preview}
                                    </div>
                                    <p class="small text-muted mb-1 text-truncate" 
                                       title="${attachment.original_name}">
                                        ${attachment.original_name}
                                    </p>
                                    <div class="btn-group btn-group-sm w-100">
                                        <a href="${asset_url}/storage/${attachment.file_path}" 
                                           class="btn btn-info" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-danger delete-attachment" 
                                                data-attachment-id="${attachment.id}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.append(attachmentHtml);
                });

                // Reattach delete event handlers
                attachDeleteHandlers();
            }

            // Function to attach delete handlers
            function attachDeleteHandlers() {
                $('.delete-attachment').on('click', function() {
                    if (confirm('Are you sure you want to delete this attachment?')) {
                        const attachmentId = $(this).data('attachment-id');
                        deleteAttachment(attachmentId);
                    }
                });
            }

            // Function to delete attachment
            function deleteAttachment(attachmentId) {
                $.ajax({
                    url: '{{ route('accounting.attachments.destroy', '') }}/' + attachmentId,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Attachment deleted successfully');

                            // Fetch updated attachments
                            fetchAttachments();
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error deleting attachment: ' + xhr.responseJSON.message);
                    }
                });
            }

            // Function to fetch current attachments
            function fetchAttachments() {
                $.ajax({
                    url: '{{ route('accounting.invoices.get-attachments', $invoice->id) }}',
                    method: 'GET',
                    success: function(response) {
                        refreshAttachmentsTab(response.attachments);
                    },
                    error: function(xhr) {
                        toastr.error('Error fetching attachments');
                    }
                });
            }

            // Handle attachment upload
            $('#uploadAttachmentsForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const $form = $(this); // Store reference to the form

                $.ajax({
                    url: '{{ route('accounting.invoices.upload-attachments', $invoice->id) }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Attachments uploaded successfully');
                            $('#uploadAttachmentsModal').modal('hide');
                            $form[0].reset(); // Use stored form reference
                            $('.custom-file-label').html('Choose files');
                            $('#selected-files').empty();

                            // Fetch updated attachments
                            fetchAttachments();

                            // Switch to attachments tab
                            $('#attachments-tab').tab('show');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error uploading attachments: ' + xhr.responseJSON
                            .message);
                    }
                });
            });

            // Initial attachment of delete handlers
            attachDeleteHandlers();

            // Add this before the upload handler
            $('#attachments').on('change', function() {
                const files = $(this)[0].files;
                const fileList = $('#selected-files');
                fileList.empty();

                for (let i = 0; i < files.length; i++) {
                    fileList.append('<div class="small">' + files[i].name + '</div>');
                }

                if (files.length > 0) {
                    $(this).next('.custom-file-label').html(files.length + ' files selected');
                } else {
                    $(this).next('.custom-file-label').html('Choose files');
                }
            });

            // Add this inside your existing $(document).ready() function
            $('.edit-receive-date').on('change', function() {
                const documentId = $(this).data('document-id');
                const receiveDate = $(this).val();
                const $input = $(this);

                $.ajax({
                    url: `${asset_url}/accounting/additional-documents/${documentId}/update-receive-date`,
                    method: 'POST',
                    data: {
                        receive_date: receiveDate,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Receive date updated successfully');
                        } else {
                            toastr.error('Error updating receive date');
                            // Reset to previous value if there's an error
                            $input.val($input.data('previous-value'));
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error updating receive date');
                        // Reset to previous value if there's an error
                        $input.val($input.data('previous-value'));
                    }
                });
            });

            // Store previous value when focusing on input
            $('.edit-receive-date').on('focus', function() {
                $(this).data('previous-value', $(this).val());
            });
        });
    </script>
@endsection
