<div id="emailToCustomerModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content ">
			<div class="modal-header">
				<h4 class="modal-title">Update Customers</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="{{route('return-exchange.updateCusromer')}}" id="customerUpdateForm" method="POST">
				@csrf
				<input type="hidden" name="selected_ids" />
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-2">
								<strong>Status:</strong>
							</div>
							<div class="col-md-8">
								<div class="form-group">
								<?php echo Form::select("status",\App\ReturnExchangeStatus::pluck("status_name","id")->toArray(),request("limti"),[
							    	"class" => "form-control",// select2
							    	"placeholder" => "-- Select Status --"
							      ]) ?>
								</div>
							</div>
						</div>
						<div class="col-md-12">
                                <div class="col-md-2">
                                    <strong>Add New Reply:</strong>
                                </div>
                                <div class="col-md-8">
                                <div class="form-group">
                                  <input type="text" class="addnewreply" placeholder="add new reply">
                                  <button class="btn btn-secondary addnewreplybtn">+</button>
                                </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <strong>Quick Reply:</strong>
                                </div>
                                <div class="col-md-8">
                                <div class="form-group">
                                  <select class="quickreply">
                                  <option value="">Select quick reply</option>
                                  @if($quickreply)
                                    @foreach($quickreply as $quickrep)
                                      <option value="{{$quickrep->id}}">{{$quickrep->reply}}</option>
                                    @endforeach
                                 @endif
                                </select>
                                </div>
                                </div>
                            </div>
						<div class="col-md-12">
							<div class="col-md-2">
								<strong>Message:</strong>
							</div>
							<div class="col-md-8">
							<div class="form-group">
							  <textarea cols="45" class="form-control" name="customer_message"></textarea>
							</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="col-md-2">
								<strong>Update type:</strong>
							</div>
							<div class="col-md-8">
							<div class="form-group">
							  <select name="update_type" class="form-control">
								<option value="1">Only send message</option>
								<option value="2">Send message and update status</option>
							  </select>
							</div>
							</div>
						</div>
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

