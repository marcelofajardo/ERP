    <div id="whatsAppConfigCreateModal" class="modal fade" role="dialog">
    	<div class="modal-dialog">

    		<!-- Modal content-->
    		<div class="modal-content">
    			<form action="{{ route('whatsapp.config.store') }}" method="POST">
    				@csrf

    				<div class="modal-header">
    					<h4 class="modal-title">Store Whats App Config</h4>
    					<button type="button" class="close" data-dismiss="modal">&times;</button>
    				</div>
    				<div class="modal-body">

                        <div class="form-group">
    						<strong>Username:</strong>
    						<input type="text" name="username" class="form-control" value="{{ old('username') }}" required>

    						@if ($errors->has('username'))
    						<div class="alert alert-danger">{{$errors->first('username')}}</div>
    						@endif
    					</div>

    					<!-- <div class="form-group">
    						<strong>Password:</strong>
    						<input type="text" name="password" class="form-control" value="{{ old('password') }}" required>

    						@if ($errors->has('password'))
    						<div class="alert alert-danger">{{$errors->first('password')}}</div>
    						@endif
    					</div> -->

    					<div class="form-group">
    						<strong>Number:</strong>
    						<input type="text" name="number" class="form-control" value="{{ old('number') }}" required>

    						@if ($errors->has('number'))
    						<div class="alert alert-danger">{{$errors->first('number')}}</div>
    						@endif
    					</div>

    					<div class="form-group">
    						<strong>Provider:</strong>
    						<input type="text" name="provider" class="form-control" value="{{ old('provider') }}" required>

    						@if ($errors->has('provider'))
    						<div class="alert alert-danger">{{$errors->first('provider')}}</div>
    						@endif
    					</div>

                        <div class="form-group">
                            <strong>Frequency:</strong>
                            <input type="text" name="frequency" class="form-control" value="{{ old('frequency') }}" required>

                            @if ($errors->has('frequency'))
                            <div class="alert alert-danger">{{$errors->first('frequency')}}</div>
                            @endif
                        </div>

    					<div class="form-group">
    						<strong>Customer Support:</strong>
    						<select class="form-control" name="customer_support">
    							<option value="1">Yes</option>
    							<option value="0">No</option>
    						</select>

    						@if ($errors->has('customer_support'))
    						<div class="alert alert-danger">{{$errors->first('customer_support')}}</div>
    						@endif
    					</div>
						
						<div class="form-group">
    						<strong>Select Store:</strong>
    						<select class="form-control" name="store_website_id">
								<option value="">Select</option>
    							@foreach($storeData as $store)
									<option value="{{$store['id']}}">{{$store['title']}}</option>
								@endforeach
    						</select>

    						@if ($errors->has('customer_support'))
    						<div class="alert alert-danger">{{$errors->first('store_website_id')}}</div>
    						@endif
    					</div> 
						<div class="form-group">
    						<strong>Default For:</strong>
    						<select class="form-control" name="default_for[]" multiple>
    							<option value="1">Customer</option>
    							<option value="2">Vendor</option>
    							<option value="3">Supplier</option>
    							<option value="4">User</option>
    						</select>

    						@if ($errors->has('customer_support'))
    						<div class="alert alert-danger">{{$errors->first('default_for')}}</div>
    						@endif
    					</div>

                        <div class="form-group">
                            <strong>Instance Id:</strong>
                            <input type="text" name="instance_id" class="form-control" value="{{ old('instance_id') }}">
                            @if ($errors->has('instance_id'))
                                <div class="alert alert-danger" >{{$errors->first('instance_id')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Token:</strong>
                            <input type="text" name="token" class="form-control" value="{{ old('token') }}">
                            @if ($errors->has('token'))
                                <div class="alert alert-danger" >{{$errors->first('token')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Is Default ?:</strong>
                            <select class="form-control" name="is_default">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            @if ($errors->has('is_default'))
                                <div class="alert alert-danger" >{{$errors->first('is_default')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Start Time:</strong>
                            <select class="form-control" name="send_start">
                                <option value="0">0</option>
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
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>

                            @if ($errors->has('send_at'))
                            <div class="alert alert-danger">{{$errors->first('send_at')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>End Time:</strong>
                             <select class="form-control" name="send_end">
                                <option value="0">0</option>
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
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>

                            @if ($errors->has('send_end'))
                            <div class="alert alert-danger">{{$errors->first('send_end')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Device Name:</strong>
                            <input type="text" name="device_name" class="form-control" value="{{ old('device_name') }}" >

                            @if ($errors->has('device_name'))
                            <div class="alert alert-danger">{{$errors->first('device_name')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Sim Card Number:</strong>
                            <input type="text" name="simcard_number" class="form-control" value="{{ old('simcard_number') }}" >

                            @if ($errors->has('simcard_number'))
                            <div class="alert alert-danger">{{$errors->first('simcard_number')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Sim Card Owner:</strong>
                            <input type="text" name="simcard_owner" class="form-control" value="{{ old('simcard_owner') }}" >

                            @if ($errors->has('simcard_owner'))
                            <div class="alert alert-danger">{{$errors->first('simcard_owner')}}</div>
                            @endif
                        </div>


                        <div class="form-group">
                            <strong>Payment :</strong>
                            <input type="text" name="payment" class="form-control" value="{{ old('payment') }}" >

                            @if ($errors->has('payment'))
                            <div class="alert alert-danger">{{$errors->first('payment')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Sim Card Type:</strong>
                             <select class="form-control" name="sim_card_type">
                                <option>Select Sim Type</option>
                                <option value="0">Pre Paid</option>
                                <option value="1">Post Paid</option>
                             </select>
                            @if ($errors->has('status'))
                            <div class="alert alert-danger">{{$errors->first('status')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Recharge Date:</strong>
                             <div class='input-group date' id='filter-whats-date'>
                            <input type='text' class="form-control" name="recharge_date" placeholder="Date"/>
                            <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                            </div>

                            @if ($errors->has('recharge_date'))
                            <div class="alert alert-danger">{{$errors->first('recharge_date')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Status:</strong>
                             <select class="form-control" name="status">
                                <option>Select Status</option>
                                <option value="1">Active</option>
                                <option value="2">Blocked</option>
                                <option value="3">Inactive</option>
                             </select>
                            @if ($errors->has('status'))
                            <div class="alert alert-danger">{{$errors->first('status')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Use own:</strong>
                             <select class="form-control" name="is_use_own">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                             </select>
                            @if ($errors->has('is_use_own'))
                            <div class="alert alert-danger">{{$errors->first('is_use_own')}}</div>
                            @endif
                        </div>
    				</div>
    				<div class="modal-footer">
    					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    					<button type="submit" class="btn btn-secondary">Store</button>
    				</div>
    			</form>
    		</div>

    	</div>
    </div>