@extends('layout.main')

@section('title_page')
    Distribution
@endsection

@section('breadcrumb_title')
    <small>accounting / spi / create</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">

            <x-acc-spi-links page='create' />

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Select Invoices to Deliver</h5>
                </div>
                <div class="card-body">
                    <table id="invoices-table" class="table table-sm table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th>Inv No</th>
                                <th>Inv Date</th>
                                <th>Rcv Date</th>
                                <th>Vendor</th>
                                <th>Project</th>
                                <th>Days</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-sm btn-primary" id="create-spi-btn" disabled>
                        Create SPI
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Create SPI Modal -->
    <div class="modal fade" id="createSpiModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New SPI</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="spi-form" action="{{ route('accounting.spi.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Hidden input for selected invoices -->
                        <input type="hidden" name="invoices" id="selected-invoices">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>SPI Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="spi_number" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Destination Project <span class="text-danger">*</span></label>
                                    <select class="form-control select2bs4" name="destination" required>
                                        <option value="">Select Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->code }}">
                                                {{ $project->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Attention Person <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="attention_person" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea class="form-control" name="notes" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-primary">Create SPI</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('adminlte/plugins/datatables/css/datatables.min.css') }}" />
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .card-header .active {
            color: black;
            text-transform: uppercase;
        }

        .document-details {
            background-color: #f8f9fa;
        }

        tr.document-details {
            display: none;
            /* Initial state */
        }

        tr.has-details {
            cursor: pointer;
        }

        tr.has-details:hover {
            background-color: #f5f5f5;
        }

        tr.details-shown {
            background-color: #f0f0f0;
        }

        /* Ensure the details row takes full width */
        tr.document-details td {
            padding: 1rem;
        }

        /* Add a subtle border to separate the details */
        tr.document-details {
            border-top: 2px solid #dee2e6;
        }
    </style>
@endsection

@section('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables/datatables.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // initialize select2
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            const table = $('#invoices-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('accounting.spi.ready-to-deliver.data') }}',
                columns: [{
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="invoice-checkbox" value="${row.id}">`;
                        }
                    },
                    {
                        data: 'invoice_number'
                    },
                    {
                        data: 'invoice_date'
                    },
                    {
                        data: 'receive_date'
                    },
                    {
                        data: 'supplier_name'
                    },
                    {
                        data: 'invoice_project',
                        className: 'text-center'
                    },
                    {
                        data: 'days',
                        className: 'text-right'
                    }
                ],
                order: [
                    [1, 'asc']
                ],
                drawCallback: function(settings) {
                    // After table is drawn, reapply the detail rows
                    const api = this.api();
                    const rows = api.rows().nodes();

                    $(rows).each(function() {
                        const $row = $(this);
                        const data = api.row($row).data();

                        if (data && data.additional_documents && Array.isArray(data
                                .additional_documents) &&
                            data.additional_documents.length > 0) {

                            // Remove any existing details row
                            $row.next('.document-details').remove();

                            let documentsHtml = `
                                <tr class="document-details" data-parent="${data.id}">
                                    <td colspan="7">
                                        <div class="pl-5 py-1 mt-0 bg-light">
                                            <h6 class="mt-1"><strong><small>Additional Documents:</small></strong></h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-borderless mb-0">
                                                    <thead class="text-muted">
                                                        <tr>
                                                            <th class="py-0"><small>Type</small></th>
                                                            <th class="py-0"><small>Number</small></th>
                                                            <th class="py-0"><small>Document Date</small></th>
                                                            <th class="py-0"><small>Receive Date</small></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        ${data.additional_documents.map(doc => `
                                                                                                                <tr>
                                                                                                                    <td class="py-0"><span class="badge badge-info"><small>${doc.type || ''}</small></span></td>
                                                                                                                    <td class="py-0"><small>${doc.number || ''}</small></td>
                                                                                                                    <td class="py-0"><small>${doc.document_date || ''}</small></td>
                                                                                                                    <td class="py-0"><small>${doc.receive_date || ''}</small></td>
                                                                                                                </tr>
                                                                                                            `).join('')}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>`;

                            $row.after(documentsHtml);
                            $row.addClass('has-details');

                            // If this row was previously expanded, show the details
                            if ($row.hasClass('details-shown')) {
                                $row.next('.document-details').show();
                            } else {
                                $row.next('.document-details').hide();
                            }
                        }
                    });
                }
            });

            // Update the click handler
            $('#invoices-table tbody').on('click', 'tr', function(e) {
                const $clickedRow = $(this);

                // Don't trigger for document detail rows or checkbox clicks
                if ($clickedRow.hasClass('document-details') || $(e.target).is(':checkbox')) {
                    return;
                }

                const $detailsRow = $clickedRow.next('.document-details');

                if ($detailsRow.length) {
                    $detailsRow.toggle();
                    $clickedRow.toggleClass('details-shown');
                }
            });

            // Handle select all checkbox
            $('#select-all').on('change', function() {
                $('.invoice-checkbox').prop('checked', $(this).prop('checked'));
                updateCreateButton();
            });

            // Handle individual checkbox changes
            $('#invoices-table').on('change', '.invoice-checkbox', function() {
                updateCreateButton();
            });

            // Update create button state
            function updateCreateButton() {
                const checkedCount = $('.invoice-checkbox:checked').length;
                $('#create-spi-btn').prop('disabled', checkedCount === 0);
            }

            // Handle create delivery button click
            $('#create-spi-btn').on('click', function() {
                const selectedInvoices = $('.invoice-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                $('#selected-invoices').val(JSON.stringify(selectedInvoices));
                $('#createSpiModal').modal('show');
            });

        });
    </script>
@endsection
