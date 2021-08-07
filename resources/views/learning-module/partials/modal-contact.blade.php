<div id="createQuickContactModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create Quick Contact</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('contact.store') }}" method="POST">
        @csrf

        <div class="modal-body">
          <div class="form-inline">
            <div class="form-group flex-fill d-flex">
              <input type="text" name="category" class="form-control input-sm flex-fill" placeholder="Category" value="{{ old('category') }}">
            </div>

            <div class="form-group flex-fill d-flex ml-1">
              <input type="text" name="name" class="form-control input-sm flex-fill" placeholder="Contact Name" value="{{ old('name') }}" required>
            </div>
          </div>

          <div class="form-group mt-1">
            <input type="text" name="phone" class="form-control input-sm" placeholder="Contact Phone" value="{{ old('phone') }}" required>
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
