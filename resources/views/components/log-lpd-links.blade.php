<div class="card mb-3">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('logistic.lpd.index', ['page' => 'dashboard']) }}"
                    class="btn {{ $page == 'dashboard' ? 'btn-secondary' : 'btn-light' }} btn-sm">
                    <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                </a>
                <a href="{{ route('logistic.lpd.index', ['page' => 'create']) }}"
                    class="btn {{ $page == 'create' ? 'btn-secondary' : 'btn-light' }} btn-sm">
                    <i class="fas fa-plus-circle mr-1"></i> Create
                </a>
                <a href="{{ route('logistic.lpd.index', ['page' => 'list']) }}"
                    class="btn {{ $page == 'list' ? 'btn-secondary' : 'btn-light' }} btn-sm">
                    <i class="fas fa-list mr-1"></i> List
                </a>
                <a href="{{ route('logistic.lpd.index', ['page' => 'search']) }}"
                    class="btn {{ $page == 'search' ? 'btn-secondary' : 'btn-light' }} btn-sm">
                    <i class="fas fa-search mr-1"></i> Search
                </a>
            </div>
        </div>
    </div>
</div>
