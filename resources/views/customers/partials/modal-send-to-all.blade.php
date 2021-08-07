<div id="sendAllModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Send Message to All Customers</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('customer.whatsapp.send.all') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" id="broadcast_image" name="image_id">
                <div class="modal-body">
                  {{-- @if ($queues_total_count > $queues_sent_count)
                    <div class="form-group alert alert-success">
                      <strong>Background Status:</strong>
                      <br>
                      {{ $queues_sent_count }} of {{ $queues_total_count }} customers are processed
                      <br>
                      <a href="{{ route('customer.whatsapp.stop.all') }}" class="btn btn-xs btn-danger">STOP</a>
                    </div>

                    <hr>
                  @endif --}}
                  
                  <div class="form-group">
                      <strong>Select Platform:</strong>
                      <select class="form-control" name="platform" class="platform" id="platform">
                        @foreach($platforms as $platform)
                        <option value="{{ $platform->name }}">{{ $platform->name }}</option>
                        @endforeach
                      </select>
                  </div>

                  <div class="form-group">
                    <strong>Schedule Date:</strong>
                    <div class='input-group date' id='schedule-datetime'>
                      <input type='text' class="form-control" name="sending_time" id="sending_time_field" value="{{ date('Y-m-d H:i') }}" required />

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
                        <strong id="message">Message</strong>
                        <textarea name="message" id="message_to_all_field" rows="2" cols="20" class="form-control"></textarea>
                    </div>

                    <div class="form-group" hidden>
                        <input type="checkbox" id="send_type" name="to_all" checked>
                        <label for="send_type">Send Message to All Existing Customers</label>
                    </div>

                    <hr>

                    <div class="form-group select-group">
                      <strong>Select Group of Customers</strong>
                      <select class="form-control" name="rating">
                        <option value="">Select a Rating</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                      </select>
                    </div>

                    <div class="form-group gender">
                      <select class="form-control" name="gender">
                        <option value>Both Genders</option>
                        <option value="female">Female</option>
                        <option value="male">Male</option>
                      </select>
                    </div>

                    <div class="form-group shoe-size">
                       <input type='text' class="form-control" name="shoe_size" placeholder="Shoe Size"/>
                     </div>
                     <div class="form-group clothing-size">
                        <input type='text' class="form-control" name="clothing_size" placeholder="Clothing Size"/>
                     </div>

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
