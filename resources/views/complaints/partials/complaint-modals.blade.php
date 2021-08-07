<div id="complaintCreateModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('complaint.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="thread_type" value="complaint">

        <div class="modal-header">
          <h4 class="modal-title">Create a Thread</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Customer:</strong>
            <select class="selectpicker form-control" data-live-search="true" data-size="15" name="customer_id" title="Choose a Customer" required>
              @foreach ($customers as $customer)
                <option data-tokens="{{ $customer['name'] }} {{ $customer['email'] }}  {{ $customer['phone'] }} {{ $customer['instahandler'] }}" value="{{ $customer['id'] }}">{{ $customer['id'] }} - {{ $customer['name'] }} - {{ $customer['phone'] }}</option>
              @endforeach
            </select>

            @if ($errors->has('customer_id'))
                <div class="alert alert-danger">{{$errors->first('customer_id')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Date:</strong>
            <div class='input-group date' id='complaint_date'>
              <input type='text' class="form-control" name="date" value="{{ date('Y-m-d') }}" required />

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>

            @if ($errors->has('date'))
              <div class="alert alert-danger">{{$errors->first('date')}}</div>
            @endif
          </div>

          <div class="form-group">
            <label for="platform">Platform:</label>
            <select required class="form-control" name="platform" id="platform">
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

          <div id="complaint-container">
            <div class="form-group">
              <strong>Complaint</strong>

              <textarea name="complaint" class="form-control" rows="8" cols="80" required>{{ old('complaint') }}</textarea>

              @if ($errors->has('complaint'))
                <div class="alert alert-danger">{{$errors->first('complaint')}}</div>
              @endif
            </div>
          </div>

          <div class="form-group">
            <input type="file" name="images[]" multiple />

            @if ($errors->has('images'))
              <div class="alert alert-danger">{{$errors->first('images')}}</div>
            @endif
          </div>

          <button type="button" class="btn btn-xs btn-secondary" id="add-complaint-button">Add Thread</button>

          <div class="form-group">
            <strong>Link</strong>

            <input type="text" name="link" class="form-control" value="{{ old('link') }}" required>

            @if ($errors->has('link'))
              <div class="alert alert-danger">{{$errors->first('link')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Where was written</strong>

            <input type="text" name="where" class="form-control" value="{{ old('where') }}">

            @if ($errors->has('where'))
              <div class="alert alert-danger">{{$errors->first('where')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Username</strong>

            <input type="text" name="username" class="form-control" value="{{ old('username') }}">

            @if ($errors->has('username'))
              <div class="alert alert-danger">{{$errors->first('username')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Name</strong>

            <input type="text" name="name" class="form-control" value="{{ old('name') }}">

            @if ($errors->has('name'))
              <div class="alert alert-danger">{{$errors->first('name')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Plan of Action</strong>

            <textarea name="plan_of_action" class="form-control" rows="8" cols="80">{{ old('plan_of_action') }}</textarea>

            @if ($errors->has('plan_of_action'))
              <div class="alert alert-danger">{{$errors->first('plan_of_action')}}</div>
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

<div id="complaintEditModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h4 class="modal-title">Update a Thread</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Customer:</strong>
            <select class="selectpicker form-control" data-live-search="true" data-size="15" id="complaint_customer_id" name="customer_id" title="Choose a Customer" required>
              @foreach ($customers as $customer)
                <option data-tokens="{{ $customer['name'] }} {{ $customer['email'] }}  {{ $customer['phone'] }} {{ $customer['instahandler'] }}" value="{{ $customer['id'] }}">{{ $customer['id'] }} - {{ $customer['name'] }} - {{ $customer['phone'] }}</option>
              @endforeach
            </select>

            @if ($errors->has('customer_id'))
                <div class="alert alert-danger">{{$errors->first('customer_id')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Date:</strong>
            <div class='input-group date' id='edit_complaint_date'>
              <input type='text' class="form-control" name="date" value="{{ date('Y-m-d') }}" required />

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>

            @if ($errors->has('date'))
              <div class="alert alert-danger">{{$errors->first('date')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Platform:</strong>
            <select class="form-control" name="platform" id="complaint_platform">
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

          <div id="edit-complaint-container">
            <div class="form-group">
              <strong>Complaint</strong>

              <textarea name="complaint" class="form-control" rows="8" cols="80" id="complaint_complaint" required>{{ old('complaint') }}</textarea>

              @if ($errors->has('complaint'))
                <div class="alert alert-danger">{{$errors->first('complaint')}}</div>
              @endif
            </div>

            <div id="complaint-container-extra"></div>
          </div>

          <div class="form-group">
            <input type="file" name="images[]" multiple />

            @if ($errors->has('images'))
              <div class="alert alert-danger">{{$errors->first('images')}}</div>
            @endif
          </div>

          <button type="button" class="btn btn-xs btn-secondary" id="add-edit-complaint-button">Add Thread</button>

          <div class="form-group">
            <strong>Link</strong>

            <input type="text" name="link" class="form-control" id="complaint_link" value="{{ old('link') }}">

            @if ($errors->has('link'))
              <div class="alert alert-danger">{{$errors->first('link')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Where was written</strong>

            <input type="text" name="where" class="form-control" value="{{ old('where') }}" id="complaint_where">

            @if ($errors->has('where'))
              <div class="alert alert-danger">{{$errors->first('where')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Username</strong>

            <input type="text" name="username" class="form-control" value="{{ old('username') }}" id="complaint_username">

            @if ($errors->has('username'))
              <div class="alert alert-danger">{{$errors->first('username')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Name</strong>

            <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="complaint_name">

            @if ($errors->has('name'))
              <div class="alert alert-danger">{{$errors->first('name')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Plan of Action</strong>

            <textarea name="plan_of_action" class="form-control" rows="8" cols="80" id="edit_plan_of_action">{{ old('plan_of_action') }}</textarea>

            @if ($errors->has('plan_of_action'))
              <div class="alert alert-danger">{{$errors->first('plan_of_action')}}</div>
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
