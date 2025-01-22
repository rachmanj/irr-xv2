<a href="{{ route('accounting.spi.show', $delivery->id) }}" class="btn btn-xs btn-primary" title="View Details">
    <i class="fas fa-eye"></i>
</a>
<a href="{{ route('accounting.spi.print-preview', $delivery->id) }}" class="btn btn-secondary btn-xs" target="_blank"
    title="Print SPI">
    <i class="fas fa-print"></i>
</a>

@if (!$delivery->sent_date)
    <a href="#" class="btn btn-success btn-xs send-spi" data-id="{{ $delivery->id }}" title="Send SPI">
        <i class="fas fa-paper-plane"></i>
    </a>
@endif
