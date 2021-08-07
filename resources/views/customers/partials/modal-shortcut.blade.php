<div id="shortcutModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Shortcut Modal</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('instruction.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="customer_id" value="" id="customer_id_field">
                <input type="hidden" name="instruction" value="" id="instruction_field">
                <input type="hidden" name="category_id" value="1">

                <div class="modal-body">
                  <div class="form-group">
                      <strong>Assign to:</strong>
                      <select class="selectpicker form-control" data-live-search="true" data-size="15" name="assigned_to" title="Choose a User" required>
                        @foreach ($users_array as $index => $user)
                         <option data-tokens="{{ $index }} {{ $user }}" value="{{ $index }}">{{ $user }}</option>
                       @endforeach
                     </select>

                      @if ($errors->has('assigned_to'))
                          <div class="alert alert-danger">{{$errors->first('assigned_to')}}</div>
                      @endif
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary">Create Instruction</button>
                </div>
            </form>
        </div>

    </div>
</div>
