<div id="ReplyModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('reply.store') }}" method="POST" enctype="multipart/form-data" id="approvalReplyForm">
        @csrf

        <div class="modal-body">
          <select class="form-control" name="category_id" id="category_id_field">
            @foreach ($reply_categories as $category)
              <option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
          </select>

          <div class="form-group">
              <strong>Quick Reply:</strong>
              <textarea class="form-control" id="reply_field" name="reply" placeholder="Quick Reply" required>{{ old('reply') }}</textarea>
              @if ($errors->has('reply'))
                  <div class="alert alert-danger">{{$errors->first('reply')}}</div>
              @endif
          </div>

          <input type="hidden" name="model" id="model_field" value="">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Create</button>
        </div>
      </form>
    </div>

  </div>
</div>
