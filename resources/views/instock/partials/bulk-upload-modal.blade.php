<div id="updateBulkProductModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Update Bulk</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('products.bulk.update') }}" method="POST" enctype="multipart/form-data">


      <div class="modal-body">
        @csrf
        <input type="hidden" name="selected_products" id="selected_products" value="">

        <div class="form-group">
            <strong>Category:</strong>
            {!! $category_selection !!}
            @if ($errors->has('category'))
                <div class="alert alert-danger">{{$errors->first('category')}}</div>
            @endif
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-secondary" id="bulkUpdateButton">Update</button>
      </div>
      </form>
    </div>

  </div>
</div>
