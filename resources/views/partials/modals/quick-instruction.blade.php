<div id="quickInstructionModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('instruction.store') }}" method="POST" id="quickInstructionForm">
        @csrf
        <input type="hidden" name="category_id" value="1">

        <div class="modal-header">
          <h4 class="modal-title">Create Instruction</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            @php
              // $quick_users_array = \App\Helpers::getUserArray(\App\User::all());
            @endphp

            <strong>Assign to:</strong>
            <select class="form-control globalSelect2"  data-ajax="{{ route('select2.user') }}" data-live-search="true" data-size="15" name="assigned_to" data-placeholder="Choose a User" id="quick_instruction_assiged_to" required>
              <option></option>
              {{-- @foreach ($quick_users_array as $index => $user)
               <option data-tokens="{{ $index }} {{ $user }}" value="{{ $index }}">{{ $user }}</option>
             @endforeach --}}
           </select>

            @if ($errors->has('assigned_to'))
                <div class="alert alert-danger">{{$errors->first('assigned_to')}}</div>
            @endif
          </div>

          <div class="form-group">
            @php
              // $quick_customers_array = \App\Customer::select(['id', 'name', 'email'])->get();
            @endphp

            <strong>Customer:</strong>
            <select class="globalSelect2 form-control" data-ajax="{{ route('select2.customer') }}"  data-live-search="true" data-size="15" name="customer_id" data-placeholder="Choose a Customer" id="quick_instruction_customer_id" required>
              <option></option>
              {{-- @foreach ($quick_customers_array as $index => $customer)
               <option data-tokens="{{ $customer->id }} {{ $customer->name }} {{ $customer->email }}" value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->email }}</option>
             @endforeach --}}
           </select>

            @if ($errors->has('assigned_to'))
                <div class="alert alert-danger">{{$errors->first('assigned_to')}}</div>
            @endif
          </div>

          <div class="form-group">
            <input type="checkbox" name="is_priority" id="quickInstructionPriority">
            <label for="quickInstructionPriority">Priority</label>
          </div>

          <div class="form-group">
            <strong>Instruction:</strong>
            <textarea type="text" class="form-control" id="quick_instruction_body" name="instruction" placeholder="Instructions" required>{{ old('instruction') }}</textarea>
            @if ($errors->has('instruction'))
                <div class="alert alert-danger">{{$errors->first('instruction')}}</div>
            @endif
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary" id="quickInstructionSubmit">Add</button>
        </div>
      </form>
    </div>

  </div>
</div>
