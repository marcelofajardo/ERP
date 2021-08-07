    <div id="changeModal" class="modal fade" role="dialog">
    	<div class="modal-dialog">

    		<!-- Modal content-->
    		<div class="modal-content">
    			<div class="modal-header">
    					<h4 class="modal-title">Change Broadcast Number</h4>
    					<button type="button" class="close" data-dismiss="modal">&times;</button>
    				</div>
    				<div class="modal-body">
              <form action="{{ route('whatsapp.config.switchBroadcast')}}" method="POST">
                @csrf
                        <input type="hidden" name="id" id="old_id">
                        <select class="form-control" name="newId">
                          @foreach($numbers as $number)
                          <option value="{{$number->id}}" @if($number->is_customer_support == 1) hidden @endif>{{$number->number}}</option>
                          @endforeach
                        </select>
                    </div>

    				<div class="modal-footer">
              <button type="submit" class="btn btn-default">Submit</button>
              </form>
    					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

    				</div>
    			
    		</div>

    	</div>
    </div>