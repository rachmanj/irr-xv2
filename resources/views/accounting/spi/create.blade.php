@extends('layout.main')

@section('title_page')
    Distribution
@endsection

@section('content')
    <div class="row">
        <div class="col-12">

            <x-acc-spi-links page='create' />

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createDistributionModal">
                Create New Distribution
            </button>

        </div>
    </div>

    <div class="modal fade" id="createDistributionModal" tabindex="-1" role="dialog"
        aria-labelledby="createDistributionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> <!-- Changed to modal-lg for a larger modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createDistributionModalLabel">Create New Distribution</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('accounting.spi.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="distribution_number">Distribution Number <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('distribution_number') is-invalid @enderror"
                                        id="distribution_number" name="distribution_number"
                                        value="{{ old('distribution_number') }}" required>
                                    @error('distribution_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type"
                                        name="type" required>
                                        <option value="">-- Select Type --</option>
                                        <option value="SPI" {{ old('type') == 'SPI' ? 'selected' : '' }}>SPI</option>
                                        <option value="LPD" {{ old('type') == 'LPD' ? 'selected' : '' }}>LPD</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                                        id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="origin">Origin Project <span class="text-danger">*</span></label>
                                    <select class="form-control select2bs4 @error('origin') is-invalid @enderror"
                                        id="origin" name="origin" required>
                                        <option value="">-- Select Origin Project --</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->code }}"
                                                {{ old('origin', '000H') == $project->code ? 'selected' : '' }}>
                                                {{ $project->code }} - {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('origin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="destination">Destination Project <span class="text-danger">*</span></label>
                                    <select class="form-control select2bs4 @error('destination') is-invalid @enderror"
                                        id="destination" name="destination" required>
                                        <option value="">-- Select Destination Project --</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->code }}"
                                                {{ old('destination', '001H') == $project->code ? 'selected' : '' }}>
                                                {{ $project->code }} - {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('destination')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="person1">Person 1</label>
                                    <input type="text" class="form-control @error('person1') is-invalid @enderror"
                                        id="person1" name="person1" value="{{ old('person1') }}">
                                    @error('person1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="person2">Person 2</label>
                                    <input type="text" class="form-control @error('person2') is-invalid @enderror"
                                        id="person2" name="person2" value="{{ old('person2') }}">
                                    @error('person2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
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
        $(function() {
            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
@endsection
