<div class="modal fade" id="uploadAttachmentsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Attachments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="uploadAttachmentsForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="attachments">Select Files</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="attachments" name="attachments[]"
                                multiple accept=".pdf,.jpg,.jpeg,.png,.gif">
                            <label class="custom-file-label" for="attachments">Choose files</label>
                        </div>
                        <small class="form-text text-muted">
                            You can select multiple files. Allowed types: PDF, JPG, JPEG, PNG, GIF
                        </small>
                    </div>
                    <div id="selected-files" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary">Upload Files</button>
                </div>
            </form>
        </div>
    </div>
</div>
