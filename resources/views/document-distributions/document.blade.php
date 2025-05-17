@extends('layout.main')

@section('title_page')
    Document Tracking
@endsection

@section('content')
    <x-distribution-links page="document" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Document Tracking System</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="text-center mb-4">
                                <img src="{{ asset('assets/img/document-tracking.png') }}" alt="Document Tracking"
                                    class="img-fluid" style="max-height: 200px;">
                                <h4 class="mt-3">Track and Manage Document Distribution</h4>
                                <p class="text-muted">
                                    Use our document tracking system to monitor the movement of invoices and other documents
                                    across departments.
                                </p>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fas fa-search"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Track Documents</span>
                                            <a href="{{ route('document-distributions.search-history') }}"
                                                class="btn btn-sm btn-info mt-2">
                                                Search Documents
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success"><i class="fas fa-paper-plane"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Distribute Documents</span>
                                            <a href="{{ route('document-distributions.create') }}"
                                                class="btn btn-sm btn-success mt-2">
                                                Create Distribution
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning"><i class="fas fa-list"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">View All Distributions</span>
                                            <a href="{{ route('document-distributions.index') }}"
                                                class="btn btn-sm btn-warning mt-2">
                                                Distribution List
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-primary"><i class="fas fa-history"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Distribution History</span>
                                            <a href="{{ route('document-distributions.search-history') }}"
                                                class="btn btn-sm btn-primary mt-2">
                                                View History
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @include('document-distributions.dashboard-widget', [
                'recentDistributions' => $recentDistributions,
            ])
        </div>
    </div>
@endsection
