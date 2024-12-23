@extends('layout.main')

@section('title_page')
    INVOICES
@endsection

@section('breadcrumb_title')
    accounting / invoices / search
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-acc-invoice-links page='search' />

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <form id="search-form">
                            <form action="{{ route('accounting.invoices.search') }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="search" name="key_word" id="search-input"
                                        class="form-control form-control-lg" placeholder="Type your keywords here">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-lg btn-default">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                            </form>
                    </div>
                    </form>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-8 offset-md-2">
                    <div id="search-results" class="list-group">
                        <!-- Search results will be inserted here -->
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
@endsection

@section('styles')
    <style>
        .card-header .active {
            color: black;
            text-transform: uppercase;
        }
    </style>
@endsection

@section('scripts')
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                var query = $('#search-input').val(); // Define the query variable

                $.ajax({
                    url: '{{ route('accounting.invoices.search') }}',
                    method: 'GET',
                    data: {
                        query: query
                    },
                    success: function(response) {
                        $('#search-results').empty();
                        if (response.length > 0) {
                            var table =
                                '<table class="table table-bordered"><thead><tr><th>Invoice Number</th><th>PO Number</th><th>Actions</th></tr></thead><tbody>';
                            $.each(response, function(index, invoice) {
                                table += '<tr><td>' + invoice.invoice_number +
                                    '</td><td>' + invoice.po_no +
                                    '</td><td><a href="{{ route('accounting.invoices.show', '') }}/' +
                                    invoice.id +
                                    '" class="btn btn-primary btn-xs">View</a> ' +
                                    '<a href="{{ route('accounting.invoices.edit', '') }}/' +
                                    invoice.id +
                                    '" class="btn btn-secondary btn-xs">Edit</a></td></tr>';
                            });
                            table += '</tbody></table>';
                            $('#search-results').append(table);
                        } else {
                            $('#search-results').append(
                                '<div class="list-group-item">No invoices found</div>');
                        }
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            });
        });
    </script>
@endsection
