@extends('layout.main')

@section('title_page')
    Delivery Details
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Delivery Information</h3>
                    @if (!$delivery->date_received)
                        <div class="card-tools">
                            <form action="{{ route('accounting.deliveries.receive', $delivery) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Mark as Received
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="200">Delivery Number</th>
                                    <td>{{ $delivery->delivery_number }}</td>
                                </tr>
                                <tr>
                                    <th width="200">Date Sent</th>
                                    <td>{{ $delivery->date_sent->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Date Received</th>
                                    <td>
                                        @if ($delivery->date_received)
                                            {{ $delivery->date_received->format('d M Y') }}
                                        @else
                                            <span class="text-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Origin Project</th>
                                    <td>{{ $delivery->originProject->name }}</td>
                                </tr>
                                <tr>
                                    <th>Destination Project</th>
                                    <td>{{ $delivery->destinationProject->name }}</td>
                                </tr>
                                <tr>
                                    <th>Attention Person</th>
                                    <td>{{ $delivery->attention_person }}</td>
                                </tr>
                                <tr>
                                    <th>Delivery Type</th>
                                    <td>
                                        @if ($delivery->delivery_type === 'full')
                                            <span class="badge badge-info">Full Package</span>
                                        @else
                                            <span class="badge badge-secondary">Documents Only</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            @if ($delivery->delivery_type === 'full')
                                <h5>Invoices</h5>
                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Invoice Number</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($delivery->invoices as $invoice)
                                                <tr>
                                                    <td>{{ $invoice->invoice_number }}</td>
                                                    <td>{{ $invoice->description }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            <h5>Additional Documents</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Document Number</th>
                                            <th>Title</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($delivery->additionalDocuments as $document)
                                            <tr>
                                                <td>{{ $document->document_number }}</td>
                                                <td>{{ $document->title }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if ($delivery->notes)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Notes</h5>
                                <p>{{ $delivery->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
