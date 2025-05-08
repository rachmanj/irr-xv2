<div class="card">
    <div class="card-header">
        @php
            $count_not_posted = \App\Models\Invoice::whereNull('sap_doc')->count();
        @endphp
        <a href="{{ route('accounting.invoices.index', ['page' => 'dashboard']) }}"
            class="{{ request()->get('page') == 'dashboard' ? 'active' : '' }}">Dashboard</a> |
        <a href="{{ route('accounting.invoices.index', ['page' => 'search']) }}"
            class="{{ request()->get('page') == 'search' ? 'active' : '' }}">Search</a> |
        <a href="{{ route('accounting.invoices.index', ['page' => 'create']) }}"
            class="{{ request()->get('page') == 'create' ? 'active' : '' }}">New</a> |
        <a href="{{ route('accounting.invoices.index', ['page' => 'not-posted']) }}"
            class="{{ request()->get('page') == 'not-posted' ? 'active' : '' }}">
            Not Posted
            @if ($count_not_posted > 0)
                <span class="badge badge-danger">{{ $count_not_posted }}</span>
            @endif
        </a> |
        <a href="{{ route('accounting.invoices.index', ['page' => 'list']) }}"
            class="{{ request()->get('page') == 'list' ? 'active' : '' }}">In-Process</a>
    </div>
</div>
