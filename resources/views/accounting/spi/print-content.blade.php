<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPI Content - {{ $spi->nomor }}</title>

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
        }

        .print-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="row">
            <div class="card col-5">
                <div class="card-body">
                    <div class="ribbon-wrapper ribbon-xl">
                        <div class="ribbon bg-warning text-lg">
                            Attention!
                        </div>
                    </div>

                    <h3 class="mt-4">Content Check List</h3>

                    <p class="text-muted">
                        Please check contents immediately after receiving and update to IRR System.<br>
                        This envelope contains these following invoices:
                    </p>

                    <ol>
                        @foreach ($spi->documents as $document)
                            <li style="margin-bottom: 5px;"><small>No.
                                    {{ $document->documentable->invoice_number ?? 'N/A' }},
                                    {{ $document->documentable->supplier->name ?? 'Unknown Supplier' }}</small></li>
                        @endforeach
                    </ol>

                    <div class="mt-4">
                        <p class="text-muted mb-0">
                            <small>
                                SPI Number: {{ $spi->nomor }}<br>
                                Date: {{ \Carbon\Carbon::parse($spi->date)->format('d M Y') }}
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button onclick="window.print()" class="print-button">
        Print Content
    </button>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>

</html>
