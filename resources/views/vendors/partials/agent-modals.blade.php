<div id="createAgentModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('agent.store') }}" method="POST">
        @csrf
        <input type="hidden" name="model_id" id="agent_vendor_id" value="{{ $vendor->id }}">
        <input type="hidden" name="model_type" value="App\Vendor">

        <div class="modal-header">
          <h4 class="modal-title">Create Vendor Agent</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Agent Name:</strong>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>

            @if ($errors->has('name'))
              <div class="alert alert-danger">{{$errors->first('name')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Agent Phone:</strong>
            <input type="number" name="phone" class="form-control" value="{{ old('phone') }}">

            @if ($errors->has('phone'))
              <div class="alert alert-danger">{{$errors->first('phone')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Address:</strong>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}">

            @if ($errors->has('address'))
              <div class="alert alert-danger">{{$errors->first('address')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Email:</strong>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">

            @if ($errors->has('email'))
              <div class="alert alert-danger">{{$errors->first('email')}}</div>
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

<div id="editAgentModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h4 class="modal-title">Update Supplier Agent</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Agent Name:</strong>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="agent_name" required>

            @if ($errors->has('name'))
              <div class="alert alert-danger">{{$errors->first('name')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Agent Phone:</strong>
            <input type="number" name="phone" class="form-control" value="{{ old('phone') }}" id="agent_phone">

            @if ($errors->has('phone'))
              <div class="alert alert-danger">{{$errors->first('phone')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Address:</strong>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}" id="agent_address">

            @if ($errors->has('address'))
              <div class="alert alert-danger">{{$errors->first('address')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Email:</strong>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" id="agent_email">

            @if ($errors->has('email'))
              <div class="alert alert-danger">{{$errors->first('email')}}</div>
            @endif
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Update</button>
        </div>
      </form>
    </div>

  </div>
</div>
