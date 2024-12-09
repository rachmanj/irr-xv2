@extends('layout.main')

@section('title_page')
    INVOICES
@endsection

@section('breadcrumb_title')
    <small>accounting / invoices / create</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-acc-invoice-links page='create' />
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Invoice</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('accounting.invoices.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="supplier_id">Select Supplier</label>
                                    <select name="supplier_id" id="supplier_id" class="form-control select2bs4">
                                        <option value="">Select Supplier</option>
                                        @foreach (App\Models\Supplier::where('type', 'vendor')->where('is_active', 1)->orderBy('name', 'asc')->get() as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->sap_code . ' | ' . $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="invoice_number">Invoice Number</label>
                                    <input type="text" name="invoice_number" id="invoice_number"
                                        class="form-control @error('invoice_number') is-invalid @enderror"
                                        value="{{ old('invoice_number') }}">
                                    @error('invoice_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div id="invoice-number-error" class="text-danger" style="display: none;">
                                        Invoice number already exists for this supplier.
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="invoice_date">Invoice Date</label>
                                    <input type="date" name="invoice_date" id="invoice_date" class="form-control"
                                        value="{{ old('invoice_date') }}">
                                    @error('invoice_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="receive_date">Receive Date</label>
                            <input type="date" name="receive_date" id="receive_date" class="form-control"
                                value="{{ old('receive_date') }}">
                            @error('receive_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="receive_project">Select Project</label>
                                    <select name="receive_project" id="receive_project" class="form-control select2bs4">
                                        <option value="000H" {{ old('receive_project') == '000H' ? 'selected' : '' }}>
                                            000H</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->code }}"
                                                {{ old('receive_project') == $project->code ? 'selected' : '' }}>
                                                {{ $project->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="invoice_project">Invoice Project</label>
                                    <input type="text" name="invoice_project" id="invoice_project" class="form-control"
                                        value="{{ old('invoice_project') }}">
                                    @error('invoice_project')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="payment_project">Payment Project</label>
                                    <input type="text" name="payment_project" id="payment_project" class="form-control"
                                        value="{{ old('payment_project') }}">
                                    @error('payment_project')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="currency">Currency</label>
                            <input type="text" name="currency" id="currency" class="form-control"
                                value="{{ old('currency', 'IDR') }}">
                            @error('currency')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control"
                                value="{{ old('amount') }}">
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <input type="text" name="status" id="status" class="form-control"
                                value="{{ old('status', 'pending') }}">
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>




                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-sm">Create Invoice</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .card-header .active {
            color: black;
            text-transform: uppercase;
        }
    </style>
@endsection

@section('scripts')
    <!-- Select2 -->
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            $('#invoice_number, #supplier_id').on('blur', function() {
                var invoiceNumber = $('#invoice_number').val();
                var supplierId = $('#supplier_id').val();
                if (invoiceNumber && supplierId) {
                    $.ajax({
                        url: '{{ route('check.invoice.number') }}',
                        method: 'GET',
                        data: {
                            invoice_number: invoiceNumber,
                            supplier_id: supplierId
                        },
                        success: function(response) {
                            if (response.exists) {
                                $('#invoice-number-error').show();
                            } else {
                                $('#invoice-number-error').hide();
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
