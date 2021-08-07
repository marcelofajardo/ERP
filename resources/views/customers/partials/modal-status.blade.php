<div id="statusModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create Action</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('status.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="modal-body">
          <div class="form-group">
              <strong>Order Status:</strong>
               <input type="text" class="form-control" name="status" placeholder="Order Status" id="status" required />
               @if ($errors->has('status'))
                   <div class="alert alert-danger">{{$errors->first('status')}}</div>
               @endif
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
