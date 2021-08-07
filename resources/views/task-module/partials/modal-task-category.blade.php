<div id="createTaskCategorytModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create Task Category</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('task_category.store') }}" method="POST">
        @csrf

        <div class="modal-body">
          <div class="form-group">
            <input type="text" name="title" value="{{ old('title') }}" class="form-control input-sm" placeholder="Category Name">
          </div>

          <div class="d-flex">
            <div class="form-group flex-fill">
              <select class="form-control input-sm" name="parent_id" id="task_category_selection">
                <option value="">Select Category</option>

                @foreach ($task_categories as $category)
                  <option value="{{ $category->id }}" data-approved="{{ $category->is_approved == 1 ? 'true' : 'false' }}">{{ $category->title }} {{ $category->is_approved == 0 ? ' - Unapproved' : '' }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group hidden">
              <button type="button" class="btn btn-xs btn-secondary" id="approveTaskCategoryButton" data-id="">Approve</button>
            </div>

            <div class="form-group">
              <button type="button" class="btn btn-image" id="deleteTaskCategoryButton" data-id=""><img src="/images/delete.png" /></button>
            </div>
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
