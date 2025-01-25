<div class="btn-group">
    <a href="{{ route('accounting.lpd.show', $delivery->id) }}" class="btn btn-primary btn-xs" title="View LPD">
        <i class="fas fa-eye"></i>
    </a>
    @if (!$delivery->sent_date)
        <a href="{{ route('accounting.lpd.edit', $delivery->id) }}" class="btn btn-info btn-xs" style="margin-left: 5px;"
            title="Edit LPD">
            <i class="fas fa-edit"></i>
        </a>
        <button type="button" class="btn btn-danger btn-xs delete-lpd" data-id="{{ $delivery->id }}"
            style="margin-left: 5px;" title="Delete LPD">
            <i class="fas fa-trash"></i>
        </button>
    @endif
</div>
