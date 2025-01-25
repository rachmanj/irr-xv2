@extends('layout.main')

@section('title_page')
    SPI Details
@endsection

@section('breadcrumb_title')
    <small>accounting / deliveries / show</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Delivery Info Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice mr-2"></i>
                        Delivery Information
                    </h3>
                    <div class="card-tools">
                        @if (!$spi->sent_date)
                            <button class="btn btn-success btn-sm send-spi" data-id="{{ $spi->id }}">
                                <i class="fas fa-paper-plane mr-1"></i> Send SPI
                            </button>
                            <a href="{{ route('accounting.spi.edit', $spi->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <button class="btn btn-danger btn-sm delete-spi" data-id="{{ $spi->id }}">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        @endif
                        <a href="{{ route('accounting.spi.print-preview', $spi->id) }}" class="btn btn-primary btn-sm"
                            target="_blank">
                            <i class="fas fa-print mr-1"></i> Print Preview
                        </a>
                        <a href="{{ route('accounting.spi.index', ['page' => 'list']) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="130">SPI Number</th>
                                    <td>: {{ $spi->nomor }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>: {{ \Carbon\Carbon::parse($spi->date)->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>: <span class="badge badge-{{ $spi->sent_date ? 'success' : 'warning' }}">
                                            {{ $spi->sent_date ? 'Sent' : 'Pending' }}
                                        </span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="130">From</th>
                                    <td>: {{ $spi->origin }}</td>
                                </tr>
                                <tr>
                                    <th>To</th>
                                    <td>: {{ $spi->destination }}</td>
                                </tr>
                                <tr>
                                    <th>Attention</th>
                                    <td>: {{ $spi->attention_person }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoices Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list-alt mr-2"></i>
                        Attached Invoices
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice Number</th>
                                    <th>Supplier</th>
                                    <th>Project</th>
                                    <th>Invoice Date</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($spi->documents as $document)
                                    @php
                                        $invoice = $document->documentable;
                                    @endphp
                                    <tr class="invoice-row" data-invoice-id="{{ $invoice->id }}" style="cursor: pointer;">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $invoice->invoice_number }}</td>
                                        <td>{{ $invoice->supplier->name }}</td>
                                        <td>{{ $invoice->invoice_project }}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</td>
                                        <td class="text-right">{{ number_format($invoice->amount, 2) }}</td>
                                    </tr>
                                    <tr class="additional-docs-row d-none" id="docs-{{ $invoice->id }}">
                                        <td colspan="6" class="p-0">
                                            <div class="bg-light p-3">
                                                <h6 class="mb-3">Additional Documents</h6>
                                                @if ($invoice->additionalDocuments->count() > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-bordered mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th>Document Type</th>
                                                                    <th>Document Number</th>
                                                                    <th>Document Date</th>
                                                                    <th>Receive Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($invoice->additionalDocuments as $doc)
                                                                    <tr>
                                                                        <td>{{ $doc->type->type_name }}</td>
                                                                        <td>{{ $doc->document_number }}</td>
                                                                        <td>{{ $doc->document_date ? \Carbon\Carbon::parse($doc->document_date)->format('d M Y') : '-' }}
                                                                        </td>
                                                                        <td>{{ $doc->receive_date ? \Carbon\Carbon::parse($doc->receive_date)->format('d M Y') : '-' }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <p class="text-muted mb-0">No additional documents available</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Total Amount:</strong></td>
                                    <td class="text-right">
                                        <strong>
                                            {{ number_format(
                                                $spi->documents->sum(function ($doc) {
                                                    return $doc->documentable->amount;
                                                }),
                                                2,
                                            ) }}
                                        </strong>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .card-title {
            float: left;
            font-size: 1.1rem;
            font-weight: 400;
            margin: 0;
        }

        .invoice-row:hover {
            background-color: #f8f9fa;
        }

        .additional-docs-row {
            background-color: #f8f9fa;
        }

        .additional-docs-row .bg-light {
            transform-origin: top;
            transform: scaleY(0);
            transition: transform 0.3s ease;
            opacity: 0;
        }

        .additional-docs-row.show .bg-light {
            transform: scaleY(1);
            opacity: 1;
        }

        .additional-docs-row.show {
            display: table-row !important;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.send-spi').click(function(e) {
                e.preventDefault();
                const spiId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to send this SPI. This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, send it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('accounting.spi.send', ':id') }}".replace(':id',
                                spiId),
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Sent!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Failed to send SPI. Please try again.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            $('.invoice-row').click(function() {
                const invoiceId = $(this).data('invoice-id');
                const docsRow = $(`#docs-${invoiceId}`);

                // If clicking the same row that's already open, just close it
                if (docsRow.hasClass('show')) {
                    docsRow.removeClass('show');
                    setTimeout(() => {
                        docsRow.addClass('d-none');
                    }, 300);
                    return;
                }

                // Hide all other open rows with animation
                $('.additional-docs-row.show').each(function() {
                    $(this).removeClass('show');
                    const row = $(this);
                    setTimeout(() => {
                        row.addClass('d-none');
                    }, 300);
                });

                // Show the clicked row
                docsRow.removeClass('d-none');
                // Force a reflow to ensure the transition works
                docsRow[0].offsetHeight;
                setTimeout(() => {
                    docsRow.addClass('show');
                }, 10);
            });

            $('.delete-spi').click(function(e) {
                e.preventDefault();
                const spiId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to delete this SPI. This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('accounting.spi.destroy', ':id') }}".replace(
                                ':id', spiId),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Deleted!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        window.location.href =
                                            "{{ route('accounting.spi.index', ['page' => 'list']) }}";
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete SPI. Please try again.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
