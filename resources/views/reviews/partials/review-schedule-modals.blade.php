<div id="scheduleReviewModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('review.schedule.store') }}" method="POST">
        @csrf

        <div class="modal-header">
          <h4 class="modal-title">Schedule a Review</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Date:</strong>
            <div class='input-group date' id='review_date'>
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
            <select class="form-control" name="platform">
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

          {{-- <div class="form-group">
            <strong>Number of Reviews:</strong>
            <select class="form-control" name="review_count">
              <option value="">Select a Number</option>
              <option value="1" {{ '1' == old('review_count') ? 'selected' : '' }}>1</option>
              <option value="2" {{ '2' == old('review_count') ? 'selected' : '' }}>2</option>
              <option value="3" {{ '3' == old('review_count') ? 'selected' : '' }}>3</option>
              <option value="4" {{ '4' == old('review_count') ? 'selected' : '' }}>4</option>
              <option value="5" {{ '5' == old('review_count') ? 'selected' : '' }}>5</option>
              <option value="6" {{ '6' == old('review_count') ? 'selected' : '' }}>6</option>
              <option value="7" {{ '7' == old('review_count') ? 'selected' : '' }}>7</option>
              <option value="8" {{ '8' == old('review_count') ? 'selected' : '' }}>8</option>
              <option value="9" {{ '9' == old('review_count') ? 'selected' : '' }}>9</option>
              <option value="10" {{ '10' == old('review_count') ? 'selected' : '' }}>10</option>
            </select>

            @if ($errors->has('review_count'))
              <div class="alert alert-danger">{{$errors->first('review_count')}}</div>
            @endif
          </div> --}}

          <div id="review-container">
            <div class="form-group">
              <strong>Review:</strong>
              <input type="text" name="review[]" class="form-control review-input-field" value="{{ old('review') }}" required>

              @if ($errors->has('review'))
                <div class="alert alert-danger">{{$errors->first('review')}}</div>
              @endif
            </div>
          </div>

          <button type="button" class="btn btn-xs btn-secondary" id="add-review-button">Add Review</button>

          <div class="form-group">
            <strong>Status:</strong>
            <select class="form-control" name="status" required>
              <option value="prepare" {{ 'prepare' == old('status') ? 'selected' : '' }}>Prepare</option>
              <option value="prepared" {{ 'prepared' == old('status') ? 'selected' : '' }}>Prepared</option>
              <option value="posted" {{ 'posted' == old('status') ? 'selected' : '' }}>Posted</option>
              <option value="pending" {{ 'pending' == old('status') ? 'selected' : '' }}>Pending</option>
            </select>

            @if ($errors->has('status'))
              <div class="alert alert-danger">{{$errors->first('status')}}</div>
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

<div id="scheduleEditModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h4 class="modal-title">Edit a Review Schedule</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Date:</strong>
            <div class='input-group date' id='edit_review_date'>
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
            <select class="form-control" name="platform" id="schedule_platform">
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
            <strong>Number of Reviews:</strong>
            <select class="form-control" name="review_count" id="schedule_review_count">
              <option value="">Select a Number</option>
              <option value="1" {{ '1' == old('review_count') ? 'selected' : '' }}>1</option>
              <option value="2" {{ '2' == old('review_count') ? 'selected' : '' }}>2</option>
              <option value="3" {{ '3' == old('review_count') ? 'selected' : '' }}>3</option>
              <option value="4" {{ '4' == old('review_count') ? 'selected' : '' }}>4</option>
              <option value="5" {{ '5' == old('review_count') ? 'selected' : '' }}>5</option>
              <option value="6" {{ '6' == old('review_count') ? 'selected' : '' }}>6</option>
              <option value="7" {{ '7' == old('review_count') ? 'selected' : '' }}>7</option>
              <option value="8" {{ '8' == old('review_count') ? 'selected' : '' }}>8</option>
              <option value="9" {{ '9' == old('review_count') ? 'selected' : '' }}>9</option>
              <option value="10" {{ '10' == old('review_count') ? 'selected' : '' }}>10</option>
            </select>

            @if ($errors->has('review_count'))
              <div class="alert alert-danger">{{$errors->first('review_count')}}</div>
            @endif
          </div>

          <div id="edit-review-container">
            <div class="form-group">
              <strong>Review:</strong>
              <input type="text" name="review[]" class="form-control" value="{{ old('review') }}" id="edit_schedule_review">

              @if ($errors->has('review'))
                <div class="alert alert-danger">{{$errors->first('review')}}</div>
              @endif
            </div>

            <div id="review-container-extra"></div>
          </div>

          <button type="button" class="btn btn-xs btn-secondary" id="add-edit-review-button">Add Review</button>

          <div class="form-group">
            <strong>Status:</strong>
            <select class="form-control" name="status" required id="schedule_status">
              <option value="prepare" {{ 'prepare' == old('status') ? 'selected' : '' }}>Prepare</option>
              <option value="prepared" {{ 'prepared' == old('status') ? 'selected' : '' }}>Prepared</option>
              <option value="posted" {{ 'posted' == old('status') ? 'selected' : '' }}>Posted</option>
              <option value="pending" {{ 'pending' == old('status') ? 'selected' : '' }}>Pending</option>
            </select>

            @if ($errors->has('status'))
              <div class="alert alert-danger">{{$errors->first('status')}}</div>
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
