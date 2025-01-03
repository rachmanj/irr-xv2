<div class="tab-pane" id="distribution">
    {{-- @if ($invoice->distributions->isEmpty())
        <p class="text-muted">No distribution records found.</p>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>From Project</th>
                    <th>To Project</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->distributions as $distribution)
                    <tr>
                        <td>{{ $distribution->date->format('d-M-Y') }}</td>
                        <td>{{ $distribution->from_project }}</td>
                        <td>{{ $distribution->to_project }}</td>
                        <td>{{ $distribution->status }}</td>
                        <td>{{ $distribution->remarks }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif --}}
</div>
