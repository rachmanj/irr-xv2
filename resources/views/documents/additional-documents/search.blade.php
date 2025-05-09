@extends('layout.main')

@section('title_page')
    Additional Documents Search
@endsection

@section('breadcrumb_title')
    Additional Documents Search
@endsection

@section('content')
    <div class="row">
        <div class="col-12">

            <x-additional-document-links page="search" /> <!-- Updated component for additional docs links -->

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Search Additional Documents</h3>
                </div>

                <div class="card-body">
                    <form id="search-form" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="document_number">Document Number</label>
                                    <input type="text" class="form-control form-control-sm" id="document_number"
                                        name="document_number">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type_id">Document Type</label>
                                    <select class="form-control form-control-sm select2bs4" id="type_id" name="type_id">
                                        <option value="">-- Select Document Type --</option>
                                        @foreach ($documentTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="po_no">PO Number</label>
                                    <input type="text" class="form-control form-control-sm" id="po_no"
                                        name="po_no">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="invoice_number">Invoice Number</label>
                                    <input type="text" class="form-control form-control-sm" id="invoice_number"
                                        name="invoice_number">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="receive_date">Receive Date</label>
                                    <input type="date" class="form-control form-control-sm" id="receive_date"
                                        name="receive_date">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cur_loc">Current Location</label>
                                    <select class="form-control form-control-sm select2bs4" id="cur_loc" name="cur_loc">
                                        <option value="">-- Select Location --</option>
                                        @foreach ($locationCodes as $locationCode)
                                            <option value="{{ $locationCode->location_code }}">
                                                {{ $locationCode->project ?? '-' }}
                                                ({{ $locationCode->department_name ?? 'Unknown Dept' }})
                                                -
                                                {{ $locationCode->location_code }}
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

                    <table class="table table-bordered table-striped table-sm" id="search-results">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Document Number</th>
                                <th>Document Type</th>
                                <th>PO Number</th>
                                <th>Invoice Number</th>
                                <th>Receive Date</th>
                                <th>Current Location</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
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

        .table-sm td,
        .table-sm th {
            padding: 0.3rem;
        }

        .table-sm {
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 0.5rem;
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
        $(document).ready(function() {
            // Initialize select2
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            // Initialize DataTable
            var table = $("#search-results").DataTable({
                processing: true,
                serverSide: true,
                deferLoading: false,
                pageLength: 25,
                ajax: {
                    url: '{{ route('documents.additional-documents.search') }}',
                    type: 'GET',
                    data: function(d) {
                        d.document_number = $('#document_number').val();
                        d.type_id = $('#type_id').val();
                        d.po_no = $('#po_no').val();
                        d.invoice_number = $('#invoice_number').val();
                        d.receive_date = $('#receive_date').val();
                        d.cur_loc = $('#cur_loc').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'document_number',
                        name: 'document_number'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'po_no',
                        name: 'po_no'
                    },
                    {
                        data: 'invoice_number',
                        name: 'invoice_number'
                    },
                    {
                        data: 'receive_date',
                        name: 'receive_date'
                    },
                    {
                        data: 'cur_loc',
                        name: 'cur_loc'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Show initial message
            $('#search-results tbody').html(
                '<tr><td colspan="9" class="text-center">Please click search to view data</td></tr>'
            );

            // Handle search form submission
            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                table.draw();
            });

            // Handle reset button
            $('#search-form button[type="reset"]').click(function() {
                $(this).closest('form').find("input[type=text], select").val("");
                $('#type_id, #cur_loc').val(null).trigger('change');
                // clear table and show initial message
                $('#search-results').html(
                    '<tr><td colspan="9" class="text-center">Please click search to view data</td></tr>'
                );
            });

            // Success message
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 1500
                });
            @endif
        });
    </script>
@endsection
