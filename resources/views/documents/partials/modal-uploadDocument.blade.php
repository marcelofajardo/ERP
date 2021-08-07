<div id="uploadDocumentModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Documents</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="{{ route('document.uploadDocument') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <input type="file" name="files" required />
                        @if ($errors->has('files'))
                            <div class="alert alert-danger">{{ $errors->first('files') }}</div>
                        @endif
                    </div>
                    <input type="hidden" name="document_id" id="document_upload_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
