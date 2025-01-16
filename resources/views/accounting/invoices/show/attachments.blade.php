<div class="tab-pane" id="attachments">
    @if ($invoice->attachments->isEmpty())
        <p class="text-muted">No attachments found.</p>
    @else
        <div class="row">
            @foreach ($invoice->attachments as $attachment)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-file"></i> {{ $attachment->original_name }}
                            </h5>
                            <p class="card-text">
                                <small class="text-muted">
                                    Uploaded: {{ $attachment->created_at->format('d-M-Y H:i') }}
                                </small>
                            </p>
                            <a href="{{ route('accounting.invoices.attachments.download', $attachment->id) }}"
                                class="btn btn-sm btn-primary">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
