@extends('layout.main')

@section('title_page')
    Create Delivery
@endsection

@section('content')
    <div class="row">
        <div class="col-12">

            <x-acc-delivery-links page="create" />

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">New Delivery</h3>

                </div>
                <form action="{{ route('accounting.deliveries.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Delivery Number</label>
                                    <input type="text" name="delivery_number"
                                        class="form-control @error('delivery_number') is-invalid @enderror"
                                        value="{{ old('delivery_number') }}" required>
                                    @error('delivery_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date Sent</label>
                                    <input type="date" name="date_sent" class="form-control"
                                        value="{{ old('date_sent', date('Y-m-d')) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Attention Person</label>
                                    <input type="text" name="attention_person" class="form-control"
                                        value="{{ old('attention_person') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Origin Project</label>
                                    <select name="origin_project_id" class="form-control select2" required>
                                        <option value="">Select Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Destination Project</label>
                                    <select name="destination_project_id" class="form-control select2" required>
                                        <option value="">Select Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Delivery Type</label>
                                    <select name="delivery_type" class="form-control" required id="delivery-type">
                                        <option value="full">Full Package (Invoices + Documents)</option>
                                        <option value="documents_only">Documents Only</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Invoices</label>
                                    <select name="invoices[]" class="form-control select2" multiple id="invoices-select">
                                        @foreach ($invoices as $invoice)
                                            <option value="{{ $invoice->id }}">
                                                {{ $invoice->invoice_number }} - {{ $invoice->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Additional Documents</label>
                                    <select name="additional_documents[]" class="form-control select2" multiple>
                                        @foreach ($additionalDocuments as $document)
                                            <option value="{{ $document->id }}">
                                                {{ $document->document_number }} - {{ $document->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Create Delivery</button>
                        <a href="{{ route('accounting.deliveries.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
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
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            // Handle delivery type change
            $('#delivery-type').change(function() {
                if ($(this).val() === 'documents_only') {
                    $('#invoices-select').prop('disabled', true).trigger('change');
                } else {
                    $('#invoices-select').prop('disabled', false);
                }
            });
        });
    </script>
@endsection
