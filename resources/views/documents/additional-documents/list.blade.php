@extends('layout.main')

@section('title_page')
    ADDITIONAL DOCUMENTS
@endsection

@section('breadcrumb_title')
    <small>documents / addocs / list</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-additional-document-links page='list' />

            <div class="card">
                <div class="card-header">
                    <h6>Additional Docs belum diterima</h6>
                </div>
                <div class="card-body">
                    <table id="addoc-table" class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Doc No</th>
                                <th>Type</th>
                                <th>Doc Date</th>
                                <th>PO No</th>
                                <th>Inv No</th>
                                <th>Days</th>
                                <th>Current Location</th>
                                <th></th>
                            </tr>
                        </thead>
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
        $(document).ready(function() {
            $('#addoc-table').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                fixedHeader: true,
                pageLength: 25,
                ajax: '{{ route('documents.additional-documents.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'document_number',
                    },
                    {
                        data: 'document_type',
                    },
                    {
                        data: 'document_date',
                    },
                    {
                        data: 'po_no',
                    },
                    {
                        data: 'invoice_number',
                    },
                    {
                        data: 'days',
                        className: 'text-right'
                    },
                    {
                        data: 'cur_loc',
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endsection
