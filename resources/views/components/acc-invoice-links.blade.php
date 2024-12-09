<div class="card">
    <div class="card-header">
        <a href="{{ route('accounting.invoices.index', ['page' => 'dashboard']) }}"
            class="{{ request()->get('page') == 'dashboard' ? 'active' : '' }}">Dashboard</a> |
        <a href="{{ route('accounting.invoices.index', ['page' => 'search']) }}"
            class="{{ request()->get('page') == 'search' ? 'active' : '' }}">Search</a> |
        <a href="{{ route('accounting.invoices.index', ['page' => 'create']) }}"
            class="{{ request()->get('page') == 'create' ? 'active' : '' }}">New</a>
    </div>
</div>
