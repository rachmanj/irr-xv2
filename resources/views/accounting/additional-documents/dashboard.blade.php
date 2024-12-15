@extends('layout.main')

@section('title_page')
    ADDITIONAL DOCUMENTS
@endsection

@section('breadcrumb_title')
    <small>accounting / addocs / dashboard</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-acc-addoc-links page='dashboard' />

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Summary</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Desc</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Orphan</td>
                                <td>25</td>
                            </tr>
                            <tr>
                                <td>Belum diterima</td>
                                <td>25</td>
                            </tr>
                            <tr>
                                <td>Belum diinput</td>
                                <td>25</td>
                            </tr>
                            <tr>
                                <td>Data baru diupload</td>
                                <td>25</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Summary by Category</h5>
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
