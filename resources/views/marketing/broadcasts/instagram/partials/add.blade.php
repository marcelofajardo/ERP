<div id="addModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Instagram Broadcast</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="/cold-leads-broadcasts" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                  <div class="form-group">
                        <strong>Name</strong>
                        <input type="text" name="name" class="form-control" required />
                    </div>

                  <div class="form-group">
                    <strong>Schedule Date:</strong>
                    <div class='input-group date' id='schedule-datetime'>
                      <input type='text' class="form-control" name="started_at" id="sending_time_field" value="{{ date('Y-m-d H:i') }}" required />

                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                    </div>

                    @if ($errors->has('sending_time'))
                        <div class="alert alert-danger">{{$errors->first('sending_time')}}</div>
                    @endif
                  </div>

                    <div class="form-group">
                        <strong>Frequency</strong>
                        <input type="number" name="frequency" class="form-control" value="10" required />
                    </div>

                    <div class="form-group">
                        <strong>Number Of User</strong>
                        <input type="number" name="number_of_users" class="form-control" required />
                    </div>

                    <div class="form-group">
                        <strong>Instagram Link</strong>
                        <textarea name="message" id="message_to_all_field" rows="2" cols="20" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <strong>Target Competitor User</strong>
                        <select class="form-control" name="competitor">
                            <option value>Select Competitor</option>
                            @foreach($competitors as $competitor)
                            <option value="{{ $competitor->id }}">{{ $competitor->name }}</option>
                            @endforeach
                      </select>
                    </div>

                    <div class="form-group">
                      <select class="form-control" name="gender">
                        <option value>Both Genders</option>
                        <option value="f">Female</option>
                        <option value="m">Male</option>
                      </select>
                    </div>
                    <input type="hidden" name="status" value="1">
                     <hr>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary">Send Message</button>
                </div>
            </form>
        </div>

    </div>
</div>
