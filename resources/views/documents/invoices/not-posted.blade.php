@extends('layout.main')

@section('title_page')
    INVOICES
@endsection

@section('breadcrumb_title')
    <small>accounting / invoices / not posted</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-acc-invoice-links page='list' />

            <div class="card">
                <div class="card-body">
                    <table id="suppliers" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><small>Vendor</small></th>
                                <th><small>Invoice no</small></th>
                                <th><small>Invoice Date</small></th>
                                <th><small>PO No</small></th>
                                <th><small>Amount</small></th>
                                <th><small>Days</small></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- SAP Document Update Modal -->
    <div class="modal fade" id="updateSapDocModal" tabindex="-1" role="dialog" aria-labelledby="updateSapDocModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateSapDocModalLabel">Update SAP DocNum</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="sapDocForm">
                        @csrf
                        <input type="hidden" id="invoice_id" name="invoice_id">
                        <div class="form-group">
                            <label for="sap_doc">SAP DocNum</label>
                            <input type="text" class="form-control" id="sap_doc" name="sap_doc" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="updateSapDoc()">Save changes</button>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('adminlte/plugins/datatables/css/datatables.min.css') }}" />
    <style>
        .card-header .active {
            color: black;
            text-transform: uppercase;
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

    <script>
        function openUpdateModal(invoiceId) {
            $('#invoice_id').val(invoiceId);
            $('#updateSapDocModal').modal('show');
        }

        function updateSapDoc() {
            const invoiceId = $('#invoice_id').val();
            const sapDoc = $('#sap_doc').val();

            if (!sapDoc) {
                alert('Please enter SAP DocNum');
                return;
            }

            $.ajax({
                url: `/accounting/invoices/${invoiceId}/update-sap-doc`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    sap_doc: sapDoc
                },
                success: function(response) {
                    $('#updateSapDocModal').modal('hide');
                    window.location.reload(); // Just reload the page, Alert will show automatically
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to update SAP DocNum';
                    if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.sap_doc) {
                        errorMessage = xhr.responseJSON.errors.sap_doc[0];
                    }
                    alert(errorMessage);
                }
            });
        }

        // Initialize DataTable
        $(document).ready(function() {
            $('#suppliers').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('accounting.invoices.not-posted.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'vendor',
                        name: 'vendor'
                    },
                    {
                        data: 'invoice_number',
                        name: 'invoice_number'
                    },
                    {
                        data: 'invoice_date',
                        name: 'invoice_date'
                    },
                    {
                        data: 'po_no',
                        name: 'po_no'
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        className: 'text-right'
                    },
                    {
                        data: 'days',
                        name: 'Days',
                        className: 'text-right'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endsection
