@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
@endsection

        {!! Form::open(array('route' => 'voucher.payment.request-submit','method'=>'POST')) !!}

      <div class="modal-header">
          <h4 class="modal-title"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">

        <div class="form-group">
            <strong>User:</strong>
            <select class="form-control select-multiple" name="user_id" id="user-select">
                <option value="">Select User</option>
                @foreach($users as $key => $user)
                <option value="{{ $user->id }}" {{$user->id == Auth::user()->id ? 'selected' : ''}}>{{ $user->name }}</option>
                @endforeach
            </select>
            @if ($errors->has('user_id'))
              <div class="alert alert-danger">{{$errors->first('user_id')}}</div>
            @endif
          </div>


        <div class="form-group">
          <strong>Date :</strong>
          <div class='input-group date' id='date_of_request'>
            <input type='text' class="form-control" name="date" value="{{ date('Y-m-d') }}" />

            <span class="input-group-addon">
              <span class="glyphicon glyphicon-calendar"></span>
            </span>
          </div>
          <br>
          <div class="form-group">
            <strong>Time spent (In minutes):</strong>
            <input type="number" name="worked_minutes" class="form-control" value="{{ old('worked_minutes') }}">

            @if ($errors->has('worked_minutes'))
              <div class="alert alert-danger">{{$errors->first('worked_minutes')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Amount:</strong>
            <input type="number" name="amount" class="form-control" value="{{ old('amount') }}" required>

            @if ($errors->has('amount'))
              <div class="alert alert-danger">{{$errors->first('amount')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Currency:</strong>
            <input type="text" name="currency" class="form-control" value="{{ old('currency') }}" required>

            @if ($errors->has('currency'))
              <div class="alert alert-danger">{{$errors->first('currency')}}</div>
            @endif
          </div>


          @if ($errors->has('date'))
              <div class="alert alert-danger">{{$errors->first('date')}}</div>
          @endif
        </div>

        <div class="form-group">
          <strong>Details:</strong>
          <textarea name="remarks" rows="4" cols="80" class="form-control">{{ old('remarks') }}</textarea>

          @if ($errors->has('remarks'))
              <div class="alert alert-danger">{{$errors->first('remarks')}}</div>
          @endif
        </div>

        <!--button type="submit" class="btn btn-secondary">Create</button-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger">Create</button>
      </div>
        {!! Form::close() !!}
      

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>

  <script>
    $('#date_of_request').datetimepicker({
      format: 'YYYY-MM-DD'
    });

    $('.select-multiple').select2({width: '100%'});
  </script>
@endsection
