<div id="createVoucherCategorytModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create Voucher Category</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('voucher.store.category') }}" method="POST">
        @csrf

        <div class="modal-body">
          <div class="form-group">
            <input type="text" name="title" value="{{ old('title') }}" class="form-control input-sm" placeholder="Category Name">
          </div>

          <div class="form-group">
            <select class="form-control input-sm" name="parent_id">
              <option value="">Select Category</option>

              @foreach ($voucher_categories as $category)
                <option value="{{ $category->id }}">{{ $category->title }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <input type="text" name="subcategory" value="{{ old('subcategory') }}" class="form-control input-sm" placeholder="Sub Category Name">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Create</button>
        </div>
      </form>
    </div>

  </div>
</div>
