<div id="instructionModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('instruction.store') }}" method="POST">
        @csrf
        <input type="hidden" name="customer_id" value="{{ $customer->id }}">

        <div class="modal-header">
          <h4 class="modal-title">Create Instruction</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
              <strong>Assign to:</strong>
              <select class="selectpicker form-control" data-live-search="true" data-size="15" name="assigned_to" title="Choose a User" id="instruction_user_id" required>
                @foreach ($users_array as $index => $user)
                 <option data-tokens="{{ $index }} {{ $user }}" value="{{ $index }}">{{ $user }}</option>
               @endforeach
             </select>

              @if ($errors->has('assigned_to'))
                  <div class="alert alert-danger">{{$errors->first('assigned_to')}}</div>
              @endif
          </div>

          <div class="form-group">
            <strong>Category:</strong>
            <select class="form-control" name="category_id" id="instruction_category_id" required>
              @foreach ($instruction_categories as $category)
                <option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>{{ $category->name }}</option>
              @endforeach
            </select>
            @if ($errors->has('category_id'))
                <div class="alert alert-danger">{{$errors->first('category_id')}}</div>
            @endif
          </div>

          <div class="form-group">
            <input type="checkbox" name="is_priority" id="instructionPriority">
            <label for="instructionPriority">Priority</label>
          </div>

          <div class="form-group">
            <strong>Instruction:</strong>
            <textarea type="text" class="form-control" id="instruction-body" name="instruction" placeholder="Instructions" required>{{ old('instruction') }}</textarea>
            @if ($errors->has('instruction'))
                <div class="alert alert-danger">{{$errors->first('instruction')}}</div>
            @endif
          </div>

          <div class="form-group">
            <input type="checkbox" name="send_whatsapp" id="sendWhatsappCheckbox">
            <label for="sendWhatsappCheckbox">Send with Whatsapp</label>
          </div>

          <hr>

          <div class="form-group">
            <select name="quickComment" id="instructionComment" class="form-control">
              <option value="">Quick Reply</option>
              @foreach ($instruction_replies as $reply)
                <option value="{{ $reply->reply }}">{{ $reply->reply }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <textarea class="form-control" id="instruction_reply_field" name="reply" placeholder="Quick Reply">{{ old('reply') }}</textarea>
            <button type="button" class="btn btn-xs btn-secondary mt-3" id="createInstructionReplyButton">Create Quick Reply</button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary" id="instructionCreateButton">Create</button>
        </div>
      </form>
    </div>

  </div>
</div>
