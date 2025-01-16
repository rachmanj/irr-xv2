<div class="card">
    <div class="card-header">
        <a href="{{ route('master.upload.index', ['page' => 'dashboard']) }}"
            class="{{ request()->get('page') == 'dashboard' ? 'active' : '' }}">Dashboard</a>
        | <a href="{{ route('master.upload.index', ['page' => 'search']) }}"
            class="{{ request()->get('page') == 'search' ? 'active' : '' }}">ITO</a>
    </div>
</div>
