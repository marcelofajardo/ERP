<div id="editCategory{{ $category->id }}" class="modal fade" role="dialog">
  <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Category</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <strong>Name:</strong>
              <input type="text" name="composition" class="form-control" value="{{ $category->title }}" id="category-name{{ $category->id }}">
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-secondary" onclick="submitCategoryChange({{ $category->id }})">Edit</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>