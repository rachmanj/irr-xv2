@extends('layout.main')

@section('title_page')
    Create LPD
@endsection

@section('breadcrumb_title')
    <small>accounting / deliveries / create</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-acc-lpd-links page='create' />

            <form action="{{ route('accounting.lpd.store') }}" method="POST" id="create-lpd-form">
                @csrf
                <input type="hidden" name="lpd_number" id="lpd_number">
                <input type="hidden" name="date" id="date">
                <input type="hidden" name="destination" id="destination">
                <input type="hidden" name="attention_person" id="attention_person">
                <input type="hidden" name="notes" id="notes">

                <!-- Documents Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list-alt mr-2"></i>
                            Select Documents to Send
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="ready-documents-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 30px;">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="select-all">
                                                <label class="custom-control-label" for="select-all"></label>
                                            </div>
                                        </th>
                                        <th>Invoice No</th>
                                        <th>Supplier</th>
                                        <th>Document Type</th>
                                        <th>Document No</th>
                                        <th>Receive Date</th>
                                        <th>Days</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <input type="hidden" name="documents" id="selected-documents" required>
                        @error('documents')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary" id="openDeliveryModal" disabled>
                            <i class="fas fa-file-invoice mr-1"></i> Create LPD
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delivery Info Modal -->
    <div class="modal fade" id="deliveryInfoModal" tabindex="-1" role="dialog" aria-labelledby="deliveryInfoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deliveryInfoModalLabel">
                        <i class="fas fa-file-invoice mr-2"></i>
                        Input Delivery Info
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="lpd_number">LPD Number</label>
                        <input type="text" class="form-control @error('lpd_number') is-invalid @enderror" id="lpd_number"
                            name="lpd_number" value="{{ old('lpd_number') }}" required>
                        @error('lpd_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" id="date"
                            name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="destination">Destination</label>
                        <input type="text" class="form-control @error('destination') is-invalid @enderror"
                            id="destination" name="destination" value="{{ old('destination') }}" required>
                        @error('destination')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="attention_person">Attention Person</label>
                        <input type="text" class="form-control @error('attention_person') is-invalid @enderror"
                            id="attention_person" name="attention_person" value="{{ old('attention_person') }}" required>
                        @error('attention_person')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary" id="submitForm">
                        <i class="fas fa-save mr-1"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
@endsection

@section('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            toastr.options = {
                "closeButton": true,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "30000",
                "extendedTimeOut": "10000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut",
                "tapToDismiss": false
            };

            // Initialize DataTable
            const table = $('#ready-documents-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: '{{ route('accounting.lpd.ready-to-deliver.data') }}',
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input document-checkbox" 
                                        id="document-${row.DT_RowIndex}" value="${row.id}">
                                    <label class="custom-control-label" for="document-${row.DT_RowIndex}"></label>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'invoice_number',
                        name: 'invoice_number'
                    },
                    {
                        data: 'supplier_name',
                        name: 'supplier_name'
                    },
                    {
                        data: 'document_type',
                        name: 'document_type'
                    },
                    {
                        data: 'document_number',
                        name: 'document_number'
                    },
                    {
                        data: 'receive_date',
                        name: 'receive_date'
                    },
                    {
                        data: 'days',
                        name: 'days',
                        className: 'text-right'
                    }
                ],
                order: [
                    [6, 'desc']
                ],
                drawCallback: function() {
                    updateSelectAllCheckbox();
                }
            });

            // Handle select all checkbox
            $('#select-all').change(function() {
                const isChecked = $(this).prop('checked');
                $('.document-checkbox:visible').prop('checked', isChecked);
                updateSelectedDocuments();
            });

            // Handle individual checkbox changes
            $(document).on('change', '.document-checkbox', function() {
                updateSelectAllCheckbox();
                updateSelectedDocuments();
            });

            // Update select all checkbox state
            function updateSelectAllCheckbox() {
                const totalVisible = $('.document-checkbox:visible').length;
                const totalChecked = $('.document-checkbox:visible:checked').length;
                $('#select-all').prop('checked', totalVisible === totalChecked && totalVisible > 0);
            }

            // Update hidden input with selected document IDs
            function updateSelectedDocuments() {
                const selectedIds = $('.document-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
                $('#selected-documents').val(JSON.stringify(selectedIds));
                $('#openDeliveryModal').prop('disabled', selectedIds.length === 0);
            }

            // Handle opening modal
            $('#openDeliveryModal').click(function() {
                // Clear previous values
                $('#deliveryInfoModal input').val('');
                $('#deliveryInfoModal textarea').val('');
                $('#deliveryInfoModal input[name="date"]').val('{{ date('Y-m-d') }}');
                $('#deliveryInfoModal .is-invalid').removeClass('is-invalid');

                $('#deliveryInfoModal').modal('show');
            });

            // Handle form submission in modal
            $('#submitForm').click(function(e) {
                e.preventDefault();

                // Validate required fields
                let isValid = true;
                $('#deliveryInfoModal input[required]').each(function() {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                if (!isValid) {
                    return;
                }

                // Transfer values to hidden form
                const fields = ['lpd_number', 'date', 'destination', 'attention_person', 'notes'];
                fields.forEach(field => {
                    const value = $(`#deliveryInfoModal [name="${field}"]`).val();
                    $(`#create-lpd-form #${field}`).val(value);
                });

                // Close modal
                $('#deliveryInfoModal').modal('hide');

                // Submit the form
                $('#create-lpd-form').submit();
            });

            // Clear validation on input
            $('#deliveryInfoModal input').on('input', function() {
                $(this).removeClass('is-invalid');
            });

            // Handle form submission with AJAX
            $('#create-lpd-form').submit(function(e) {
                e.preventDefault();
                const selectedDocuments = JSON.parse($('#selected-documents').val() || '[]');

                if (selectedDocuments.length === 0) {
                    toastr.error('Please select at least one document.');
                    return false;
                }

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        toastr.success('LPD created successfully');
                        window.location.href =
                            "{{ route('accounting.lpd.index', ['page' => 'list']) }}";
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Validation errors
                            if (xhr.responseJSON.errors) {
                                const errors = xhr.responseJSON.errors;
                                Object.keys(errors).forEach(key => {
                                    toastr.error(errors[key][0]);
                                });
                            } else {
                                toastr.error(xhr.responseJSON.message);
                            }
                        } else {
                            // Other errors including duplicate entry
                            const error = xhr.responseJSON?.message ||
                                'An error occurred while creating LPD';
                            toastr.error(error);
                        }
                    }
                });
            });
        });
    </script>
@endsection
