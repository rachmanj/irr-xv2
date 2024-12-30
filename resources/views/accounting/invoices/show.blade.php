@extends('layout.main')

@section('title_page')
    INVOICES
@endsection

@section('breadcrumb_title')
    <small>accounting / invoices / show</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            {{-- <x-acc-invoice-links page='search' /> --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Invoice Details</h3>
                    <a href="{{ route('accounting.invoices.index', ['page' => 'search']) }}"
                        class="float-right btn-sm btn btn-primary"><i class="fas fa-arrow-left"></i> Back</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="supplier_id">Vendor</label>
                                <input type="text" class="form-control"
                                    value="{{ $invoice->supplier->name . ' | ' . $invoice->supplier->sap_code }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="invoice_type">Invoice Type</label>
                                <input type="text" class="form-control" value="{{ $invoice->invoiceType->type_name }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label for="invoice_number">Invoice Number</label>
                                <input type="text" class="form-control" value="{{ $invoice->invoice_number }}" readonly>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="po_no">PO Number</label>
                                <input type="text" class="form-control" value="{{ $invoice->po_no }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="invoice_date">Invoice Date</label>
                                <input type="text" class="form-control"
                                    value="{{ $invoice->invoice_date->format('d-M-Y') }}" readonly>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="receive_date">Receive Date</label>
                                <input type="text" class="form-control"
                                    value="{{ $invoice->receive_date->format('d-M-Y') }}" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="currency">Curr</label>
                                        <input type="text" class="form-control" value="{{ $invoice->currency }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input type="text" class="form-control"
                                            value="{{ number_format($invoice->amount, 2) }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="receive_project">Received in</label>
                                <input type="text" class="form-control" value="{{ $invoice->receive_project }}" readonly>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="invoice_project">For Project</label>
                                <input type="text" class="form-control" value="{{ $invoice->invoice_project }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="payment_project">Payment in</label>
                                <input type="text" class="form-control" value="{{ $invoice->payment_project }}"
                                    readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <input type="text" class="form-control" value="{{ $invoice->remarks }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <h5>Additional Documents</h5>
                    @if ($invoice->additionalDocuments->isEmpty())
                        <p>No additional documents found.</p>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Document Number</th>
                                    <th>Document Date</th>
                                    <th>PO No</th>
                                    <th>Project</th>
                                    <th>Receive Date</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->additionalDocuments as $document)
                                    <tr>
                                        <td>{{ $document->document_number }}</td>
                                        <td>{{ \Carbon\Carbon::parse($document->document_date)->format('d-M-Y') }}</td>
                                        <td>{{ $document->po_no }}</td>
                                        <td>{{ $document->project }}</td>
                                        <td>{{ $document->receive_date ? \Carbon\Carbon::parse($document->receive_date)->format('d-M-Y') : 'NRY' }}
                                        </td>
                                        <td>{{ $document->remarks }}</td>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('accounting.invoices.edit', $invoice->id) }}" class="btn btn-primary btn-sm">Edit
                        Invoice</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card-header .active {
            color: black;
            text-transform: uppercase;
        }
    </style>
@endsection
