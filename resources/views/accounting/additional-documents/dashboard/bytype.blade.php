<div class="card">
    <div class="card-header">
        <h5 class="card-title"><b>Summary by Type</b> <small>(addocs yg orphan atau belum diterima)</small></h5>
    </div>
    <div class="card-body">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-right">#</th>
                    <th class="text-left">Type <span class="float-right">Count</span></th>
                    {{-- <th></th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($dashboardData['type'] as $index => $item)
                    <tr>
                        <td style="width: 50px;" class="text-right">{{ $index + 1 }}</td>
                        <td class="text-left">{{ $item['type'] }}<span class="float-right">{{ $item['count'] }}</span>
                        </td>
                        {{-- <td>{{ $item['count'] }}</td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
