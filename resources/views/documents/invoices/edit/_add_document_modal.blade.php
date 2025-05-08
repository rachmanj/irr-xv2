<div class="modal fade" id="addDocumentModal" tabindex="-1" role="dialog" aria-labelledby="addDocumentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDocumentModalLabel">Add New Additional Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addDocumentForm">
                @csrf
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="document_type">Document Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="document_type" name="type_id" required>
                                    <option value="">Select Document Type</option>
                                    @foreach ($documentTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="document_number">Document Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="document_number" name="document_number"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="po_no">PO Number</label>
                                <input type="text" class="form-control" id="po_no" name="po_no">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="document_date">Document Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="document_date" name="document_date"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="receive_date">Receive Date</label>
                                <input type="date" class="form-control" id="receive_date" name="receive_date">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <input type="text" class="form-control" id="remarks" name="remarks">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary">Save Document</button>
                </div>
            </form>
        </div>
    </div>
</div>
