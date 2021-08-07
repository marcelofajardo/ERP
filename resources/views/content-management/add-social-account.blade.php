

    <form action="{{ route('content-management.social.submit') }}" method="POST">
            @csrf

            <div class="modal-header">
              <h4 class="modal-title">Create social account</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <strong>Website:</strong>
                <select class="form-control" name="store_website_id" id="" required>
                    <option value="">Select</option>
                    @foreach($websites as $w)
                    <option value="{{$w->id}}">{{$w->title}}</option>
                    @endforeach
                </select>
              </div>

              <div class="form-group">
                <strong>Platform type:</strong>
                <select class="form-control" name="platform" id="" required>
                    <option value="">Select</option>
                    <option value="facebook">Facebook</option>
                    <option value="instagram">Instagram</option>
                </select>
              </div>

              <div class="form-group">
                <strong>URL:</strong>
                <input type="text" name="url" class="form-control" value="{{ old('url') }}" required>
                @if ($errors->has('url'))
                  <div class="alert alert-danger">{{$errors->first('url')}}</div>
                @endif
              </div>

              <div class="form-group">
                <strong>Username:</strong>
                <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>

                @if ($errors->has('username'))
                  <div class="alert alert-danger">{{$errors->first('username')}}</div>
                @endif
              </div>

              <div class="form-group">
                <strong>Password:</strong>
                <input type="text" name="password" class="form-control" value="{{ old('password') }}" required>

                @if ($errors->has('password'))
                  <div class="alert alert-danger">{{$errors->first('password')}}</div>
                @endif
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-secondary">Store</button>
            </div>
          </form>