<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPI Print - {{ $spi->nomor }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

    <style>
        @media print {
            .print-button {
                display: none;
            }

            .print-content-button {
                display: none;
            }
        }

        .print-button {
            position: fixed;
            bottom: 20px;
            right: 140px;
            padding: 8px 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .print-content-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 8px 16px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .print-button:hover,
        .print-content-button:hover {
            opacity: 0.9;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
            color: white;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Main content -->
        <section class="invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-12">
                    <table class="table">
                        <tr>
                            <td rowspan="2">
                                <h4>PT Arkananta Apta Pratista</h4>
                            </td>
                            <td rowspan="2" class="text-center">
                                <h3><b>Supplier Payment Instruction</b></h3>
                                <h4>Number: {{ $spi->nomor }}</h4>
                            </td>
                            <td class="text-right">ARKA/ACC/IV/01.01</td>
                        </tr>
                        <tr>
                            <td class="text-right">{{ date('d-M-Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- info row -->
            <div class="row">
                <div class="col-5">
                    <p class="mb-1">To:</p>
                    <address>
                        <strong>{{ $spi->destination }}</strong><br>
                        <p class="mb-0">Up. {{ $spi->attention_person }}</p>
                    </address>
                </div>
                <div class="col-6">
                    <p>
                    <h5>Date: {{ \Carbon\Carbon::parse($spi->date)->format('d-M-Y') }}</h5>
                    </p>
                    <p>From: {{ $spi->origin }}</p>
                </div>
            </div>

            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>INVOICE NO.</th>
                                <th>INVOICE DATE</th>
                                <th class="text-center">AMOUNT</th>
                                <th>SUPPLIER</th>
                                <th>PROJECT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($spi->documents as $document)
                                @php
                                    $invoice = $document->documentable;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</td>
                                    <td class="text-right">{{ number_format($invoice->amount, 2) }}</td>
                                    <td>{{ $invoice->supplier->name }}</td>
                                    <td>{{ $invoice->invoice_project }}</td>
                                </tr>
                                @if ($invoice->additionalDocuments->count() > 0)
                                    <tr>
                                        <td colspan="2" class="text-right">Additional Docs:</td>
                                        <td>Document Type</td>
                                        <td colspan="3">Document Number</td>
                                    </tr>
                                    @foreach ($invoice->additionalDocuments as $additionalDoc)
                                        <tr>
                                            <td colspan="2"></td>
                                            <td>{{ $additionalDoc->type->type_name ?? 'Unknown Type' }}</td>
                                            <td colspan="3">{{ $additionalDoc->document_number }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Signature row -->
            <div class="row mt-4">
                <div class="col-12">
                    <table class="table">
                        <tr>
                            <th class="text-center">Prepared by</th>
                            <th class="text-center">Acknowledge</th>
                            <th class="text-center">Received by</th>
                        </tr>
                        <tr>
                            <td class="py-4"></td>
                            <td class="py-4"></td>
                            <td class="py-4"></td>
                        </tr>
                        <tr>
                            <td class="text-center">(____________________________________)</td>
                            <td class="text-center">(____________________________________)</td>
                            <td class="text-center">(____________________________________)</td>
                        </tr>
                        <tr>
                            <td class="text-center">{{ $spi->origin }}</td>
                            <td class="text-center"></td>
                            <td class="text-center">{{ $spi->destination }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <button onclick="window.print()" class="print-button">
        Print Document
    </button>

    <a href="{{ route('accounting.spi.print-content', $spi->id) }}" class="print-content-button" target="_blank">
        Print Content
    </a>

    {{-- <script>
        window.onload = function() {
            // Add a small delay before auto-printing to ensure styles are loaded
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script> --}}
</body>

</html>
