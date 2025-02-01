<div class="btn-group" role="group">
    <!-- View Details -->
    <a href="{{ route('logistic.lpd.show', $lpd->id) }}" class="btn btn-xs btn-info mr-2" title="View Details">
        <i class="fas fa-eye"></i>
    </a>

    <!-- Edit -->
    @if ($lpd->status === 'draft')
        <a href="{{ route('logistic.lpd.edit', $lpd->id) }}" class="btn btn-xs btn-warning mr-2" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
    @endif

    <!-- Print -->
    <a href="{{ route('logistic.lpd.print', $lpd->id) }}" class="btn btn-xs btn-secondary mr-2" title="Print"
        target="_blank">
        <i class="fas fa-print"></i>
    </a>

    <!-- Send -->
    @if ($lpd->status === 'draft')
        <button type="button" class="btn btn-xs btn-success btn-send mr-2" data-id="{{ $lpd->id }}"
            title="Send">
            <i class="fas fa-paper-plane"></i>
        </button>
    @endif

    <!-- Delete -->
    @if ($lpd->status === 'draft')
        <button type="button" class="btn btn-xs btn-danger btn-delete" data-id="{{ $lpd->id }}" title="Delete">
            <i class="fas fa-trash"></i>
        </button>
    @endif
</div>
