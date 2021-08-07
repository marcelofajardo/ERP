<div id="createRefundModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg update-refund-section">
		<!-- Modal content-->
		<div class="modal-content ">
			<div class="modal-header">
				<h4 class="modal-title">Create Refund</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="{{route('return-exchange.createRefund')}}" id="createRefundForm" method="POST">
				@csrf
				<div class="modal-body">

                <div class="form-group">
                    <strong>Customer:</strong>
                    <?php echo Form::select("customer_id",\App\Customer::pluck("name","id")->toArray(),null,[
                                        "class" => "form-control select2",// select2
                                        "placeholder" => "-- Select Customer --"
                                    ]) ?>
                </div>
                <div class="form-group">
                    <strong>Refund Type:</strong>
                    <select name="refund_amount_mode" class="form-control" required>
                        <option value="Cash" {{ old('type') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Bank Transfer" {{ old('type') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    </select>
                </div>
                <div class="form-group">
  				<strong>CHQ Number:</strong>
  				<input type="text" name="chq_number" class="form-control" placeholder="00000000" value="{{ old('chq_number') }}">
  				@if ($errors->has('chq_number'))
  						<div class="alert alert-danger">{{$errors->first('chq_number')}}</div>
  				@endif
  			    </div>
                  <div class="form-group">
  				<strong>Refund amount:</strong>
  				<input type="number" step=0.1 name="refund_amount" class="form-control">
  			    </div>
                  <div class="form-group">
                    <strong>Date of Refund Request:</strong>
                    <div class='input-group date' id='date_of_request'>
                        <input type='text' class="form-control" name="date_of_request" value="{{ date('Y-m-d H:i') }}" />

                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>

                    @if ($errors->has('date_of_request'))
                        <div class="alert alert-danger">{{$errors->first('date_of_request')}}</div>
                    @endif
                    </div>
                    <div class="form-group">
								<?php echo Form::select("status",\App\ReturnExchangeStatus::pluck("status_name","id")->toArray(),request("limti"),[
							    	"class" => "form-control",
							    	"placeholder" => "-- Select Status --"
							      ]) ?>
								</div>

                    <div class="form-group">
                    <strong>Details:</strong>
                    <textarea name="details" rows="8" cols="80" class="form-control">{{ old('details') }}</textarea>

                    @if ($errors->has('details'))
                        <div class="alert alert-danger">{{$errors->first('details')}}</div>
                    @endif
                    </div>
                    <div class="form-group">
                    <input type="checkbox" name="dispatched" id="dispatch_date">
                    <label for="dispatch_date">Mark as Dispatched</label>
                    </div>

                    <div id="additional-fields" style="display: none;">
                    <div class="form-group">
                        <strong>Date of Dispatch</strong>
                        <div class='input-group date' id='date_of_dispatched'>
                        <input type='text' class="form-control" name="dispatch_date" value="" />

                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        </div>
                    </div>

                    <div class="form-group">
                                <strong>AWB:</strong>
                                <input type="text" name="awb" class="form-control" placeholder="00000000" value="">
                            </div>
                    </div>
                    <div class="form-group">
                    <input type="checkbox" name="credited" id="credited">
                    <label for="credited">Mark as Credited</label>
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

