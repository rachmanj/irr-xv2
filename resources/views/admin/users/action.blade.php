@if ($model->is_active == 1)
    <form id="deactivate-form-{{ $model->id }}" action="{{ route('admin.users.deactivate', $model->id) }}"
        method="POST" style="display: none;">
        @csrf @method('PUT')
    </form>
    <button type="button" class="btn btn-xs btn-warning"
        onclick="confirmFormSubmit('deactivate-form-{{ $model->id }}', 'Are you sure you want to deactivate this user?')">deactivate</button>
@endif

@if ($model->is_active == 0)
    <form id="activate-form-{{ $model->id }}" action="{{ route('admin.users.activate', $model->id) }}" method="POST"
        style="display: none;">
        @csrf @method('PUT')
    </form>
    <button type="button" class="btn btn-xs btn-warning"
        onclick="confirmFormSubmit('activate-form-{{ $model->id }}', 'Are you sure you want to activate this user?')">activate</button>
@endif

<a href="{{ route('admin.users.edit', $model->id) }}" class="btn btn-xs btn-info d-inline">edit</a>

@if ($model->is_active == 0)
    @include('admin.partials.delete-button', [
        'id' => $model->id,
        'action' => route('admin.users.destroy', $model->id),
        'text' => 'delete',
    ])
@endif
