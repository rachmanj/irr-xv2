<div class="card">
    <div class="card-header">
        <a href="{{ route('master.upload.index', ['page' => 'dashboard']) }}"
            class="{{ request()->get('page') == 'dashboard' ? 'active' : '' }}">Dashboard</a> |
        <a href="{{ route('master.upload.index', ['page' => 'ito']) }}"
            class="{{ request()->get('page') == 'ito' ? 'active' : '' }}">ITO</a> |
    </div>
</div>
