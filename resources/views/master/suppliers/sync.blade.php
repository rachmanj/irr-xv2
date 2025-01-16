@extends('layout.main')

@section('title_page')
    SUPPLIERS
@endsection

@section('breadcrumb_title')
    <small>master / suppliers / sync</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-master-supplier-links page='sync' />

            <div class="card">
                <div class="card-header">
                    <button id="check-target" class="btn btn-primary btn-xs">Check Target</button>
                    <button id="import-data" class="btn btn-success btn-xs">Import Data</button>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Customers</dt>
                        <dd class="col-sm-8"></dd>
                        <dt class="col-sm-3">Vendors</dt>
                        <dd class="col-sm-8"></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <style>
        .card-header .active {
            color: black;
            text-transform: uppercase;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#check-target').on('click', function() {
                $.ajax({
                    // url: "http://payreq-one.local/api/customers",
                    url: "http://192.168.32.17/payreq-x-v3/api/customers",
                    type: 'GET',
                    success: function(response) {
                        $('dd:eq(0)').text(response.customer_count);
                        $('dd:eq(1)').text(response.vendor_count);
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            });

            $('#import-data').on('click', function() {
                $.ajax({
                    // url: "http://payreq-one.local/api/customers",
                    url: "http://192.168.32.17/payreq-x-v3/api/customers",
                    type: 'GET',
                    success: function(response) {
                        $.ajax({
                            url: "{{ route('master.suppliers.import') }}",
                            type: 'POST',
                            data: {
                                customers: response.customers,
                                _token: '{{ csrf_token() }}' // Include CSRF token
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: response.message,
                                    });
                                    window.location.href =
                                        "{{ route('master.suppliers.index') }}?page=list";
                                }
                            },
                            error: function(response) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.responseJSON.message,
                                });
                                console.log(response);
                            }
                        });
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            });
        });
    </script>
@endsection
