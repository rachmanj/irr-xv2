<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LPD Print - {{ $lpd->nomor }}</title>

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

            @page {
                size: A4;
                margin: 1cm;
            }

            body {
                margin: 0;
                padding: 20px;
            }
        }

        .print-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
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

        .print-button:hover {
            opacity: 0.9;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
            color: white;
            text-decoration: none;
        }

        .table th {
            vertical-align: middle !important;
        }

        .signature-row td {
            height: 100px;
            vertical-align: bottom !important;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Main content -->
        <section class="invoice p-4">
            <!-- title row -->
            <div class="row">
                <div class="col-12">
                    <table class="table table-borderless">
                        <tr>
                            <td rowspan="2" style="width: 30%">
                                <h4>PT Arkananta Apta Pratista</h4>
                            </td>
                            <td rowspan="2" class="text-center" style="width: 40%">
                                <h3><b>Local Package Delivery</b></h3>
                                <h4>Number: {{ $lpd->nomor }}</h4>
                            </td>
                            <td class="text-right" style="width: 30%">ARKA/ACC/IV/01.02</td>
                        </tr>
                        <tr>
                            <td class="text-right">{{ date('d-M-Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- info row -->
            <div class="row mt-4">
                <div class="col-6">
                    <p class="mb-1">To:</p>
                    <address>
                        <strong>{{ $lpd->destination }}</strong><br>
                        <p class="mb-0">Up. {{ $lpd->attention_person }}</p>
                    </address>
                </div>
                <div class="col-6">
                    <p>
                    <h5>Date: {{ \Carbon\Carbon::parse($lpd->date)->format('d-M-Y') }}</h5>
                    </p>
                    <p>From: {{ $lpd->origin }}</p>
                </div>
            </div>

            <!-- Table row -->
            <div class="row mt-4">
                <div class="col-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px">NO</th>
                                <th>INVOICE NO.</th>
                                <th>DOCUMENT TYPE</th>
                                <th>DOCUMENT NO.</th>
                                <th>SUPPLIER</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lpd->documents as $document)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $document->invoice?->invoice_number ?? '-' }}</td>
                                    <td>{{ $document->type->type_name ?? '-' }}</td>
                                    <td>{{ $document->document_number ?? '-' }}</td>
                                    <td>{{ $document->invoice?->supplier?->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Notes row if exists -->
            @if ($lpd->notes)
                <div class="row mt-4">
                    <div class="col-12">
                        <strong>Notes:</strong>
                        <p>{{ $lpd->notes }}</p>
                    </div>
                </div>
            @endif

            <!-- Signature row -->
            <div class="row mt-4">
                <div class="col-12">
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-center">Prepared by</th>
                            <th class="text-center">Acknowledge</th>
                            <th class="text-center">Received by</th>
                        </tr>
                        <tr class="signature-row">
                            <td class="text-center">
                                <br><br><br>
                                (___________________)
                                <br>
                                {{ $lpd->origin }}
                            </td>
                            <td class="text-center">
                                <br><br><br>
                                (___________________)
                                <br>
                                &nbsp;
                            </td>
                            <td class="text-center">
                                <br><br><br>
                                (___________________)
                                <br>
                                {{ $lpd->destination }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <button onclick="window.print()" class="print-button">
        <i class="fas fa-print mr-1"></i> Print Document
    </button>

    <!-- Scripts required for the print button -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Auto print when the page loads (optional)
            // window.print();
        });
    </script>
</body>

</html>
