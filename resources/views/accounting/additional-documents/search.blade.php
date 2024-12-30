@extends('layout.main')

@section('title_page')
    ADDITIONAL DOCUMENTS
@endsection

@section('breadcrumb_title')
    <small>accounting / addocs / search</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-acc-addoc-links page='search' />
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Search Additional Documents</h3>
                </div>
                <div class="card-body">
                    <form id="searchForm">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Document Number</label>
                                    <input type="text" class="form-control" id="document_number" name="document_number">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Document Type</label>
                                    <select class="form-control select2bs4" id="type_id" name="type_id">
                                        <option value="">Select Type</option>
                                        @foreach ($documentTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->type_name }}</option>
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
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Invoice Number</label>
                                    <input type="text" class="form-control" id="invoice_number" name="invoice_number">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Receive Date</label>
                                    <input type="date" class="form-control" id="receive_date" name="receive_date">
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-sm btn-primary">Search</button>
                                <button type="reset" class="btn btn-sm btn-secondary ml-2">Reset</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered table-striped" id="resultsTable">
                            <thead>
                                <tr>
                                    <th>Document Number</th>
                                    <th>Document Type</th>
                                    <th>PO Number</th>
                                    <th>Invoice Number</th>
                                    <th>Receive Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
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
    {{-- select2bs4 --}}
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
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
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            let table = $('#resultsTable').DataTable({
                processing: true,
                searching: false,
                language: {
                    emptyTable: "No documents found"
                }
            });

            $('#searchForm').on('submit', function(e) {
                e.preventDefault();

                let formData = {
                    document_number: $('#document_number').val(),
                    type_id: $('#type_id').val(),
                    po_no: $('#po_no').val(),
                    invoice_number: $('#invoice_number').val(),
                    receive_date: $('#receive_date').val()
                };

                $.ajax({
                    url: '{{ route('additional-documents.search') }}',
                    type: 'GET',
                    data: formData,
                    success: function(response) {
                        table.clear();

                        response.forEach(function(doc) {
                            table.row.add([
                                doc.document_number,
                                doc.document_type.type_name,
                                doc.po_no || 'N/A',
                                doc.invoice ? doc.invoice.invoice_number :
                                'N/A',
                                doc.receive_date || 'Not received',
                                `<div class="btn-group">
                                    <a href="/additional-documents/${doc.id}/edit" class="btn btn-xs btn-warning mr-2">Edit</a>
                                    <a href="/additional-documents/${doc.id}" class="btn btn-xs btn-info">View</a>
                                </div>`
                            ]).draw();
                        });
                    },
                    error: function(xhr) {
                        console.error('Search failed:', xhr);
                        alert('Error performing search. Please try again.');
                    }
                });
            });

            $('#type_id').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
@endsection
