@extends('layout.main')

@section('title_page')
    MIGRASI
@endsection

@section('breadcrumb_title')
    migrasi
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <button id="copyInvoiceBtn" class="btn btn-primary">Copy Invoice from IRR5</button>
        </div>
    </div>
@endsection

@section('styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/sweetalert2.min.css') }}">
@endsection

@section('scripts')
    <!-- Select2 -->
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('adminlte/dist/js/sweetalert2.min.js') }}"></script>
    <script>
        document.getElementById('copyInvoiceBtn').addEventListener('click', function() {
            if (confirm('Are you sure you want to copy the invoice from IRR5?')) {
                fetch('{{ route('admin.migrasi.copyInvoiceIRR5') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while copying the invoice.',
                        });
                        console.error('Error:', error);
                    });
            }
        });
    </script>
@endsection
