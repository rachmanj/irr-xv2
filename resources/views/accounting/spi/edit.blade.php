@extends('layout.main')

@section('title_page')
    Edit SPI
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
                        Edit SPI {{ $spi->nomor }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('accounting.spi.show', $spi->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('accounting.spi.update', $spi->id) }}" method="POST" id="edit-spi-form">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="spi_number">SPI Number</label>
                                    <input type="text" class="form-control @error('spi_number') is-invalid @enderror"
                                        id="spi_number" name="spi_number" value="{{ old('spi_number', $spi->nomor) }}"
                                        required>
                                    @error('spi_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                                        id="date" name="date" value="{{ old('date', $spi->date) }}" required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $spi->notes) }}</textarea>
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
                                        value="{{ old('destination', $spi->destination) }}" required>
                                    @error('destination')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="attention_person">Attention Person</label>
                                    <input type="text"
                                        class="form-control @error('attention_person') is-invalid @enderror"
                                        id="attention_person" name="attention_person"
                                        value="{{ old('attention_person', $spi->attention_person) }}" required>
                                    @error('attention_person')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                </div>
            </div>

            <!-- Invoices Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list-alt mr-2"></i>
                        Select Invoices
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ready-invoices-table" class="table table-bordered table-striped">
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
                                    <th>Project</th>
                                    <th>Invoice Date</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <input type="hidden" name="invoices" id="selected-invoices" required>
                    @error('invoices')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Update SPI
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
            // Get current SPI's invoice IDs
            const currentInvoices = {!! json_encode($spi->documents->pluck('documentable_id')) !!};

            // Helper function to check if invoice was previously selected
            function isInvoiceSelected(invoiceId) {
                return currentInvoices.includes(invoiceId);
            }

            // Initialize DataTable
            const table = $('#ready-invoices-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('accounting.spi.ready-to-deliver.data') }}',
                    data: function(d) {
                        d.include_current = true;
                        d.current_spi_id = '{{ $spi->id }}';
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
                                    <input type="checkbox" class="custom-control-input invoice-checkbox" 
                                        id="invoice-${row.DT_RowIndex}" value="${row.id}"
                                        ${isInvoiceSelected(row.id) ? 'checked' : ''}>
                                    <label class="custom-control-label" for="invoice-${row.DT_RowIndex}"></label>
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
                        data: 'project_code',
                        name: 'project_code'
                    },
                    {
                        data: 'invoice_date',
                        name: 'invoice_date'
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        className: 'text-right'
                    }
                ],
                order: [
                    [1, 'asc']
                ],
                drawCallback: function() {
                    updateSelectAllCheckbox();
                }
            });

            // Initialize selected invoices with current ones
            $('#selected-invoices').val(JSON.stringify(currentInvoices));

            // Handle select all checkbox
            $('#select-all').change(function() {
                const isChecked = $(this).prop('checked');
                $('.invoice-checkbox:visible').prop('checked', isChecked);
                updateSelectedInvoices();
            });

            // Handle individual checkbox changes
            $(document).on('change', '.invoice-checkbox', function() {
                updateSelectAllCheckbox();
                updateSelectedInvoices();
            });

            // Update select all checkbox state
            function updateSelectAllCheckbox() {
                const totalVisible = $('.invoice-checkbox:visible').length;
                const totalChecked = $('.invoice-checkbox:visible:checked').length;
                $('#select-all').prop('checked', totalVisible === totalChecked && totalVisible > 0);
            }

            // Update hidden input with selected invoice IDs
            function updateSelectedInvoices() {
                const selectedIds = $('.invoice-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
                $('#selected-invoices').val(JSON.stringify(selectedIds));
            }

            // Form submission handling
            $('#edit-spi-form').submit(function(e) {
                const selectedInvoices = JSON.parse($('#selected-invoices').val() || '[]');
                if (selectedInvoices.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one invoice.');
                    return false;
                }
                return true;
            });
        });
    </script>
@endsection
