<div id="whatsAppConfigEditModal{{$whatsAppConfig->id}}" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form action="{{ route('whatsapp.config.edit') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h4 class="modal-title">Edit Whats App Config</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{$whatsAppConfig->id}}">
                        <div class="form-group">
                            <strong>Username:</strong>
                            <input type="text" name="username" class="form-control" value="{{ $whatsAppConfig->username }}" required>

                            @if ($errors->has('username'))
                                <div class="alert alert-danger">{{$errors->first('username')}}</div>
                            @endif
                        </div>
                            <input type="hidden" name="id" value="{{ $whatsAppConfig->id }}"/>
                        <!-- <div class="form-group">
                            <strong>Password:</strong>
                            <input type="text" name="password" class="form-control" value="" required>

                            @if ($errors->has('password'))
                                <div class="alert alert-danger" >{{$errors->first('password')}}</div>
                            @endif
                        </div> -->
                        <div class="form-group">
                            <strong>Number:</strong>
                            <input type="text" name="number" class="form-control" value="{{ $whatsAppConfig->number }}">

                            @if ($errors->has('number'))
                                <div class="alert alert-danger" >{{$errors->first('number')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Provider:</strong>
                            <input type="text" name="provider" class="form-control" value="{{ $whatsAppConfig->provider }}">

                            @if ($errors->has('provider'))
                                <div class="alert alert-danger" >{{$errors->first('provider')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Customer Support:</strong>
                            <select class="form-control" name="customer_support">
                                <option value="1" @if($whatsAppConfig->is_customer_support == 1) selected @endif>Yes</option>
                                <option value="0" @if($whatsAppConfig->is_customer_support == 0) selected @endif>No</option>
                            </select>
                            @if ($errors->has('is_customer_support'))
                                <div class="alert alert-danger" >{{$errors->first('is_customer_support')}}</div>
                            @endif
                        </div>
						
						
						<div class="form-group">
    						<strong>Select Store:</strong>
    						<select class="form-control" name="store_website_id">
								<option value="">Select</option>
    							@foreach($storeData as $store)
									<option value="{{$store['id']}}" @if($whatsAppConfig->store_website_id == $store['id']) selected @endif>{{$store['title']}}</option>
								@endforeach
    						</select>
    						@if ($errors->has('store_website_id'))
								<div class="alert alert-danger">{{$errors->first('store_website_id')}}</div>
    						@endif
    					</div>
						
						@php
							$defaultForArr = explode(",",$whatsAppConfig->default_for);
						@endphp
						<div class="form-group">
    						<strong>Default For:</strong>
    						<select class="form-control select-multiple-default_for" name="default_for[]" multiple>
								
    							<option value="1" @if(in_array(1,$defaultForArr)) selected @endif>Customer</option>
    							<option value="2" @if(in_array(2,$defaultForArr)) selected @endif>Vendor</option>
    							<option value="3" @if(in_array(3,$defaultForArr)) selected @endif>Supplier</option>
    							<option value="4" @if(in_array(4,$defaultForArr)) selected @endif>User</option>
    						</select>

    						@if ($errors->has('customer_support'))
    						<div class="alert alert-danger">{{$errors->first('default_for')}}</div>
    						@endif
    					</div>
						
                        <div class="form-group">
                            <strong>Instance Id:</strong>
                            <input type="text" name="instance_id" class="form-control" value="{{ $whatsAppConfig->instance_id }}">
                            @if ($errors->has('instance_id'))
                                <div class="alert alert-danger" >{{$errors->first('instance_id')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Token:</strong>
                            <input type="text" name="token" class="form-control" value="{{ $whatsAppConfig->token }}">
                            @if ($errors->has('token'))
                                <div class="alert alert-danger" >{{$errors->first('token')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Is Default ?:</strong>
                            <select class="form-control" name="is_default">
                                <option value="0" @if($whatsAppConfig->is_default == 0) selected @endif>No</option>
                                <option value="1" @if($whatsAppConfig->is_default == 1) selected @endif>Yes</option>
                            </select>
                            @if ($errors->has('is_default'))
                                <div class="alert alert-danger" >{{$errors->first('is_default')}}</div>
                            @endif
                        </div>
						
						
                        <div class="form-group">
                            <strong>Frequency:</strong>
                            <input type="text" name="frequency" class="form-control" value="{{ $whatsAppConfig->frequency }}">
                            @if ($errors->has('frequency'))
                                <div class="alert alert-danger" >{{$errors->first('frequency')}}</div>
                            @endif
                        </div>

                         <div class="form-group">
                            <strong>Start Time:</strong>
                            <select class="form-control" name="send_start">
                                <option value="0" @if($whatsAppConfig->send_start == 0) selected @endif>0</option>
                                <option value="1" @if($whatsAppConfig->send_start == 1) selected @endif>1</option>
                                <option value="2" @if($whatsAppConfig->send_start == 2) selected @endif>2</option>
                                <option value="3" @if($whatsAppConfig->send_start == 3) selected @endif>3</option>
                                <option value="4" @if($whatsAppConfig->send_start == 4) selected @endif>4</option>
                                <option value="5" @if($whatsAppConfig->send_start == 5) selected @endif>5</option>
                                <option value="6" @if($whatsAppConfig->send_start == 6) selected @endif>6</option>
                                <option value="7" @if($whatsAppConfig->send_start == 7) selected @endif>7</option>
                                <option value="8" @if($whatsAppConfig->send_start == 8) selected @endif>8</option>
                                <option value="9" @if($whatsAppConfig->send_start == 9) selected @endif>9</option>
                                <option value="10" @if($whatsAppConfig->send_start == 10) selected @endif>10</option>
                                <option value="11" @if($whatsAppConfig->send_start == 11) selected @endif>11</option>
                                <option value="12" @if($whatsAppConfig->send_start == 12) selected @endif>12</option>
                                <option value="13" @if($whatsAppConfig->send_start == 13) selected @endif>13</option>
                                <option value="14" @if($whatsAppConfig->send_start == 14) selected @endif>14</option>
                                <option value="15" @if($whatsAppConfig->send_start == 15) selected @endif>15</option>
                                <option value="16" @if($whatsAppConfig->send_start == 16) selected @endif>16</option>
                                <option value="17" @if($whatsAppConfig->send_start == 17) selected @endif>17</option>
                                <option value="18" @if($whatsAppConfig->send_start == 18) selected @endif>18</option>
                                <option value="19" @if($whatsAppConfig->send_start == 19) selected @endif>19</option>
                                <option value="20" @if($whatsAppConfig->send_start == 20) selected @endif>20</option>
                                <option value="21" @if($whatsAppConfig->send_start == 21) selected @endif>21</option>
                                <option value="22" @if($whatsAppConfig->send_start == 22) selected @endif>22</option>
                                <option value="23" @if($whatsAppConfig->send_start == 23) selected @endif>23</option>
                            </select>

                            @if ($errors->has('send_at'))
                            <div class="alert alert-danger">{{$errors->first('send_at')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>End Time:</strong>
                             <select class="form-control" name="send_end">
                                <option value="0" @if($whatsAppConfig->send_end == 0) selected @endif>0</option>
                                <option value="1" @if($whatsAppConfig->send_end == 1) selected @endif>1</option>
                                <option value="2" @if($whatsAppConfig->send_end == 2) selected @endif>2</option>
                                <option value="3" @if($whatsAppConfig->send_end == 3) selected @endif>3</option>
                                <option value="4" @if($whatsAppConfig->send_end == 4) selected @endif>4</option>
                                <option value="5" @if($whatsAppConfig->send_end == 5) selected @endif>5</option>
                                <option value="6" @if($whatsAppConfig->send_end == 6) selected @endif>6</option>
                                <option value="7" @if($whatsAppConfig->send_end == 7) selected @endif>7</option>
                                <option value="8" @if($whatsAppConfig->send_end == 8) selected @endif>8</option>
                                <option value="9" @if($whatsAppConfig->send_end == 9) selected @endif>9</option>
                                <option value="10" @if($whatsAppConfig->send_end == 10) selected @endif>10</option>
                                <option value="11" @if($whatsAppConfig->send_end == 11) selected @endif>11</option>
                                <option value="12" @if($whatsAppConfig->send_end == 12) selected @endif>12</option>
                                <option value="13" @if($whatsAppConfig->send_end == 13) selected @endif>13</option>
                                <option value="14" @if($whatsAppConfig->send_end == 14) selected @endif>14</option>
                                <option value="15" @if($whatsAppConfig->send_end == 15) selected @endif>15</option>
                                <option value="16" @if($whatsAppConfig->send_end == 16) selected @endif>16</option>
                                <option value="17" @if($whatsAppConfig->send_end == 17) selected @endif>17</option>
                                <option value="18" @if($whatsAppConfig->send_end == 18) selected @endif>18</option>
                                <option value="19" @if($whatsAppConfig->send_end == 19) selected @endif>19</option>
                                <option value="20" @if($whatsAppConfig->send_end == 20) selected @endif>20</option>
                                <option value="21" @if($whatsAppConfig->send_end == 21) selected @endif>21</option>
                                <option value="22" @if($whatsAppConfig->send_end == 22) selected @endif>22</option>
                                <option value="23" @if($whatsAppConfig->send_end == 23) selected @endif>23</option>
                            </select>

                            @if ($errors->has('send_end'))
                            <div class="alert alert-danger">{{$errors->first('send_end')}}</div>
                            @endif
                        </div>

                         <div class="form-group">
                            <strong>Device Name:</strong>
                            <input type="text" name="device_name" class="form-control" value="{{ $whatsAppConfig->device_name }}" >

                            @if ($errors->has('device_name'))
                            <div class="alert alert-danger">{{$errors->first('device_name')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Sim Card Number:</strong>
                            <input type="text" name="simcard_number" class="form-control" value="{{ $whatsAppConfig->simcard_number }}" >

                            @if ($errors->has('simcard_number'))
                            <div class="alert alert-danger">{{$errors->first('simcard_number')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Sim Card Owner:</strong>
                            <input type="text" name="simcard_owner" class="form-control" value="{{ $whatsAppConfig->simcard_owner }}">

                            @if ($errors->has('simcard_owner'))
                            <div class="alert alert-danger">{{$errors->first('simcard_owner')}}</div>
                            @endif
                        </div>


                        <div class="form-group">
                            <strong>Payment :</strong>
                            <input type="text" name="payment" class="form-control" value="{{ $whatsAppConfig->payment }}">

                            @if ($errors->has('payment'))
                            <div class="alert alert-danger">{{$errors->first('payment')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Sim Card Type:</strong>
                             <select class="form-control" name="sim_card_type">
                                <option>Select Sim Card Type</option>
                                <option value="0"  @if($whatsAppConfig->sim_card_type == 0) selected @endif>Pre Paid</option>
                                <option value="1"  @if($whatsAppConfig->sim_card_type == 1) selected @endif>Post Paid</option>
                             </select>
                            @if ($errors->has('status'))
                            <div class="alert alert-danger">{{$errors->first('status')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Recharge Date:</strong>
                             <div class='input-group date' id='filter-whats-date'>
                            <input type='text' class="form-control" name="recharge_date" placeholder="Date" value="{{ $whatsAppConfig->recharge_date }}" />
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
                                <option value="1" @if($whatsAppConfig->status == 1) selected @endif>Active</option>
                                <option value="2" @if($whatsAppConfig->status == 2) selected @endif>Blocked</option>
                                <option value="3" @if($whatsAppConfig->status == 3) selected @endif>Inactive</option>
                             </select>
                            @if ($errors->has('status'))
                            <div class="alert alert-danger">{{$errors->first('status')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Use own:</strong>
                             <select class="form-control" name="is_use_own">
                                <option value="0" @if($whatsAppConfig->is_use_own == 0) selected @endif>No</option>
                                <option value="1" @if($whatsAppConfig->is_use_own == 1) selected @endif>Yes</option>
                             </select>
                            @if ($errors->has('is_use_own'))
                            <div class="alert alert-danger">{{$errors->first('is_use_own')}}</div>
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