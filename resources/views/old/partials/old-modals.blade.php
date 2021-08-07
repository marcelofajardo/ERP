<div id="oldCreateModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('old.store') }}" method="POST">
        @csrf

        <div class="modal-header">
          <h4 class="modal-title">Store a Old Vendor</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Category:</strong>
            <select class="form-control" name="category_id" required>
              <option value="">Select a Category</option>

              @foreach ($old_categories as $category)
                <option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>{{ $category->category }}</option>
              @endforeach
            </select>

            @if ($errors->has('category_id'))
              <div class="alert alert-danger">{{$errors->first('category_id')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Name:</strong>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>

            @if ($errors->has('name'))
              <div class="alert alert-danger">{{$errors->first('name')}}</div>
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
            <strong>Phone:</strong>
            <input type="number" name="phone" class="form-control" value="{{ old('phone') }}">

            @if ($errors->has('phone'))
              <div class="alert alert-danger">{{$errors->first('phone')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Email:</strong>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">

            @if ($errors->has('email'))
              <div class="alert alert-danger">{{$errors->first('email')}}</div>
            @endif
          </div>

          

          <div class="form-group">
            <strong>GST:</strong>
            <input type="text" name="gst" class="form-control" value="{{ old('gst') }}">

            @if ($errors->has('gst'))
              <div class="alert alert-danger">{{$errors->first('gst')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Account Name:</strong>
            <input type="text" name="account_name" class="form-control" value="{{ old('account_name') }}">

            @if ($errors->has('account_name'))
              <div class="alert alert-danger">{{$errors->first('account_name')}}</div>
            @endif
          </div>


          <div class="form-group">
            <strong>Account Number:</strong>
            <input type="text" name="account_number" class="form-control" value="{{ old('account_number') }}">

            @if ($errors->has('account_number'))
              <div class="alert alert-danger">{{$errors->first('account_number')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>IBAN:</strong>
            <input type="text" name="account_iban" class="form-control" value="{{ old('account_iban') }}">

            @if ($errors->has('account_iban'))
              <div class="alert alert-danger">{{$errors->first('account_iban')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>SWIFT:</strong>
            <input type="text" name="account_swift" class="form-control" value="{{ old('account_swift') }}">

            @if ($errors->has('account_swift'))
              <div class="alert alert-danger">{{$errors->first('account_swift')}}</div>
            @endif
          </div>
          <div class="form-group">
            <strong>Total Payment:</strong>
            <input type="integer" name="amount" class="form-control" value="{{ old('amount') }}" required>

            @if ($errors->has('amount'))
              <div class="alert alert-danger">{{$errors->first('amount')}}</div>
            @endif
          </div>
          <div class="form-group">
            <strong>Pending Payment:</strong>
            <input type="integer" name="pending_payment" class="form-control" value="{{ old('pending_payment') }}" required>

            @if ($errors->has('pending_payment'))
              <div class="alert alert-danger">{{$errors->first('pending_payment')}}</div>
            @endif
          </div>
          <div class="form-group">
            <strong>Currency:</strong>
            <select class="form-control" name="currency">
              <option value="EUR">EUR</option>
              <option value="Dollar">Dollar</option>
              <option value="Rupees">Rupees</option>
            </select>
          </div>
          <div class="form-group">
            <strong>Description:</strong>
            <input type="text" name="description" class="form-control" value="{{ old('description') }}" required>

            @if ($errors->has('description'))
              <div class="alert alert-danger">{{$errors->first('description')}}</div>
            @endif
          </div>
            @if($type == 1)
              <input type="hidden" name="is_payable" value="1">
            @elseif($type == 0) 
              <input type="hidden" name="is_payable" value="0"> 
            @else
            <div class="form-group">
            <strong>Payment Type:</strong>
            <select name="is_payable" class="form-control">
              <option value="0">Pending Incoming Payment</option>
               <option  value="1">Pending Outgoing Payment</option>
            </select>  
            @if ($errors->has('is_payable'))
              <div class="alert alert-danger">{{$errors->first('is_payable')}}</div>
            @endif
          </div>

            @endif


             <div class="form-group">
         <strong>Select Status:</strong>   
        {!! Form::select('status', $status, null, ['class' => 'form-control'.($errors->has('status') ? ' is-invalid' : ''), 'placeholder' => 'Select Status','required' => '']) !!}
        @if ($errors->has('status'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('status') }}</strong>
                    </span>
                @endif
            </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Add</button>
        </div>
      </form>
    </div>

  </div>
</div>

<div id="oldEditModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h4 class="modal-title">Update Old Vendor</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Category:</strong>
            <select class="form-control" name="category_id" id="old_category" required>
              <option value="">Select a Category</option>

              @foreach ($old_categories as $category)
                <option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>{{ $category->category }}</option>
              @endforeach
            </select>

            @if ($errors->has('category_id'))
              <div class="alert alert-danger">{{$errors->first('category_id')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Name:</strong>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required id="old_name">

            @if ($errors->has('name'))
              <div class="alert alert-danger">{{$errors->first('name')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Address:</strong>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}" id="old_address">

            @if ($errors->has('address'))
              <div class="alert alert-danger">{{$errors->first('address')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Phone:</strong>
            <input type="number" name="phone" class="form-control" value="{{ old('phone') }}" id="old_phone" required>

            @if ($errors->has('phone'))
              <div class="alert alert-danger">{{$errors->first('phone')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Email:</strong>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" id="old_email" required>

            @if ($errors->has('email'))
              <div class="alert alert-danger">{{$errors->first('email')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>GST:</strong>
            <input type="text" name="gst" class="form-control" value="{{ old('gst') }}" id="old_gst">

            @if ($errors->has('gst'))
              <div class="alert alert-danger">{{$errors->first('gst')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Account Name:</strong>
            <input type="text" name="account_name" class="form-control" value="{{ old('account_name') }}" id="old_account_name">

            @if ($errors->has('account_name'))
              <div class="alert alert-danger">{{$errors->first('account_name')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Account Number:</strong>
            <input type="text" name="account_number" class="form-control" value="{{ old('account_number') }}" id="old_account_number">

            @if ($errors->has('account_number'))
              <div class="alert alert-danger">{{$errors->first('account_number')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>IBAN:</strong>
            <input type="text" name="account_iban" class="form-control" value="{{ old('account_iban') }}" id="old_account_iban">

            @if ($errors->has('account_iban'))
              <div class="alert alert-danger">{{$errors->first('account_iban')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>SWIFT:</strong>
            <input type="text" name="account_swift" class="form-control" value="{{ old('account_swift') }}" id="old_account_swift">

            @if ($errors->has('account_swift'))
              <div class="alert alert-danger">{{$errors->first('account_swift')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Total Amount:</strong>
            <input type="integer" name="amount" class="form-control" value="{{ old('amount') }}" required id="old_amount">

            @if ($errors->has('amount'))
              <div class="alert alert-danger">{{$errors->first('amount')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Pending Payment:</strong>
            <input type="text" name="pending_payment" class="form-control" value="{{ old('pending_payment') }}" id="pending_payment">

            @if ($errors->has('account_swift'))
              <div class="alert alert-danger">{{$errors->first('pending_payment')}}</div>
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
