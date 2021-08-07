<div id="productCreateModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('vendors.product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="modal-header">
          <h4 class="modal-title">Create a Product</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          @if (!isset($vendor_show))
            <div class="form-group">
              <strong>Vendor:</strong>
              <select class="form-control" name="vendor_id" required>
                <option value="">Select a Vendor</option>

                @foreach ($vendors as $vendor)
                  <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                @endforeach
              </select>

              @if ($errors->has('vendor_id'))
                <div class="alert alert-danger">{{$errors->first('vendor_id')}}</div>
              @endif
            </div>
          @else
            <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
          @endif

          <div class="form-group">
            <strong>Images:</strong>
            <input type="file" name="images[]" value="" multiple>

            @if ($errors->has('images'))
              <div class="alert alert-danger">{{$errors->first('images')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Date of Order:</strong>
            <div class='input-group date' id='date-of-order'>
              <input type='text' class="form-control" name="date_of_order" value="{{ date('Y-m-d H:i') }}" />

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>

            @if ($errors->has('date_of_order'))
              <div class="alert alert-danger">{{$errors->first('date_of_order')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Product Name:</strong>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>

            @if ($errors->has('name'))
              <div class="alert alert-danger">{{$errors->first('name')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Quantity:</strong>
            <input type="number" name="qty" class="form-control" min="1" value="{{ old('qty') ?? '1' }}">

            @if ($errors->has('qty'))
              <div class="alert alert-danger">{{$errors->first('qty')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Price:</strong>
            <input type="number" name="price" class="form-control" value="{{ old('price') }}">

            @if ($errors->has('price'))
              <div class="alert alert-danger">{{$errors->first('price')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Payment Terms:</strong>
            <textarea name="payment_terms" class="form-control" rows="8" cols="80">{{ old('payment_terms') }}</textarea>

            @if ($errors->has('payment_terms'))
              <div class="alert alert-danger">{{$errors->first('payment_terms')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Recurring Type:</strong>
            <select class="form-control" name="recurring_type" required>
              <option value="OneTime" {{ "OneTime" == old('recurring_type') ? 'selected' : '' }}>One Time</option>
              <option value="EveryDay" {{ "EveryDay" == old('recurring_type') ? 'selected' : '' }}>Every Day</option>
              <option value="EveryWeek" {{ "EveryWeek" == old('recurring_type') ? 'selected' : '' }}>Every Week</option>
              <option value="EveryMonth" {{ "EveryMonth" == old('recurring_type') ? 'selected' : '' }}>Every Month</option>
              <option value="EveryYear" {{ "EveryYear" == old('recurring_type') ? 'selected' : '' }}>Every Year</option>
            </select>

            @if ($errors->has('recurring_type'))
              <div class="alert alert-danger">{{$errors->first('recurring_type')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Delivery Date:</strong>
            <div class='input-group date' id='delivery-date'>
              <input type='text' class="form-control" name="delivery_date" value="{{ date('Y-m-d H:i') }}" />

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>

            @if ($errors->has('delivery_date'))
              <div class="alert alert-danger">{{$errors->first('delivery_date')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Received By:</strong>
            <input type="text" name="received_by" class="form-control" value="{{ old('received_by') }}">

            @if ($errors->has('received_by'))
              <div class="alert alert-danger">{{$errors->first('received_by')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Approved By:</strong>
            <input type="text" name="approved_by" class="form-control" value="{{ old('approved_by') }}">

            @if ($errors->has('approved_by'))
              <div class="alert alert-danger">{{$errors->first('approved_by')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Payment Details:</strong>
            <textarea name="payment_details" class="form-control" rows="8" cols="80">{{ old('payment_details') }}</textarea>

            @if ($errors->has('payment_details'))
              <div class="alert alert-danger">{{$errors->first('payment_details')}}</div>
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

<div id="productEditModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h4 class="modal-title">Update a Vendor Product</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          @if (!isset($vendor_show))
            <div class="form-group">
              <strong>Vendor:</strong>
              <select class="form-control" name="vendor_id" id="vendor_vendor_id" required>
                <option value="">Select a Vendor</option>

                @foreach ($vendors as $vendor)
                  <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                @endforeach
              </select>

              @if ($errors->has('vendor_id'))
                <div class="alert alert-danger">{{$errors->first('vendor_id')}}</div>
              @endif
            </div>
          @endif

          <div class="form-group">
            <strong>More Images:</strong>
            <input type="file" name="images[]" value="" multiple>

            @if ($errors->has('images'))
              <div class="alert alert-danger">{{$errors->first('images')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Date of Order:</strong>
            <div class='input-group date' id='vendor-date-of-order'>
              <input type='text' class="form-control" name="date_of_order" id="vendor_date_of_order" value="{{ date('Y-m-d H:i') }}" />

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>

            @if ($errors->has('date_of_order'))
              <div class="alert alert-danger">{{$errors->first('date_of_order')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Product Name:</strong>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="vendor_name" required>

            @if ($errors->has('name'))
              <div class="alert alert-danger">{{$errors->first('name')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Quantity:</strong>
            <input type="number" name="qty" class="form-control" min="1" id="vendor_qty" value="{{ old('qty') ?? '1' }}">

            @if ($errors->has('qty'))
              <div class="alert alert-danger">{{$errors->first('qty')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Price:</strong>
            <input type="number" name="price" class="form-control" value="{{ old('price') }}" id="vendor_price">

            @if ($errors->has('price'))
              <div class="alert alert-danger">{{$errors->first('price')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Payment Terms:</strong>
            <textarea name="payment_terms" class="form-control" rows="8" cols="80" id="vendor_payment_terms">{{ old('payment_terms') }}</textarea>

            @if ($errors->has('payment_terms'))
              <div class="alert alert-danger">{{$errors->first('payment_terms')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Recurring Type:</strong>
            <select class="form-control" name="recurring_type" id="vendor_recurring_type" required>
              <option value="OneTime" {{ "OneTime" == old('recurring_type') ? 'selected' : '' }}>One Time</option>
              <option value="EveryDay" {{ "EveryDay" == old('recurring_type') ? 'selected' : '' }}>Every Day</option>
              <option value="EveryWeek" {{ "EveryWeek" == old('recurring_type') ? 'selected' : '' }}>Every Week</option>
              <option value="EveryMonth" {{ "EveryMonth" == old('recurring_type') ? 'selected' : '' }}>Every Month</option>
              <option value="EveryYear" {{ "EveryYear" == old('recurring_type') ? 'selected' : '' }}>Every Year</option>
            </select>

            @if ($errors->has('recurring_type'))
              <div class="alert alert-danger">{{$errors->first('recurring_type')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Delivery Date:</strong>
            <div class='input-group date' id='vendor-delivery-date'>
              <input type='text' class="form-control" name="delivery_date" id="vendor_delivery_date" value="{{ date('Y-m-d H:i') }}" />

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>

            @if ($errors->has('delivery_date'))
              <div class="alert alert-danger">{{$errors->first('delivery_date')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Received By:</strong>
            <input type="text" name="received_by" class="form-control" id="vendor_received_by" value="{{ old('received_by') }}">

            @if ($errors->has('received_by'))
              <div class="alert alert-danger">{{$errors->first('received_by')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Approved By:</strong>
            <input type="text" name="approved_by" class="form-control" id="vendor_approved_by" value="{{ old('approved_by') }}">

            @if ($errors->has('approved_by'))
              <div class="alert alert-danger">{{$errors->first('approved_by')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Payment Details:</strong>
            <textarea name="payment_details" class="form-control" rows="8" cols="80" id="vendor_payment_details">{{ old('payment_details') }}</textarea>

            @if ($errors->has('payment_details'))
              <div class="alert alert-danger">{{$errors->first('payment_details')}}</div>
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
