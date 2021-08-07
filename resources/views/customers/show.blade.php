@extends('layouts.app')

@section('title', 'Customer Page')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">

  <style>

      .nav-tabs>li {
          height: 43px;
      }
      li.active {
           background: none !important;
      }
      li.active a{
          background-color: #dddddd2b !important;
      }
      button:focus{
          outline: none !important;
      }
      .inbox_people {
          background: #f8f8f8 none repeat scroll 0 0;
          float: left;
          overflow: hidden;
          width: 40%; border-right:1px solid #c4c4c4;
      }
      .inbox_msg {
          border: 1px solid #c4c4c4;
          clear: both;
          overflow: hidden;
      }
      .top_spac{ margin: 20px 0 0;}


      .recent_heading {float: left; width:40%;}
      .srch_bar {
          display: inline-block;
          text-align: right;
          width: 60%; padding:
      }
      .headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}

      .recent_heading h4 {
          color: #3595d7;
          font-size: 21px;
          margin: auto;
      }
      .srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
      .srch_bar .input-group-addon button {
          background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
          border: medium none;
          padding: 0;
          color: #707070;
          font-size: 18px;
      }
      .srch_bar .input-group-addon { margin: 0 0 0 -27px;}

      .chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
      .chat_ib h5 span{ font-size:13px; float:right;}
      .chat_ib p{ font-size:14px; color:#989898; margin:auto}
      .chat_img {
          float: left;
          width: 11%;
      }
      .chat_ib {
          float: left;
          padding: 0 0 0 15px;
          width: 88%;
      }

      .chat_people{ overflow:hidden; clear:both;}
      .chat_list {
          border-bottom: 1px solid #c4c4c4;
          margin: 0;
          padding: 18px 16px 10px;
      }
      .inbox_chat { height: 550px; overflow-y: scroll;}

      .active_chat{ background:#ebebeb;}

      .incoming_msg_img {
          display: inline-block;
          width: 6%;
      }
      .received_msg {
          display: inline-block;
          padding: 0 0 0 10px;
          vertical-align: top;
          width: 92%;
      }
      .received_withd_msg p {
          background: #ebebeb none repeat scroll 0 0;
          border-radius: 3px;
          color: #646464;
          font-size: 14px;
          margin: 0;
          padding: 5px 10px 5px 12px;
          width: 100%;
      }
      .time_date {
          color: #747474;
          display: block;
          font-size: 12px;
          margin: 8px 0 0;
      }
      .received_withd_msg { width: 57%;}
      .mesgs {
          padding: 30px 15px 10px 25px;
          width: 100%;
          background: #F9F9F9;
          margin-bottom: 50px;
      }

      .sent_msg p {
          background: #3595d7 none repeat scroll 0 0;
          border-radius: 3px;
          font-size: 14px;
          margin: 0; color:#fff;
          padding: 5px 10px 5px 12px;
          width:100%;
      }
      .outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
      .sent_msg {
          float: right;
          width: 46%;
      }
      .input_msg_write input {
          background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
          border: medium none;
          color: #4c4c4c;
          padding: 15px 2px;
          font-size: 15px;
          min-height: 48px;
          outline: none !important;
          width: 100%;
      }

      .type_msg {border-top: 1px solid #c4c4c4;position: relative;}
      .msg_send_btn {
          background: #3595d7 none repeat scroll 0 0;
          border: medium none;
          border-radius: 50%;
          color: #fff;
          cursor: pointer;
          font-size: 17px;
          height: 33px;
          position: absolute;
          right: 0;
          top: 11px;
          width: 33px;
      }
      .messaging { padding: 0 0 50px 0;}
      .msg_history {
          height: 516px;
          overflow-y: auto;
          padding-bottom: 15px;
      }

      .remove-screenshot {
        position: absolute;
        top: 0px;
        right: 0px;
      }

      .floating-arrows {
        position: fixed;
        z-index: 9;
        top: 50%;
      }

      .floating-arrows.left {
        left: 20px;
      }

      .floating-arrows.right {
        right: 20px;
      }

      #message-wrapper {
        height: 450px;
        overflow-y: scroll;
      }

      .show-images-wrapper {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
      }
      .balon1, .balon2 {

          margin-top: 5px !important;
          margin-bottom: 5px !important;

      }


      .balon1 a {

          background: #42a5f5;
          color: #fff !important;
          border-radius: 20px 20px 3px 20px;
          display: block;
          max-width: 75%;
          padding: 7px 13px 7px 13px;

      }

      .balon1:before {

          content: attr(data-is);
          position: absolute;
          right: 15px;
          bottom: -0.8em;
          display: block;
          font-size: .750rem;
          color: rgba(84, 110, 122,1.0);

      }

      .balon2 a {

          background: #f1f1f1;
          color: #000 !important;
          border-radius: 20px 20px 20px 3px;
          display: block;
          max-width: 75%;
          padding: 7px 13px 7px 13px;

      }

      .balon2:before {

          content: attr(data-is);
          position: absolute;
          left: 13px;
          bottom: -0.8em;
          display: block;
          font-size: .750rem;
          color: rgba(84, 110, 122,1.0);

      }

      #preview-image-modelodal {
        text-align: center;
        padding: 2!important;
      }
      .mic-button-input{
        width: 50px;
      }
      .mic-active {
        background-color: red; 
      }
  </style>
  {{--new chat--}}
  <style>
      .nav-tabs{
          border-bottom:none !important;
      }
        #chat-history {
            /*background-color: #EEEEEE;*/
            max-height: 516px;
            overflow-y: scroll;
            overflow-x: hidden;
        }

        .speech-wrapper .bubble.alt {
            margin: 0 0 25px 20% !important;
        }

        .show-images-wrapper {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
      select.input-sm {
          height: 35px;
          font-size: 14px !important;
          line-height: 35px;
          color: #757575 !important;
      }
      .input-sm {
          height: 35px;
          font-size: 14px !important;
          color: #757575 !important;
      }

        .label-attached-img {
            border: 1px solid #fff;
            display: block;
            position: relative;
            cursor: pointer;
        }

        .label-attached-img:before {
            background-color: white;
            color: white;
            content: " ";
            display: block;
            border-radius: 50%;
            border: 1px solid grey;
            position: absolute;
            top: -5px;
            left: -5px;
            width: 25px;
            height: 25px;
            text-align: center;
            line-height: 28px;
            transition-duration: 0.4s;
            transform: scale(0);
        }

        :checked + .label-attached-img {
            border-color: #ddd;
        }

        :checked + .label-attached-img:before {
            content: "✓";
            background-color: grey;
            transform: scale(1);
        }

        :checked + .label-attached-img img {
            transform: scale(0.9);
            box-shadow: 0 0 5px #333;
            z-index: -1;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 58px;
            height: 27px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider.round {
            border-radius: 36px;
            height: 28px;
            width: 57px;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 19px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .dot {
            height: 10px;
            width: 10px;
            background-color: green;
            border-radius: 50%;
            display: inline-block;
        }
        .top-head-btn{
            margin-right: 15px;
        }
        .top-head-btn .btn.btn-xs.btn-secondary, .add-address, .middle-sec .btn-secondary, .sec-msg .btn-secondary, #updateCustomerButton, .verify-btn{
            background: #fff;
            color: #757575 !important;
            border: 1px solid #ddd;
            padding: 5px 12px !important;
        }
    </style>
@endsection

@section('content')
    <div id="overlay">
        <div style="position: absolute; top: 10px; right: 10px">
            <button class="btn btn-danger btn-sm maximize-chat-box">Close</button>
        </div>
    </div>


    <h2 class="page-heading flex" style="padding: 8px 5px 8px 10px;border-bottom: 1px solid #ddd;line-height: 32px;">Customer Page
        <div class="margin-tb" style="flex-grow: 1;">
            <div class="pull-right ">
                @if(isset($searchedMessages) && $searchedMessages)
                    <div>
                        <h5 style="display: block;">You searched: <strong>{{ Request::get('sm') }}</strong></h5>
                        @foreach($searchedMessages as $message)
                            <p class="p-2 m-2" style="border-left: 4px solid #cccccc; background-color: #f5f5f5">{{ $message->message }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="pull-right top-head-btn mr-2" style="flex-grow: 1; text-align: right">
                    <a class="btn btn-xs btn-secondary" href="{{ route('customer.index') }}">Back</a>
                    <a class="btn btn-xs btn-secondary" href="#" id="quick_add_lead">+ Lead</a>
                    <a class="btn btn-xs btn-secondary" href="#" id="quick_add_order">+ Order</a>
                    <button type="button" class="btn btn-xs btn-secondary" data-toggle="modal" data-target="#privateViewingModal">Set Up for Private Viewing</button>
                    <a class="btn btn-secondary btn-xs" href="{{ action('CustomerController@exportCommunication', $customer->id) }}">Export Chat</a>
                </div>
            </div>
        </div>
    </h2>



@include('customers.partials.modal-private-viewing')

@include('partials.flash_messages')

@if (isset($customer_ids))
  @if ($previous_customer_id != 0)
    <div class="floating-arrows left">
      <form class="d-inline" action="{{ route('customer.post.show', $previous_customer_id) }}" method="POST">
        @csrf
        <input type="hidden" name="customer_ids" value="{{ $customer_ids }}">

        <button type="submit" class="btn btn-image"><img src="/images/back.png" /></button>
      </form>
    </div>
  @endif

  @if ($next_customer_id != 0)
    <div class="floating-arrows right">
      <form class="d-inline" action="{{ route('customer.post.show', $next_customer_id) }}" method="POST">
        @csrf
        <input type="hidden" name="customer_ids" value="{{ $customer_ids }}">

        <button type="submit" class="btn btn-image"><img src="/images/next.png" /></button>
      </form>
    </div>
  @endif
@endif

<div style="margin: 0 10px" id="exTab2">
  <ul class="nav nav-tabs">
    <li class="active">
      <a href="#one" data-toggle="tab" class="btn btn-image"><img src="/images/customer-info.png" /></a>
    </li>
    <li>
      <a href="#6" data-toggle="tab" class="btn btn-image"><img src="/images/customer-call-recording.png" /></a>
    </li>
    @if (count($customer->leads) > 0)
    <li><a href="#2" data-toggle="tab" class="btn btn-image"><img src="/images/customer-lead.png" /></a></li>
    @endif
    @if (count($customer->orders) > 0)
      <li><a href="#3" data-toggle="tab" class="btn btn-image"><img src="/images/customer-order.png" /></a></li>
    @endif
    @if ($customer->instagramThread)
      <li><a href="#igdm" data-toggle="tab">Instagram DM</a></li>
    @endif
      @if($customer->facebook_id)
          <li><a class="btn btn-image" href="#facebook" data-toggle="tab">
                  <img style="width: 16px;" src="{{ asset('images/facebook.png') }}" alt="Facebook">
              </a></li>
      @endif
    @if (count($customer->private_views) > 0)
      <li><a href="#private_view_tab" data-toggle="tab" class="btn btn-image"><img src="/images/customer-private-viewing.png" /></a></li>
    @endif
    <li>
      <a href="#suggestion_tab" data-toggle="tab" class="btn btn-image"><img src="/images/customer-suggestion.png" /></a>
    </li>
    <li>
      <a href="#email_tab" data-toggle="tab" data-customerid="{{ $customer->id }}" data-type="inbox" class="btn btn-image"><img src="/images/customer-email.png" /></a>
    </li>
  </ul>
</div>

<div style="margin: 0 10px" class="row">
  <div class="col-xs-12 col-md-4 border">
    <!-- The Modal -->
    <div id="preview-image-model" class="modal col-6" data-backdrop="false">
      <span class="close">&times;</span>
      <div class="row">
        <div class="col-12"><img class="modal-content" height="500px;" id="img01"></div>
      </div>
    </div>
    <div class="tab-content">
      <div class="tab-pane active mt-3 ml-3" id="one">
        <div class="row">
          <div class="col-xs-12 pl-1">
            <div class="d-flex">
              @if ($customer->is_priority == 1)
                <div class="form-group">
                  <button type="button" class="btn btn-image priority-customer pl-0" data-id="{{ $customer->id }}"><img src="/images/customer-priority.png" /></button>
                </div>
              @else
                <div class="form-group">
                  <button type="button" class="btn btn-image priority-customer pl-0" data-id="{{ $customer->id }}"><img src="/images/customer-not-priority.png" /></button>
                </div>
              @endif

              <div class="form-group form-inline">
                <input type="text" name="name" id="customer_name" class="form-control input-sm" placeholder="Name" value="{{ $customer->name }}">
              </div>

              <div class="form-group pl-3 d-flex">
                <button type="button" class="btn btn-xs btn-image call-twilio " data-context="customers" data-id="{{ $customer->id }}" data-phone="{{ $customer->phone }}"><img src="/images/call.png" /></button>

                @if ($customer->is_blocked == 1)
                  <button type="button" class="btn btn-xs btn-image block-twilio" data-id="{{ $customer->id }}"><img src="/images/blocked-twilio.png" /></button>
                @else
                  <button type="button" class="btn btn-xs btn-image block-twilio" data-id="{{ $customer->id }}"><img src="/images/unblocked-twilio.png" /></button>
                @endif

                @if ($customer->do_not_disturb == 1)
                  <button type="button" class="btn btn-xs btn-image" data-id="{{ $customer->id }}" id="do_not_disturb"><img src="/images/do-not-disturb.png" /></button>
                @else
                  <button type="button" class="btn btn-xs btn-image" data-id="{{ $customer->id }}" id="do_not_disturb"><img src="/images/do-disturb.png" /></button>
                @endif

                @if ($customer->is_flagged == 1)
                  <button type="button" class="btn btn-xs btn-image flag-customer" data-id="{{ $customer->id }}"><img src="/images/flagged.png" /></button>
                @else
                  <button type="button" class="btn btn-xs btn-image flag-customer" data-id="{{ $customer->id }}"><img src="/images/unflagged.png" /></button>
                @endif

                <button type="button" class="btn btn-xs btn-image" data-toggle="modal" data-target="#advancePaymentModal"><img src="/images/advance-link.png" /></button>
                <button type="button" class="btn btn-xs btn-xs btn-image" data-toggle="modal" data-target="#sendContacts"><img src="/images/details.png" /></button>
                <a class="d-flex" style="align-items: center" href="{{ route('customer.download.contact-pdf',[$customer->id]) }}" target="_blank">
                  <button type="button" class="btn btn-xs btn-image"><img src="/images/download.png" /></button>
                </a>

                @include('customers.partials.modal-advance-link')
              </div>
            </div>

            {{-- <div class="form-group">
      				<input type="checkbox" name="do_not_disturb" id="do_not_disturb" {{ $customer->do_not_disturb ? 'checked' : '' }} data-id="{{ $customer->id }}">
      				<label for="do_not_disturb">Do Not Disturb</label>

              <span class="text-success change_status_message" style="display: none;">Successfully updated DND status</span>
      			</div> --}}

              <div class="form-group form-inline">
                <input type="number" id="customer_phone" name="phone" class="form-control input-sm" placeholder="910000000000" value="{{ $customer->phone }}">

                @if (strlen($customer->phone) != 12 || !preg_match('/^[91]{2}/', $customer->phone))
                  <span class="badge badge-danger ml-3" data-toggle="tooltip" data-placement="top" title="Number must be 12 digits and start with 91">!</span>
                @endif
                {{-- <strong>Phone:</strong> <span data-twilio-call data-context="customers" data-id="{{ $customer->id }}">{{ $customer->phone }}</span> --}}
              </div>

            <div class="form-group">
              {{-- <strong>Address:</strong> {{ $customer->address }} --}}
              <textarea name="address" id="customer_address" class="form-control input-sm" rows="3" cols="80" placeholder="Address">{{ $customer->address }}</textarea>
            </div>

              @if (!$customer->customerAddress->isEmpty())
                  @foreach ($customer->customerAddress as $address)
                      @if($address->address_type && $address->company)
                      <label class="pl-1">{{$address->address_type}}</label>
                      <textarea name="address" class="form-control input-sm mb-4" rows="2" cols="80" placeholder="Address">{{$address->company . ','}} {{$address->street . ','}} {{$address->region }} {{ '-' . $address->postcode . ','}} {{$address->country_id}}
                      </textarea>
                      @endif
                  @endforeach
               @endif

              <button class="btn btn-secondary add-address mb-4" data-toggle="modal" data-target="#exampleModal">Add Address +</button>

              <div class="modal" id="exampleModal" tabindex="-1" role="dialog">
                  <div class="modal-dialog" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title">Add Address</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                          <div class="modal-body">
                              <form id="add_customer_address">

                                  @csrf
                                  <input type="hidden" name="customer_id" value="{{$customer->id}}">
                                  <div class="row">
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <input type="number" name="entity_id" class="form-control input-sm" placeholder="Entitty Id">
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <input type="number" name="parent_id"  class="form-control input-sm" placeholder="Parent Id">
                                          </div>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <input type="text" name="address_type" class="form-control input-sm" placeholder="Add address Type">
                                          </div>

                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <input type="text" name="region" class="form-control input-sm" placeholder="Region">
                                          </div>

                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <input type="number" name="region_id"  class="form-control input-sm" placeholder="Region Id">
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <input type="text" name="postcode" class="form-control input-sm" placeholder="Postcode">
                                          </div>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <input type="text" name="firstname"  class="form-control input-sm" placeholder="First Name">
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <input type="text" name="middlename"  class="form-control input-sm" placeholder="Middlename">
                                          </div>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <input type="text" name="company" class="form-control input-sm" placeholder="Company name">
                                          </div>

                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <input type="text" name="country_id"  class="form-control input-sm" placeholder="Country">
                                          </div>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <input type="number" name="telephone"  class="form-control input-sm" placeholder="Telephone">
                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <select name="prefix" id="filter" class="form-control form-control-sm">
                                                  <option value="">Select Prefix..</option>
                                                  <option value="Mr">Mr.</option>
                                                  <option value="Mrs">Mrs</option>
                                                  <option value="Miss">Miss</option>
                                              </select>
                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <input type="text" name="street"  class="form-control input-sm" placeholder="Street">
                                          </div>
                                      </div>
                                  </div>


                                <div style="text-align: right;" class="mt-4">
                                    <button class="btn btn-primary add_customer_address-btn">Save changes</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>




                              </form>
                          </div>
                      </div>
                  </div>
              </div>



            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <input type="text" name="city" id="customer_city" class="form-control input-sm" placeholder="City" value="{{ $customer->city }}">
                </div>
              </div>

              <div class="col-6">
                <div class="form-group">
                  <input type="text" name="country" id="customer_country" class="form-control input-sm" placeholder="Country" value="{{ $customer->country }}">
                </div>
              </div>

              <div class="col-6">
                <div class="form-group">
                  <input type="number" name="pincode" id="customer_pincode" class="form-control input-sm" placeholder="91111" value="{{ $customer->pincode }}">
                </div>
              </div>
            </div>

              <div class="form-group">
                {{-- <strong>Email:</strong> <a href="#" class="btn-link" data-toggle="modal" data-target="#emailSendModal">{{ $customer->email }}</a> --}}
                <input type="email" name="email" id="customer_email" class="form-control input-sm" placeholder="Email" value="{{ $customer->email }}">
              </div>

              <div class="form-group">
                <input type="text" name="insta_handle" id="customer_insta_handle" class="form-control input-sm" placeholder="Instagram Handle" value="{{ $customer->insta_handle }}">
              </div>

              <div class="form-group">
        				<select name="whatsapp_number" class="form-control input-sm" id="whatsapp_change">
        					<option value>Whatsapp Number</option>

                  @foreach ($api_keys as $api_key)
        						<option value="{{ $api_key->number }}" {{ $customer->whatsapp_number == $api_key->number ? 'selected' : '' }}>{{ $api_key->number }}</option>
        					@endforeach
        				</select>

                <span class="text-success change_status_message" style="display: none;">Successfully changed whatsapp number</span>
        			</div>

            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <select name="rating" class="form-control input-sm" id="customer_rating">
                    <option value>Select Rating</option>
                    <option value="1" {{ '1' == $customer->rating ? 'selected' : '' }}>1</option>
                    <option value="2" {{ '2' == $customer->rating ? 'selected' : '' }}>2</option>
                    <option value="3" {{ '3' == $customer->rating ? 'selected' : '' }}>3</option>
                    <option value="4" {{ '4' == $customer->rating ? 'selected' : '' }}>4</option>
                    <option value="5" {{ '5' == $customer->rating ? 'selected' : '' }}>5</option>
                    <option value="6" {{ '6' == $customer->rating ? 'selected' : '' }}>6</option>
                    <option value="7" {{ '7' == $customer->rating ? 'selected' : '' }}>7</option>
                    <option value="8" {{ '8' == $customer->rating ? 'selected' : '' }}>8</option>
                    <option value="9" {{ '9' == $customer->rating ? 'selected' : '' }}>9</option>
                    <option value="10" {{ '10' == $customer->rating ? 'selected' : '' }}>10</option>
                  </select>
                </div>
              </div>

              <div class="col-6">
                <div class="form-group">
                  <select class="form-control input-sm" name="shoe_size" id="customer_shoe_size">
                    <option value="">Select a Shoe Size</option>
                    <option value="34" {{ $customer->shoe_size == '34'? 'selected' : '' }}>34</option>
                    <option value="34.5" {{ $customer->shoe_size == '34.5'? 'selected' : '' }}>34.5</option>
                    <option value="35" {{ $customer->shoe_size == '35'? 'selected' : '' }}>35</option>
                    <option value="35.5" {{ $customer->shoe_size == '35.5'? 'selected' : '' }}>35.5</option>
                    <option value="36" {{ $customer->shoe_size == '36'? 'selected' : '' }}>36</option>
                    <option value="36.5" {{ $customer->shoe_size == '36.5'? 'selected' : '' }}>36.5</option>
                    <option value="37" {{ $customer->shoe_size == '37'? 'selected' : '' }}>37</option>
                    <option value="37.5" {{ $customer->shoe_size == '37.5'? 'selected' : '' }}>37.5</option>
                    <option value="38" {{ $customer->shoe_size == '38'? 'selected' : '' }}>38</option>
                    <option value="38.5" {{ $customer->shoe_size == '38.5'? 'selected' : '' }}>38.5</option>
                    <option value="39" {{ $customer->shoe_size == '39'? 'selected' : '' }}>39</option>
                    <option value="39.5" {{ $customer->shoe_size == '39.5'? 'selected' : '' }}>39.5</option>
                    <option value="40" {{ $customer->shoe_size == '40'? 'selected' : '' }}>40</option>
                    <option value="40.5" {{ $customer->shoe_size == '40.5'? 'selected' : '' }}>40.5</option>
                    <option value="41" {{ $customer->shoe_size == '41'? 'selected' : '' }}>41</option>
                    <option value="41.5" {{ $customer->shoe_size == '41.5'? 'selected' : '' }}>41.5</option>
                    <option value="42" {{ $customer->shoe_size == '42'? 'selected' : '' }}>42</option>
                    <option value="42.5" {{ $customer->shoe_size == '42.5'? 'selected' : '' }}>42.5</option>
                    <option value="43" {{ $customer->shoe_size == '43'? 'selected' : '' }}>43</option>
                    <option value="43.5" {{ $customer->shoe_size == '43.5'? 'selected' : '' }}>43.5</option>
                    <option value="44" {{ $customer->shoe_size == '44' ? 'selected' : ''}}>44</option>
                  </select>
                </div>
              </div>

              <div class="col-6">
                <div class="form-group">
                  <input type="text" name="clothing_size" id="customer_clothing_size" class="form-control input-sm" placeholder="Clothing Size" value="{{ $customer->clothing_size }}">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <select class="form-control input-sm" name="gender" id="customer_gender">
                    <option value="female" {{ 'female' == $customer->gender ? 'selected' : '' }}>Female</option>
                    <option value="male" {{ 'male' == $customer->gender ? 'selected' : '' }}>Male</option>
                  </select>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label>Whatsapp No :</label>
                  <select class="form-control change-whatsapp-no" data-customer-id="<?php echo $customer->id; ?>" data-type="whatsapp_number">
                      <option value="">-No Selected-</option>
                      @foreach(array_filter(config("apiwha.instances")) as $number => $apwCate)
                          @if($number != "0")
                              <option {{ ($number == $customer->whatsapp_number && $customer->whatsapp_number != '') ? "selected='selected'" : "" }} value="{{ $number }}">{{ $number }}</option>
                          @endif
                      @endforeach
                  </select>
                </div>
                <div class="form-group">
                    <label>Broadcast No :</label>
                    <select class="form-control change-whatsapp-no" data-customer-id="<?php echo $customer->id; ?>" data-type="broadcast_number">
                        <option value="">-No Selected-</option>
                        @foreach($broadcastsNumbers as $number => $apwCate)
                            @if($number != "0")
                                <option {{ ($number == $customer->broadcast_number && $customer->broadcast_number != '') ? "selected='selected'" : "" }} value="{{ $number }}">{{ $number }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
              </div>
              <div class="col-9">
                <div class="form-group">
                  <label>Broadcast Number</label>
                  <input type="text"  class="form-control input-sm" value="{{ $customer->broadcast_number }}" disabled>
                </div>
              </div>
              <div class="col-9">
                <div class="form-group">
                  <label>Registration Source</label>
                  <select class="form-control update-customer-field select2" data-customer-id="<?php echo $customer->id; ?>" data-type="store_website_id">
                      <option value="">-No Selected-</option>
                      @foreach(\App\StoreWebsite::all()->pluck("website","id")->toArray() as $r => $sWeb)
                          <option {{ ($r == $customer->store_website_id && $customer->store_website_id != '') ? "selected='selected'" : "" }} value="{{ $r }}">{{ $sWeb }}</option>
                      @endforeach
                  </select>
                </div>
              </div>
              <div class="col-9">
                <div class="form-group">
                  <label>Registration User</label>
                  <select class="form-control update-customer-field select2" data-customer-id="<?php echo $customer->id; ?>" data-type="user_id">
                      <option value="">-No Selected-</option>
                      @foreach(\App\User::all()->pluck("name","id")->toArray() as $r => $sWeb)
                          <option {{ ($r == $customer->user_id && $customer->user_id != '') ? "selected='selected'" : "" }} value="{{ $r }}">{{ $sWeb }}</option>
                      @endforeach
                  </select>
                </div>
              </div>
               <div class="col-3 mb-2 d-flex align-bottom" style="align-items: flex-end">
                <div class="form-group">
                 <label class="switch" style="margin: 0px">
                    @if(isset($customer->customerMarketingPlatformActive) && $customer->customerMarketingPlatformActive->active == 1)
                    <input type="checkbox" class="checkboxs" checked value="{{ $customer->id }}">
                    @else
                      <input type="checkbox" class="checkboxs" value="{{ $customer->id }}" id="marketing{{ $customer->id }}">
                    @endif
                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <strong>Created at:</strong> {{ Carbon\Carbon::parse($customer->created_at)->format('d-m H:i') }}
                </div>
              </div>
            </div>

            <div class="form-group">
              <button type="button" id="updateCustomerButton" class="btn btn-xs btn-secondary">Save</button>
            </div>

            @if ($customer->credit > 0)
              <div class="form-group">
                <strong>Credit:</strong> {{ $customer->credit }}
              </div>

              <div class="form-group">
                <button type="button" class="btn btn-xs btn-secondary issue-credit-button" data-id="{{ $customer->id }}">Issue Credit</button>
              </div>

              @if ($customer->credits_issued)
                <ul>
                  @foreach ($customer->credits_issued as $credit)
                    <li>Email sent on {{ \Carbon\Carbon::parse($credit->created_at)->format('H:i d-m') }}</li>
                  @endforeach
                </ul>
              @endif
            @endif
          </div>


        </div>
      </div>

      <div class="tab-pane mt-3" id="6">
        <div class="row">
          <h4 class="ml-3">Call Recording</h4>
          <div class="table-responsive m-3">
            <table class="table table-bordered">
              {{-- <tr>
                <th style="width: 50%">Call Recording</th>
                <th style="width: 25%">Message</th>
                <th style="width: 25%">Call Time</th>
              </tr> --}}
              @if (count($customer->call_recordings) > 0)
                @foreach ($customer->call_recordings as $history_val)
                  <tr class="">
                    <td>
                      {{$history_val['message']}}
                      {{ \Carbon\Carbon::parse($history_val['created_at'])->format('H:i d-m') }}
                      <br>

                      <audio src="{{$history_val['recording_url']}}" controls preload="metadata">
                        <p>Alas, your browser doesn't support html5 audio.</p>
                      </audio>
                    </td>
                    {{-- <td></td>
                    <td></td> --}}
                  </tr>
                @endforeach
              @endif
          </table>
          </div>
        </div>
      </div>
        @if ($customer->instagramThread)
            <div class="tab-pane mt-3" id="igdm">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mesgs">
                            <p style="font-size: 24px;font-weight: bolder;" class="text-center mb-5">Instagram Messages</p>
                            <div class="msg_history"></div>
                            <div class="type_msg">
                                <div class="input_msg_write">
                                    <input data-thread-id="{{$customer->instagramThread->thread_id}}" type="text" class="write_msg ig-reply" placeholder="Type a message" />
                                    <label for="ig_image" class="btn btn-info"  style="position: absolute; top: 10px; right: 5px; border-radius:50%"><i class="fa fa-image" aria-hidden="true"></i></label>
                                    <input type="file" data-thread-id="{{$customer->instagramThread->thread_id}}" name="ig_image" id="ig_image" style="display: none;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

      @if (count($customer->leads) > 0)
        <div class="tab-pane mt-3" id="2">
          <div id="leadAccordion">
            <?php $leadCount = count($customer->leads);?>
            @foreach ($customer->leads as $key => $lead)
              <div class="card">
                <div class="card-header" id="headingLead{{ $key + 1 }}">
                  <h5 class="mb-0 mt-0">
                    <button class="btn btn-link collapsed collapse-fix" data-toggle="collapse" data-target="#lead{{ $key + 1 }}" aria-expanded="false" aria-controls="lead{{ $key + 1 }}">
                      Lead {{ $leadCount }}
                      <?php $leadCount--;?>
                      <a href="{{ route('leads.show', $lead->id) }}" class="btn-image" target="_blank"><img src="/images/view.png" /></a>
                    </button>
                  </h5>
                </div>
                <div id="lead{{ $key + 1 }}" class="collapse collapse-element" aria-labelledby="headingLead{{ $key + 1 }}" data-parent="#leadAccordion">
                  <div class="card-body">
                    <form action="{{ route('leads.erpLeads.store') }}" method="POST" enctype="multipart/form-data" class="erp_lead_frm">
                      <span class="text-success erp_update_message" style="display: none;">Successfully update</span>
                      @csrf
                      <input type="hidden" name="id" value="{{$lead->id}}">
                      <input type="hidden" name="product_id" value="{{$lead->product_id}}">
                      <input type="hidden" name="customer_id" value="{{$lead->customer_id}}">
                      <div class="row">
                        <div class="col-xs-12">
                          <div class="form-group">
                            <strong>Brand:</strong>
                            <select name="brand_id" class="form-control multi_brand multi_brand_select multi_select2" multiple>
                              <option value="">Brand</option>
                              @foreach($brands as $brand_item)
                                <option value="{{$brand_item['id']}}" {{ $brand_item['id'] == $lead->brand_id ? "selected" : ''}} data-brand-segment="{{$brand_item['brand_segment']}}">{{$brand_item['name']}}</option>
                              @endforeach
                            </select>

                          </div>

                          <div class="form-group">
                            <strong>Category</strong>
                            @php
                            $category_selection = \App\Category::attr(['name' => 'category_id','class' => 'form-control multi_select2'])
                            ->selected($lead->category_id)
                            ->renderAsDropdown();
                            @endphp
                            {!! $category_selection  !!}
                          </div>
                          <div class="form-group">
                            <strong>Brand Segment:</strong>
                            {{ App\Helpers\ProductHelper::getBrandSegment('brand_segment[]', explode(",", $lead->brand_segment), ['class' => "form-control brand_segment_select", 'multiple' => ''])}}
                          </div>
                          <?php /*
                          <div class="form-group">
                            <strong> Selected Product :</strong>

                            <select name="selected_product[]" class="select2{{ $key + 1 }} form-control" multiple="multiple"></select>

                            @if ($errors->has('selected_product'))
                              <div class="alert alert-danger">{{$errors->first('selected_product')}}</div>
                            @endif
                          </div>

                          <script type="text/javascript">
                          $(document).ready(function() {
                            var key = {{ $key + 1 }};
                            jQuery('.select2' + key).select2({
                              ajax: {
                                url: '/productSearch/',
                                dataType: 'json',
                                delay: 750,
                                data: function (params) {
                                  return {
                                    q: params.term, // search term
                                  };
                                },
                                processResults: function (data,params) {

                                  params.page = params.page || 1;

                                  return {
                                    results: data,
                                    pagination: {
                                      more: (params.page * 30) < data.total_count
                                    }
                                  };
                                },
                              },
                              placeholder: 'Search for Product by id, Name, Sku',
                              escapeMarkup: function (markup) { return markup; },
                              minimumInputLength: 5,
                              width: '100%',
                              templateResult: formatProduct,
                              templateSelection:function(product) {
                                return product.text || product.name;
                              },

                            });




                            @php
                              $selected_products_array = json_decode( $lead->selected_product );
                              $products_array = [];

                              if ( ! empty( $selected_products_array  ) ) {
                                foreach ($selected_products_array  as $product_id) {
                                  $product = \App\Product::find($product_id);

                                  if ($product) {
                                    $products_array[$product_id] = $product->name ? $product->name : $product->sku;
                                  }

                                }
                              }
                            @endphp
                            @if(!empty($products_array ))
                              let data = [
                              @forEach($products_array as $key => $value)
                                {
                                  'id': '{{ $key }}',
                                  'text': '{{$value  }}',
                                },
                              @endforeach
                              ];
                            @endif

                            let productSelect = jQuery('.select2' + key);
                            // create the option and append to Select2
                            @if(!empty($products_array ))
                              data.forEach(function (item) {

                                var option = new Option(item.text,item.id , true, true);
                                productSelect.append(option).trigger('change');

                                // manually trigger the `select2:select` event
                                productSelect.trigger({
                                  type: 'select2:select',
                                  params: {
                                    data: item
                                  }
                                });

                              });
                            @endif

                            function formatProduct (product) {
                              if (product.loading) {
                                return product.sku;
                              }

                              return "<p> <b>Id:</b> " +product.id  + (product.name ? " <b>Name:</b> "+product.name : "" ) +  " <b>Sku:</b> "+product.sku+" </p>";
                            }
                          });
                        </script>
                        */?>

                        <div class="form-group">
                          <strong>status:</strong>

                          @if (count($lead->status_changes) > 0)
                            <button type="button" class="btn btn-xs btn-secondary change-history-toggle">?</button>

                            <div class="change-history-container hidden">
                              <ul>
                                @foreach ($lead->status_changes as $status_history)
                                  <li>
                                    {{ array_key_exists($status_history->user_id, $users_array) ? $users_array[$status_history->user_id] : 'Unknown User' }} - <strong>from</strong>: {{ $status_history->from_status }} <strong>to</strong> - {{ $status_history->to_status }} <strong>on</strong> {{ \Carbon\Carbon::parse($status_history->created_at)->format('H:i d-m') }}
                                  </li>
                                @endforeach
                              </ul>
                            </div>
                          @endif

                          <Select name="lead_status_id" class="form-control change_status" data-leadid="{{ $lead->id }}">
                            @foreach($lead_status as $key => $value)
                              <option value="{{$value}}" {{$value == $lead->lead_status_id ? 'selected':''}}>{{$key}}</option>
                            @endforeach
                          </Select>
                          <span class="text-success change_status_message" style="display: none;">Successfully changed status</span>

                          <input type="hidden" class="form-control" name="userid" placeholder="status" value="{{$lead->userid}}"/>

                        </div>
                        <?php /*
                        <div class="form-group">
                          <strong>Created by:</strong>

                          <input type="text" class="form-control" name="" placeholder="Created by" value="{{ $lead->userid != 0 ? App\Helpers::getUserNameById($lead->userid) : '' }}" readonly/>
                        </div>

                        <div class="form-group">
                          <strong>Comments:</strong>
                          <textarea  class="form-control" name="comments" placeholder="comments">{{$lead->comments}} </textarea>
                        </div>
                        */?>
                        <div class="form-group">
                          <strong>Sizes:</strong>
                          <input type="text" name="size" value="{{ $lead->size }}" class="form-control" placeholder="S, M, L">
                        </div>
                        <?php /*
                        <div class="form-group">
                          <strong>Assigned To:</strong>
                          <Select name="assigned_user" class="form-control">

                            @foreach($users_array as $id => $user)
                              <option value="{{ $id }}" {{ $id == $lead->assigned_user ? 'Selected=Selected':''}}>{{ $user }}</option>
                            @endforeach
                          </Select>
                        </div>
                        */?>
                        <div class="form-group">
                          <strong>Gender:</strong>
                          <select name="gender" class="form-control">
                            <option value="male" {{ 'male' == $lead->gender ? "selected" : ''}}>Male</option>
                            <option value="female" {{ 'female' == $lead->gender ? "selected" : ''}}>Female</option>
                          </select>
                        </div>
                        <?php $images = $lead->getMedia(config('constants.media_tags')) ?>
                        @if ($lead->hasMedia(config('constants.media_tags')))
                          <div class="row">
                            @foreach ($images as $key => $image)
                              <div class="col-md-4 old-image{{ $key }}" style="
                              @if ($errors->has('image'))
                                display: none;
                              @endif
                              ">
                              <p>
                                <img src="{{ $image->getUrl() ?? '#no-image' }}" class="img-responsive" alt="">
                                <button class="btn btn-image removeOldImage" data-id="{{ $key }}" media-id="{{ $image->id }}"><img src="/images/delete.png" /></button>

                                <input type="text" hidden name="oldImage[{{ $key }}]" value="{{ $images ? '0' : '-1' }}">
                              </p>
                            </div>
                          @endforeach
                        </div>
                      @endif


                      @if (count($images) == 0)
                        <input type="text" hidden name="oldImage[0]" value="{{ $images ? '0' : '-1' }}">
                      @endif

                      <div class="form-group new-image" style="">
                        <strong>Upload Image:</strong>
                        <input  type="file" enctype="multipart/form-data" class="form-control" name="image[]" multiple />
                        @if ($errors->has('image'))
                          <div class="alert alert-danger">{{$errors->first('image')}}</div>
                        @endif
                      </div>

                      <div class="form-group">
                        <strong>Created at:</strong>
                        {{ Carbon\Carbon::parse($lead->created_at)->format('d-m H:i') }}
                      </div>
                    </div>

                    <div class="col-xs-12 text-center">
                      <div class="form-group">
                        <button type="submit" class="btn btn-secondary">Update</button>
                      </div>
                    </div>

                  </div>
                </form>
                  </div>
                </div>
              </div>
        @endforeach
          </div>
        </div>
      @endif

      @if (count($customer->orders) > 0)
        <div class="tab-pane mt-3" id="3">
          <div id="orderAccordion">
            @php
              $refunded_orders = [];
              $orderCount = count($customer->orders);
            @endphp
            @foreach ($customer->orders as $key => $order)
              @if ($order->order_status == 'Refund to be processed')
                @php
                  array_push($refunded_orders, $order);
                @endphp
              @endif

              <div class="card">
                <div class="card-header" id="headingOrder{{ $orderCount }}">
                  @php
                    $orderCount--;
                  @endphp
                  <h5 class="mb-0">
                    <button class="btn btn-link collapsed collapse-fix" data-toggle="collapse" data-target="#order{{ $key + 1 }}" aria-expanded="false" aria-controls="order{{ $key + 1 }}">
                      <span class="{{ $order->is_priority == 1 ? 'text-danger' : '' }}">{{ $order->order_id }}</span>
                      <a href="{{ route('order.show', $order->id) }}" class="btn-image" target="_blank"><img src="/images/view.png" /></a>
                      <span class="ml-3">
                        @if (isset($order->delivery_approval) && $order->delivery_approval->approved == 0)
                          <span class="badge">Waiting for Delivery Approval</span>
                        @endif
                      </span>
                    </button>
                  </h5>
                </div>
                <div id="order{{ $key + 1 }}" class="collapse collapse-element" aria-labelledby="headingOrder{{ $key + 1 }}" data-parent="#orderAccordion">
                  <div class="card-body">
                    <form action="{{ route('order.update',$order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="type" value="customer">

                      <div class="row">
                          <div class="col-xs-12">

                            <div class="form-group">
                              <input type="checkbox" name="is_priority" {{ $order->is_priority == 1 ? 'checked' : '' }}>
                              <label for="is_priority">Priority</label>
                            </div>

                            <div class="form-group">
                                <strong>Balance Amount:</strong>
                                <input type="text" class="form-control" name="balance_amount" placeholder="Balance Amount"
                                       value="{{ old('balance_amount') ? old('balance_amount') : $order->balance_amount }}"/>
                                @if ($errors->has('balance_amount'))
                                    <div class="alert alert-danger">{{$errors->first('balance_amount')}}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <strong>Payment Mode :</strong>
                          <?php
                          $paymentModes = new \App\ReadOnly\PaymentModes();

                          echo Form::select('payment_mode',$paymentModes->all(), ( old('payment_mode') ? old('payment_mode') : $order->payment_mode ), ['placeholder' => 'Select a mode','class' => 'form-control']);?>

                                @if ($errors->has('payment_mode'))
                                    <div class="alert alert-danger">{{$errors->first('payment_mode')}}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <strong>Advance Amount:</strong>
                                <input type="text" class="form-control" name="advance_detail" placeholder="Advance Detail"
                                       value="{{ old('advance_detail') ? old('advance_detail') : $order->advance_detail }}"/>
                                @if ($errors->has('advance_detail'))
                                    <div class="alert alert-danger">{{$errors->first('advance_detail')}}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <strong>Received By:</strong>
                                <input type="text" class="form-control" name="received_by" placeholder="Received By"
                                       value="{{ old('received_by') ? old('received_by') : $order->received_by }}"/>
                                @if ($errors->has('received_by'))
                                    <div class="alert alert-danger">{{$errors->first('received_by')}}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <strong>Advance Date:</strong>
                                <input type="date" class="form-control" name="advance_date" placeholder="Advance Date"
                                       value="{{ old('advance_date') ? old('advance_date') : $order->advance_date }}"/>
                                @if ($errors->has('advance_date'))
                                    <div class="alert alert-danger">{{$errors->first('advance_date')}}</div>
                                @endif
                            </div>

                             <div class="form-group">
                                 <strong>status:</strong>

                                 @if (count($order->status_changes) > 0)
                                   <button type="button" class="btn btn-xs btn-secondary change-history-toggle">?</button>

                                   <div class="change-history-container hidden">
                                     <ul>
                                       @foreach ($order->status_changes as $status_history)
                                         <li>
                                           {{ array_key_exists($status_history->user_id, $users_array) ? $users_array[$status_history->user_id] : 'Unknown User' }} - <strong>from</strong>: {{ $status_history->from_status }} <strong>to</strong> - {{ $status_history->to_status }} <strong>on</strong> {{ \Carbon\Carbon::parse($status_history->created_at)->format('H:i d-m') }}
                                         </li>
                                       @endforeach
                                     </ul>
                                   </div>
                                 @endif

                                 <Select name="status" class="form-control change_status order_status" data-orderid="{{ $order->id }}">
                                      @php $order_status = (new \App\ReadOnly\OrderStatus)->all(); @endphp
                                      @foreach($order_status as $key => $value)
                                       <option value="{{$value}}" {{$value == $order->order_status ? 'Selected=Selected':''}}>{{$key}}</option>
                                       @endforeach
                                 </Select>
                                 <span class="text-success change_status_message" style="display: none;">Successfully changed status</span>
                             </div>

                             <div id="tracking-wrapper-{{ $order->id }}" style="display: {{ ($order->order_status == 'Product shiped to Client' || $order->order_status_id == \App\Helpers\OrderHelper::$productShippedToClient) ? 'block' : 'none' }}">
                               <div class="form-group">
                                 <strong>AWB Number:</strong>
                                 <input type="text" name="awb" class="form-control" id="awb_field_{{ $order->id }}" value="{{ $order->awb }}" placeholder="00000000000">
                                 <button type="button" class="btn btn-xs btn-secondary mt-1 track-shipment-button" data-id="{{ $order->id }}">Track</button>
                               </div>

                               <div class="form-group" id="tracking-container-{{ $order->id }}">

                               </div>
                             </div>

                             <div class="form-group">
                                 <strong>Estimated Delivery Date:</strong>
                                 <input type="date" class="form-control" name="estimated_delivery_date" placeholder="Advance Date"
                                        value="{{ old('estimated_delivery_date') ? old('estimated_delivery_date') : $order->estimated_delivery_date }}"/>
                                 @if ($errors->has('estimated_delivery_date'))
                                     <div class="alert alert-danger">{{$errors->first('estimated_delivery_date')}}</div>
                                 @endif
                             </div>


                             <div class="form-group">
                                 <strong>Note if any:</strong>
                                 <input type="text" class="form-control" name="note_if_any" placeholder="Note if any"
                                        value="{{ old('note_if_any') ? old('note_if_any') : $order->note_if_any }}"/>
                                 @if ($errors->has('note_if_any'))
                                     <div class="alert alert-danger">{{$errors->first('note_if_any')}}</div>
                                 @endif
                             </div>


                            <div class="form-group">
                                <strong> Name of Order Handler :</strong>
                          <?php
                          $sales_persons = \App\Helpers::getUsersArrayByRole( 'Admin' );
                          echo Form::select('sales_person',$sales_persons, ( old('sales_person') ? old('sales_person') : $order->sales_person ), ['placeholder' => 'Select a name','class' => 'form-control']);?>
                                @if ($errors->has('sales_person'))
                                    <div class="alert alert-danger">{{$errors->first('sales_person')}}</div>
                                @endif
                            </div>

                             <div class="form-group">
                                 <strong>Created by:</strong>
                                 {{ $order->user_id != 0 ? App\Helpers::getUserNameById($order->user_id) : 'Unknown' }}
                             </div>

                             <div class="form-group">
                               <strong>Created at:</strong>
                               {{ Carbon\Carbon::parse($order->created_at)->format('d-m H:i') }}
                             </div>
                            <div class="form-group">
                                <strong>Remark</strong>
                                {{ $order->remark }}
                            </div>

                            <div class="row">
                              <div class="col-6">
                                @if (isset($order->waybill))
                                  <div class="form-group">
                                    <strong>AWB: </strong> {{ $order->waybill->awb }}
                                    <br>

                                    <a href="{{ route('order.download.package-slip', $order->waybill->id) }}" class="btn-link">Download Package Slip</a>
                                  </div>

                                @else
                                  <div class="form-group">
                                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#generateAWBMODAL{{ $order->id }}">Generate AWB</button>
                                  </div>
                                @endif
                              </div>

                              @if (isset($order))
                                <div class="col-6">
                                    <div class="form-group">
                                      <a href="{{ route('order.advance.receipt.email', $order->id) }}" class="btn btn-secondary">Email Advance Receipt</a>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                      <a href="{{ route('order.advance.receipt.print', $order->id) }}" class="btn btn-secondary">Print Advance Receipt</a>
                                    </div>
                                </div>

                                <div class="col-6">
                                  <div class="form-group">
                                    <a href="{{ route('order.generate.invoice', $order->id) }}" class="btn btn-secondary">Generate Invoice</a>
                                    <a href="{{ route('settings.index') }}" class="btn-link" target="_blank">Edit Consignor Details</a>
                                  </div>
                                </div>

                                <div class="col-6">
                                  @if (!$order->is_sent_refund_initiated())
                                    <div class="form-group">
                                      <button type="button" class="btn btn-secondary send-refund" data-id="{{ $order->id }}">Send Refund Messages</button>
                                      <span class="text-success send-refund-message" style="display: none;">Successfully sent refund messages</span>
                                    </div>
                                  @else
                                    <div class="form-group">
                                      Refund Initiated Email was Sent
                                    </div>
                                  @endif
                                </div>

                                <div class="col-6">
                                  @if ($order->order_type == 'offline' && !$order->is_sent_offline_confirmation())
                                    <div class="form-group">
                                      <a href="{{ route('order.send.confirmation.email', $order->id) }}" class="btn btn-secondary">Send Confirmation Email</a>
                                    </div>
                                  @elseif ($order->is_sent_offline_confirmation())
                                    <div class="form-group">
                                      Offline Confirmation Email was sent
                                    </div>
                                  @endif
                                </div>

                                <div class="col-6">
                                  @if ($order->is_sent_online_confirmation())
                                    <div class="form-group">
                                      Online Confirmation Email was sent
                                    </div>
                                  @endif
                                </div>
                              @endif
                            </div>



                            {{-- <div class="form-group">
                              <a href="#" class="btn btn-secondary create-magento-product" data-id="{{ $order->id }}">Create Magento Product</a>
                            </div> --}}

                          </div>

                          <div class="col-xs-12">
                            <div class="text-center">
                              <h4>Product Details</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="products-table-{{ $order->id }}">
                                  <thead>
                                    <th>Name</th>
                                    <th>Action</th>
                                  </thead>
                                  <tbody>
                                    @foreach($order->order_product  as $order_product)
                                      <tr>

                                          {{-- <td>
                                            @if(isset($order_product->product))

                                            @endif
                                          </td> --}}
                                          <td>
                                            @if(isset($order_product->product))
                                              @php
                                                $string = $order_product->product->supplier;
                                                $expr = '/(?<=\s|^)[a-z]/i';
                                                preg_match_all($expr, $string, $matches);
                                                $supplier_initials = implode('', $matches[0]);
                                                $supplier_initials = strtoupper($supplier_initials);
                                              @endphp

                                              @if ($order_product->product->hasMedia(config('constants.media_tags')))
                                                <img width="150" src="{{ $order_product->product->getMedia(config('constants.media_tags'))->first()->getUrl() }}" data-toggle='tooltip' data-html='true' data-placement='top' title="{{ Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HOD of CRM') ? "<strong>Supplier:</strong> $supplier_initials" : '' }}" />
                                              @endif

                                              {{ $order_product->product->name }} - {{ $order_product->product->color }}
                                              <br>
                                              <span class="text-muted">{{ \App\Http\Controllers\BrandController::getBrandName($order_product->product->brand) }}</span>
                                              <span class="text-muted">{{ $order_product->product->sku }}</span>
                                            @else
                                              {{ $order_product->sku }}
                                            @endif

                                              <input class="form-control" type="text" value="{{ $order_product->product_price }}" name="order_products[{{ $order_product->id }}][product_price]">

                                            @if(!empty($order_product->product->size))
                                              <?php

                                              $sizes = \App\Helpers::explodeToArray($order_product->product->size);
                                              $size_name = 'order_products['.$order_product->id.'][size]';
                                              ?>
                                              <div class="form-inline">
                                                {!! Form::select($size_name,$sizes,( $order_product->size ), ['class' => 'form-control', 'placeholder' => 'Select a size']) !!}

                                                @if (($customer->shoe_size != '' || $customer->clothing_size != '') && $order_product->size != '' && $order->order_type == 'online')
                                                  @if ($customer->shoe_size != $order_product->size && !preg_match("/{$customer->clothing_size}/i", $order_product->size))
                                                    <span class="badge" data-toggle="tooltip" title="Customer and order sizes does not match">!</span>
                                                  @endif
                                                @endif
                                              </div>

                                            @else
                                              <select hidden class="form-control" name="order_products[{{ $order_product->id }}][size]">
                                                <option selected="selected" value=""></option>
                                              </select>
                                              nil
                                            @endif

                                            @if(isset($order_product->product) && count($order_product->product->purchases) > 0)
                                              <select name="order_products[{{$order_product->id}}][purchase_status]" class="form-control">
                                                 @foreach($purchase_status as $key => $value)
                                                  <option value="{{$value}}" {{ $value == $order_product->purchase_status ? 'selected=selected' : '' }}>{{$key}}</option>
                                                  @endforeach
                                              </select>

                                              @if (count($order_product->status_changes) > 0)
                                                <button type="button" class="btn btn-xs btn-secondary change-history-toggle">?</button>

                                                <div class="change-history-container hidden">
                                                  <ul>
                                                    @foreach ($order_product->status_changes as $status_history)
                                                      <li>
                                                        {{ array_key_exists($status_history->user_id, $users_array) ? $users_array[$status_history->user_id] : 'Unknown User' }} - <strong>from</strong>: {{ $status_history->from_status }} <strong>to</strong> - {{ $status_history->to_status }} <strong>on</strong> {{ \Carbon\Carbon::parse($status_history->created_at)->format('H:i d-m') }}
                                                      </li>
                                                    @endforeach
                                                  </ul>
                                                </div>
                                              @endif
                                            @elseif ($order_product->purchase_status != '')
                                              {{ $order_product->purchase_status }}
                                            @else
                                              No Purchase
                                            @endif
                                          </td>

                                          @if(isset($order_product->product))
                                            <td>
                                              <a class="btn btn-image" href="{{ route('products.show',$order_product->product->id) }}"><img src="/images/view.png" /></a>
                                              <a class="btn btn-image remove-product" href="#" data-product="{{ $order_product->id }}"><img src="/images/delete.png" /></a>
                                            </td>
                                          @else
                                            <td></td>
                                          @endif
                                        </tr>
                                      @endforeach
                                  </tbody>
                                </table>
                            </div>

                            <div class="form-group btn-group">
                                <a href="{{ route('attachProducts',['order',$order->id, 'fake-parameter', $customer->id]) }}" class="btn btn-image"><img src="/images/attach.png" /></a>
                                <button type="button" class="btn btn-secondary add-product-button" data-orderid="{{ $order->id }}" data-toggle="modal" data-target="#productModal">+</button>
                            </div>
                          </div>

                          <div class="col-xs-12 text-center">
                            <button type="submit" class="btn btn-secondary">Update</button>
                          </div>
                      </div>
                    </form>

                    <hr>

                    <h4 class="text-center">Delivery Approval</h4>

                    {{-- <form class="form-inline my-3" action="{{ route('order.upload.approval', $order->id) }}" method="POST" enctype="multipart/form-data">
                      @csrf

                      <div class="form-group">
                        <input type="file" name="images[]" required multiple>
                      </div>

                      <button type="submit" class="btn btn-xs btn-secondary ml-3">Upload for Approval</button>
                    </form> --}}

                    @if (isset($order->delivery_approval))
                      <div class="table-responsive">
                        <table class="table">
                          <thead>
                            <tr>
                              <th>Uploaded Photos</th>
                              <th>Approved</th>
                              {{-- <th>Voucher</th> --}}
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>
                                @if ($order->delivery_approval->hasMedia(config('constants.media_tags')))
                                  @foreach ($order->delivery_approval->getMedia(config('constants.media_tags')) as $image)
                                    <img width="150" src="{{ $image->getUrl() ?? '#no-image' }}" />
                                  @endforeach
                                @endif
                              </td>
                              <td>
                                @if ($order->delivery_approval->approved == 1)
                                  Approved
                                @else
                                  <form action="{{ route('order.delivery.approve', $order->delivery_approval->id) }}" method="POST">
                                    @csrf

                                    <button type="submit" class="btn btn-xs btn-secondary">Approve</button>
                                  </form>
                                @endif
                              </td>
                              {{-- <td>
                                @if ($order->delivery_approval->approved == 2)
                                  Approved
                                @else
                                  <form action="{{ route('order.delivery.approve', $order->delivery_approval->id) }}" method="POST">
                                    @csrf

                                    <button type="submit" class="btn btn-xs btn-secondary">Approve</button>
                                  </form>
                                @endif
                              </td> --}}
                              {{-- <td>

                                 @if(auth()->user()->checkPermission('voucher'))
                                  @if ($order->delivery_approval->voucher)
                                    <button type="button" class="btn btn-xs btn-secondary edit-voucher" data-toggle="modal" data-target="#editVoucherModal" data-id="{{ $order->delivery_approval->voucher->id }}" data-amount="{{ $order->delivery_approval->voucher->amount }}" data-travel="{{ $order->delivery_approval->voucher->travel_type }}">Edit Voucher</button>
                                  @else
                                    <button type="button" class="btn btn-xs btn-secondary create-voucher" data-id="{{ $order->delivery_approval->id }}">Create Voucher</button>
                                  @endif
                                @endif
                              </td> --}}
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    @else
                      No Delivery Approvals
                    @endif

                    @include('customers.partials.modal-awb')

                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        @include('customers.partials.modal-status')

        @include('customers.partials.modal-voucher')

        @include('customers.partials.modal-product')
      @endif

      @if (count($customer->private_views) > 0)
        <div class="tab-pane mt-3" id="private_view_tab">
          <div class="table-responsive">
            <table class="table table-bordered">
              <tr>
                <th>Products</th>
                <th>Date</th>
                <th>Delivery Images</th>
                <th>Status</th>
                <th>Office Boy</th>
              </tr>
              @foreach ($customer->private_views as $view)
                <tr class="{{ \Carbon\Carbon::parse($view->date)->format('Y-m-d') == date('Y-m-d') ? 'row-highlight' : '' }}">
                  <td>
                    @foreach ($view->products as $product)
                      @if ($product->hasMedia(config('constants.media_tags')))
                        <img src="{{ $product->getMedia(config('constants.media_tags'))->first()->getUrl() }}" class="img-responsive" style="width: 50px;" alt="">
                      @endif
                    @endforeach
                  </td>
                  <td>{{ \Carbon\Carbon::parse($view->date)->format('d-m') }}</td>
                  <td>
                    @if ($view->hasMedia(config('constants.media_tags')))
                      @foreach ($view->getMedia(config('constants.media_tags')) as $image)
                        <a href="{{ $image->getUrl() }}" target="_blank" class="d-inline-block">
                          <img src="{{ $image->getUrl() }}" class="img-responsive" style="width: 50px;" alt="">
                        </a>
                      @endforeach
                    @endif
                    {{-- @elseif (\Carbon\Carbon::parse($view->date)->format('Y-m-d') == date('Y-m-d')) --}}
                      <form action="{{ route('stock.private.viewing.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                          <input type="hidden" name="view_id" value="{{ $view->id }}">
                          <input type="file" name="images[]" required multiple>
                        </div>

                        <button type="submit" class="btn btn-xs btn-secondary">Upload</button>
                      </form>
                    {{-- @endif --}}
                  </td>
                  <td>{{ ucwords($view->status) }}</td>
                  <td>
                    @if (array_key_exists($view->assigned_user_id, $users_array))
                      {{ $users_array[$view->assigned_user_id] }}
                    @else
                      No Assigned User
                    @endif
                  </td>
                </tr>
              @endforeach
          </table>
          </div>
        </div>
      @endif

      <div class="tab-pane mt-3" id="suggestion_tab">
        <h3>Suggestions</h3>
        <div class="row">
          <div class="col-12" id="suggestion-container"></div>
        </div>
      </div>

      <div class="tab-pane mt-3" id="email_tab">
        <div id="exTab3" class="mb-3">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#email-inbox" data-toggle="tab" id="email-inbox-tab" data-customerid="{{ $customer->id }}" data-type="inbox">Inbox</a>
            </li>
            <li>
              <a href="#email-sent" data-toggle="tab" id="email-sent-tab" data-customerid="{{ $customer->id }}" data-type="sent">Sent</a>
            </li>
            <li class="nav-item ml-auto">
              <button type="button" class="btn btn-image" data-toggle="modal" data-target="#emailSendModal"><img src="{{ asset('images/filled-sent.png') }}" /></button>
            </li>
          </ul>
        </div>

        <div id="email-container">
          @include('customers.email')
        </div>
      </div>

        @if($customer->facebook_id)
            <div class="tab-pane mt-3" id="facebook">
                @foreach($facebookMessages as $msg)
                    <div class="p-2 position-relative {{ $msg->is_sent_by_me ? 'balon1' : 'balon2' }}">
                        <a @click="event.preventDefault()" class="{{ $msg->is_sent_by_me ? 'float-right' : 'float-left' }}">
                            {{ $msg->message }}
                        </a>
                    </div>
                    <br clear="all">
                @endforeach
            </div>
        @endif
    </div>
  </div>

  <div class="col-xs-12 col-md-4 mb-3 middle-sec">
    <div class="border pl-4">
      <form action="{{ route('whatsapp.send', 'customer') }}" method="POST" enctype="multipart/form-data">
        <div class="d-flex">
          @csrf

          <div class="form-group mb-2">
            <div class="upload-btn-wrapper btn-group pr-0 pl-0 d-flex">
              <button class="btn btn-image px-1"><img src="/images/upload.png" /></button>
              <input type="file" name="image" />

              <button type="submit" class="btn btn-image px-1 send-communication received-customer"><img src="/images/filled-sent.png" /></button>
            </div>
          </div>

          <div class="form-group flex-fill mr-3 mb-2">
            <button type="button" id="customerMessageButton" class="btn btn-xs mt-2 btn-image"><img src="/images/support.png" /></button>
            <textarea  class="form-control mb-3 hidden" style="height: 110px;" name="body" placeholder="Received from Customer"></textarea>
            <input type="hidden" name="status" value="0" />
          </div>

          {{-- <div class="form-group">
            <div class="upload-btn-wrapper">
              <button class="btn btn-image px-1"><img src="/images/upload.png" /></button>
              <input type="file" name="image" />
            </div>
          </div> --}}
        </div>

      </form>

      <form action="{{ route('whatsapp.send', 'customer') }}" method="POST" enctype="multipart/form-data">
        <div id="paste-container" style="width: 200px;">

        </div>

        <div class="d-flex">
          @csrf



          <div class="form-group flex-fill mb-0">
            <textarea id="message-body" class="form-control mb-3 message-strong" style="height: 110px;" name="body" placeholder="Send for approval"></textarea>

            <input type="hidden" name="screenshot_path" value="" id="screenshot_path" />
            <input type="hidden" name="status" value="1" />

            <div class="paste-container"></div>


          </div>
            <div class="form-group mr-3">
                <div style="display:inline-grid;">
                    <div style="display:inline-grid;">
                        <div class="upload-btn-wrapper btn-group px-0">
                            <button class="btn btn-image p-0"><img src="/images/upload.png" /></button>
                            <input type="file" name="image" />

                        </div>
                        <button type="submit" class="btn px-1 btn-image px-1 send-communication"><img src="/images/filled-sent.png" /></button>

                    </div>

                    <div class="" style="display:inline-grid;">
                        <a href="{{ route('attachImages', ['customer', $customer->id, 1]) }}?return_url={{ request()->getRequestUri() }}" class="btn btn-image p-0"><img src="/images/attach.png" /></a>


                        <button type="button" class="btn btn-image px-1" data-toggle="modal" data-target="#suggestionModal"><img src="/images/customer-suggestion.png" /></button>
                    </div>
                </div>
            </div>
          {{-- <div class="form-group">
            <div class="upload-btn-wrapper">
              <button class="btn btn-image px-1"><img src="/images/upload.png" /></button>
              <input type="file" name="image" />
            </div>
          </div> --}}
        </div>
        <div class="d-flex">
          <div class="upload-btn-wrapper" id="root">
          </div>
        </div>
        <div class="pb-5">
          <div class="row">
            <div class=" pl-4" style="width: calc(100% - 190px)">
              <div class="d-inline form-inline mb-3">
                  <input style="width: calc(100% - 40px)" type="text" name="category_name" placeholder="Add Category" class="form-control  quick_category">
                  <button class="btn btn-secondary quick_category_add">+</button>
              </div>
              <div class="d-flex">
                <div class="mt-3" style="float: left;width: calc(100% - 40px)">
                  <select name="quickCategory" id="quickCategory" class="form-control input-sm mb-3">
                    <option value="">Select Category</option>
                    @foreach($reply_categories as $category)
                      <option value="{{ $category->approval_leads }}" data-id="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mt-3" style="width:20px;">
                  <a class="btn btn-image delete_category"><img src="/images/delete.png"></a>
                </div>
              </div>
              <div class="d-flex">
                <div style="float: left; width: calc(100% - 40px)">
                  <select name="quickComment" id="quickComment" class="form-control input-sm">
                    <option value="">Quick Reply</option>
                  </select>
                  </div>
                  <div style="width:20px;">
                    <a class="btn btn-image delete_quick_comment"><img src="/images/delete.png"></a>
                  </div>
              </div>
            </div>
            <div class="pl-1" style="width: 130px">
              <button type="button" class="btn btn-xs btn-secondary" data-toggle="modal" data-target="#ReplyModal" id="approval_reply">Create Quick Reply</button>
            </div>
          </div>
        </div>

      </form>

      <div class="row">
        <div class="col-12 mb-3">
          <form action="{{ route('status.report.store') }}" method="POST">
            @csrf

            <input type="hidden" name="customer_id" value="{{ $customer->id }}">

            <div class="row mr-2">
              <div class="col-6 pr-0">
                <div class="form-group mb-3">
                  <strong>Next action due</strong>
                  <a href="#" data-toggle="modal" data-target="#statusModal" class="btn-link">Add Action</a>

                  <select class="form-control input-sm" name="status_id" required>
                    <option value="">Select action</option>
                    @foreach ($order_status_report as $status)
                      <option value="{{ $status->id }}">{{ $status->status }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="col-6 pr-3">
                <div class="form-group mb-3" id="completion_form_group">
                  <strong>Completion Date:</strong>
                  <div class='input-group date' id='report-completion-datetime'>
                    <input type='text' class="form-control input-sm" name="completion_date" value="{{ date('Y-m-d H:i') }}" />

                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>

                  @if ($errors->has('completion_date'))
                      <div class="alert alert-danger">{{$errors->first('completion_date')}}</div>
                  @endif
                </div>
              </div>
            </div>

            <button type="submit" class="btn btn-xs btn-secondary">Add Report</button>

            <button type="button" class="btn btn-xs btn-secondary" id="showActionsButton">Show</button>
          </form>


          <div id="actions-container" class="hidden">
            @if (count($customer->many_reports) > 0)
              <h4>Order Reports</h4>

              <table class="table table-bordered mt-4">
                <thead>
                  <tr>
                    <th>Status</th>
                    <th>Created at</th>
                    <th>Creator</th>
                    <th>Due date</th>
                  </tr>
                </thead>

                <tbody>
                  @foreach ($customer->many_reports as $report)
                    <tr>
                      <td>{{ $report->status }}</td>
                      <td>{{ Carbon\Carbon::parse($report->created_at)->format('d-m H:i') }}</td>
                      <td>{{ $users_array[$report->user_id] }}</td>
                      <td>{{ Carbon\Carbon::parse($report->completion_date)->format('d-m H:i') }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              No Actions
            @endif
          </div>

        </div>
      </div>
    </div>
      <div id="notes" class="mt-3">
          <div class="panel-group">
              <div class="panel panel-default">
                  <div class="panel-heading">
                      <h4 class="panel-title">
                          <a data-toggle="collapse" href="#collapse1">Remarks ({{ is_array($customer->notes) ? count($customer->notes) : 0 }})</a>
                      </h4>
                  </div>
                  <div id="collapse1" class="panel-collapse collapse">
                      <div class="panel-body" id="note_list">
                          @if($customer->notes && is_array($customer->notes))
                              @foreach($customer->notes as $note)
                                  <li>{{ $note }}</li>
                              @endforeach
                          @endif
                      </div>
                      <div class="panel-footer">
                          <input name="add_new_remark" id="add_new_remark" type="text" placeholder="Type new remark..." class="form-control add-new-remark">
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <div class="col-xs-12 col-md-4 sec-msg pl-0 pr-0">
    <div class="border p-3">
      {{-- <h4>Messages</h4> --}}

      <div class="row">
        <div class="col-xs-6">
          <div class="form-inline">
            <div class="form-group">
              <form action="{{ route('customer.initiate.followup', $customer->id) }}" method="POST">
                @csrf

                <button type="submit" class="btn btn-xs btn-secondary" {{ $customer->is_initiated_followup() ? 'disabled' : '' }}>Follow Up</button>
              </form>
            </div>

            @if ($customer->is_initiated_followup())
              <div class="form-group ml-3 mt-">
                <form action="{{ route('customer.stop.followup', $customer->id) }}" method="POST">
                  @csrf

                  <button type="submit" class="btn btn-xs btn-secondary">STOP</button>
                </form>
              </div>
            @endif
          </div>
        </div>

        @if (isset($refunded_orders) && count($refunded_orders) > 0)
          <div class="col-xs-6">
            {{-- <h5>Refund Orders Status</h5> --}}

            <div class="form-inline">
              <div class="form-group">
                <select class="form-control input-sm refund-orders" name="">
                  <option value="">Select Order</option>
                  @foreach ($refunded_orders as $order)
                    <option value="{{ $order->id }}" data-answer={{ $order->refund_answer }}>{{ $order->order_id }}</option>
                  @endforeach
                </select>
              </div>

              <div class="d-inline ml-3">
                <button type="button" class="btn btn-xs btn-secondary customer-refund-answer" id="refund_answer_yes" data-answer="yes">Yes</button>
                <button type="button" class="btn btn-xs btn-secondary customer-refund-answer" id="refund_answer_no" data-answer="no">No</button>
              </div>
            </div>
          </div>
        @endif
        <div class="col-xs-6" >
          <div class="form-inline" style="justify-content: flex-end">
             <div class="form-group">
                <a type="button" class="btn btn-xs btn-image load-communication-modal" data-object="customer" data-id="{{$customer->id}}" data-load-type="text" data-all="1" title="Load messages" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}"><img src="/images/chat.png" alt=""></a>
                <a type="button" class="btn btn-xs btn-image load-communication-modal" data-object="customer" data-id="{{$customer->id}}" data-attached="1" data-load-type="images" data-all="1" title="Load Auto Images attacheds" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}"><img src="/images/archive.png" alt=""></a>
                <a type="button" class="btn btn-xs btn-image load-communication-modal" data-object="customer" data-id="{{$customer->id}}" data-attached="1" data-load-type="pdf" data-all="1" title="Load PDF" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}"><img src="/images/icon-pdf.svg" alt=""></a>
              </div>
            </div>
          </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
            <input type="text" name="search_chat_pop"  class="form-control search_chat_pop mt-3" placeholder="Search Message">
        </div>
      </div>

      <div class="row" id="allHolder">
        <div class="load-communication-modal chat-history-load-communication-modal" style="display: none;" data-object="customer" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}" data-attached="1" data-id="{{ $customer->id }}">
        </div>
        <div class="col-12" id="chat-history"></div>
      </div>
    </div>
  </div>
</div>

@include('customers.partials.modal-email')

{{-- <div class="row mt-5"> --}}
  {{-- <div class="col-xs-12 col-sm-6">
    <form action="{{ route('whatsapp.send', 'customer') }}" method="POST" enctype="multipart/form-data">
      <div class="d-flex">
        @csrf

        <div class="form-group">
          <div class="upload-btn-wrapper btn-group">
            <button type="submit" class="btn btn-image px-1 send-communication"><img src="/images/filled-sent.png" /></button>
          </div>
        </div>

        <div class="form-group flex-fill mr-3">
          <textarea  class="form-control mb-3" style="height: 110px;" name="body" placeholder="Received from Customer"></textarea>
          <input type="hidden" name="status" value="0" />
        </div>

        <div class="form-group">
          <input type="file" class="dropify" name="image" data-height="100" />
        </div>
      </div>
     </form>
   </div> --}}

   @include('customers.partials.modal-suggestion')

   {{-- <div class="col-xs-12 col-sm-6">

   </div> --}}

   {{-- <hr> --}}

   @include('customers.partials.modal-reply')

   {{-- <div class="col-xs-12 col-sm-6 mt-3">
     <form action="{{ route('whatsapp.send', 'customer') }}" method="POST" enctype="multipart/form-data">
       <div class="d-flex">
         @csrf

         <div class="form-group">
           <div class="upload-btn-wrapper btn-group">
             <button type="submit" class="btn btn-image px-1 send-communication"><img src="/images/filled-sent.png" /></button>
           </div>
         </div>

         <div class="form-group flex-fill mr-3">
           <textarea class="form-control mb-3" style="height: 110px;" name="body" placeholder="Internal Communications" id="internal-message-body"></textarea>

           <input type="hidden" name="status" value="4" />

           <p class="pb-4" style="display: block;">
             <select name="quickCategoryInternal" id="quickCategoryInternal" class="form-control mb-3">
               <option value="">Select Category</option>
               @foreach($reply_categories as $category)
                   <option value="{{ $category->internal_leads }}">{{ $category->name }}</option>
               @endforeach
             </select>

             <select name="quickCommentInternal" id="quickCommentInternal" class="form-control">
               <option value="">Quick Reply</option>
             </select>
           </p>
         </div>

         <div class="form-group">
           <input type="file" class="dropify" name="image" data-height="100" />

           <strong class="mt-3">Assign to</strong>
           <select name="assigned_to" class="form-control mb-3" required>
             <option value="">Select User</option>
             @foreach($users_array as $id => $user)
               <option value="{{ $id }}">{{ $user }}</option>
             @endforeach
           </select>

           <button type="button" class="btn btn-xs btn-secondary mb-3" data-toggle="modal" data-target="#ReplyModal" id="internal_reply">Create Quick Reply</button>
         </div>
       </div>

     </form>
   </div> --}}

  {{-- <div class="col-xs-12 col-sm-6 mt-3">
    <div class="d-flex">
      <div class="form-group">
        <a href="{{ route('attachImages', ['customer', $customer->id, 9, 9]) }}" class="btn btn-image px-1"><img src="/images/attach.png" /></a>
        <button id="waMessageSend" class="btn btn-sm btn-image"><img src="/images/filled-sent.png" /></button>
      </div>

      <div class="form-group flex-fill mr-3">
        <textarea id="waNewMessage" class="form-control mb-3" style="height: 110px;" placeholder="Whatsapp message"></textarea>
      </div>

      <div class="form-group">
        <input type="file" id="waMessageMedia" class="dropify" name="image" data-height="100" />
      </div>
    </div>

  </div> --}}
{{-- </div> --}}

<div style="margin: 0 10px" class="row mt-5">
  <div class="col-xs-12 p-0">
    {{-- <button type="button" class="btn btn-secondary mb-3" data-toggle="modal" data-target="#instructionModal">Add Instruction</button> --}}

    {{-- <form class="form-inline mb-3" action="{{ route('instruction.category.store') }}" method="POST">
      @csrf
      <div class="form-group">
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Category" required>
      </div>

      <button type="submit" class="btn btn-secondary ml-3">Create Category</button>
    </form> --}}

    @include('customers.partials.modal-instruction')

    <div id="exTab3" >
      <ul class="nav nav-tabs">
        <li class="active">
          <a href="#4" data-toggle="tab">Instructions</a>
        </li>
        <li><a href="#5" data-toggle="tab">Complete</a></li>
        <li>
          <a href="#6" class="mb-3" data-toggle="modal" data-target="#instructionModal">+ Add</a>
        </li>

      <ul class="nav nav-tabs" style="flex-grow: 1;justify-content: flex-end">
          <li>
              <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                  @csrf
                  <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                  <input type="hidden" name="instruction" value="Send images">
                  <input type="hidden" name="category_id" value="6">
                  <input type="hidden" name="assigned_to" value="{{ \App\Setting::get('image_shortcut') }}">

                  <button type="submit" class="btn btn-image quick-shortcut-button" title="Send Images"><img src="/images/attach.png" /></button>
              </form>
          </li>

          <li>
              <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                  @csrf
                  <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                  <input type="hidden" name="instruction" value="Send price">
                  <input type="hidden" name="category_id" value="3">
                  <input type="hidden" name="assigned_to" value="{{ \App\Setting::get('price_shortcut') }}">

                  <button type="submit" class="btn btn-image quick-shortcut-button" title="Send Price"><img src="/images/price.png" /></button>
              </form>
          </li>

          <li>
              <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                  @csrf
                  <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                  <input type="hidden" name="instruction" value="{{ $users_array[\App\Setting::get('call_shortcut')] }} call this client">
                  <input type="hidden" name="category_id" value="10">
                  <input type="hidden" name="assigned_to" value="{{ \App\Setting::get('call_shortcut') }}">

                  <button type="submit" class="btn btn-image quick-shortcut-button" title="Call this Client"><img src="/images/call.png" /></button>
              </form>
          </li>

          <li>
              <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                  @csrf
                  <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                  <input type="hidden" name="instruction" value="Attach image">
                  <input type="hidden" name="category_id" value="8">
                  <input type="hidden" name="assigned_to" value="{{ \App\Setting::get('screenshot_shortcut') }}">

                  <button type="submit" class="btn btn-image quick-shortcut-button" title="Attach Image"><img src="/images/upload.png" /></button>
              </form>
          </li>

          <li>
              <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                  @csrf
                  <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                  <input type="hidden" name="instruction" value="Attach screenshot">
                  <input type="hidden" name="category_id" value="12">
                  <input type="hidden" name="assigned_to" value="{{ \App\Setting::get('screenshot_shortcut') }}">

                  <button type="submit" class="btn btn-image quick-shortcut-button" title="Attach Screenshot"><img src="/images/screenshot.png" /></button>
              </form>
          </li>

          <li>
              <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                  @csrf
                  <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                  <input type="hidden" name="instruction" value="Give details">
                  <input type="hidden" name="category_id" value="14">
                  <input type="hidden" name="assigned_to" value="{{ \App\Setting::get('details_shortcut') }}">

                  <button type="submit" class="btn btn-image quick-shortcut-button" title="Give Details"><img src="/images/details.png" /></button>
              </form>
          </li>

          <li>
              <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                  @csrf
                  <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                  <input type="hidden" name="instruction" value="Check for the Purchase">
                  <input type="hidden" name="category_id" value="7">
                  <input type="hidden" name="assigned_to" value="{{ \App\Setting::get('purchase_shortcut') }}">

                  <button type="submit" class="btn btn-image quick-shortcut-button" title="Check for Purchase"><img src="/images/purchase.png" /></button>
              </form>
          </li>

          <li>
              <div class="d-inline">
                  <button type="button" class="btn btn-image send-instock-shortcut" data-id="{{ $customer->id }}">Send In Stock</button>
              </div>
          </li>

          <li>
              <div class="d-inline">
                  <button type="button" class="btn latest-scraped-shortcut" data-id="{{ $customer->id }}" data-toggle="modal" data-target="#categoryBrandModal">Send 20 Scraped</button>
              </div>
          </li>

          <li>
              <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                  @csrf
                  <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                  <input type="hidden" name="instruction" value="Please show client chat to Yogesh">
                  <input type="hidden" name="category_id" value="13">
                  <input type="hidden" name="assigned_to" value="{{ \App\Setting::get('price_shortcut') }}">

                  <button type="submit" class="btn btn-image quick-shortcut-button" title="Show Client Chat"><img src="/images/chat.png" /></button>
              </form>
          </li>
      </ul>
      </ul>
    </div>

    @include('customers.partials.modal-category-brand')

    <div class="tab-content ">

      <div class="tab-pane active " id="4">
        <div class="table-responsive" style="border-top-left-radius:0">
            <table class="table table-bordered m-0">
           <thead>
           <tr>
               <th>Number</th>
               <th>Assigned to</th>
               <th>Category</th>
               <th>Instructions</th>
               <th colspan="3" class="text-center">Action</th>
               <th>Created at</th>
               <th>Remark</th>
           </tr>
           </thead>
            @foreach ($customer->instructions()->where('verified', 0)->orderBy('is_priority', 'DESC')->orderBy('created_at', 'DESC')->limit(3)->get() as $instruction)
                <tr>
                  <td>
                    <span data-twilio-call data-context="customers" data-id="{{ $customer->id }}">{{ $instruction->customer->phone }}</span>
                  </td>
                  <td>{{ $users_array[$instruction->assigned_to] ?? '' }}</td>
                  <td>{{ $instruction->category ? $instruction->category->name : 'Non Existing Category' }}</td>
                  <td>
                    <div class="form-inline">
                      @if ($instruction->is_priority == 1)
                        <strong class="text-danger mr-1">!</strong>
                      @endif

                      {{ $instruction->instruction }}
                    </div>
                  </td>
                  <td>
                    @if ($instruction->completed_at)
                      {{ Carbon\Carbon::parse($instruction->completed_at)->format('d-m H:i') }}
                    @else
                      <a href="#" class="btn-link complete-call" data-id="{{ $instruction->id }}" data-assignedfrom="{{ $instruction->assigned_from }}">Complete</a>
                    @endif
                  </td>
                  <td>
                    @if ($instruction->completed_at)
                      Completed
                    @else
                      @if ($instruction->pending == 0)
                        <a href="#" class="btn-link pending-call" data-id="{{ $instruction->id }}">Mark as Pending</a>
                      @else
                        Pending
                      @endif
                    @endif
                  </td>
                  <td>
                    @if ($instruction->verified == 1)
                      <span class="badge">Verified</span>
                    @elseif ($instruction->assigned_from == Auth::id() && $instruction->verified == 0)
                      <a href="#" class="btn btn-xs btn-secondary verify-btn" data-id="{{ $instruction->id }}">Verify</a>
                    @else
                      <span class="badge">Not Verified</span>
                    @endif
                  </td>
                  <td>{{ $instruction->created_at->diffForHumans() }}</td>
                  <td>
                    <a href class="add-task" data-toggle="modal" data-target="#addRemarkModal" data-id="{{ $instruction->id }}">Add</a>
                    <span> | </span>
                    <a href class="view-remark" data-toggle="modal" data-target="#viewRemarkModal" data-id="{{ $instruction->id }}">View</a>
                  </td>
                </tr>
            @endforeach
        </table>
        </div>

        <div id="instructionAccordion">
            <div class="card mb-5">
              <div class="card-header" id="headingInstruction">
                <h5 class="mb-0 mt-0">
                  <button class="btn btn-link collapsed collapse-fix" data-toggle="collapse" data-target="#instructionAcc" aria-expanded="false" aria-controls="">
                    Rest of Instructions
                  </button>
                </h5>
              </div>
              <div id="instructionAcc" class="collapse collapse-element" aria-labelledby="headingInstruction" data-parent="#instructionAccordion">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <tbody>
                        @foreach ($customer->instructions()->where('verified', 0)->orderBy('is_priority', 'DESC')->orderBy('created_at', 'DESC')->offset(3)->limit(100)->get() as $key => $instruction)
                          <tr>
                            <td>
                              <span data-twilio-call data-context="customers" data-id="{{ $customer->id }}">{{ $instruction->customer->phone }}</span>
                            </td>
                            <td>{{ $users_array[$instruction->assigned_to] ?? '' }}</td>
                            <td>{{ $instruction->category ? $instruction->category->name : 'Non Existing Category' }}</td>
                            <td>
                              <div class="form-inline">
                                @if ($instruction->is_priority == 1)
                                  <strong class="text-danger mr-1">!</strong>
                                @endif

                                {{ $instruction->instruction }}
                              </div>
                            </td>
                            <td>
                              @if ($instruction->completed_at)
                                {{ Carbon\Carbon::parse($instruction->completed_at)->format('d-m H:i') }}
                              @else
                                <a href="#" class="btn-link complete-call" data-id="{{ $instruction->id }}" data-assignedfrom="{{ $instruction->assigned_from }}">Complete</a>
                              @endif
                            </td>
                            <td>
                              @if ($instruction->completed_at)
                                Completed
                              @else
                                @if ($instruction->pending == 0)
                                  <a href="#" class="btn-link pending-call" data-id="{{ $instruction->id }}">Mark as Pending</a>
                                @else
                                  Pending
                                @endif
                              @endif
                            </td>
                            <td>
                              @if ($instruction->verified == 1)
                                <span class="badge">Verified</span>
                              @elseif ($instruction->assigned_from == Auth::id() && $instruction->verified == 0)
                                <a href="#" class="btn btn-xs btn-secondary verify-btn" data-id="{{ $instruction->id }}">Verify</a>
                              @else
                                <span class="badge">Not Verified</span>
                              @endif
                            </td>
                            <td>{{ $instruction->created_at->diffForHumans() }}</td>
                            <td>
                              <a href class="add-task" data-toggle="modal" data-target="#addRemarkModal" data-id="{{ $instruction->id }}">Add</a>
                              <span> | </span>
                              <a href class="view-remark" data-toggle="modal" data-target="#viewRemarkModal" data-id="{{ $instruction->id }}">View</a>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        </div>
      </div>

      <div class="tab-pane " id="5">
        <div class="table-responsive">
            <table class="table table-bordered m-0">
            <tr>
              <th>Number</th>
              <th>Assigned to</th>
              <th>Category</th>
              <th>Instructions</th>
              <th colspan="3" class="text-center">Action</th>
              <th>Created at</th>
              <th>Remark</th>
            </tr>
            @foreach ($customer->instructions()->where('verified', 1)->latest('completed_at')->limit(3)->get() as $instruction)
                <tr>
                  <td>
                    <span data-twilio-call data-context="customers" data-id="{{ $customer->id }}">{{ $instruction->customer->phone }}</span>
                  </td>
                  <td>{{ $users_array[$instruction->assigned_to] ?? '' }}</td>
                  <td>{{ $instruction->category ? $instruction->category->name : 'Non Existing Category' }}</td>
                  <td>{{ $instruction->instruction }}</td>
                  <td>
                    @if ($instruction->completed_at)
                      {{ Carbon\Carbon::parse($instruction->completed_at)->format('d-m H:i') }}
                    @else
                      <a href="#" class="btn-link complete-call" data-id="{{ $instruction->id }}" data-assignedfrom="{{ $instruction->assigned_from }}">Complete</a>
                    @endif
                  </td>
                  <td>
                    @if ($instruction->completed_at)
                      Completed
                    @else
                      @if ($instruction->pending == 0)
                        <a href="#" class="btn-link pending-call" data-id="{{ $instruction->id }}">Mark as Pending</a>
                      @else
                        Pending
                      @endif
                    @endif
                  </td>
                  <td>
                    @if ($instruction->verified == 1)
                      <span class="badge">Verified</span>
                    @elseif ($instruction->assigned_from == Auth::id() && $instruction->verified == 0)
                      <a href="#" class="btn btn-xs btn-secondary verify-btn" data-id="{{ $instruction->id }}">Verify</a>
                    @else
                      <span class="badge">Not Verified</span>
                    @endif
                  </td>
                  <td>{{ $instruction->created_at->diffForHumans() }}</td>
                  <td>
                    <a href class="add-task" data-toggle="modal" data-target="#addRemarkModal" data-id="{{ $instruction->id }}">Add</a>
                    <span> | </span>
                    <a href class="view-remark" data-toggle="modal" data-target="#viewRemarkModal" data-id="{{ $instruction->id }}">View</a>
                  </td>
                </tr>
            @endforeach
        </table>
        </div>

        <div id="instructionCompletedAccordion">
            <div class="card mb-5">
              <div class="card-header" id="headingInstruction">
                <h5 class="mb-0">
                  <button class="btn btn-link collapsed collapse-fix" data-toggle="collapse" data-target="#instructionCompletedAcc" aria-expanded="false" aria-controls="">
                    Rest of Instructions
                  </button>
                </h5>
              </div>
              <div id="instructionCompletedAcc" class="collapse collapse-element" aria-labelledby="headingInstruction" data-parent="#instructionCompletedAccordion">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <tbody>
                        @foreach ($customer->instructions()->where('verified', 1)->latest('completed_at')->offset(3)->limit(100)->get() as $key => $instruction)
                          <tr>
                            <td>
                              <span data-twilio-call data-context="customers" data-id="{{ $customer->id }}">{{ $instruction->customer->phone }}</span>
                            </td>
                            <td>{{ $users_array[$instruction->assigned_to] ?? '' }}</td>
                            <td>{{ $instruction->category ? $instruction->category->name : 'Non Existing Category' }}</td>
                            <td>{{ $instruction->instruction }}</td>
                            <td>
                              @if ($instruction->completed_at)
                                {{ Carbon\Carbon::parse($instruction->completed_at)->format('d-m H:i') }}
                              @else
                                <a href="#" class="btn-link complete-call" data-id="{{ $instruction->id }}" data-assignedfrom="{{ $instruction->assigned_from }}">Complete</a>
                              @endif
                            </td>
                            <td>
                              @if ($instruction->completed_at)
                                Completed
                              @else
                                @if ($instruction->pending == 0)
                                  <a href="#" class="btn-link pending-call" data-id="{{ $instruction->id }}">Mark as Pending</a>
                                @else
                                  Pending
                                @endif
                              @endif
                            </td>
                            <td>
                              @if ($instruction->verified == 1)
                                <span class="badge">Verified</span>
                              @elseif ($instruction->assigned_from == Auth::id() && $instruction->verified == 0)
                                <a href="#" class="btn btn-xs btn-secondary verify-btn" data-id="{{ $instruction->id }}">Verify</a>
                              @else
                                <span class="badge">Not Verified</span>
                              @endif
                            </td>
                            <td>{{ $instruction->created_at->diffForHumans() }}</td>
                            <td>
                              <a href class="add-task" data-toggle="modal" data-target="#addRemarkModal" data-id="{{ $instruction->id }}">Add</a>
                              <span> | </span>
                              <a href class="view-remark" data-toggle="modal" data-target="#viewRemarkModal" data-id="{{ $instruction->id }}">View</a>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>

    @if($customer->wishListBasket)
      @php
        $basket = $customer->wishListBasket;
        $basketProducts = $customer->wishListBasket->basketProducts;
      @endphp
      <div class="tab-content mt-3 mb-3">
        <h4>Wishlist</h4>
        <div class="table-responsive">
            <table class="table table-bordered m-0">
                <thead>
                <tr>
                    <th>Product name</th>
                    <th>Sku</th>
                    <th>Price</th>
                    <th>Currency</th>
                    <th>Website</th>
                    <th>Created At</th>
                </tr>
                </thead>
                @foreach($basketProducts as $basketProduct)
                  <tr>
                    <td>{{$basketProduct->product_name}}</td>
                    <td>{{$basketProduct->product_sku}}</td>
                    <td>{{$basketProduct->product_price}}</td>
                    <td>{{$basketProduct->product_currency}}</td>
                    <td>{{$basket->storeWebsite->website}}</td>
                    <td>{{$basket->created_at}}</td>
                  </tr>
                @endforeach
            </table>
          </div>
      </div>  
    @endif
    @include('customers.partials.modal-remark')
  </div>
</div>

<div id="sendContacts" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
        <label for="sel1">Select User for send contact data:</label>
        <form method="post" id="send-contact-to-user">
            {{ Form::open(array('url' => '', 'id' => 'send-contact-user-form')) }}
            {!! Form::hidden('customer_id',$customer->id) !!}
            {!! Form::select('user_id', \App\User::all()->sortBy("name")->pluck("name","id"), 6, ['class' => 'form-control select-user-wha-list multi_select2', 'style'=> 'width:100%']) !!}
            {{ Form::close() }}
        </form>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default send-contact-user-btn"><img style="width: 17px;" src="/images/filled-sent.png"></button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div id="downloadContacts" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
        <label for="sel1">Select User for send contact data:</label>
        <form method="post" id="download-contact-to-user">
            {{ Form::open(array('url' => '', 'id' => 'download-contact-user-form')) }}
            {!! Form::hidden('customer_id',$customer->id) !!}
            {!! Form::select('user_id', \App\User::all()->sortBy("name")->pluck("name","id"), 6, ['class' => 'form-control select-user-wha-list multi_select2', 'style'=> 'width:100%']) !!}
            {{ Form::close() }}
        </form>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default download-contact-user-btn"><img style="width: 17px;" src="/images/filled-sent.png"></button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="add_lead" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="{{ route('leads.erpLeads.store') }}" method="POST" enctype="multipart/form-data" class="js-erp-lead-frm" data-reload='1'>
            <div class="modal-header">
                <h2>Add Lead</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{$customer->id}}">
                    <input type="hidden" name="assigned_user" value="6">
                    <input type="hidden" name="rating" value="1">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <strong>Brand:</strong>
                                <select name="brand_id" class="form-control multi_brand multi_brand_select multi_select2" multiple>
                                    <option value="">Brand</option>
                                    @foreach($brands as $brand_item)
                                        <option value="{{$brand_item['id']}}" data-brand-segment="{{$brand_item['brand_segment']}}">{{$brand_item['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <strong>Category</strong>
                                {!! \App\Category::attr(['name' => 'category_id','class' => 'form-control multi_select2 add_lead_category_id'])->selected(['1'])->renderAsDropdown()  !!}
                            </div>
                            <div class="form-group">
                                <strong>Brand Segment:</strong>
                                {{ App\Helpers\ProductHelper::getBrandSegment('brand_segment[]', [], ['class' => "form-control brand_segment_select", 'multiple' => ''])}}
                            </div>
                            <div class="form-group">
                                <strong>status:</strong>
                                    <Select name="lead_status_id" class="form-control">
                                        @foreach($lead_status as $key => $value)
                                            <option value="{{$value}}" {{$value == '3' ? 'selected':''}}>{{$key}}</option>
                                        @endforeach
                                    </Select>
                            </div>
                            <div class="form-group">
                                <strong>Sizes:</strong>
                                <input type="text" name="size" value="" class="form-control" placeholder="S, M, L">
                            </div>
                            <div class="form-group">
                                <strong>Gender:</strong>
                                <select name="gender" class="form-control">
                                    <option value="male" selected>Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="form-group show-product-image">
                                </div>
                            </div>
                            <input type="hidden"  name="oldImage[0]" value="-1">
                            <div class="form-group new-image" style="">
                                <strong>Upload Image:</strong>
                                <input  type="file" enctype="multipart/form-data" class="form-control" name="image[]" multiple />
                            </div>
                        </div>
                    </div>
                </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-default">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
        </div>
    </form>
  </div>
</div>
</div>

<div id="add_order" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{  route('order.store') }}" method="POST" enctype="multipart/form-data" class="add_order_frm" data-reload='1'>
                <div class="modal-header">
                    <h2>Add Order</h2>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{$customer->id}}">
                        <input type="hidden" name="return_url_back" value="1">
                        <input type="hidden" name="order_type" value="offline">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Order Date:</strong>
                                    <input type="date" class="form-control" name="order_date" placeholder="Order Date" value="{{date('Y-m-d')}}" required/>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Date of Delivery:</strong>
                                    <input type="date" class="form-control" name="date_of_delivery" placeholder="Date of Delivery" value="" />
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Advance Amount:</strong>
                                    <input type="text" class="form-control" name="advance_detail" placeholder="Advance Detail" value="" />
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Advance Date:</strong>
                                    <input type="date" class="form-control" name="advance_date" placeholder="Advance Date" value="" />
                                </div>
                            </div>
                             <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Balance Amount:</strong>
                                    <input type="text" class="form-control" name="balance_amount" placeholder="Balance Amount" value="" />
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong> Status :</strong>
                                    <?php
                                        $orderStatus = new \App\ReadOnly\OrderStatus;
                                        echo Form::select('order_status',$orderStatus->all(), 'Follow up for advance', ['placeholder' => 'Select a status','class' => 'form-control']);
                                    ?>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Received By:</strong>
                                    <input type="text" class="form-control" name="received_by" placeholder="Received By" />
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong> Payment Mode :</strong>
                                    <?php
                                        $paymentModes = new \App\ReadOnly\PaymentModes();
                                        echo Form::select('payment_mode',$paymentModes->all(), null, ['placeholder' => 'Select a mode','class' => 'form-control']);
                                    ?>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Note if any:</strong>
                                    <input type="text" class="form-control" name="note_if_any" placeholder="Note if any" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default">Add</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

 <div id="chat-list-history" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Communication</h4>&nbsp;&nbsp;
                <input type="text" name="search_chat_pop"  class="form-control search_chat_pop" placeholder="Search Message" style="width: 50%;">&nbsp;&nbsp;
                <!-- <input type="text" name="search_chat_pop_time"  class="form-control search_chat_pop_time" placeholder="Search Time" style="width: 200px;"> -->
      <input style="min-width: 30px;" placeholder="Search by date" value="" type="text" class="form-control search_chat_pop_time" name="search_chat_pop_time">
                
            </div>
            <div class="modal-body" style="background-color: #999999;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<form action="" method="POST" id="product-remove-form">
  @csrf
</form>

@include('customers.partials.modal-forward')

@if (isset($order))
  @include('customers.partials.modal-yes')
@endif

<form action="index.html" method="POST" id="createMagentoProductForm">
  @csrf


</form>
@endsection

@section('scripts')
  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js"></script> --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js" integrity="sha256-Y1rRlwTzT5K5hhCBfAFWABD4cU13QGuRN6P5apfWzVs=" crossorigin="anonymous"></script>
  <script type="text/javascript" src="<?php echo env("DOMAIN_NODE_SPEECH_TO_TEXT"); ?>/scripts/bundle.js"></script>
  <script type="text/javascript">
      //$(document).ready(function () {
      //    $('.chat-history-load-communication-modal').trigger('click');
      //});
      jQuery(document).ready(function() {
        $('.multi_select2').select2({width: '100%'});
        //$('.brand_segment_select').select2({width: '100%'});

        $(".multi_brand_select").change(function() {
            var brand_segment = [];
            $(this).find(':selected').each(function() {
                if ($(this).data('brand-segment') && brand_segment.indexOf($(this).data('brand-segment')) == '-1') {
                  brand_segment.push($(this).data('brand-segment'));
                }
            })
            $(this).closest('form').find(".brand_segment_select").val(brand_segment).trigger('change');
        });
      })


      $(document).on('click', '.quick_category_add', function (e) {
            e.preventDefault();
            var textBox = $(".quick_category");

            if (textBox.val() == "") {
                alert("Please Enter Category!!");
                return false;
            }

            $.ajax({
                type: "POST",
                url: "{{ route('add.reply.category') }}",
                data: {
                    '_token'    : "{{ csrf_token() }}",
                    'name'      : textBox.val()
                }
            }).done(function (response) {
                textBox.val('');
                $("#quickCategory").append($('<option>', {
                    value: "[]",
                    text: response.data.name
                }));

                $("#category_id_field").append($('<option>', {
                    value: response.data.id,
                    text: response.data.name
                }));

            })

            return false;
        });
        $(document).on('click', '.delete_category', function () {
              var quickCategory = $("#quickCategory");

              if (quickCategory.val() == "") {
                  alert("Please Select Category!!");
                  return false;
              }

              var quickCategoryId = quickCategory.children("option:selected").data('id');
              if (! confirm("Are sure you want to delete category?")) {
                return false;
              }
              $.ajax({
                  type: "POST",
                  url: "{{ route('destroy.reply.category') }}",
                  data: {
                      '_token'  : "{{ csrf_token() }}",
                      'id'      : quickCategoryId
                  }
              }).done(function (response) {
                  location.reload();
              })
          });

          $(document).on('click', '.delete_quick_comment', function () {
              var quickComment = $("#quickComment");

              if (quickComment.val() == "") {
                  alert("Please Select Quick Comment!!");
                  return false;
              }

              var quickCommentId = quickComment.children("option:selected").data('id');
              if (! confirm("Are sure you want to delete comment?")) {
                return false;
              }
              $.ajax({
                  type: "DELETE",
                  url: "/reply/"+quickCommentId,
                  headers: {
                      'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                  },
              }).done(function (response) {
                  location.reload();
              })
          });
      $(document).on('keyup', '.add-new-remark', function(event) {
          let note = $(this).val();
          let self = this;
          if (event.which != 13) {
              return;
          }
          $.ajax({
              url: "{{ action('CustomerController@addNote', $customer->id) }}",
              data: {
                  note: note,
                  _token: "{{csrf_token()}}"
              },
              type: 'post',
              success: function() {
                  toastr['success']('Remark added successfully', 'success');
                  $(self).removeAttr('disabled');
                  $(self).val('');
                  $('#note_list').append('<li>'+note+'</li>');
              },
              beforeSend: function() {
                  $(self).attr('disabled', true);
              },
              error: function() {
                  $(self).removeAttr('disabled');
              }
          });
      });

      $(document).on('click', '.talk-bubble img', function(event) {
          event.preventDefault();
          $(this).attr('href', $(this).attr('src'));
          $(this).ekkoLightbox();
      });

  jQuery(document).ready(function( $ ) {
      //$('.select2').select2();
    $('audio').on("play", function (me) {
      $('audio').each(function (i,e) {
        if (e !== me.currentTarget) {
          this.pause();
        }
      });
    });

    $('.dropify').dropify();
  })

  $(document).on('change', '.refund-orders', function() {
    var answer = $(this).find(':selected').data('answer');

    if (answer == 'no') {
      $('#refund_answer_no').hide();
      $('#refund_answer_yes').hide();
    } else {
      $('#refund_answer_no').show();
      $('#refund_answer_yes').show();
    }
  });

  $(document).on('click', '.customer-refund-answer', function() {
    var thiss = $(this);
    var answer = $(this).data('answer');
    var selected_order = $(this).parent().parent().find('select').val();
    var selected_answer = $(this).parent().parent().find(':selected').data('answer');

    if (selected_order.length > 0) {
      if (answer == 'yes') {
        var url = "{{ url('order') }}/" + selected_order + "/send/suggestion";

        $('#refund_instruction_order_id').val(selected_order);
        $('#yesModal form').attr('action', url);

        $('#yesModal').modal();
      } else {
        $.ajax({
          type: "POST",
          url: "{{ url('order') }}/" + selected_order + "/refund/answer",
          data: {
            _token: "{{ csrf_token() }}",
            answer: "no"
          },
          beforeSend: function() {
            $(thiss).text('Loading...');
          }
        }).done(function() {
          window.location.reload();
        }).fail(function(response) {
          $(thiss).text('No');

          alert('Could not say No!');
          console.log(response);
        });
      }
    } else {
      alert('Please select Order first!');
    }
  });

  var selected_product_images = [];

  $(document).on('click', '.select-product-image', function() {
    var checked = $(this).prop('checked');
    var id = $(this).data('id');

    if (checked) {
      selected_product_images.push(id);
    } else {
      var index = selected_product_images.indexOf(id);

      selected_product_images.splice(index, 1);
    }
  });

  $('#create_refund_instruction').on('click', function () {
    var thiss = $(this);
    var order_id = $('#refund_instruction_order_id').val();

    $.ajax({
      type: "POST",
      url: "{{ route('instruction.store') }}",
      data: {
        _token: "{{ csrf_token() }}",
        customer_id: "{{ $customer->id }}",
        instruction: "Send images",
        category_id: 1,
        assigned_to: "{{ \App\Setting::get('image_shortcut') }}"
      },
      beforeSend: function() {
        $(thiss).text('Loading...');
      }
    }).done(function() {
      $.ajax({
        type: "POST",
        url: "{{ url('order') }}/" + order_id + "/refund/answer",
        data: {
          _token: "{{ csrf_token() }}",
          answer: "yes"
        }
      }).done(function() {
        window.location.reload();
      }).fail(function(response) {
        alert('Could not say Yes to refund!');

        console.log(response);
      })
    }).fail(function(response) {
      $(thiss).text('Create Instruction');

      alert('Could not create instruction');
      console.log(response);
    });
  });


  $(document).on('click', '.create-magento-product', function(e) {
    e.preventDefault();

    var form = $('#createMagentoProductForm');
    var id = $(this).data('id');
    var url = "{{ url('order') }}/" + id + "/createProductOnMagento";

    form.attr('action', url);
    form.submit();
  });

    $('#date, #report-completion-datetime').datetimepicker({
      format: 'YYYY-MM-DD HH:mm'
    });
      @if ($customer->instagramThread)
      var ft = true;
      function loadThread() {
          let threadId = "{{$customer->instagramThread->thread_id}}";
          $.ajax({
              url: "{{ action('InstagramController@getThread', '') }}"+'/'+threadId,
              success: function(response) {
                  updateThreadInChatBox(response);
              },
              error: function() {
                  alert("We could not load Instagram messages at the moment");
              }
          });
      }

      function updateThreadInChatBox(data) {
          $('.msg_history').html('');
          let messages = data.messages;
          let profile_picture = data.profile_picture;
          let username = data.username;
          messages.forEach(function(item) {
              let messageHTML = '';
              if (item.type == 'sent') {
                  messageHTML += '<div class="outgoing_msg">';
                  messageHTML += '<div class="sent_msg">';
                  if (item.item_type == 'text' || item.item_type == 'like') {
                      messageHTML += '<p>'+item.text+'</p>';
                  } else if (item.item_type == 'media') {
                      messageHTML += '<p><img src="'+item.text+'" class="img-responsive"></p>';
                  } else {
                      messageHTML += '<p></p>';
                  }
                  messageHTML += '</div>';
                  messageHTML += '</div>';
              } else {
                  messageHTML += '<div class="incoming_msg">';
                  messageHTML += '<div class="incoming_msg_img"><img class="img-responsive img-circle" src="'+profile_picture+'" alt="'+username+'"></div>';
                  messageHTML += '<div class="received_msg">';
                  messageHTML += '<div class="received_withd_msg">';
                  if (item.item_type == 'text' || item.item_type == 'like') {
                      messageHTML += '<p>'+item.text+'</p>';
                  } else if (item.item_type == 'media') {
                      messageHTML += '<p><img src="'+item.text+'" class="img-responsive"></p>';
                  } else {
                      messageHTML += '<p></p>';
                  }
                  messageHTML += '</div>';
                  messageHTML += '</div>';
                  messageHTML += '</div>';
              }

              $('.msg_history').prepend(messageHTML);
              if (ft) {
                  ft = false;
                  $(".msg_history").animate({ scrollTop: $('.msg_history').prop("scrollHeight")}, 1000);
              }
          });
      }

      setInterval(function() {
          loadThread();
      }, 5000);
      @endif

        jQuery(document).ready(function() {
            $('.ig-reply').keyup(function(event) {
                if (event.keyCode == 13) {
                    let threadId = $(this).data('thread-id');
                    let message = $(this).val();
                    $(this).attr('disabled', 'disabled');
                    let self = this;
                    if (message != '') {
                        $.ajax({
                            url: '{{ action('InstagramController@replyToThread', '') }}'+'/'+threadId,
                            data: {
                                message: message,
                                _token: "{{ csrf_token() }}"
                            },
                            type: 'POST',
                            success: function(response) {
                                $(self).val('');
                                $(self).removeAttr('disabled');
                                updateThreadInChatBox(response);
                                $(".msg_history").animate({ scrollTop: $('.msg_history').prop("scrollHeight")}, 1000);
                            },
                            error: function() {
                                alert("Couldn't send message right now!");
                            }
                        });
                    }
                }
            });

            $("#ig_image").change(function(event) {
                let threadId = $(this).data('thread-id');
                let fd = new FormData();
                fd.append('photo', $('#ig_image').prop('files')[0]);
                $('#ig_image').val('');
                $.ajax({
                    url: '{{action('InstagramController@replyToThread', '')}}'+'/'+threadId,
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: fd,
                    success: function(response) {
                        loadThread(response);
                    }
                });
            });

        });

        $('.change_status').on('change', function() {
          var thiss = $(this);
          var token = "{{ csrf_token() }}";
          var status = $(this).val();


          if ($(this).hasClass('order_status')) {
            var id = $(this).data('orderid');
            var url = '/order/' + id + '/changestatus';
          } else {
            var id = $(this).data('leadid');
            var url = '/erp-leads/' + id + '/changestatus';
          }

          $.ajax({
            url: url,
            type: 'POST',
            data: {
              _token: token,
              status: status
            }
          }).done( function(response) {
            if ($(thiss).hasClass('order_status') && status == 'Product shiped to Client') {
              $('#tracking-wrapper-' + id).css({'display' : 'block'});
            }

            $(thiss).siblings('.change_status_message').fadeIn(400);

            setTimeout(function () {
              $(thiss).siblings('.change_status_message').fadeOut(400);
            }, 2000);
          }).fail(function(errObj) {
            alert("Could not change status");
          });
        });


        $('.erp_lead_frm').on('submit', function(e) {
          e.preventDefault();
          var thiss = $(this);
          var url = "{{ route('leads.erpLeads.store') }}";

          if ($(this).find('.multi_brand').val() == "") {
            alert('Please Select Brand');
            return false;
          }

          if ($(this).find('input[name="category_id"]').val() == "") {
            alert('Please Select Category');
            return false;
          }

          if ($(this).find('input[name="lead_status_id"]').val() == "") {
            alert('Please Select Status');
            return false;
          }

          var formData = new FormData(this);

          $.ajax({
            url: url,
            type: 'POST',
            data:formData,
            contentType: false,
            processData: false
          }).done( function(response) {
            if (thiss.data('reload')) {
                window.location.reload();
            }
            $(thiss).find('.erp_update_message').fadeIn(400);
            $('html, body').animate({
                scrollTop: $(thiss).find('.erp_update_message').offset().top-150
            }, 1000);
            setTimeout(function () {
              $(thiss).find('.erp_update_message').fadeOut(400);
            }, 5000);
          }).fail(function(errObj) {
            alert("Could not update");
          });
          return false;
        });

        $('#whatsapp_change').on('change', function() {
          var thiss = $(this);
          var token = "{{ csrf_token() }}";
          var number = $(this).val();
          var customer_id = {{ $customer->id }};
          var url = "{{ url('customer') }}/" + customer_id + "/updateNumber";

          $.ajax({
            url: url,
            type: 'POST',
            data: {
              _token: token,
              whatsapp_number: number
            }
          }).done( function(response) {
            $(thiss).siblings('.change_status_message').fadeIn(400);

            setTimeout(function () {
              $(thiss).siblings('.change_status_message').fadeOut(400);
            }, 2000);
          }).fail(function(response) {
            alert("Could not change status");
          });
        });

        $('.send-refund').on('click', function() {
          var thiss = $(this);
          var token = "{{ csrf_token() }}";
          var order_id = $(this).data('id');
          var url = "{{ url('order') }}/" + order_id + "/sendRefund";

          $.ajax({
            url: url,
            type: 'POST',
            data: {
              _token: token
            }
          }).done( function(response) {
            $(thiss).siblings('.send-refund-message').fadeIn(400);

            setTimeout(function () {
              $(thiss).siblings('.send-refund-message').fadeOut(400);
            }, 2000);
          }).fail(function(response) {
            alert("Could not send refund messages!");
            console.log(response);
          });
        });

        $(document).on('click', '.add-product-button', function() {
          $('input[name="order_id"]').val($(this).data('orderid'));
        });

        $('.createProduct').on('click', function() {
          var token = "{{ csrf_token() }}";
          var url = "{{ route('products.store') }}";
          // var order_id = $(this).data('orderid');
          var order_id = $('input[name="order_id"]').val();
          var image = $('#product-image').prop('files')[0];
          var name = $('#product-name').val();
          var sku = $('#product-sku').val();
          var color = $('#product-color').val();
          var brand = $('#product-brand').val();
          var price = $('#product-price').val();
          var price_special = $('#product-price-special').val();
          var size = $('#product-size').val();
          var quantity = $('#product-quantity').val();
          var thiss = $(this);

          var form_data = new FormData();
          form_data.append('_token', token);
          form_data.append('order_id', order_id);
          form_data.append('image', image);
          form_data.append('name', name);
          form_data.append('sku', sku);
          form_data.append('color', color);
          form_data.append('brand', brand);
          form_data.append('price', price);
          form_data.append('price_inr_special', price_inr_special);
          form_data.append('size', size);
          form_data.append('quantity', quantity);

          $.ajax({
            type: 'POST',
            url: url,
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            data: form_data,
            beforeSend: function() {
              $(thiss).text('Creating...');
            }
          }).done(function(response) {
            $(thiss).text('Create');

            var brands_array = {!! json_encode(\App\Helpers::getUserArray(\App\Brand::all())) !!};
            var show_url = "{{ url('products') }}/" + response.product.id;
            var delete_url = "{{ url('deleteOrderProduct') }}/" + response.order.id;
            var product_row = '<tr><td><img width="200" src="' + response.product_image + '" /></td>';
                product_row += '<td>' + response.product.name + '</td>';
                product_row += '<td>' + response.product.sku + '</td>';
                product_row += '<td>' + response.product.color + '</td>';
                product_row += '<td>' + brands_array[response.product.brand] + '</td>';
                product_row += '<td><input class="table-input" type="text" value="' + response.order.product_price + '" name="order_products[' + response.order.id + '][product_price]"></td>';
                // product_row += '<th>' + response.product.size + '</th>';

                if (response.product.size != null) {
                  var exploded = response.product.size.split(',');

                  product_row += '<td><select class="form-control" name="order_products[' + response.order.id + '][size]">';
                  product_row += '<option selected="selected" value="">Select</option>';

                  $(exploded).each(function(index, value) {
                    product_row += '<option value="' + value + '">' + value + '</option>';
                  });

                  product_row += '</select></td>';

                } else {
                    product_row += '<td><select hidden class="form-control" name="order_products[' + response.order.id + '][size]"><option selected="selected" value=""></option></select>nil</td>';
                }

                product_row += '<td><input class="table-input" type="number" min="1" value="' + response.order.qty + '" name="order_products[' + response.order.id + '][qty]"></td>';
                product_row += '<td></td>';
                product_row += '<td><a class="btn btn-image" href="' + show_url + '"><img src="/images/view.png" /></a>';
                product_row += '<a class="btn btn-image remove-product" href="#" data-product="' + response.order.id + '"><img src="/images/delete.png" /></a></td>';
                product_row += '</tr>';

            $('#products-table-' + order_id).append(product_row);

            $('#productModal').find('.close').click();
          }).fail(function(response) {
            $(thiss).text('Create');

            console.log(response);
            alert('Could not create a product!');
          });
        });

        $(document).on('click', '.remove-product', function(e) {
          e.preventDefault();

          var product_id = $(this).data('product');
          var url = "{{ url('deleteOrderProduct') }}/" + product_id;

          $('#product-remove-form').attr('action', url);
          $('#product-remove-form').submit();
        });

        $(document).on('click', ".collapsible-message", function() {
          var selection = window.getSelection();
          if (selection.toString().length === 0) {
            var short_message = $(this).data('messageshort');
            var message = $(this).data('message');
            var status = $(this).data('expanded');

            if (status == false) {
              $(this).addClass('expanded');
              $(this).html(message);
              $(this).data('expanded', true);
              // $(this).siblings('.thumbnail-wrapper').remove();
              $(this).closest('.talktext').find('.message-img').removeClass('thumbnail-200');
              $(this).closest('.talktext').find('.message-img').parent().css('width', 'auto');
            } else {
              $(this).removeClass('expanded');
              $(this).html(short_message);
              $(this).data('expanded', false);
              $(this).closest('.talktext').find('.message-img').addClass('thumbnail-200');
              $(this).closest('.talktext').find('.message-img').parent().css('width', '200px');
            }
          }
        });

        $(document).ready(function() {
        var container = $("div#message-container");
        var suggestion_container = $("div#suggestion-container");
        // var sendBtn = $("#waMessageSend");
        var customerId = "{{$customer->id}}";
             var addElapse = false;
             function errorHandler(error) {
                 console.error("error occured: " , error);
             }
             function approveMessage(element, message) {
               if (!$(element).attr('disabled')) {
                 $.ajax({
                   type: "POST",
                   url: "/whatsapp/approve/customer",
                   data: {
                     _token: "{{ csrf_token() }}",
                     messageId: message.id
                   },
                   beforeSend: function() {
                     $(element).attr('disabled', true);
                     $(element).text('Approving...');
                   }
                 }).done(function( data ) {
                   element.remove();
                   console.log(data);
                 }).fail(function(response) {
                   $(element).attr('disabled', false);
                   $(element).text('Approve');

                   console.log(response);
                   alert(response.responseJSON.message);
                 });
               }
             }


          $(document).on('mouseover', '.talktext .thumbnail-wrapper', function(e) {
              $('#preview-image-model').find(".modal-content").attr("src",$(this).find("img").attr("src"));
              if($(".container").find(".chat-window").length == 0) {
                $('#preview-image-model').modal('show');
              }
          });

          $(document).on('mouseout', '.talktext .thumbnail-wrapper', function(e) {
             $('#preview-image-model').modal('hide');
          });



         $(document).on('click', '.send-communication', function(e) {
           e.preventDefault();

           var thiss = $(this);
           var url = $(this).closest('form').attr('action');
           var token = "{{ csrf_token() }}";
           var file = $($(this).closest('form').find('input[type="file"]'))[0].files[0];
           var status = $(this).closest('form').find('input[name="status"]').val();
           var screenshot_path = $('#screenshot_path').val();
           var customer_id = {{ $customer->id }};
           var formData = new FormData();

           formData.append("_token", token);
           formData.append("image", file);
           formData.append("message", $(this).closest('form').find('textarea').val());
           // formData.append("moduletype", $(this).closest('form').find('input[name="moduletype"]').val());
           formData.append("customer_id", customer_id);
           formData.append("assigned_to", $(this).closest('form').find('select[name="assigned_to"]').val());
           formData.append("status", status);
           formData.append("screenshot_path", screenshot_path);

           // if (status == 4) {
           //   formData.append("assigned_user", $(this).closest('form').find('select[name="assigned_user"]').val());
           // }

           if ($(this).closest('form')[0].checkValidity()) {
             $.ajax({
               type: 'POST',
               url: url,
               data: formData,
               processData: false,
               contentType: false
             }).done(function(response) {
               console.log(response);
               $('.load-communication-modal').trigger('click');
               $(thiss).closest('form').find('textarea').val('');
               $('#paste-container').empty();
               $('#screenshot_path').val('');
               $(thiss).closest('form').find('.dropify-clear').click();

               if ($(thiss).hasClass('received-customer')) {
                 $(thiss).closest('form').find('#customerMessageButton').removeClass('hidden');
                 $(thiss).closest('form').find('textarea').addClass('hidden');
               }
             }).fail(function(response) {
               console.log(response);
               alert('Error sending a message');
             });
           } else {
             $(this).closest('form')[0].reportValidity();
           }

         });

         var can_load_more = true;

         $('#message-wrapper').scroll(function() {
           var top = $('#message-wrapper').scrollTop();
           var document_height = $(document).height();
           var window_height = $('#message-container').height();

           console.log($('#message-wrapper').scrollTop());
           console.log($(document).height());
           console.log($('#message-container').height());

           // if (top >= (document_height - window_height - 200)) {
           if (top >= (window_height - 1500)) {
             console.log('should load', can_load_more);
             if (can_load_more) {
               var current_page = $('#load-more-messages').data('nextpage');
               $('#load-more-messages').data('nextpage', current_page + 1);
               var next_page = $('#load-more-messages').data('nextpage');
               console.log(next_page);
               $('#load-more-messages').text('Loading...');

               can_load_more = false;

               $('.load-communication-modal').trigger('click');
             }
           }
         });

         $(document).on('click', '#load-more-messages', function() {
           var current_page = $(this).data('nextpage');
           $(this).data('nextpage', current_page + 1);
           var next_page = $(this).data('nextpage');
           $('#load-more-messages').text('Loading...');

           $('.load-communication-modal').trigger('click');
         });

         $(document).on('click', '#sendAdvanceLink', function(e) {
           e.preventDefault();

           var thiss = $(this);
           var price_inr = $(this).closest('form').find('input[name="price_inr"]').val();
           var price_special = $(this).closest('form').find('input[name="price_special"]').val();

           $.ajax({
             type: "POST",
             url: "{{ url('customers') }}/" + {{ $customer->id }} + "/sendAdvanceLink",
             data: {
               _token: "{{ csrf_token() }}",
               price_inr: price_inr,
               price_special: price_special,
             },
             beforeSend: function() {
               $(thiss).text('Sending...');
             }
           }).done(function() {
             $('.load-communication-modal').trigger('click');

             $(thiss).text('Send Link');

             $('#advancePaymentModal').find('.close').click();
           }).fail(function(response) {
             $(thiss).text('Send Link');

             console.log(response);

             alert('Could not send link');
           });
         });
      });

      $(document).on('click', '.change_message_status', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        var token = "{{ csrf_token() }}";
        var thiss = $(this);

        if ($(this).hasClass('wa_send_message')) {
          var message_id = $(this).data('messageid');
          var message = $('#message_body_' + message_id).find('p').data('message').toString().trim();

          $.ajax({
            url: "{{ url('whatsapp/updateAndCreate') }}",
            type: 'POST',
            data: {
              _token: token,
              moduletype: "customer",
              message_id: message_id
            },
            beforeSend: function() {
              $(thiss).text('Loading');
            }
          }).done( function(response) {
          }).fail(function(errObj) {
            console.log(errObj);
            alert("Could not create whatsapp message");
          });
        }
          $.ajax({
            url: url,
            type: 'GET'
          }).done( function(response) {
            $(thiss).remove();
          }).fail(function(errObj) {
            alert("Could not change status");
          });



      });

      $(document).on('click', '.edit-message', function(e) {
        e.preventDefault();
        var thiss = $(this);
        var message_id = $(this).data('messageid');

        $('#message_body_' + message_id).css({'display': 'none'});
        $('#edit-message-textarea' + message_id).css({'display': 'block'});

        $('#edit-message-textarea' + message_id).keypress(function(e) {
          var key = e.which;

          if (key == 13) {
            e.preventDefault();
            var token = "{{ csrf_token() }}";
            var url = "{{ url('message') }}/" + message_id;
            var message = $('#edit-message-textarea' + message_id).val();

            if ($(thiss).hasClass('whatsapp-message')) {
              var type = 'whatsapp';
            } else {
              var type = 'message';
            }

            $.ajax({
              type: 'POST',
              url: url,
              data: {
                _token: token,
                body: message,
                type: type
              },
              success: function(data) {
                $('#edit-message-textarea' + message_id).css({'display': 'none'});
                $('#message_body_' + message_id).text(message);
                $('#message_body_' + message_id).css({'display': 'block'});
              }
            });
          }
        });
      });

      $(document).on('click', '.thumbnail-delete', function(event) {
        event.preventDefault();
        var thiss = $(this);
        var image_id = $(this).data('image');
        var message_id = $(this).closest('.talk-bubble').find('.collapsible-message').data('messageid');
        // var message = $(this).closest('.talk-bubble').find('.collapsible-message').data('message');
        var token = "{{ csrf_token() }}";
        var url = "{{ url('message') }}/" + message_id + '/removeImage';
        var type = 'message';

        if ($(this).hasClass('whatsapp-image')) {
          type = "whatsapp";
        }


        $.ajax({
          type: 'POST',
          url: url,
          data: {
            _token: token,
            image_id: image_id,
            message_id: message_id,
            type: type
          },
          success: function(data) {
            $(thiss).parent().remove();
          }
        });
      });

      $(document).ready(function() {
        $("body").tooltip({ selector: '[data-toggle=tooltip]' });
      });

      $('#approval_reply').on('click', function() {
        $('#model_field').val('Approval Lead');
      });

      $('#internal_reply').on('click', function() {
        $('#model_field').val('Internal Lead');
      });

      $('#approvalReplyForm').on('submit', function(e) {
        e.preventDefault();

        var url = "{{ route('reply.store') }}";
        var reply = $('#reply_field').val();
        var category_id = $('#category_id_field').val();
        var model = $('#model_field').val();

        $.ajax({
          type: 'POST',
          url: url,
          headers: {
              'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
          },
          data: {
            reply: reply,
            category_id: category_id,
            model: model
          },
          success: function(reply) {
            // $('#ReplyModal').modal('hide');
            $('#reply_field').val('');
            if (model == 'Approval Lead') {
              $('#quickComment').append($('<option>', {
                value: reply,
                text: reply
              }));
            } else {
              $('#quickCommentInternal').append($('<option>', {
                value: reply,
                text: reply
              }));
            }

          }
        });
      });

      $('#quick_add_lead').on('click', function(e) {
        e.preventDefault();
        $('.add_lead_category_id').val('1').trigger('change');
        if ($('#add_lead').find('input[name="product_id"]').length > 0) {
            $('#add_lead').find('input[name="product_id"]').val('');
        }
        $('#add_lead').find('.show-product-image').html('');
        $('#add_lead').modal('show');
      });

      $('#quick_add_order').on('click', function(e) {
            e.preventDefault();
            if ($('#add_order').find('input[name="selected_product[]"]').length > 0) {
                $('#add_order').find('input[name="selected_product[]"]').val('');
            }

            if ($('#add_order').find('input[name="convert_order"]').length > 0) {
                $('#add_order').find('input[name="convert_order"]').val('');
            }
            $('#add_order').modal('show');
      });

      $(document).on('change', '.update-customer-field', function () {
          var $this = $(this);
          $.ajax({
              type: "POST",
              url: "{{ route('customer.update.field') }}",
              data: {
                  _token: "{{ csrf_token() }}",
                  field: $this.data("type"),
                  value: $this.val(),
                  customer_id: $this.data("customer-id")
              }
          }).done(function (response) {
              alert(response.message);
          }).fail(function (response) {
              console.log(response);
          });
      });

      /*
      $('#quick_add_order').on('click', function(e) {
        e.preventDefault();

        var thiss = $(this);
        var token = "{{ csrf_token() }}";
        var url = "{{ route('order.store') }}";
        var customer_id = {{ $customer->id }};

        $.ajax({
          type: 'POST',
          url: url,
          data: {
            _token: token,
            customer_id: customer_id,
            order_type: "offline",
            order_status: "Follow up for advance"
          },
          beforeSend: function() {
            $(thiss).text('Creating...');
          },
          success: function() {
            location.reload();
          }
        }).fail(function(error) {
          console.log(error);
          alert('There was an error creating a order');
        });
      });
*/
      $(document).on('click', '.complete-call', function(e) {
        e.preventDefault();

        var thiss = $(this);
        var token = "{{ csrf_token() }}";
        var url = "{{ route('instruction.complete') }}";
        var id = $(this).data('id');
        var assigned_from = $(this).data('assignedfrom');
        var current_user = {{ Auth::id() }};

        $.ajax({
          type: 'POST',
          url: url,
          data: {
            _token: token,
            id: id
          },
          beforeSend: function() {
            $(thiss).text('Loading');
          }
        }).done( function(response) {
          // $(thiss).parent().html(moment(response.time).format('DD-MM HH:mm'));
          $(thiss).parent().html('Completed');


        }).fail(function(errObj) {
          console.log(errObj);
          alert("Could not mark as completed");
        });
      });

      $(document).on('click', '.pending-call', function(e) {
        e.preventDefault();

        var thiss = $(this);
        var token = "{{ csrf_token() }}";
        var url = "{{ route('instruction.pending') }}";
        var id = $(this).data('id');

        $.ajax({
          type: 'POST',
          url: url,
          data: {
            _token: token,
            id: id
          },
          beforeSend: function() {
            $(thiss).text('Loading');
          }
        }).done( function(response) {
          $(thiss).parent().html('Pending');
          $(thiss).remove();
        }).fail(function(errObj) {
          console.log(errObj);
          alert("Could not mark as completed");
        });
      });

      $('#quickCategory').on('change', function() {
        var replies = JSON.parse($(this).val());
        $('#quickComment').empty();

        $('#quickComment').append($('<option>', {
          value: '',
          text: 'Quick Reply'
        }));

        replies.forEach(function(reply) {
          $('#quickComment').append($('<option>', {
            value: reply.reply,
            text: reply.reply,
            "data-id": reply.id
          }));
        });
      });

      $('#quickCategoryInternal').on('change', function() {
        var replies = JSON.parse($(this).val());
        $('#quickCommentInternal').empty();

        $('#quickCommentInternal').append($('<option>', {
          value: '',
          text: 'Quick Reply'
        }));

        replies.forEach(function(reply) {
          $('#quickCommentInternal').append($('<option>', {
            value: reply.reply,
            text: reply.reply
          }));
        });
      });

      $(document).on('click', '.collapse-fix', function() {
        if (!$(this).hasClass('collapsed')) {
          var target = $(this).data('target');
          var all = $('.collapse-element').not($(target));

          Array.from(all).forEach(function(element) {
            $(element).removeClass('in');
          });
        }
      });

      $('.add-task').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#add-remark input[name="id"]').val(id);
      });

      $('#addRemarkButton').on('click', function() {
        var id = $('#add-remark input[name="id"]').val();
        var remark = $('#add-remark textarea[name="remark"]').val();

        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('task.addRemark') }}',
            data: {
              id:id,
              remark:remark,
              module_type: 'instruction'
            },
        }).done(response => {
            alert('Remark Added Success!')
            window.location.reload();
        }).fail(function(response) {
          console.log(response);
        });
      });


      $(".view-remark").click(function () {
        var id = $(this).attr('data-id');

          $.ajax({
              type: 'GET',
              headers: {
                  'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
              },
              url: '{{ route('task.gettaskremark') }}',
              data: {
                id:id,
                module_type: "instruction"
              },
          }).done(response => {
              var html='';

              $.each(response, function( index, value ) {
                html+=' <p> '+value.remark+' <br> <small>By ' + value.user_name + ' updated on '+ moment(value.created_at).format('DD-M H:mm') +' </small></p>';
                html+"<hr>";
              });
              $("#viewRemarkModal").find('#remark-list').html(html);
          });
      });

      $(document).on('click', '.track-shipment-button', function() {
        var thiss = $(this);
        var order_id = $(this).data('id');
        var awb = $('#awb_field_' + order_id).val();

        $.ajax({
          type: "POST",
          url: "{{ route('stock.track.package') }}",
          data: {
            _token: "{{ csrf_token() }}",
            awb: awb
          },
          beforeSend: function() {
            $(thiss).text('Tracking...');
          }
        }).done(function(response) {
          $(thiss).text('Track');

          $('#tracking-container-' + order_id).html(response);
        }).fail(function(response) {
          $(thiss).text('Tracking...');
          alert('Could not track this package');
          console.log(response);
        });
      });

      $(document).on('click', '.verify-btn', function(e) {
        e.preventDefault();

        var thiss = $(this);
        var id = $(this).data('id');

        $.ajax({
          type: "POST",
          url: "{{ route('instruction.verify') }}",
          data: {
            _token: "{{ csrf_token() }}",
            id: id
          },
          beforeSend: function() {
            $(thiss).text('Verifying...');
          }
        }).done(function(response) {
          // $(thiss).parent().html('<span class="badge">Verified</span>');
          var current_user = {{ Auth::id() }};

          $(thiss).closest('tr').remove();

          // var row = '<tr><td></td><td></td><td></td><td>' + response.instruction + '</td><td>' + moment(response.completed_at).format('DD-MM HH:mm') + '</td><td>Completed</td><td>' + verify_button + '</td><td></td><td></td></tr>';
          // console.log(row);
          //
          // $('#5 tbody').append($(row));
          window.location.reload();
        }).fail(function(response) {
          $(thiss).text('Verify');
          console.log(response);
          alert('Could not verify the instruction!');
        });
      });

      $('#createInstructionReplyButton').on('click', function(e) {
       e.preventDefault();

       var url = "{{ route('reply.store') }}";
       var reply = $('#instruction_reply_field').val();

       $.ajax({
         type: 'POST',
         url: url,
         headers: {
             'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
         },
         data: {
           reply: reply,
           category_id: 1,
           model: 'Instruction'
         },
         success: function(reply) {
           $('#instruction_reply_field').val('');
           $('#instructionComment').append($('<option>', {
             value: reply,
             text: reply
           }));
         }
       });
      });

      $(document).on('click', '.create-voucher', function() {
        var id = $(this).data('id');
        var thiss = $(this);
        var description = "Delivery to {{ $customer->name }} at {{ preg_replace('/\s+/', ' ', $customer->address) }}, {{ $customer->city }}";
        var date = moment().add(5, 'days').format('YYYY-MM-DD');

        $.ajax({
          url: "{{ route('voucher.store') }}",
          type: "POST",
          data: {
            _token: "{{ csrf_token() }}",
            user_id: {{ Auth::id() }},
            delivery_approval_id: id,
            description: description,
            date: date
          },
          beforeSend: function() {
            $(thiss).text('Creating...');
          }
        }).done(function(response) {
          var edit_button = '<button type="button" class="btn btn-xs btn-secondary edit-voucher" data-toggle="modal" data-target="#editVoucherModal" data-id="' + response.id + '" data-amount="" data-travel="">Edit Voucher</button>';
          $(thiss).parent().html($(edit_button));

        }).fail(function(response) {
          $(thiss).text('Create Voucher');

          console.log(response);
          alert('There was an error creating voucher');
        });
      });

      $(document).on('click', '.edit-voucher', function() {
        var id = $(this).data('id');
        var amount = $(this).data('amount');
        var travel = $(this).data('travel');
        var url = "{{ url('voucher') }}/" + id;
        var form = $('#editVoucherForm');
        var travel_select = $('option[value="' + travel + '"]');

        form.attr('action', url);
        travel_select.attr('selected', true);
        $('#voucher_amount_field').val(amount);
      });

      $(document).on('click', '.email-fetch', function(e) {
        e.preventDefault();

        //var uid = $(this).data('uid');
        var id = $(this).data('id');
        var type = $(this).data('type');
        var email_type = type;
        var thiss = $(this);

        /*if (uid == 'no') {
          uid = $(this).data('id');
          email_type = 'local';
        }*/
		

        $.ajax({
          type: "GET",
          url: "{{ route('customer.email.fetch') }}",
          data: {
            id: id,
            type: type,
            email_type: email_type
          },
          beforeSend: function() {
            // $('#email-content .card').html('Loading...');
            $(thiss).closest('.card').find('.email-content .card').html('Loading...');
          }
        }).done(function(response) {
          $(thiss).closest('.card').find('.email-content .card').html(response.email);
        }).fail(function(response) {
          $(thiss).closest('.card').find('.email-content .card').html();

          alert('Could not fetch an email');
          console.log(response);
        })
      });

      $('a[href="#email_tab"], #email-inbox-tab, #email-sent-tab').on('click', function() {
        var customer_id = $(this).data('customerid');
        var type = $(this).data('type');

        $.ajax({
          url: "{{ route('customer.email.inbox') }}",
          type: "GET",
          data: {
            customer_id: customer_id,
            type: type
          },
          beforeSend: function() {
            $('#email_tab #email-container').find('.card').html('Loading emails');
          }
        }).done(function(response) {
          $('#email_tab #email-container').html(response.emails);
        }).fail(function(response) {
          $('#email_tab #email-container').find('.card').html();

          alert('Could not fetch emails');
          console.log(response);
        });
      });

      $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
          url: url,
          type: "GET"
        }).done(function(response) {
          $('#email_tab #email-container').html(response.emails);
        }).fail(function(response) {
          alert('Could not load emails');
          console.log(response);
        });
      });

      $(document).ready(function() {
        var size_list  = {
          5: ['34', '34.5', '35', '35.5', '36', '36.5', '37', '37.5', '38', '38.5', '39', '39.5', '40', '40.5', '41', '41.5', '42', '42.5', '43', '43.5', '44'], // Men Shoes
          12: ['36-36S', '38-38S', '40-40S', '42-42S', '44-44S', '46-46S', '48-48S', '50-50S'], // Men Clothing
          31: ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXL'], // Men T-Shirt
          131: ['24-24S', '25-25S', '26-26S', '27-27S', '28-28S', '29-29S', '30-30S', '31-31S', '32-32S'], // Men Sweat Pants
          14: ['60', '65', '70', '75', '80', '85', '90', '95', '100', '105', '110', '115', '120'], // Men Belts
        };

        Object.keys(size_list).forEach(function(size) {
          size_list[size].forEach(function(value) {
            $('#size_selection').append($('<option>', {
              value: value,
              text: value
            }));
          });

        });
      });

      $(document).on('click', '.block-twilio', function() {
        var customer_id = $(this).data('id');
        var thiss = $(this);

        $.ajax({
          type: "POST",
          url: "{{ route('customer.block') }}",
          data: {
            _token: "{{ csrf_token() }}",
            customer_id: customer_id
          },
          beforeSend: function() {
            $(thiss).text('Blocking...');
          }
        }).done(function(response) {
          if (response.is_blocked == 1) {
            $(thiss).html('<img src="/images/blocked-twilio.png" />');
          } else {
            $(thiss).html('<img src="/images/unblocked-twilio.png" />');
          }
        }).fail(function(response) {
          $(thiss).text('Block on Twilio');

          alert('Could not block customer!');

          console.log(response);
        });
      });

      $(document).on('click', '.issue-credit-button', function() {
        var customer_id = $(this).data('id');
        var thiss = $(this);

        $.ajax({
          type: "POST",
          url: "{{ route('customer.issue.credit') }}",
          data: {
            _token: "{{ csrf_token() }}",
            customer_id: customer_id
          },
          beforeSend: function() {
            $(thiss).text('Sending Email...');
          }
        }).done(function(response) {
          $(thiss).text('Issue Credit');
        }).fail(function(response) {
          $(thiss).text('Issue Credit');

          alert('Could not issue credit!');

          console.log(response);
        });
      });

      $(document).on('click', '#do_not_disturb', function() {
        // var checked = $(this).prop('checked');
        var id = $(this).data('id');
        var thiss = $(this);

        // if (checked) {
        //   var option = 1;
        // } else {
        //   var option = 0;
        // }

        $.ajax({
          type: "POST",
          url: "{{ url('customer') }}/" + id + '/updateDND',
          data: {
            _token: "{{ csrf_token() }}",
            // do_not_disturb: option
          },
          beforeSend: function() {
            $(thiss).text('DND...');
          }
        }).done(function(response) {
          console.log(response);
          if (response.do_not_disturb == 1) {
            $(thiss).html('<img src="/images/do-not-disturb.png" />');
          } else {
            $(thiss).html('<img src="/images/do-disturb.png" />');
          }
        }).fail(function(response) {
          alert('Could not update DND status');

          console.log(response);
        })
      });




        // if ($(this).is(":focus")) {
        // Created by STRd6
        // MIT License
        // jquery.paste_image_reader.js
        (function($) {
          var defaults;
          $.event.fix = (function(originalFix) {
            return function(event) {
              event = originalFix.apply(this, arguments);
              if (event.type.indexOf('copy') === 0 || event.type.indexOf('paste') === 0) {
                event.clipboardData = event.originalEvent.clipboardData;
              }
              return event;
            };
          })($.event.fix);
          defaults = {
            callback: $.noop,
            matchType: /image.*/
          };
          return $.fn.pasteImageReader = function(options) {
            if (typeof options === "function") {
              options = {
                callback: options
              };
            }
            options = $.extend({}, defaults, options);
            return this.each(function() {
              var $this, element;
              element = this;
              $this = $(this);
              return $this.bind('paste', function(event) {
                var clipboardData, found;
                found = false;
                clipboardData = event.clipboardData;
                return Array.prototype.forEach.call(clipboardData.types, function(type, i) {
                  var file, reader;
                  if (found) {
                    return;
                  }
                  if (type.match(options.matchType) || clipboardData.items[i].type.match(options.matchType)) {
                    file = clipboardData.items[i].getAsFile();
                    reader = new FileReader();
                    reader.onload = function(evt) {
                      return options.callback.call(element, {
                        dataURL: evt.target.result,
                        event: evt,
                        file: file,
                        name: file.name
                      });
                    };
                    reader.readAsDataURL(file);
                    return found = true;
                  }
                });
              });
            });
          };
        })(jQuery);

          var dataURL, filename;
          $("html").pasteImageReader(function(results) {
            console.log(results);

            // $('#message-body').on('focus', function() {
            	filename = results.filename, dataURL = results.dataURL;

              var img = $('<div class="image-wrapper position-relative"><img src="' + dataURL + '" class="img-responsive" /><button type="button" class="btn btn-xs btn-secondary remove-screenshot">x</button></div>');

              $('#paste-container').empty();
              $('#paste-container').append(img);
              $('#screenshot_path').val(dataURL);
            // });

          });

          $(document).on('click', '.remove-screenshot', function() {
            $(this).closest('.image-wrapper').remove();
            $('#screenshot_path').val('');
          });
        // }


      $(document).on('click', '.change-history-toggle', function() {
        $(this).siblings('.change-history-container').toggleClass('hidden');
      });

      $('#instructionCreateButton').on('click', function(e) {
        e.preventDefault();

        var assigned_to = $('#instruction_user_id').val();
        var category_id = $('#instruction_category_id').val();
        var instruction = $('#instruction-body').val();
        var send_whatsapp = $('#sendWhatsappCheckbox').prop('checked') ? 'send' : '';
        var is_priority = $('#instructionPriority').prop('checked') ? 'on' : '';

        console.log(send_whatsapp);

        if ($(this).closest('form')[0].checkValidity()) {
          $.ajax({
            type: 'POST',
            url: "{{ route('instruction.store') }}",
            data: {
              _token: "{{ csrf_token() }}",
              assigned_to: assigned_to,
              category_id: category_id,
              instruction: instruction,
              customer_id: {{ $customer->id }},
              send_whatsapp: send_whatsapp,
              is_priority: is_priority,
            }
          }).done(function() {
            $('#instructionModal').find('.close').click();
          }).fail(function(response) {
            console.log(response);
            alert('Could not create an instruction');
          });
        } else {
          $(this).closest('form')[0].reportValidity();
        }
      });

      $('#customerMessageButton').on('click', function() {
        $(this).siblings('textarea').removeClass('hidden');
        $(this).addClass('hidden');
      });

      $('#updateCustomerButton').on('click', function() {
        var id = {{ $customer->id }};
        var thiss = $(this);
        var name = $('#customer_name').val();
        var phone = $('#customer_phone').val();
        var whatsapp_number = $('#whatsapp_change').val();
        var address = $('#customer_address').val();
        var city = $('#customer_city').val();
        var country = $('#customer_country').val();
        var pincode = $('#customer_pincode').val();
        var email = $('#customer_email').val();
        var insta_handle = $('#customer_insta_handle').val();
        var rating = $('#customer_rating').val();
        var shoe_size = $('#customer_shoe_size').val();
        var clothing_size = $('#customer_clothing_size').val();
        var gender = $('#customer_gender').val();

        $.ajax({
          type: "POST",
          url: "{{ url('customer') }}/" + id + '/edit',
          data: {
            _token: "{{ csrf_token() }}",
            name: name,
            phone: phone,
            whatsapp_number: whatsapp_number,
            address: address,
            city: city,
            country: country,
            pincode: pincode,
            email: email,
            insta_handle: insta_handle,
            rating: rating,
            shoe_size: shoe_size,
            clothing_size: clothing_size,
            gender: gender,
            do_not_disturb: "{{ $customer->do_not_disturb == 1 ? "on" : '' }}",
            is_blocked: "{{ $customer->is_blocked == 1 ? "on" : '' }}"
          },
          beforeSend: function() {
            $(thiss).text('Saving...');
          }
        }).done(function() {
          $(thiss).text('Save');
          $(thiss).removeClass('btn-secondary');
          $(thiss).addClass('btn-success');

          setTimeout(function () {
            $(thiss).addClass('btn-secondary');
            $(thiss).removeClass('btn-success');
          }, 2000);
        }).fail(function(response) {
          $(thiss).text('Save');
          console.log(response);
          alert('Could not update customer');
        });
      });

      $('#email_order_id').on('change', function() {
        var order_id = $(this).val();

        var subject = $(this).closest('form').find('input[name="subject"]').val();
        var new_subject = order_id + ' ' + subject;

        $(this).closest('form').find('input[name="subject"]').val(new_subject);
      });

      $('#showActionsButton').on('click', function() {
        $('#actions-container').toggleClass('hidden');
      });

      $(document).on('click', '.flag-customer', function() {
        var customer_id = $(this).data('id');
        var thiss = $(this);

        $.ajax({
          type: "POST",
          url: "{{ route('customer.flag') }}",
          data: {
            _token: "{{ csrf_token() }}",
            customer_id: customer_id
          },
          beforeSend: function() {
            $(thiss).text('Flagging...');
          }
        }).done(function(response) {
          if (response.is_flagged == 1) {
            // var badge = $('<span class="badge badge-secondary">Flagged</span>');
            //
            // $(thiss).parent().append(badge);
            $(thiss).html('<img src="/images/flagged.png" />');
          } else {
            $(thiss).html('<img src="/images/unflagged.png" />');
            // $(thiss).parent().find('.badge').remove();
          }

          // $(thiss).remove();
        }).fail(function(response) {
          $(thiss).html('<img src="/images/unflagged.png" />');

          alert('Could not flag customer!');

          console.log(response);
        });
      });

      $(document).on('click', '.show-images-button', function() {
          $(this).siblings('.show-images-wrapper').toggleClass('hidden');
          $(this).parent().find(".select-all-images-button").toggleClass('hidden');
      });

       $(document).on('click', '.select-all-images-button', function() {
          $(this).parent().find(".select-product-image").trigger('click');
      });

      $(document).on('click', '.fix-message-error', function() {
        var id = $(this).data('id');
        var thiss = $(this);

        $.ajax({
          type: "POST",
          url: "{{ url('whatsapp') }}/" + id + "/fixMessageError",
          data: {
            _token: "{{ csrf_token() }}",
          },
          beforeSend: function() {
            $(thiss).text('Fixing...');
          }
        }).done(function() {
          $(thiss).remove();
        }).fail(function(response) {
          $(thiss).html('<img src="/images/flagged.png" />');

          console.log(response);

          alert('Could not mark as fixed');
        });
      });

      $(document).on('click', '.resend-message', function() {
        var id = $(this).data('id');
        var thiss = $(this);

        $.ajax({
          type: "POST",
          url: "{{ url('whatsapp') }}/" + id + "/resendMessage",
          data: {
            _token: "{{ csrf_token() }}",
          },
          beforeSend: function() {
            $(thiss).text('Sending...');
          }
        }).done(function() {
          $(thiss).remove();
        }).fail(function(response) {
          $(thiss).text('Resend');

          console.log(response);

          alert('Could not resend message');
        });
      });

      $(document).on('click', '.quick-shortcut-button', function(e) {
        e.preventDefault();

        var customer_id = $(this).parent().find('input[name="customer_id"]').val();
        var instruction = $(this).parent().find('input[name="instruction"]').val();
        var category_id = $(this).parent().find('input[name="category_id"]').val();
        var assigned_to = $(this).parent().find('input[name="assigned_to"]').val();

        $.ajax({
          type: "POST",
          url: "{{ route('instruction.store') }}",
          data: {
            _token: "{{ csrf_token() }}",
            customer_id: customer_id,
            instruction: instruction,
            category_id: category_id,
            assigned_to: assigned_to,
          },
          beforeSend: function() {

          }
        }).done(function(response) {
          window.location.reload();
        }).fail(function(response) {
          alert('Could not execute shortcut!');

          console.log(response);
        });
      });

      $(document).on('click', '.send-instock-shortcut', function() {
        var customer_id = $(this).data('id');
        var thiss = $(this);

        $.ajax({
          type: "POST",
          url: "{{ route('customer.send.instock') }}",
          data: {
            _token: "{{ csrf_token() }}",
            customer_id: customer_id
          },
          beforeSend: function() {
            $(thiss).text('Sending...');
          }
        }).done(function(response) {
          $(thiss).text('Send In Stock');
        }).fail(function(response) {
          $(thiss).text('Send In Stock');

          alert('Could not sent instock!');

          console.log(response);
        });
      });

      $(document).on('click', '.latest-scraped-shortcut', function() {
        var id = $(this).data('id');

        $('#categoryBrandModal').find('input[name="customer_id"]').val(id);
      });

      $('#sendScrapedButton').on('click', function(e) {
        e.preventDefault();

        var formData = $('#categoryBrandModal').find('form').serialize();
        console.log(formData);
        var thiss = $(this);

        if (!$(this).is(':disabled')) {
          $.ajax({
            type: "POST",
            url: "{{ route('customer.send.scraped') }}",
            data: formData,
            beforeSend: function() {
              $(thiss).text('Sending...');
              $(thiss).attr('disabled', true);
            }
          }).done(function() {
            $('#categoryBrandModal').find('.close').click();
            $(thiss).text('Send');
            $(thiss).attr('disabled', false);
          }).fail(function(response) {
            $(thiss).text('Send');
            $(thiss).attr('disabled', false);
            console.log(response);

            alert('Could not send 20 images');
          });
        }
      });

      $(document).on('click', '.priority-customer', function() {
        var customer_id = $(this).data('id');
        var thiss = $(this);

        $.ajax({
          type: "POST",
          url: "{{ route('customer.priority') }}",
          data: {
            _token: "{{ csrf_token() }}",
            customer_id: customer_id
          },
          beforeSend: function() {
            $(thiss).text('Prioritizing...');
          }
        }).done(function(response) {
          if (response.is_priority == 1) {
            $(thiss).html('<img src="/images/customer-priority.png" />');
          } else {
            $(thiss).html('<img src="/images/customer-not-priority.png" />');
          }

        }).fail(function(response) {
          $(thiss).html('<img src="/images/customer-not-priority.png" />');

          alert('Could not prioritize customer!');

          console.log(response);
        });
      });

      $(document).on('click', '.maximize-chat-box', function() {
          $("#allHolder").toggleClass('chat-window');
          $("#overlay").fadeToggle();
      });

      /*$(document).on('click', '.resend-message-js', function() {
          let messageId = $(this).attr('data-id');
          $.ajax({
              url: "{{ action('WhatsAppController@resendMessage2') }}",
              data: {
                    message_id: messageId
              },
              success: function() {
                    toastr['success']('Message resent successfully!')
              }
          });
      });*/

      $(document).on('change', '.change-whatsapp-no', function () {
            var $this = $(this);
            $.ajax({
                type: "POST",
                url: "{{ route('customer.change.whatsapp') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    customer_id: $this.data("customer-id"),
                    number : $this.val(),
                    type : $this.data("type")
                }
            }).done(function () {
                alert('Number updated successfully!');
            }).fail(function (response) {
                console.log(response);
            });
        });

      $(document).on('click', '.send-contact-user-btn', function () {
            var $form = $("#send-contact-to-user");
            var $this = $(this);
            $.ajax({
                type: "POST",
                url: "{{ route('customer.send.contact') }}",
                data: $form.serialize(),
                beforeSend : function(){
                  $this.html("Sending message...");
                }
            }).done(function () {
                $this.html('<img style="width: 17px;" src="/images/filled-sent.png">');
                $("#sendContacts").modal("hide");
            }).fail(function (response) {
                console.log(response);
            });
        });

        $(document).on('click', '.download-contact-user-btn', function () {
            var $form = $("#download-contact-to-user");
            var $this = $(this);
            $.ajax({
                type: "POST",
                url: "{{ route('customer.download.contact') }}",
                data: $form.serialize(),
                beforeSend : function(){
                  $this.html("Sending message...");
                }
            }).done(function () {
                $this.html('<img style="width: 17px;" src="/images/filled-sent.png">');
                $("#downloadContacts").modal("hide");
            }).fail(function (response) {
                console.log(response);
            });
        });


        $(document).on('submit', '.js-erp-lead-frm', function (e) {
          e.preventDefault();
            var url = $(this).attr('action');
            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                  if ($('#add_lead').find('input[name="product_id"]').length > 0 && $('#add_lead').find('input[name="product_id"]').val()) {
                      var dataSending = $('#add_lead').find('input[name="product_id"]').data('object');
                      if (typeof dataSending != 'object'){
                          dataSending = {};
                      }

                      $.ajax({
                          type: "POST",
                          url: "/leads/sendPrices",
                          data: $.extend({
                              _token:  $('meta[name="csrf-token"]').attr('content'),
                              customer_id: $('#add_lead').find('input[name="customer_id"]').val(),
                              selected_product: [$('#add_lead').find('input[name="product_id"]').val()]
                          },dataSending),
                          success: function(response) {
                            location.reload();
                          }
                      });
                  } else {
                    location.reload();
                  }
                }
            }).fail(function(error) {
                alert('There was an error creating a lead');
            });
        });

        $(document).on('submit', '.add_order_frm', function (e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                  if ($('#add_order').find('input[name="selected_product[]"]').length > 0 && $('#add_order').find('input[name="selected_product[]"]').val()) {
                      $.ajax({
                          type: "POST",
                          url: "/order/send/Delivery",
                          data: {
                            _token:  $('meta[name="csrf-token"]').attr('content'),
                            customer_id: $('#add_order').find('input[name="customer_id"]').val(),
                            order_id: response.order.id,
                            selected_product: [$('#add_order').find('input[name="selected_product[]"]').val()]
                          },
                          success: function(response) {
                            location.reload();
                          }
                      });
                  } else {
                    location.reload();
                  }
                }
            }).fail(function(error) {
                alert('There was an error creating a lead');
            });
        });
      // $(document).on()

      $(".checkboxs").change(function () {
            id = $(this).val();

            if (this.checked) {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('broadcast.add.manual') }}',
                    data: {
                        id: id,
                        type: 1,
                    }, success: function (data) {
                        console.log(data);
                        if (data.status == 'error') {
                            alert('Something went wrong');
                        } else {
                            // alert('Customer Added to Broadcastlist');
                        }

                    },
                    error: function (data) {
                        alert('Something went wrong');
                    }
                });
            } else {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('broadcast.add.manual') }}',
                    data: {
                        id: id,
                        type: 0
                    },
                }).done(response => {
                    //alert('Customer Removed From Broadcastlist');
                });
            }
        });




      // $("#add_customer_address").validate({
      //     rules: {
      //         telephone: "required",
      //         entity_id: "required",
      //         parent_id: "required",
      //         address_type: "required",
      //         region: "required",
      //         region_id: "required",
      //         postcode: "required",
      //         firstname: "required",
      //         middlename: "required",
      //         company: "required",
      //         country_id: "required",
      //     },
      //     messages: {
      //         first_name : "Please enter name",
      //         last_name : "Please enter surname",
      //         telephone : "Please enter phone number",
      //     },
      //     submitHandler: function (event) {
      //         // $('#apply_job').submit(function (event) {
      //             event.preventDefault();
      //         var formData = new FormData($(this)[0]);
      //         $.ajax({
      //             url: '/api/customer/add_customer_data',
      //             type: 'POST',
      //             headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
      //             data: formData,
      //             dataType: 'json',
      //             contentType: false,
      //             processData: false,
      //             success: function (result) {
      //                 console.log(result);
      //             }
      //         });
      //     }
      // });


      $(document).on('click', '.add_customer_address-btn', function (e) {
          e.preventDefault();
          var $form = $("#add_customer_address");
          var $this = $(this);
          $.ajax({
              type: "POST",
              url: "/customer/add_customer_address",
              data: $form.serialize(),
              beforeSend : function(){
                  $this.html("Sending message...");
              }
          }).done(function () {
              $this.html('<img style="width: 17px;" src="/images/filled-sent.png">');
              $("#exampleModal").modal("hide");
          }).fail(function (response) {
              console.log(response);
          });
      });
  </script>

  <style>
      .chat-window {
          display: block;
          position: fixed;
          top: 50px;
          left: 450px;
          width: 700px;
          /*height: 700px;*/
          background: aliceblue;
          border: 5px solid #ccc;
          z-index: 4;
          /*overflow: auto;*/
      }

      .chat-window #message-wrapper {
          height: 600px;
      }

      #overlay {
          background: #000;
          opacity: 0.6;
          position: fixed;
          top: 0;
          width: 100%;
          height: 100%;
          left: 0;
          z-index: 4;
          display: none;
      }
  </style>
@endsection
