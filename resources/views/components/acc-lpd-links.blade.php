<div class="card">
    <div class="card-header">
        <a href="{{ route('accounting.lpd.index', ['page' => 'dashboard']) }}"
            class="{{ request()->get('page') == 'dashboard' ? 'active' : '' }}">Dashboard</a> |
        <a href="{{ route('accounting.lpd.index', ['page' => 'search']) }}"
            class="{{ request()->get('page') == 'search' ? 'active' : '' }}">Search</a> |
        <a href="{{ route('accounting.lpd.index', ['page' => 'create']) }}"
            class="{{ request()->get('page') == 'create' ? 'active' : '' }}">New</a> |
        <a href="{{ route('accounting.lpd.index', ['page' => 'list']) }}"
            class="{{ request()->get('page') == 'list' ? 'active' : '' }}">List</a>
    </div>
</div>
