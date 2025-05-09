@extends('layout.main')

@section('title_page')
    Edit LPD
@endsection

@section('breadcrumb_title')
    <small>accounting / deliveries / edit</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-log-lpd-links page='list' />

            <form action="{{ route('logistic.lpd.update', $lpd->id) }}" method="POST" id="edit-lpd-form">
                @csrf
                @method('PUT')

                <!-- Add hidden inputs -->
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
                        <button type="button" class="btn btn-primary" id="openDeliveryModal">
                            <i class="fas fa-save mr-1"></i> Update LPD
                        </button>

                        <a href="{{ route('logistic.lpd.index', ['page' => 'list']) }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
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
                        <i class="fas fa-edit mr-2"></i>
                        Edit LPD Info
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="lpd_number">LPD Number</label>
                        <input type="text" class="form-control @error('lpd_number') is-invalid @enderror" id="lpd_number"
                            name="lpd_number" value="{{ old('lpd_number', $lpd->nomor) }}" required>
                        @error('lpd_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" id="date"
                            name="date"
                            value="{{ old('date', $lpd->date instanceof \Carbon\Carbon ? $lpd->date->format('Y-m-d') : $lpd->date) }}"
                            required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="destination_project">Destination Project</label>
                        <select class="form-control @error('destination_project') is-invalid @enderror"
                            id="destination_project" name="destination_project" required>
                            <option value="">Select Project</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->code }}"
                                    {{ old('destination_project', $lpd->destination_project) == $project->code ? 'selected' : '' }}>
                                    {{ $project->code }} - {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('destination_project')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="destination_department">Destination Department</label>
                        <select class="form-control @error('destination_department') is-invalid @enderror"
                            id="destination_department" name="destination_department" required>
                            <option value="">Select Department</option>
                        </select>
                        @error('destination_department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="attention_person">Attention Person</label>
                        <input type="text" class="form-control @error('attention_person') is-invalid @enderror"
                            id="attention_person" name="attention_person"
                            value="{{ old('attention_person', $lpd->attention_person) }}" required>
                        @error('attention_person')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $lpd->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary" id="submitForm">
                        <i class="fas fa-save mr-1"></i> Save Changes
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
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endsection

@section('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

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
                            const isSelected = @json($lpd->documents->pluck('id')->toArray())
                                .includes(row.id);
                            return `
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input document-checkbox" 
                                        id="document-${row.DT_RowIndex}" value="${row.id}" ${isSelected ? 'checked' : ''}>
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

            // Handle opening modal
            $('#openDeliveryModal').click(function() {
                $('#deliveryInfoModal').modal('show');
            });

            // Handle form submission in modal
            $('#submitForm').click(function(e) {
                e.preventDefault();

                // Validate required fields
                let isValid = true;
                $('#deliveryInfoModal select[required], #deliveryInfoModal input[required]').each(
                    function() {
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
                const fields = ['lpd_number', 'date', 'destination_department', 'attention_person',
                    'notes'
                ];
                fields.forEach(field => {
                    const value = $(`#deliveryInfoModal [name="${field}"]`).val();
                    $(`#edit-lpd-form input[name="${field}"]`).val(value);
                });

                // Close modal
                $('#deliveryInfoModal').modal('hide');

                // Submit the form
                $('#edit-lpd-form').submit();
            });

            // Clear validation on input
            $('#deliveryInfoModal input').on('input', function() {
                $(this).removeClass('is-invalid');
            });

            // Handle form submission with AJAX
            $('#edit-lpd-form').submit(function(e) {
                e.preventDefault();
                const selectedDocuments = JSON.parse($('#selected-documents').val() || '[]');

                if (selectedDocuments.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please select at least one document.',
                    });
                    return false;
                }

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'LPD updated successfully',
                            showConfirmButton: true,
                            timer: 1500
                        }).then(() => {
                            window.location.href =
                                "{{ route('logistic.lpd.index', ['page' => 'list']) }}";
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while updating LPD';

                        if (xhr.status === 422) {
                            // Validation errors
                            errorMessage = xhr.responseJSON?.errors ?
                                Object.values(xhr.responseJSON.errors).flat().join('\n') :
                                xhr.responseJSON?.message || errorMessage;
                        } else {
                            // Other errors
                            errorMessage = xhr.responseJSON?.message || errorMessage;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            showConfirmButton: true
                        });
                    }
                });
            });

            // Pre-select documents
            updateSelectedDocuments();

            // Initialize Select2
            $('#destination_project').select2({
                theme: 'bootstrap4',
                placeholder: 'Select Project',
                allowClear: true
            });

            $('#destination_department').select2({
                theme: 'bootstrap4',
                placeholder: 'Select Department',
                allowClear: true
            });

            // Handle project change
            $('#destination_project').change(function() {
                const projectCode = $(this).val();
                const departmentSelect = $('#destination_department');

                departmentSelect.empty().append('<option value="">Select Department</option>');

                if (projectCode) {
                    console.log('Fetching departments for project:', projectCode);

                    $.ajax({
                        url: "{{ route('api.projects.departments', ['projectCode' => ':projectCode']) }}"
                            .replace(':projectCode', projectCode),
                        type: 'GET',
                        dataType: 'json',
                        beforeSend: function() {
                            departmentSelect.prop('disabled', true);
                        },
                        success: function(response) {
                            console.log('Departments response:', response);

                            if (Array.isArray(response)) {
                                response.forEach(function(dept) {
                                    departmentSelect.append(
                                        `<option value="${dept.id}">${dept.department_name} - ${dept.akronim}</option>`
                                    );
                                });

                                // If editing, set the previously selected department
                                @if (isset($lpd))
                                    $('#destination_department').val(
                                    '{{ $lpd->destination }}');
                                @endif
                            } else {
                                console.error('Invalid response format:', response);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Invalid response format from server',
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading departments:', {
                                xhr,
                                status,
                                error
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.error ||
                                    'Failed to load departments',
                            });
                        },
                        complete: function() {
                            departmentSelect.prop('disabled', false);
                        }
                    });
                }
            });

            // For edit page, trigger the change event on load to populate departments
            @if (isset($lpd))
                $(function() {
                    $('#destination_project').trigger('change');
                });
            @endif
        });
    </script>
@endsection
