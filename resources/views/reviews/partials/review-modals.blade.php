<div id="reviewEditModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h4 class="modal-title">Edit a Review</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Posted Date:</strong>
            <div class='input-group date' id='edit_posted_date'>
              <input type='text' class="form-control" name="posted_date" value="{{ date('Y-m-d') }}" />

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>

            @if ($errors->has('posted_date'))
              <div class="alert alert-danger">{{$errors->first('posted_date')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Serial Number</strong>
            <input type="text" name="serial_number" class="form-control" id="edit_review_serial" value="">

            @if ($errors->has('serial_number'))
              <div class="alert alert-danger">{{$errors->first('serial_number')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Review</strong>
            <input type="text" name="review" class="form-control" id="edit_review_review" value="" required>

            @if ($errors->has('review'))
              <div class="alert alert-danger">{{$errors->first('review')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Review Link</strong>
            <input type="text" name="review_link" class="form-control" id="edit_review_link" value="">

            @if ($errors->has('review_link'))
              <div class="alert alert-danger">{{$errors->first('review_link')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Platform:</strong>
            <select class="form-control" name="platform" id="edit_review_platform">
              <option value="">Select a Platform</option>
              <option value="instagram" {{ 'instagram' == old('platform') ? 'selected' : '' }}>Instagram</option>
              <option value="facebook" {{ 'facebook' == old('platform') ? 'selected' : '' }}>Facebook</option>
              <option value="sitejabber" {{ 'sitejabber' == old('platform') ? 'selected' : '' }}>Sitejabber</option>
              <option value="google" {{ 'google' == old('platform') ? 'selected' : '' }}>Google</option>
              <option value="trustpilot" {{ 'trustpilot' == old('platform') ? 'selected' : '' }}>Trustpilot</option>
            </select>

            @if ($errors->has('platform'))
              <div class="alert alert-danger">{{$errors->first('platform')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Account</strong>
            <select class="form-control" name="account_id" id="edit_review_account">
              <option value="">Select Account</option>

              @foreach ($accounts as $account)
                <option value="{{ $account->id }}">{{ $account->email }} ({{ ucwords($account->platform) }})</option>
              @endforeach
            </select>

            @if ($errors->has('account_id'))
              <div class="alert alert-danger">{{$errors->first('account_id')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Customer:</strong>
            <select class="selectpicker form-control" data-live-search="true" data-size="15" name="customer_id" id="edit_customer_id" title="Choose a Customer">
              @foreach ($customers as $customer)
                <option data-tokens="{{ $customer['name'] }} {{ $customer['email'] }}  {{ $customer['phone'] }} {{ $customer['instahandler'] }}" value="{{ $customer['id'] }}">{{ $customer['id'] }} - {{ $customer['name'] }} - {{ $customer['phone'] }}</option>
              @endforeach
            </select>

            @if ($errors->has('customer_id'))
                <div class="alert alert-danger">{{$errors->first('customer_id')}}</div>
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
