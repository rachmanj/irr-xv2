@extends('layout.main')

@section('title_page')
    Deliveries
@endsection

@section('content')
    <div class="row">
        <div class="col-12">

            <x-acc-delivery-links page="list" />

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Document Deliveries</h3>
                    <div class="card-tools">
                        <a href="{{ route('accounting.deliveries.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Delivery
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Delivery Number</th>
                                    <th>Date Sent</th>
                                    <th>Status</th>
                                    <th>Origin Project</th>
                                    <th>Destination Project</th>
                                    <th>Attention Person</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deliveries as $delivery)
                                    <tr>
                                        <td>{{ $delivery->delivery_number }}</td>
                                        <td>{{ $delivery->date_sent->format('d M Y') }}</td>
                                        <td>
                                            @if ($delivery->date_received)
                                                <span class="badge badge-success">Received</span>
                                            @else
                                                <span class="badge badge-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $delivery->originProject->name }}</td>
                                        <td>{{ $delivery->destinationProject->name }}</td>
                                        <td>{{ $delivery->attention_person }}</td>
                                        <td>
                                            @if ($delivery->delivery_type === 'full')
                                                <span class="badge badge-info">Full Package</span>
                                            @else
                                                <span class="badge badge-secondary">Documents Only</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('accounting.deliveries.show', $delivery) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $deliveries->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
