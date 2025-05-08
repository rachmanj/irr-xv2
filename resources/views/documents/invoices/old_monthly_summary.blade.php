@extends('layout.main')

@section('title_page')
    Invoice Monthly Summary
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Monthly Summary Report</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-center align-middle">Year</th>
                                @for ($month = 1; $month <= 12; $month++)
                                    <th colspan="2" class="text-center">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</th>
                                @endfor
                                <th colspan="2" class="text-center">Total</th>
                            </tr>
                            <tr>
                                @for ($month = 1; $month <= 13; $month++)
                                    <th class="text-center">Count</th>
                                    <th class="text-center">Avg</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $yearData)
                                <tr>
                                    <td class="text-center">{{ $yearData['year'] }}</td>
                                    @foreach ($yearData['monthly_data'] as $monthData)
                                        <td class="text-center">{{ $monthData['receive_count'] }}</td>
                                        <td class="text-center">
                                            {{ $monthData['average_duration'] > 0 ? $monthData['average_duration'] : '-' }}
                                        </td>
                                    @endforeach
                                    <td class="text-center"><strong>{{ $yearData['year_invoice_count'] }}</strong></td>
                                    <td class="text-center">
                                        <strong>{{ $yearData['year_average_duration'] > 0 ? $yearData['year_average_duration'] : '-' }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .table thead th {
            vertical-align: middle;
            background-color: #f4f6f9;
        }

        .table td {
            vertical-align: middle;
        }

        /* Make the table horizontally scrollable on small screens */
        .table-responsive {
            overflow-x: auto;
        }

        /* Sticky first column */
        .table thead th:first-child,
        .table tbody td:first-child {
            position: sticky;
            left: 0;
            background-color: #f4f6f9;
            z-index: 1;
        }
    </style>
@endsection
