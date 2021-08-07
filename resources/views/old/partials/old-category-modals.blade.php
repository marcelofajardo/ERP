<div id="createOldCategorytModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create Old Category</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('old.category.create') }}" method="POST">
        @csrf

        <div class="modal-body">
          <div class="form-group">
            <input type="text" name="category" value="{{ old('category') }}" class="form-control input-sm" placeholder="Category Name">
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
