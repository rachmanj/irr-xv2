<div class="card">
    <div class="card-header bg-primary py-1 px-2">
        <h3 class="card-title">Monthly Summary Report</h3>
    </div>
    @foreach ($data['monthly_summary'] as $yearData)
        <div class="card-body p-0">
            <h3 class="card-title px-2 py-1">Year: {{ $yearData['year'] }}</h3>
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
                        <td><small>Sent Count</small></td>
                        @foreach ($yearData['monthly_data'] as $monthData)
                            <td class="text-right"><small>{{ $monthData['sent_count'] }}</small></td>
                        @endforeach
                        <td class="text-right"><small>{{ $yearData['year_invoice_count'] }}</small></td>
                    </tr>
                    <tr>
                        <td><small>Sent Percentage</small></td>
                        @foreach ($yearData['monthly_data'] as $monthData)
                            <td class="text-right"><small>{{ $monthData['sent_percentage'] }}%</small></td>
                        @endforeach
                        <td class="text-right">
                            <small>{{ $yearData['year_sent_percentage'] }}%</small>
                        </td>
                    </tr>
                    <tr>
                        <td><small>Avg Duration</small></td>
                        @foreach ($yearData['monthly_data'] as $monthData)
                            <td class="text-right @if ($monthData['average_duration'] > 7) text-danger @endif">
                                <small>{{ $monthData['average_duration'] }}</small></td>
                        @endforeach
                        <td class="text-right @if ($yearData['year_average_duration'] > 7) text-danger @endif">
                            <small>{{ $yearData['year_average_duration'] }}</small></td>
                    </tr>
                </thead>
            </table>
        </div>
    @endforeach
</div>
