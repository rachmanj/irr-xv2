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
        <dd class="col-sm-8">:
            @if ($invoice->sap_doc)
                {{ $invoice->sap_doc }}
            @else
                @can('update-sap-doc')
                    <button type="button" class="btn btn-xs btn-warning" onclick="openUpdateSapDocModal()">
                        <i class="fas fa-edit"></i> Update SAP Document
                    </button>
                @else
                    Not posted yet
                @endcan
            @endif
        </dd>

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

<!-- SAP Document Update Modal -->
<div class="modal fade" id="updateSapDocModal" tabindex="-1" role="dialog" aria-labelledby="updateSapDocModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateSapDocModalLabel">Update SAP Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="sapDocForm">
                    @csrf
                    <div class="form-group">
                        <label for="sap_doc">SAP Document Number</label>
                        <input type="text" class="form-control" id="sap_doc" name="sap_doc" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="updateSapDoc()">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openUpdateSapDocModal() {
        $('#updateSapDocModal').modal('show');
    }

    function updateSapDoc() {
        const sapDoc = $('#sap_doc').val();
        if (!sapDoc) {
            toastr.error('Please enter SAP Document Number');
            return;
        }

        $.ajax({
            url: '{{ route('documents.invoices.update-sap-doc', $invoice->id) }}',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                sap_doc: sapDoc
            },
            success: function(response) {
                $('#updateSapDocModal').modal('hide');
                toastr.success('SAP Document updated successfully');

                // Update the SAP Document field without refreshing
                const sapDocCell = $('dt:contains("SAP Document")').next('dd');
                sapDocCell.html(': ' + sapDoc);
            },
            error: function(xhr) {
                let errorMessage = 'Failed to update SAP Document';
                if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.sap_doc) {
                    errorMessage = xhr.responseJSON.errors.sap_doc[0];
                }
                toastr.error(errorMessage);
            }
        });
    }
</script>
