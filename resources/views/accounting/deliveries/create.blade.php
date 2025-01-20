@extends('layout.main')

@section('title_page')
    Distribution
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-acc-delivery-links page='create' />

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
                                <th>Supplier</th>
                                <th>Invoice Number</th>
                                <th>Receive Date</th>
                                <th>Project</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>

                    <div class="mt-3">
                        <button type="button" class="btn btn-primary" id="create-delivery-btn" disabled>
                            Create Delivery
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Delivery Modal -->
    <div class="modal fade" id="createDeliveryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Delivery</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="delivery-form" action="{{ route('accounting.deliveries.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Hidden input for selected invoices -->
                        <input type="hidden" name="invoices" id="selected-invoices">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Delivery Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="delivery_number" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="date_sent" value="{{ date('Y-m-d') }}"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Destination Project <span class="text-danger">*</span></label>
                                    <select class="form-control select2bs4" name="destination_project" required>
                                        <option value="">Select Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->code }}">
                                                {{ $project->code }} - {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Delivery Type <span class="text-danger">*</span></label>
                                    <select class="form-control" name="delivery_type" required>
                                        <option value="full">Full Delivery</option>
                                        <option value="documents_only">Documents Only</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Attention Person <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="attention_person" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea class="form-control" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Delivery</button>
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
@endsection

@section('scripts')
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $(function() {
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            const table = $('#invoices-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('accounting.deliveries.ready-to-deliver.data') }}',
                columns: [{
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="invoice-checkbox" value="${row.id}">`;
                        }
                    },
                    {
                        data: 'supplier_name'
                    },
                    {
                        data: 'invoice_number'
                    },
                    {
                        data: 'receive_date'
                    },
                    {
                        data: 'invoice_project'
                    },
                    {
                        data: 'amount',
                    },
                    {
                        data: 'status'
                    }
                ]
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
                $('#create-delivery-btn').prop('disabled', checkedCount === 0);
            }

            // Handle create delivery button click
            $('#create-delivery-btn').on('click', function() {
                const selectedInvoices = $('.invoice-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                $('#selected-invoices').val(JSON.stringify(selectedInvoices));
                $('#createDeliveryModal').modal('show');
            });
        });
    </script>
@endsection
