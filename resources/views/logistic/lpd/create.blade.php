@extends('layout.main')

@section('title_page')
    Create LPD
@endsection

@section('breadcrumb_title')
    <small>logistic / lpd / create</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-log-lpd-links page='create' />

            <form action="{{ route('logistic.lpd.store') }}" method="POST" id="create-lpd-form">
                @csrf

                <!-- Add after the @csrf token in the form -->
                <input type="hidden" name="lpd_number" id="lpd_number">
                <input type="hidden" name="date" id="date">
                <input type="hidden" name="destination_department" id="destination_department">
                <input type="hidden" name="attention_person" id="attention_person">
                <input type="hidden" name="notes" id="notes">
                <input type="hidden" name="documents" id="selected-documents" required>

                <!-- Documents Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list-alt mr-2"></i>
                            Select Documents to Send
                        </h3>
                    </div>
                    <div class="card-body">
                        <table id="ready-documents-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 30px;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="select-all">
                                            <label class="custom-control-label" for="select-all"></label>
                                        </div>
                                    </th>
                                    <th>DocType</th>
                                    <th>DocNum</th>
                                    <th>DocDate</th>
                                    <th>Invoice No</th>
                                    <th>Supplier</th>
                                    <th>Days</th>
                                </tr>
                            </thead>
                        </table>
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deliveryInfoModalLabel">
                        <i class="fas fa-file-invoice mr-2"></i>
                        Input LPD Info
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modal-form">
                        <div class="form-group">
                            <label for="lpd_number">LPD Number</label>
                            <input type="text" class="form-control" id="lpd_number" name="lpd_number" required>
                        </div>

                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" name="date"
                                value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="destination_project">Destination Project</label>
                            <select class="form-control select2bs4" id="destination_project" name="destination_project"
                                style="width: 100%;" required>
                                <option value="">Select Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->code }}">{{ $project->code }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="destination_department">Destination Department</label>
                            <select class="form-control select2bs4" id="destination_department"
                                name="destination_department" style="width: 100%;" required>
                                <option value="">Select Department</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="attention_person">Attention Person</label>
                            <input type="text" class="form-control" id="attention_person" name="attention_person"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitForm">Create LPD</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Select2 -->
    <link href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        .select2-container {
            z-index: 9999;
        }

        .modal-body .select2-container {
            width: 100% !important;
        }

        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(2.25rem + 2px) !important;
        }
    </style>
@endsection

@section('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#ready-documents-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: '{{ route('logistic.lpd.ready-to-send.data') }}',
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
                        data: 'document_type',
                        name: 'document_type'
                    },
                    {
                        data: 'document_number',
                        name: 'document_number'
                    },
                    {
                        data: 'document_date',
                        name: 'document_date'
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
                        data: 'days',
                        name: 'days',
                        className: 'text-right'
                    }
                ],
                order: [
                    [5, 'desc']
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

            // Initialize modal selects when modal is shown
            $('#deliveryInfoModal').on('shown.bs.modal', function() {
                // Initialize project select
                if (!$('#destination_project').data('select2')) {
                    $('#destination_project').select2({
                        theme: 'bootstrap4',
                        placeholder: 'Select Project',
                        dropdownParent: $('#deliveryInfoModal'),
                        width: '100%'
                    });
                }

                // Initialize department select
                if (!$('#destination_department').data('select2')) {
                    $('#destination_department').select2({
                        theme: 'bootstrap4',
                        placeholder: 'Select Department',
                        dropdownParent: $('#deliveryInfoModal'),
                        width: '100%'
                    });
                }
            });

            // Destroy Select2 when modal is hidden
            $('#deliveryInfoModal').on('hidden.bs.modal', function() {
                $('#destination_project, #destination_department').select2('destroy');
            });

            // Handle project change
            $('#destination_project').on('change', function() {
                const projectCode = $(this).val();
                const departmentSelect = $('#destination_department');

                // Clear departments
                departmentSelect.empty().append('<option value="">Select Department</option>');

                if (!projectCode) {
                    return;
                }

                // Show loading state
                departmentSelect.prop('disabled', true);

                // Fetch departments
                $.ajax({
                    url: "{{ route('api.projects.departments', ['projectCode' => ':projectCode']) }}"
                        .replace(':projectCode', projectCode),
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log('Departments received:', response);

                        if (Array.isArray(response)) {
                            // Destroy and reinitialize select2
                            if (departmentSelect.data('select2')) {
                                departmentSelect.select2('destroy');
                            }

                            // Add options
                            response.forEach(function(dept) {
                                departmentSelect.append(new Option(
                                    `${dept.department_name} - ${dept.akronim}`,
                                    dept.id,
                                    false,
                                    false
                                ));
                            });

                            // Reinitialize select2
                            departmentSelect.select2({
                                theme: 'bootstrap4',
                                placeholder: 'Select Department',
                                dropdownParent: $('#deliveryInfoModal'),
                                width: '100%'
                            });

                            // Trigger change event to update the display
                            departmentSelect.trigger('change.select2');
                        } else {
                            console.error('Invalid response format:', response);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Invalid response format from server',
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading departments:', xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load departments'
                        });
                    },
                    complete: function() {
                        departmentSelect.prop('disabled', false);
                    }
                });
            });

            // Handle modal open
            $('#openDeliveryModal').click(function() {
                // Reset form
                $('#modal-form')[0].reset();

                // Set default date
                $('#date').val('{{ date('Y-m-d') }}');

                // Show modal
                $('#deliveryInfoModal').modal('show');
            });

            // Handle form submission
            $('#submitForm').click(function() {
                // Validate form
                if (!$('#modal-form')[0].checkValidity()) {
                    $('#modal-form')[0].reportValidity();
                    return;
                }

                // Transfer values to hidden form
                const fields = ['lpd_number', 'date', 'destination_department', 'attention_person',
                    'notes'
                ];
                fields.forEach(field => {
                    const value = $(`#modal-form [name="${field}"]`).val();
                    $(`#create-lpd-form [name="${field}"]`).val(value);
                });

                // Close modal and submit form
                $('#deliveryInfoModal').modal('hide');
                $('#create-lpd-form').submit();
            });

            // Handle form submission with AJAX
            $('#create-lpd-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = response.redirect;
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred';
                        if (xhr.responseJSON) {
                            errorMessage = xhr.responseJSON.message || Object.values(xhr
                                .responseJSON.errors).flat().join('\n');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage
                        });
                    }
                });
            });

            // For edit page, trigger the change event on load to populate departments
            @if (isset($lpd))
                $(function() {
                    $('#destination_project').trigger('change');
                    // Set the selected department after departments are loaded
                    setTimeout(() => {
                        $('#destination_department').val('{{ $lpd->destination_department }}');
                    }, 500);
                });
            @endif

        });
    </script>
@endsection
