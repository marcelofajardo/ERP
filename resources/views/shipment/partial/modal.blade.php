@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

<div id="send_email_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
  
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Send an Email</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
  
        <form action="{{ route('shipment/send/email') }}" method="POST" enctype="multipart/form-data">
          @csrf
			<input type="hidden" name="order_id" id="order_id" value="" >
          <div class="modal-body">
            <div class="form-group">
                <label>Select Name</label>
                <select class="form-control" name="email_name" id="email_name" required>
                    <option value="">Select Template</option>
                    @if($template_names)
                        @foreach($template_names as $name)
                            <option value="{{ $name->name }}">{{ $name->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
              <div class="form-group">
                  <label>Select Template</label>
                  <select class="form-control" name="template" id="templates" required>

                  </select>
              </div>

            <div class="form-group">
				<label>To</label>
				<select class="form-control to-email" name="to[]" multiple="multiple" required style="width: 100%;">
				</select>
            </div>

            <div id="cc-label" class="form-group">
              <strong class="mr-3">Cc</strong>
				<select class="form-control cc-email" name="cc[]" multiple style="width: 100%;">
				</select>
            </div>
    
            <div id="bcc-label" class="form-group">
              	<strong class="mr-3">Bcc</strong>
              	<select class="form-control bcc-email" name="bcc[]" multiple style="width: 100%;">
				</select>
            </div>
    
            <div class="form-group">
              <strong>Subject</strong>
              <input type="text" class="form-control" name="subject" value="{{ old('subject') }}" required>
            </div>
  
            <div class="form-group">
              <strong>Message</strong>
              <textarea name="message" class="form-control" rows="8" cols="80" required>{{ old('message') }}</textarea>
            </div>
  
            <div class="form-group">
              <strong>Files</strong>
              <input type="file" name="file[]" value="" multiple>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-secondary">Send</button>
          </div>
        </form>
      </div>
  
    </div>
</div>



<div id="view_sent_email_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
  
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Communication History</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
    
            <div class="modal-body" id="view_email_body">

			</div>
		</div>
	</div>
</div>

<div id="view_waybill_track_histories" class="modal fade" role="dialog">
    <div class="modal-dialog">
  
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Waybill Track history</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
    
            <div class="modal-body" id="view_track_body">

      </div>
    </div>
  </div>
</div>


<div id="pickup_request" class="modal fade" role="dialog">
    <div class="modal-dialog">
  
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Pickup Request</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
  
        <form action="{{ route('shipment/pickup-request') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="waybill_id" id="waybill_id" value="" >
          <div class="modal-body">
            <div class="form-group">
                <label>Pickup datetime</label>
                <div class='input-group date' id='pickup-datetime'>
                  <input type="text" class="form-control" name="pickup_time" value="{{ date('Y-m-d H:i') }}" required>
                  <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                  </span>
                </div>
            </div>
            <div class="form-group">
                <label>Pickup Location</label>
                <input type="text" class="form-control" name="pickup_location" value="{{ old('pickup_location') }}" required>
            </div>
            <div class="form-group">
              <label>Special Pickup instructions</label>
              <input type="text" class="form-control" name="special_pickup_instruction" value="{{ old('special_pickup_instruction') }}">
            </div>
            <div class="form-group">
                <label>Location close time</label>
                <div class='input-group time' id='location-close-time'>
                  <input type="text" class="form-control" name="location_close_time" value="{{ date('H:i') }}" required>
                  <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                  </span>
                </div>
            </div>

            <div id="cc-label" class="form-group">
              <strong class="mr-3">Service Type</strong>
                <select class="form-control service_type" name="service_type" style="width: 100%;">
                  <option value="" selected>Select Service type</option>
                  <option value="P">International non-document shipments</option>
                  <option value="D">International document shipments</option>
                  <option value="N">Domestic shipments</option>
                  <option value="U">Intra-Europe shipments</option>
                </select>
            </div> 
            
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-secondary">Send</button>
          </div>
        </form>
      </div>
  
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
    $('#pickup-datetime').datetimepicker({
      format: 'YYYY-MM-DD H:mm'
    });
    $('#location-close-time').datetimepicker({
      format: 'H:mm'
    });
</script>

  