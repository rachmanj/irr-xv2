<div class="tab-pane" id="attachments">
    @if ($invoice->attachments->isEmpty())
        <p class="text-muted">No attachments found.</p>
    @else
        <div class="row">
            @foreach ($invoice->attachments as $attachment)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center">
                                @if (in_array(strtolower(pathinfo($attachment->file_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ asset('storage/' . $attachment->file_path) }}" class="img-fluid"
                                        style="max-height: 100px;" alt="Attachment preview">
                                @else
                                    <i class="fas fa-file fa-3x text-secondary"></i>
                                @endif
                            </div>
                            <p class="small text-muted mb-1 text-truncate" title="{{ $attachment->original_name }}">
                                {{ $attachment->original_name }}
                            </p>
                            <p class="card-text">
                                <small class="text-muted">
                                    Uploaded: {{ $attachment->created_at->format('d-M-Y H:i') }}
                                </small>
                            </p>
                            <a href="{{ asset('storage/' . $attachment->file_path) }}" class="btn btn-sm btn-primary"
                                target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
