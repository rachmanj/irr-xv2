<div class="card">
    <div class="card-header">
        <h5 class="card-title"><b>Summary by Type</b> <small>(addocs yg orphan atau belum diterima)</small></h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm table-bordered">
            <thead>
                <tr class="bg-light font-weight-bold">
                    <th class="text-right"><small>#</small></th>
                    <th class="text-left"><small>Type</small> <span class="float-right"><small>Count</small></span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dashboardData['type']['data'] as $index => $item)
                    <tr>
                        <td style="width: 50px;" class="text-right"><small>{{ $index + 1 }}</small> </td>
                        <td class="text-left"><small>{{ $item['type'] }} <span
                                    class="float-right">{{ $item['count'] }}</span></small></td>
                    </tr>
                @endforeach
                <tr class="bg-light font-weight-bold">
                    <td style="width: 50px;"></td>
                    <td colspan="2" class="text-left"><b>Total</b><small> <span
                                class="float-right"><b>{{ $dashboardData['type']['total_count'] }}</b></span></small>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
