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
                        <dd class="col-sm-8" id="customer-count">-</dd>
                        <dt class="col-sm-3">Vendors</dt>
                        <dd class="col-sm-8" id="vendor-count">-</dd>
                        <dt class="col-sm-3">Total Data</dt>
                        <dd class="col-sm-8" id="total-data">-</dd>
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
            const apiUrl = "{{ config('services.payreq.api_url') }}/suppliers";

            $('#check-target').on('click', function() {
                $.ajax({
                    url: apiUrl,
                    type: 'GET',
                    success: function(response) {
                        $('#customer-count').text(response.customer_count || 0);
                        $('#vendor-count').text(response.vendor_count || 0);
                        $('#total-data').text((response.customers ? response.customers.length :
                            0));
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr, status, error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Unable to connect to the API server',
                        });
                    }
                });
            });

            $('#import-data').on('click', function() {
                Swal.fire({
                    title: 'Importing Data',
                    text: 'Please wait while we fetch and import data...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: apiUrl,
                    type: 'GET',
                    success: function(response) {
                        console.log('API Response:', response);

                        if (!response.customers || response.customers.length === 0) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'No Data',
                                text: 'No suppliers data found to import',
                            });
                            return;
                        }

                        // Prepare data for import - we need to use JSON stringify to handle large datasets
                        const postData = {
                            customers: response.customers.slice(0,
                                50), // Limit to 50 for first test
                            debug: true
                        };

                        console.log('Import data (size):', postData.customers.length);

                        $.ajax({
                            url: "{{ route('master.suppliers.import') }}",
                            type: 'POST',
                            data: JSON.stringify(postData),
                            contentType: 'application/json',
                            processData: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content')
                            },
                            success: function(response) {
                                console.log('Import Response:', response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: response.message,
                                    }).then(() => {
                                        window.location.href =
                                            "{{ route('master.suppliers.index') }}?page=list";
                                    });
                                } else {
                                    // Handle non-success response
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Warning',
                                        text: response.message ||
                                            'Unknown error occurred',
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                // Improved error handling to display raw HTML response
                                let errorMessage = 'Failed to import data';
                                console.error('Import Error:', xhr, status, error);

                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                } else if (xhr.status === 419) {
                                    errorMessage =
                                        'CSRF token mismatch. Please refresh the page and try again.';
                                } else if (xhr.responseText) {
                                    // For HTML responses, show the first 100 characters
                                    errorMessage = 'Server error: ' + xhr
                                        .responseText.substring(0, 100) + '...';
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error: ' + xhr.status,
                                    html: errorMessage,
                                });
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('API Error:', xhr, status, error);
                        Swal.fire({
                            icon: 'error',
                            title: 'API Connection Error',
                            text: 'Unable to connect to the API server: ' + (xhr
                                .status ? xhr.status : 'unknown error'),
                        });
                    }
                });
            });
        });
    </script>
@endsection
