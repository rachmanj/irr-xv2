<div class="tab-pane" id="distribution">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">
            <i class="fas fa-truck"></i> Delivery History
        </h5>
        <div>
            <button type="button" class="btn btn-primary btn-sm" onclick="printDeliveryHistory()">
                <i class="fas fa-print"></i> Print History
            </button>
        </div>
    </div>

    @if ($invoice->spis->isEmpty())
        <div class="text-center py-4">
            <i class="fas fa-truck-loading fa-3x text-muted mb-3"></i>
            <p class="text-muted">No delivery records found for this invoice.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="spi-table">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center" style="width: 50px">No</th>
                        <th>Document No</th>
                        <th>Document Date</th>
                        <th>From</th>
                        <th>To</th>
                        {{-- <th>Type</th> --}}
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Received By</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->spis as $index => $spi)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <a href="#" class="text-primary"
                                    onclick="viewDeliveryDetails('{{ $spi->nomor }}')">
                                    {{ $spi->nomor }}
                                </a>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($spi->tanggal)->format('d-M-Y') }}</td>
                            <td>
                                <span class="badge badge-light">
                                    <i class="fas fa-building mr-1"></i>
                                    {{ $spi->asal }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-light">
                                    <i class="fas fa-building mr-1"></i>
                                    {{ $spi->destination }}
                                </span>
                            </td>
                            {{-- <td>
                                <span class="badge badge-info">
                                    {{ $delivery->type }}
                                </span>
                            </td> --}}
                            <td class="text-center">
                                <span class="badge badge-{{ $spi->received_date ? 'success' : 'warning' }}">
                                    <i
                                        class="fas {{ $spi->received_date ? 'fa-check-circle' : 'fa-shipping-fast' }} mr-1"></i>
                                    {{ $spi->received_date ? 'Received' : 'In Transit' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-secondary">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ $spi->created_by }}
                                </span>
                            </td>
                            <td>
                                @if ($spi->received_by)
                                    <span class="badge badge-success">
                                        <i class="fas fa-user-check mr-1"></i>
                                        {{ $spi->received_by }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($spi->notes)
                                    <span class="text-muted" data-toggle="tooltip" title="{{ $spi->notes }}">
                                        {{ \Str::limit($spi->notes, 30) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <small class="text-muted">
                <i class="fas fa-info-circle"></i>
                Showing all delivery records associated with this invoice.
                Last updated: {{ $invoice->spis->first()?->updated_at?->format('d-M-Y H:i') ?? '-' }}
            </small>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        $(function() {
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });

        function viewDeliveryDetails(spiNumber) {
            // Implement delivery details modal/view logic here
            alert('View delivery details for: ' + spiNumber);
        }

        function printDeliveryHistory() {
            const printContent = document.getElementById('spi-table').outerHTML;
            const printWindow = window.open('', '_blank');

            printWindow.document.write(`
            <html>
                <head>
                    <title>Delivery History - Invoice ${@json($invoice->invoice_number)}</title>
                    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
                    <style>
                        body { padding: 20px; }
                        .badge { border: 1px solid #ddd; padding: 5px 10px; }
                    </style>
                </head>
                <body>
                    <h4 class="mb-3">Delivery History - Invoice ${@json($invoice->invoice_number)}</h4>
                    ${printContent}
                </body>
            </html>
        `);

            printWindow.document.close();
            printWindow.focus();

            // Print after styles are loaded
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        }
    </script>
@endpush

@push('styles')
    <style>
        .badge-light {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
        }

        .table td {
            vertical-align: middle;
        }

        .badge {
            font-size: 0.9em;
        }
    </style>
@endpush
