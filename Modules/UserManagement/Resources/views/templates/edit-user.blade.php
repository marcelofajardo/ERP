{!! Form::model($user, ['method' => 'PATCH','route' => ['user-management.update', $user->id]]) !!}

		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">Edit User</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
                <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
    <strong>Name:</strong>
    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Email:</strong>
                            {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <strong>Phone:</strong>
                            <input type="number" class="form-control" name="phone" placeholder="900000000" value="{{ $user->phone }}" />
                            @if ($errors->has('phone'))
                                <div class="alert alert-danger">{{$errors->first('phone')}}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <select name="whatsapp_number" class="form-control">
                                <option value>Whatsapp Number</option>
                                <option value="971569119192" {{ '971569119192' == $user->whatsapp_number ? ' selected' : '' }}>971569119192 Indian</option>
                                <option value="971502609192" {{ '971502609192' == $user->whatsapp_number ? ' selected' : '' }}>971502609192 Dubai</option>
                            </select>

                            @if ($errors->has('whatsapp_number'))
                                <div class="alert alert-danger">{{$errors->first('whatsapp_number')}}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Password:</strong>
                            {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Confirm Password:</strong>
                            {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Logout Timeout:</strong>
                            <input type="number" name="user_timeout" class="form-control" value="{{ $user->user_timeout }}">
                            <small>Please addd time in seconds. 1 Minute = 60 Seconds</small>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Amount of Assigned Products:</strong>
                            <input type="number" name="amount_assigned" class="form-control" value="{{ $user->amount_assigned }}">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Listing Approval Rate:</strong>
                            <input type="text" name="listing_approval_rate" class="form-control" value="{{ $user->listing_approval_rate ?? 0 }}">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Listing Rejection Rate:</strong>
                            <input type="text" name="listing_rejection_rate" class="form-control" value="{{ $user->listing_rejection_rate ?? 0 }}">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Rate:</strong>
                            {!! Form::text('hourly_rate', isset($userRate) ? $userRate->hourly_rate: '', array('placeholder' => 'Rate','class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Currency:</strong>
                            {!! Form::text('currency', isset($userRate) ? $userRate->currency: '', array('placeholder' => 'USD','class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Payment frequency:</strong>
                            <select class="form-control"  name="payment_frequency">
                            <option value="">Select frequency</option>
                            <option value="fornightly" {{ $user->payment_frequency == 'fornightly' ? 'selected' : '' }}>Fornightly</option>
                            <option value="weekly" {{ $user->payment_frequency == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="biweekly" {{ $user->payment_frequency == 'biweekly' ? 'selected' : '' }}>Bi weekly</option>
                            <option value="monthly" {{ $user->payment_frequency == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Billing frequency (days):</strong>
                            {!! Form::text('billing_frequency_day', isset($userRate) ? $userRate->billing_frequency_day: '', array('placeholder' => 'Billing Frequency day','class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Salaried or fixed price job:</strong>
                            <select class="form-control"  name="fixed_price_user_or_job">
                            <option value="">Select</option>
                            <option value="1" {{ $user->fixed_price_user_or_job == 1 ? 'selected' : '' }}>Fixed Price job</option>
                            <option value="2" {{ $user->fixed_price_user_or_job == 2 ? 'selected' : '' }}>Salaried</option>
    
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Auto Approval:</strong>
                            <select class="form-control"  name="is_auto_approval">
                                <option value="0" {{ $user->is_auto_approval == 0 ? 'selected' : '' }}>No</option>
                                <option value="1" {{ $user->is_auto_approval == 1 ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                    </div>

                    @if ($user->hasRole('Customer Care'))
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Assigned Customers:</strong>
                                <select class="selectpicker form-control" data-live-search="true" data-size="15" name="customer[]" title="Choose Customers" multiple>
                                    @foreach ($customers_all as $customer)
                                        <option data-tokens="{{ $customer['name'] }} {{ $customer['email'] }}  {{ $customer['phone'] }} {{ $customer['instahandler'] }}" value="{{ $customer['id'] }}" {{ $user->customers && $user->customers->contains($customer['id']) ? 'selected' : '' }}>{{ $customer['id'] }} - {{ $customer['name'] }} - {{ $customer['phone'] }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('customer'))
                                    <div class="alert alert-danger">{{$errors->first('customer')}}</div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group padding-top-30">
                        <strong>Responsible User:</strong>
                        <select name="responsible_user" class="form-control">
                            <option value="">Select User</option>
                            @foreach($users as $useritem)
                                <option value="{{$useritem->id}}" {{ $useritem->id == $user->responsible_user ? 'selected' : '' }}>{{$useritem->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Agent Role:</strong>
                    {!! Form::select('agent_role[]', $agent_roles,$user_agent_roles, array('class' => 'form-control','multiple')) !!}
                </div>
            </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-secondary">Save Changes</button>
                    </div>          
                </div>
		   </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      
		   </div>
		</div>
        {!! Form::close() !!} 