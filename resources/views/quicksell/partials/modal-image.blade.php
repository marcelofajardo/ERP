<div id="imageModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Upload Images</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('quicksell.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="modal-body text-left">
          <div class="form-group">
            <input type="file" name="images[]" multiple required />
            @if ($errors->has('images'))
            <div class="alert alert-danger">{{$errors->first('images')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>SKU:</strong>
            <input type="text" name="sku" class="form-control" />
            @if ($errors->has('sku'))
            <div class="alert alert-danger">{{$errors->first('sku')}}</div>
            @endif
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Upload</button>
        </div>
      </form>
    </div>

  </div>
</div>
