@extends('layout.main')

@section('title_page')
    INVOICE TYPES
@endsection

@section('breadcrumb_title')
    <small>document-distributions / dashboard</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">

            <x-distribution-links page='dashboard' />

            <div class="row">
                <div class="col-12">
                    @include('document-distributions.dashboard-widget')
                </div>
            </div>

        </div>
    </div>
@endsection
