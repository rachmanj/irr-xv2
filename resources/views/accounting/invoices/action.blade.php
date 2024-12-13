<a href="{{ route('accounting.invoices.edit', $model->id) }}" class="btn btn-primary btn-xs">Edit</a>
<form action="{{ route('accounting.invoices.destroy', $model->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-xs" @if ($model->additionalDocuments->count() > 0) disabled @endif
        onclick="return confirm('Do you really want to delete this invoice? This action cannot be undone.')">Delete</button>
</form>
