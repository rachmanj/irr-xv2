<!-- resources/views/master/invoice-types/action.blade.php -->
<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editModal{{ $model->id }}">
    Edit
</button>

<form action="{{ route('master.invoice-types.destroy', $model->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-xs"
        onclick="return confirm('Are you sure you want to delete this item?');">
        Delete
    </button>
</form>

<!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('master.invoice-types.update', $model->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Invoice Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="type_name">Type Name</label>
                        <input type="text" class="form-control" id="type_name" name="type_name"
                            value="{{ old('type_name', $model->type_name) }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
