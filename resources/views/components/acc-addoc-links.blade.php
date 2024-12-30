<div class="card">
    <div class="card-header">
        <a href="{{ route('accounting.additional-documents.index', ['page' => 'dashboard']) }}"
            class="{{ request()->get('page') == 'dashboard' ? 'active' : '' }}">Dashboard</a>
        | <a href="{{ route('accounting.additional-documents.index', ['page' => 'search']) }}"
            class="{{ request()->get('page') == 'search' ? 'active' : '' }}">Search</a>
        | <a href="{{ route('accounting.additional-documents.index', ['page' => 'create']) }}"
            class="{{ request()->get('page') == 'create' ? 'active' : '' }}">Create</a>
        | <a href="{{ route('accounting.additional-documents.index', ['page' => 'list']) }}"
            class="{{ request()->get('page') == 'list' ? 'active' : '' }}">List</a>

    </div>
</div>
