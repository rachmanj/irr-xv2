<div class="card">
    <div class="card-header">
        <a href="{{ route('master.suppliers.index', ['page' => 'dashboard']) }}"
            class="{{ request()->get('page') == 'dashboard' ? 'active' : '' }}">Dashboard</a> |
        <a href="{{ route('master.suppliers.index', ['page' => 'list']) }}"
            class="{{ request()->get('page') == 'list' ? 'active' : '' }}">List</a> |
        <a href="{{ route('master.suppliers.index', ['page' => 'sync']) }}"
            class="{{ request()->get('page') == 'sync' ? 'active' : '' }}">Sync</a>
    </div>
</div>
