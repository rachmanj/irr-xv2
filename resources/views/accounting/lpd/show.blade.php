@extends('layout.main')

@section('title_page')
    LPD Details
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
                        LPD Information
                    </h3>
                    <div class="card-tools">
                        @if (!$lpd->sent_date)
                            <button class="btn btn-success btn-sm send-lpd" data-id="{{ $lpd->id }}">
                                <i class="fas fa-paper-plane mr-1"></i> Send LPD
                            </button>
                            <a href="{{ route('accounting.lpd.edit', $lpd->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <button class="btn btn-danger btn-sm delete-lpd" data-id="{{ $lpd->id }}">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        @endif
                        <a href="{{ route('accounting.lpd.print-preview', $lpd->id) }}" class="btn btn-sm btn-secondary"
                            target="_blank">
                            <i class="fas fa-print mr-1"></i> Print Preview
                        </a>
                        <a href="{{ route('accounting.lpd.index', ['page' => 'list']) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="130">LPD Number</th>
                                    <td>: {{ $lpd->nomor }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>: {{ \Carbon\Carbon::parse($lpd->date)->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>: <span class="badge badge-{{ $lpd->sent_date ? 'success' : 'warning' }}">
                                            {{ $lpd->sent_date ? 'Sent' : 'Pending' }}
                                        </span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="130">From</th>
                                    <td>: {{ $lpd->origin }}</td>
                                </tr>
                                <tr>
                                    <th>To</th>
                                    <td>: {{ $lpd->destination }}</td>
                                </tr>
                                <tr>
                                    <th>Attention</th>
                                    <td>: {{ $lpd->attention_person }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list-alt mr-2"></i>
                        Attached Documents
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
                                    <th>Document Type</th>
                                    <th>Document Number</th>
                                    <th>Document Date</th>
                                    <th>Receive Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lpd->documents as $document)
                                    @php
                                        $additionalDoc = $document->documentable;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $additionalDoc->invoice->invoice_number ?? '' }}</td>
                                        <td>{{ $additionalDoc->invoice->supplier->name ?? '' }}</td>
                                        <td>{{ $additionalDoc->type->type_name ?? '' }}</td>
                                        <td>{{ $additionalDoc->document_number }}</td>
                                        <td>{{ $additionalDoc->document_date ? \Carbon\Carbon::parse($additionalDoc->document_date)->format('d M Y') : '' }}
                                        </td>
                                        <td>{{ $additionalDoc->receive_date ? \Carbon\Carbon::parse($additionalDoc->receive_date)->format('d M Y') : '' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Handle send LPD
            $('.send-lpd').click(function(e) {
                e.preventDefault();
                const lpdId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to send this LPD. This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, send it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('accounting.lpd.send', ':id') }}".replace(':id',
                                lpdId),
                            type: 'GET',
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
                                        window.location.reload();
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
                                    'Failed to send LPD. Please try again.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Handle delete LPD
            $('.delete-lpd').click(function(e) {
                e.preventDefault();
                const lpdId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to delete this LPD. This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('accounting.lpd.destroy', ':id') }}".replace(
                                ':id', lpdId),
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
                                            "{{ route('accounting.lpd.index', ['page' => 'list']) }}";
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
                                    'Failed to delete LPD. Please try again.',
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
