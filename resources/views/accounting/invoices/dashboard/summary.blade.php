<div class="card">
    <div class="card-header">
        <h3 class="card-title">Monthly Summary Report</h3>
    </div>
    @foreach ($data['monthly_summary'] as $yearData)
        <div class="card-body p-0">
            <h3 class="card-title px-2 pt-2">Year: {{ $yearData['year'] }}</h3>
            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Description</th>
                        @for ($month = 1; $month <= 12; $month++)
                            <th class="text-right">{{ date('M', mktime(0, 0, 0, $month, 1)) }}</th>
                        @endfor
                        <th class="text-right">Total</th>
                    </tr>
                    <tr>
                        <td><small>Receive Count</small></td>
                        @foreach ($yearData['monthly_data'] as $monthData)
                            <td class="text-right"><small>{{ $monthData['receive_count'] }}</small></td>
                        @endforeach
                        <td class="text-right"><small>{{ $yearData['year_invoice_count'] }}</small></td>
                    </tr>
                    <tr>
                        <td><small>Avg Duration</small></td>
                        @foreach ($yearData['monthly_data'] as $monthData)
                            <td class="text-right">{{ $monthData['average_duration'] }}</td>
                        @endforeach
                        <td class="text-right">{{ $yearData['year_average_duration'] }}</td>
                    </tr>
                </thead>
            </table>
        </div>
    @endforeach
</div>
