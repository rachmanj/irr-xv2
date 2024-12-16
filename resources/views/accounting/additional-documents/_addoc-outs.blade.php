<div class="card">
    <div class="card-header">
        <h5 class="card-title"><b>Summary</b></h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-right">#</th>
                    <th class="text-left">Desc <span class="float-right">Count</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dashboardData['outs'] as $index => $item)
                    <tr>
                        <td style="width: 50px;" class="text-right">{{ $index + 1 }}</td>
                        <td class="text-left">{{ $item['description'] }} <span
                                class="float-right">{{ $item['count'] }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
