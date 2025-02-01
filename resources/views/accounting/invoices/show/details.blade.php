<div class="tab-pane active" id="details">
    <dl class="row">
        <dt class="col-sm-4">Vendor</dt>
        <dd class="col-sm-8">: {{ $invoice->supplier->name . ' | ' . $invoice->supplier->sap_code }}</dd>

        <dt class="col-sm-4">Invoice Type</dt>
        <dd class="col-sm-8">: {{ $invoice->invoiceType->type_name }}</dd>

        <dt class="col-sm-4">Invoice Number</dt>
        <dd class="col-sm-8">: {{ $invoice->invoice_number }}</dd>

        <dt class="col-sm-4">PO Number</dt>
        <dd class="col-sm-8">: {{ $invoice->po_no }}</dd>

        <dt class="col-sm-4">Invoice Date</dt>
        <dd class="col-sm-8">: {{ $invoice->invoice_date->format('d M Y') }}</dd>

        <dt class="col-sm-4">Receive Date</dt>
        <dd class="col-sm-8">: {{ $invoice->receive_date->format('d M Y') }}</dd>

        <dt class="col-sm-4">Currency</dt>
        <dd class="col-sm-8">: {{ $invoice->currency }}</dd>

        <dt class="col-sm-4">Amount</dt>
        <dd class="col-sm-8">: {{ number_format($invoice->amount, 2) }}</dd>

        <dt class="col-sm-4">Received in</dt>
        <dd class="col-sm-8">: {{ $invoice->receive_project }}</dd>

        <dt class="col-sm-4">For Project</dt>
        <dd class="col-sm-8">: {{ $invoice->invoice_project }}</dd>

        <dt class="col-sm-4">Payment in</dt>
        <dd class="col-sm-8">: {{ $invoice->payment_project }}</dd>

        <dt class="col-sm-4">Current Location</dt>
        <dd class="col-sm-8">: {{ $invoice->curLoc->location_code }}</dd>

        <dt class="col-sm-4">Status</dt>
        <dd class="col-sm-8">: {{ $invoice->status }}</dd>

        <dt class="col-sm-4">SAP Document</dt>
        <dd class="col-sm-8">: {{ $invoice->sap_doc ?? 'Not posted yet' }}</dd>

        <dt class="col-sm-4">Remarks</dt>
        <dd class="col-sm-8">: {{ $invoice->remarks }}</dd>

        <dt class="col-sm-4">Created By</dt>
        <dd class="col-sm-8">: {{ $invoice->createdBy->name }}</dd>

        <dt class="col-sm-4">Created At</dt>
        <dd class="col-sm-8">: {{ $invoice->created_at->format('d-M-Y H:i:s') }}</dd>

        <dt class="col-sm-4">Updated At</dt>
        <dd class="col-sm-8">: {{ $invoice->updated_at->format('d-M-Y H:i:s') }}</dd>
    </dl>
</div>
