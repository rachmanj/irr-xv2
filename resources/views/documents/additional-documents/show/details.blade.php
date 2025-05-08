<div class="card-body">
    <dl class="row">
        <dt class="col-sm-4">Document No</dt>
        <dd class="col-sm-8">: {{ $additionalDocument->document_number }}</dd>
        <dt class="col-sm-4">Document Type</dt>
        <dd class="col-sm-8">:
            {{ $additionalDocument->documentType ? $additionalDocument->documentType->type_name : 'N/A' }}
        </dd>
        <dt class="col-sm-4">Document Date</dt>
        <dd class="col-sm-8">: {{ \Carbon\Carbon::parse($additionalDocument->document_date)->format('d M Y') }}</dd>
        <dt class="col-sm-4">Received Date</dt>
        <dd class="col-sm-8">:
            {{ $additionalDocument->received_date ? \Carbon\Carbon::parse($additionalDocument->received_date)->format('d M Y') : '-' }}
        </dd>
        <dt class="col-sm-4">Remarks</dt>
        <dd class="col-sm-8">: {{ $additionalDocument->remarks ? $additionalDocument->remarks : 'N/A' }}</dd>
        <dt class="col-sm-4">Created By</dt>
        <dd class="col-sm-8">: {{ $additionalDocument->createdBy ? $additionalDocument->createdBy->name : '-' }}
        </dd>
        <dt class="col-sm-4">Created At</dt>
        <dd class="col-sm-8">: {{ $additionalDocument->created_at->format('d M Y H:i') }}</dd>
        <dt class="col-sm-4">Updated At</dt>
        <dd class="col-sm-8">: {{ $additionalDocument->updated_at->format('d M Y H:i') }}</dd>
        <dt class="col-sm-4">Status</dt>
        <dd class="col-sm-8">: {{ $additionalDocument->status }}</dd>
    </dl>
</div>
