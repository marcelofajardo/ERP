@extends('layouts.app')

@section('title', 'User Edit Page')

@section('styles')
    <style>
        #collapse {
            overflow-y: scroll;
            height: 600px;
        }
        #collapse1 {
            overflow-y: scroll;
            height: 600px;
        }

        li {
            list-style-type: none;
        }
        .padding-left-zero {
            padding-left: 0px;
        }

    </style>
@endsection
@section('content')


    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit New User</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('users.index') }}"> Back</a>
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addPermission">Add Permission</button>
            </div>

        </div>
    </div>


    @include('partials.flash_messages')


    {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
    <div class="container">
        <div class="row">
            <div class="col-md-6">
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
                            <select name="whatsapp_number" class="form-control" id="whatsapp_change">
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
                    
                    <!-- START - Purpose : Email notification - DEVTASK-4359 -->
                    <div class="col-xs-12 col-sm-12 col-md-12 notification_mail_id_cls">
                        <div class="form-group">
                            <hr>
                            <strong>Email Notification :</strong>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 email_notification_chkbox_cls">
                        <div class="form-group">
                            @if ($user->mail_notification == 1)
                            <input type="checkbox" id="email_notification_chkbox" name="email_notification_chkbox" checked value="">
                            @else
                            <input type="checkbox" id="email_notification_chkbox" name="email_notification_chkbox" value="">
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 notification_mail_id_cls">
                        <div class="form-group">
                            <strong>Email Id :</strong> (Add Multiple Email Saprated by comma)
                            <input type="text" name="notification_mail_id" class="form-control" value="{{$email_notification_data->emails ?? ''}}">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 notification_mail_id_cls">
                        <div class="form-group">
                            <hr>
                            <strong>Webhook Notifications:</strong>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 notification_mail_id_cls">
                        <div class="form-group">
                            <strong>Method:</strong>
                            <select class="form-control" name=webhook[method] >
                                <option value="" >Select Request Method</option>
                                <option value="GET"  {{ $user->webhookNotification && $user->webhookNotification->method === 'get' ? 'selected' : '' }} >GET</option>
                                <option value="POST" {{ $user->webhookNotification && $user->webhookNotification->method === 'post' ? 'selected' : '' }} >POST</option>
                                <option value="PUT" {{ $user->webhookNotification && $user->webhookNotification->method === 'put' ? 'selected' : '' }} >PUT</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <strong>Url:</strong>
                            <input type="text" name="webhook[url]" class="form-control" value="{{ $user->webhookNotification->url ?? '' }}">
                        </div>
                        <div class="form-group">
                            <strong>Content Type:</strong>
                            <input type="text" name="webhook[content_type]" class="form-control" value="{{ $user->webhookNotification->content_type ?? '' }}">
                        </div>
                        <div class="form-group">
                            <strong>Payload:</strong>
                            <input type="text" name="webhook[payload]" class="form-control" value="{{  $user->webhookNotification->payload ?? ''}}">
                        </div>
                    </div>
                    

                    <!-- END - DEVTASK-4359 -->

                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-secondary">Save Changes</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="overflow-auto" id="collapse1">
                        <strong>Role:</strong>
                        <input type="text" id="myInputRole" onkeyup="roleSearch()" placeholder="Search for roles.." class="form-control">
                        <ul id="myRole" class="padding-left-zero">
                        @foreach($roles as $key => $value)
                           <li>
                            <a>{{ Form::checkbox('roles[]',  $key  , (in_array($value, $userRole)) ? "checked" : '') }} <strong>{{ $value }}</strong></a>
                            </li>
                        @endforeach
                        </ul>

                    </div>
                    <br />
                    <br />
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



            </div>
            <div class="col-md-3">
                <div class="overflow-auto" id="collapse">
                    <strong>Permission:</strong>
                    <input type="text" id="myInput" onkeyup="permissionSearch()" placeholder="Search for permissions.." class="form-control">
                    <ul id="myUL" class="padding-left-zero">
                    @foreach($permission as $key => $value)
                        <li><a>
                           {{ Form::checkbox('permissions[]',  $key , (in_array($value, $userPermission)) ? "checked" : '') }} <strong>{{ $value }}</strong></a>

                        </li>
                    @endforeach
                    </ul>
                </div>
                <br>
                <div class="form-group">
                    <strong>Agent Role:</strong>
                    {!! Form::select('agent_role[]', $agent_roles,$user_agent_roles, array('class' => 'form-control','multiple')) !!}
                </div>


            </div>
        </div>
    </div>

    {!! Form::close() !!}

    <div class="form-group">
        <form action="{{ route('user.activate', $user->id) }}" method="POST">
            @csrf

            <button type="submit" class="btn btn-secondary">
                @if ($user->is_active == 1)
                    Is Active
                @else
                    Not Active
                @endif
            </button>
        </form>
    </div>

    {{-- <div class="form-group">
      <form action="{{ route('user.assign.products', $user->id) }}" method="POST">
        @csrf

        <button type="submit" class="btn btn-secondary">Assign Products</button>
      </form>
    </div> --}}

@include('users.partials.add-permission')
@endsection

@section('scripts')

<script>
    //START - Purpose : set Checkbox val - DEVTASK-4359
    $(document).ready(function(){
            
            $('#email_notification_chkbox').on('click', function() {
                if($('#email_notification_chkbox').is(":checked") == true){
                    $('#email_notification_chkbox').val('1');
                }else{
                    $('#email_notification_chkbox').val('0');
                }
            });
        });
    //END - DEVTASK-4359

function permissionSearch() {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    ul = document.getElementById("myUL");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("a")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}

function roleSearch() {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("myInputRole");
    filter = input.value.toUpperCase();
    ul = document.getElementById("myRole");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("a")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}


</script>

@endsection

