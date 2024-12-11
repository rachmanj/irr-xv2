@extends('layout.main')

@section('title_page')
    SUPPLIERS
@endsection

@section('breadcrumb_title')
    <small>master / suppliers / dashboard</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-master-supplier-links page='dashboard' />

            <div class="card">
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-3 col-6">
                            <div class="description-block border-right">
                                {{-- <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span> --}}
                                <h5 class="description-header">{{ $dashboard_data['total_vendors'] }}</h5>
                                <span class="description-text">TOTAL VENDORS</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-3 col-6">
                            <div class="description-block border-right">
                                {{-- <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i>0%</span> --}}
                                <h5 class="description-header">{{ $dashboard_data['total_customers'] }}</h5>
                                <span class="description-text">TOTAL CUSTOMERS</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-3 col-6">
                            <div class="description-block border-right">
                                {{-- <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span> --}}
                                <h5 class="description-header">$24,813.53</h5>
                                <span class="description-text">TOTAL PROFIT</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-3 col-6">
                            <div class="description-block">
                                {{-- <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i>18%</span> --}}
                                <h5 class="description-header">1200</h5>
                                <span class="description-text">GOAL COMPLETIONS</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                    </div>
                    <!-- /.row -->
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
