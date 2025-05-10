@extends('layout.main')

@section('title_page')
    INVOICES
@endsection

@section('breadcrumb_title')
    <small>documents / invoices / search</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-invoice-links page='search' />

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Search Invoices</h3>
                </div>
                <div class="card-body">
                    <form id="search-form" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Invoice Number</label>
                                    <input type="text" class="form-control" id="invoice_number" name="invoice_number">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Supplier</label>
                                    <select class="form-control select2bs4" id="supplier_id" name="supplier_id">
                                        <option value="">Select Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>PO Number</label>
                                    <input type="text" class="form-control" id="po_no" name="po_no">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Invoice Type</label>
                                    <select class="form-control select2bs4" id="type_id" name="type_id">
                                        <option value="">Select Type</option>
                                        @foreach ($invoiceTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Invoice Project</label>
                                    <select class="form-control select2bs4" id="invoice_project" name="invoice_project">
                                        <option value="">Select Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->code }}">{{ $project->code }} - {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-sm btn-primary">Search</button>
                                <button type="reset" class="btn btn-sm btn-secondary">Reset</button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered table-striped" id="search-results-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice Number</th>
                                <th>Supplier</th>
                                <th>PO Number</th>
                                <th>Invoice Type</th>
                                <th>Project</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Results will be inserted here -->
                        </tbody>
                    </table>
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
    {{-- select2bs4 --}}
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.css') }}">
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
    {{-- select2bs4 --}}
    <script src="{{ asset('adminlte/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(function() {
            // Check for success message
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 1500
                });
            @endif

            // Initialize select2
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            // Initialize DataTable
            var table = $("#search-results-table").DataTable({
                processing: true,
                serverSide: true,
                deferLoading: false, // Prevents initial ajax request
                ajax: {
                    url: '{{ route('documents.invoices.search') }}',
                    data: function(d) {
                        d.invoice_number = $('#invoice_number').val();
                        d.po_no = $('#po_no').val();
                        d.supplier_id = $('#supplier_id').val();
                        d.type_id = $('#type_id').val();
                        d.invoice_project = $('#invoice_project').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'invoice_number',
                        name: 'invoice_number'
                    },
                    {
                        data: 'supplier_name',
                        name: 'supplier.name'
                    },
                    {
                        data: 'po_no',
                        name: 'po_no'
                    },
                    {
                        data: 'invoice_type_name',
                        name: 'invoiceType.type_name'
                    },
                    {
                        data: 'invoice_project',
                        name: 'invoice_project'
                    },
                    {
                        data: 'formatted_amount',
                        name: 'amount',
                        className: 'text-right'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'asc']
                ]
            });

            // Show initial message
            $('#search-results-table tbody').html(
                '<tr><td colspan="8" class="text-center">Please click search to view data</td></tr>'
            );

            // Handle search form submission
            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                table.draw();
            });

            // Handle reset button
            $('#search-form button[type="reset"]').click(function() {
                $(this).closest('form').find("input[type=text], select").val("");
                // Reset select2 elements
                $('#supplier_id, #type_id, #invoice_project').val(null).trigger('change');
                // Clear table and show initial message
                $('#search-results-table tbody').html(
                    '<tr><td colspan="8" class="text-center">Please click search to view data</td></tr>'
                );
            });
        });
    </script>
@endsection
