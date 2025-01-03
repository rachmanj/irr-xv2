<div class="tab-pane" id="additional-docs">
    {{-- @if ($invoice->additionalDocs->isEmpty())
        <p class="text-muted">No additional documents found.</p>
    @else
        <ul class="list-group">
            @foreach ($invoice->additionalDocs as $doc)
                <li class="list-group-item">
                    <strong>{{ $doc->title }}</strong>
                    <p>{{ $doc->description }}</p>
                    <a href="{{ route('accounting.invoices.additional_docs.download', $doc->id) }}"
                        class="btn btn-sm btn-primary">
                        <i class="fas fa-download"></i> Download
                    </a>
                </li>
            @endforeach
        </ul>
    @endif --}}
</div>
