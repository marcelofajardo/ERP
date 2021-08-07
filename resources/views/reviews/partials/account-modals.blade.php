<div id="accountCreateModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('account.store') }}" method="POST">
        @csrf

        <div class="modal-header">
          <h4 class="modal-title">Create an Account</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>First Name:</strong>
            <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}">

            @if ($errors->has('first_name'))
              <div class="alert alert-danger">{{$errors->first('first_name')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Last Name:</strong>
            <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}">

            @if ($errors->has('last_name'))
              <div class="alert alert-danger">{{$errors->first('last_name')}}</div>
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
            <strong>Country</strong>
            <select name="country" class="form-control" id="country_acc_create">
              <option value="">Any</option>
              @foreach($countries as $country)
                <option value="{{$country->region}}">{{$country->region}}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <strong>Gender</strong>
            <select class="form-control" name="gender" id="gender">
              <option value="all">Any</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
          </div>

          <div class="form-group">
            <strong>Password:</strong>
            <input type="text" name="password" class="form-control" value="{{ old('password') }}" required>

            @if ($errors->has('password'))
              <div class="alert alert-danger">{{$errors->first('password')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Platform:</strong>
            <select class="form-control" name="platform" required>
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
            <strong>Number of Followers:</strong>
            <input type="number" name="followers_count" class="form-control" value="{{ old('followers_count') }}">

            @if ($errors->has('followers_count'))
              <div class="alert alert-danger">{{$errors->first('followers_count')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Number of Posts:</strong>
            <input type="number" name="posts_count" class="form-control" value="{{ old('posts_count') }}">

            @if ($errors->has('posts_count'))
              <div class="alert alert-danger">{{$errors->first('posts_count')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Number of DP:</strong>
            <input type="number" name="dp_count" class="form-control" value="{{ old('dp_count') }}">

            @if ($errors->has('dp_count'))
              <div class="alert alert-danger">{{$errors->first('dp_count')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Date of Birth:</strong>
            <div class='input-group date' id='birthday-datetime'>
              <input type='text' class="form-control" name="dob" value="{{ date('Y-m-d') }}" />

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>

            @if ($errors->has('dob'))
              <div class="alert alert-danger">{{$errors->first('dob')}}</div>
            @endif
          </div>
          <div class="form-group">
            <label for="broadcast">User for broadcast?</label>
            <input type="checkbox" name="broadcast" id="broadcast">
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

<div id="accountEditModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h4 class="modal-title">Update an Account</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>First Name:</strong>
            <input type="text" name="first_name" class="form-control" value="" id="account_first_name">

            @if ($errors->has('first_name'))
              <div class="alert alert-danger">{{$errors->first('first_name')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Last Name:</strong>
            <input type="text" name="last_name" class="form-control" value="" id="account_last_name">

            @if ($errors->has('last_name'))
              <div class="alert alert-danger">{{$errors->first('last_name')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Email:</strong>
            <input type="email" name="email" class="form-control" value="" id="account_email">

            @if ($errors->has('email'))
              <div class="alert alert-danger">{{$errors->first('email')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Password:</strong>
            <input type="text" name="password" class="form-control" value="" id="account_password" required>

            @if ($errors->has('password'))
              <div class="alert alert-danger">{{$errors->first('password')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Platform:</strong>
            <select class="form-control" name="platform" required id="account_platform">
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
            <strong>Country</strong>
            <select class="form-control" name="country" id="country_edit">
              @foreach($countries as $country)
                <option value="{{$country->region}}">{{$country->region}}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <strong>Gender</strong>
            <select class="form-control" name="gender" id="gender_edit">
              <option value="all">All</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
          </div>

          <div class="form-group">
            <strong>Number of Followers:</strong>
            <input type="number" name="followers_count" class="form-control" value="{{ old('followers_count') }}" id="account_followers">

            @if ($errors->has('followers_count'))
              <div class="alert alert-danger">{{$errors->first('followers_count')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Number of Posts:</strong>
            <input type="number" name="posts_count" class="form-control" value="{{ old('posts_count') }}" id="account_posts">

            @if ($errors->has('posts_count'))
              <div class="alert alert-danger">{{$errors->first('posts_count')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Number of DP:</strong>
            <input type="number" name="dp_count" class="form-control" value="{{ old('dp_count') }}" id="account_dp">

            @if ($errors->has('dp_count'))
              <div class="alert alert-danger">{{$errors->first('dp_count')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Date of Birth:</strong>
            <div class='input-group date' id='account_birthday'>
              <input type='text' class="form-control" name="dob" value="{{ date('Y-m-d') }}" />

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>

            @if ($errors->has('dob'))
              <div class="alert alert-danger">{{$errors->first('dob')}}</div>
            @endif
          </div>
          <div class="form-group">
            <label for="broadcast">User for broadcast?</label>
            <input type="checkbox" name="broadcast" id="broadcast2" value="1" >
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
