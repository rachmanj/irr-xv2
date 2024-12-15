@extends('layout.main')

@section('title_page')
    UPLOAD
@endsection

@section('breadcrumb_title')
    <small>master / upload / ito</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-master-upload-links page='ito' />



            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal"
                        data-target="#uploadModal">
                        Upload File
                    </button>
                </div>
                <div class="card-body">
                    <table id="itos-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ITO No</th>
                                <th>PO No</th>
                                <th>Upload at</th>
                                {{-- <th></th> --}}
                            </tr>
                        </thead>
                    </table>
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
            $('#itos-table').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                fixedHeader: true,
                ajax: '{{ route('master.upload.ito.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'document_number',
                        name: 'ITO No'
                    },
                    {
                        data: 'po_no',
                        name: 'PO No'
                    },
                    {
                        data: 'upload_at',
                        name: 'Upload at'
                    }
                    // {
                    //     data: 'action',
                    //     name: 'action',
                    //     orderable: false,
                    //     searchable: false
                    // }
                ]
            });
        });
    </script>
@endsection
