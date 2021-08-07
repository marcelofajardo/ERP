<div class="modal-content ">
			<div class="modal-header">
				<h4 class="modal-title">Update Refund</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="{{route('return-exchange.updateRefund')}}" id="updateRefundForm" method="POST">
				@csrf
				<div class="modal-body">

                <div class="form-group">
                    <strong>Customer:</strong>
                    <input type="text" name="customer_id" value="{{$returnExchange->customer->name}}" class="form-control" readonly>
                    <input type="hidden" name="id" value="{{$id}}" class="form-control">
                    <input type="hidden" name="customer_id" value="{{$returnExchange->customer_id}}" class="form-control">
                </div>
                <div class="form-group">
                    <strong>Refund Type:</strong>
                    <select name="refund_amount_mode" class="form-control" required>
                        <option value="Cash" {{$returnExchange->refund_amount_mode == 'Cash' ? 'selected' : ''}}>Cash</option>
                        <option value="Bank Transfer" {{$returnExchange->refund_amount_mode == 'Bank Transfer' ? 'selected' : ''}}>Bank Transfer</option>
                    </select>
                </div>
                <div class="form-group">
  				<strong>CHQ Number:</strong>
  				<input type="text" name="chq_number" class="form-control" placeholder="00000000" value="{{ $returnExchange->chq_number }}">
  			    </div>
                  <div class="form-group">
  				<strong>Refund amount:</strong>
  				<input type="number" step=0.1 name="refund_amount" class="form-control" value="{{ $returnExchange->refund_amount }}">
  			    </div>
                  <div class="form-group">
                    <strong>Date of Refund Request:</strong>
                    <div class='input-group date' id='date_of_request'>
                        <input type='text' class="form-control" name="date_of_request" value="{{ $returnExchange->date_of_request }}" />

                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    </div>
                    <div class="form-group">
								<?php echo Form::select("status",\App\ReturnExchangeStatus::pluck("status_name","id")->toArray(),$returnExchange->status,[
							    	"class" => "form-control",
							    	"placeholder" => "-- Select Status --"
							      ]) ?>
								</div>

                    <div class="form-group">
                    <strong>Details:</strong>
                    <textarea name="details" rows="8" cols="80" class="form-control">{{ $returnExchange->details }}</textarea>
                    </div>
                    <div class="form-group">
                    <input type="checkbox" name="dispatched" id="dispatch_date" {{ $returnExchange->dispatch_date ? 'checked' : '' }}>
                    <label for="dispatch_date">Mark as Dispatched</label>
                    </div>

                    <div id="additional-fields" style="display: none;">
                    <div class="form-group">
                        <strong>Date of Dispatch</strong>
                        <div class='input-group date' id='date_of_dispatched'>
                        <input type='text' class="form-control" name="dispatch_date" value="{{ $returnExchange->dispatch_date }}" />

                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        </div>
                    </div>

                    <div class="form-group">
                                <strong>AWB:</strong>
                                <input type="text" name="awb" class="form-control" placeholder="00000000" value="{{ $returnExchange->awb }}">
                            </div>
                    </div>

        <div class="form-group">
          <input type="checkbox" name="credited" id="credited" {{ $returnExchange->credited ? 'checked' : '' }}>
          <label for="credited">Mark as Credited</label>
        </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-secondary">Update</button>
				</div>
			</form>
		</div>

        <script>
        	$('#date_of_request, #date_of_dispatched').datetimepicker({
			format: 'YYYY-MM-DD HH:mm'
		});
        </script>