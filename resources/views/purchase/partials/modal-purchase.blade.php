<div id="sendExportModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Send Export</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('purchase.send.export') }}" id="" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="modal-body">
          <div class="form-group">
            <select class="form-control" name="supplier_id" id="export_supplier" required>
              <option value="">Select Supplier</option>

              @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <select class="form-control" name="agent_id[]" id="export_agent" multiple required>
              <option value="">Select Agent</option>

            </select>
          </div>

          <div class="form-group">
            <strong>Subject</strong>
            <input type="text" name="subject" class="form-control" value="{{ old('subject') ?? 'Purchase Export Generated' }}" required>
          </div>

          <div class="form-group">
            <strong>Message</strong>
            <textarea name="message" class="form-control" rows="8" cols="80" required>{{ old('message') }}</textarea>
          </div>

          <div class="form-group">
            <strong>Attachment</strong>
            <input type="file" name="file" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Send</button>
        </div>
      </form>
    </div>

  </div>
</div>
