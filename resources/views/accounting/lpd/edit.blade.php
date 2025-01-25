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
            <!-- Basic Info Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice mr-2"></i>
                        Edit LPD {{ $lpd->nomor }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('accounting.lpd.show', $lpd->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('accounting.lpd.update', $lpd->id) }}" method="POST" id="edit-lpd-form">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lpd_number">LPD Number</label>
                                    <input type="text" class="form-control @error('lpd_number') is-invalid @enderror"
                                        id="lpd_number" name="lpd_number" value="{{ old('lpd_number', $lpd->nomor) }}"
                                        required>
                                    @error('lpd_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                                        id="date" name="date" value="{{ old('date', $lpd->date) }}" required>
                                    @error('date')
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

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="destination">Destination</label>
                                    <input type="text" class="form-control @error('destination') is-invalid @enderror"
                                        id="destination" name="destination"
                                        value="{{ old('destination', $lpd->destination) }}" required>
                                    @error('destination')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="attention_person">Attention Person</label>
                                    <input type="text"
                                        class="form-control @error('attention_person') is-invalid @enderror"
                                        id="attention_person" name="attention_person"
                                        value="{{ old('attention_person', $lpd->attention_person) }}" required>
                                    @error('attention_person')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                </div>
            </div>

            <!-- Documents Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list-alt mr-2"></i>
                        Select Documents
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
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Update LPD
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Get current LPD's document IDs
            const currentDocuments = {!! json_encode($lpd->documents->pluck('documentable_id')) !!};

            // Helper function to check if document was previously selected
            function isDocumentSelected(documentId) {
                return currentDocuments.includes(documentId);
            }

            // Initialize DataTable
            const table = $('#ready-documents-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('accounting.lpd.ready-to-deliver.data') }}',
                    data: function(d) {
                        d.include_current = true;
                        d.current_lpd_id = '{{ $lpd->id }}';
                    }
                },
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input document-checkbox" 
                                        id="document-${row.DT_RowIndex}" value="${row.id}"
                                        ${isDocumentSelected(row.id) ? 'checked' : ''}>
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

            // Initialize selected documents with current ones
            $('#selected-documents').val(JSON.stringify(currentDocuments));

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
            }

            // Form submission handling
            $('#edit-lpd-form').submit(function(e) {
                const selectedDocuments = JSON.parse($('#selected-documents').val() || '[]');
                if (selectedDocuments.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one document.');
                    return false;
                }
                return true;
            });
        });
    </script>
@endsection
