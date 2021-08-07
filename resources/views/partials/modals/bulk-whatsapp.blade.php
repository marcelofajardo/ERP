<div id="bulkWhatsappModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('dubbizle.bulk.whatsapp') }}" method="POST">
        @csrf

        <div class="modal-header">
          <h4 class="modal-title">Send Message in Bulk</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Group:</strong>
            <select class="form-control" name="group" required>
              <option value="">Select Group</option>
              @foreach ($keywords as $keyword => $items)
                <option value="{{ $keyword }}" {{ $keyword == old('group') ? 'selected' : '' }}>{{ $keyword }}</option>
              @endforeach
            </select>

            @if ($errors->has('group'))
              <div class="alert alert-danger">{{$errors->first('group')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Message:</strong>
            <textarea class="form-control" name="message" placeholder="Text Message" required>{{ old('message') }}</textarea>
            @if ($errors->has('message'))
              <div class="alert alert-danger">{{$errors->first('message')}}</div>
            @endif
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
