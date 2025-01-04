<div class="card">
    <div class="card-header">
        <h5 class="card-title"><b>Summary</b></h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm table-bordered">
            <thead>
                <tr class="bg-light font-weight-bold">
                    <th class="text-right"><small>#</small></th>
                    <th class="text-left"><small>Desc</small> <span class="float-right"><small>Count</small></span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dashboardData['outs'] as $index => $item)
                    <tr>
                        <td style="width: 50px;" class="text-right"><small>{{ $index + 1 }}</small></td>
                        <td class="text-left"><small>{{ $item['description'] }} <span
                                    class="float-right">{{ $item['count'] }}</span></small></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
