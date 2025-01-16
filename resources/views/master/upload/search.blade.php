@extends('layout.main')

@section('title_page')
    ITO
@endsection

@section('breadcrumb_title')
    <small>master / upload / search ito</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-master-upload-links page='search' />

            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal"
                        data-target="#uploadModal">
                        Upload File
                    </button>
                </div>
                <div class="card-body">
                    <form id="search-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="document_number">ITO Number</label>
                                    <input type="text" class="form-control" id="document_number" name="document_number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="po_no">PO Number</label>
                                    <input type="text" class="form-control" id="po_no" name="po_no">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="destination_wh">Destination WH</label>
                                    <input type="text" class="form-control" id="destination_wh" name="destination_wh">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="document_date">Document Date</label>
                                    <input type="date" class="form-control" id="document_date" name="document_date">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="remarks">Remarks</label>
                                    <input type="text" class="form-control" id="remarks" name="remarks">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-sm btn-primary" id="btn-search">Search</button>
                                <button type="button" class="btn btn-sm btn-secondary" id="btn-reset">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <table id="search-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ITO No</th>
                                <th>PO No</th>
                                <th>Destination WH</th>
                                <th>Document Date</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('master.upload.ito.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="attachment">Choose file</label>
                            <input type="file" class="form-control" id="attachment" name="attachment" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
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
        $(document).ready(function() {
            let table = $('#search-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('master.upload.ito.search.data') }}',
                    data: function(d) {
                        d.document_number = $('#document_number').val();
                        d.po_no = $('#po_no').val();
                        d.destination_wh = $('#destination_wh').val();
                        d.document_date = $('#document_date').val();
                        d.remarks = $('#remarks').val();
                        d.search_clicked = window.searchClicked ? 1 : 0;
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
                        data: 'po_no',
                        name: 'po_no'
                    },
                    {
                        data: 'destination_wh',
                        name: 'destination_wh'
                    },
                    {
                        data: 'document_date_formatted',
                        name: 'document_date'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    }
                ],
                order: [
                    [1, 'desc']
                ],
                drawCallback: function() {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            window.searchClicked = false;

            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                window.searchClicked = true;
                table.draw();
            });

            $('#btn-reset').on('click', function() {
                $('#search-form')[0].reset();
                window.searchClicked = false;
                table.draw();
            });
        });
    </script>
@endsection
