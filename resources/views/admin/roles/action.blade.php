<a href="{{ route('admin.roles.edit', $model->id) }}" class="btn btn-xs btn-warning">edit</a>

@include('admin.partials.delete-button', [
    'id' => $model->id,
    'action' => route('admin.roles.destroy', $model->id),
    'text' => 'delete',
])
