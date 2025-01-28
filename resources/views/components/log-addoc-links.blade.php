<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('logistic.addoc.index', ['page' => 'dashboard']) }}"
                    class="{{ request()->get('page') == 'dashboard' ? 'active' : '' }}"> Dashboard |
                </a>
                <a href="{{ route('logistic.addoc.index', ['page' => 'search']) }}"
                    class="{{ request()->get('page') == 'search' ? 'active' : '' }}"> Search |
                </a>
                <a href="{{ route('logistic.addoc.index', ['page' => 'create']) }}"
                    class="{{ request()->get('page') == 'create' ? 'active' : '' }}"> Create |
                </a>
                <a href="{{ route('logistic.addoc.index', ['page' => 'list']) }}"
                    class="{{ request()->get('page') == 'list' ? 'active' : '' }}"> List
                </a>
            </div>
        </div>
    </div>
</div>
