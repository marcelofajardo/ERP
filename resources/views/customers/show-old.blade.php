@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">

  <style>
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
  </style>
@endsection

@section('content')


<div class="row">
  <div class="col-lg-12 margin-tb">
    <div class="pull-left">
      <h2>Customer Page</h2>
    </div>
    <div class="pull-right mt-4">
      <a class="btn btn-secondary" href="{{ route('customer.index') }}">Back</a>
      <a class="btn btn-secondary" href="#" id="quick_add_lead">+ Lead</a>
      <a class="btn btn-secondary" href="#" id="quick_add_order">+ Order</a>
      <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#privateViewingModal">Set Up for Private Viewing</button>
    </div>
  </div>
</div>

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

<div id="exTab2" class="container">
  <ul class="nav nav-tabs">
    <li class="active">
      <a href="#one" data-toggle="tab">Customer Info</a>
    </li>
    <li>
      <a href="#6" data-toggle="tab">Call Recording</a>
    </li>
    @if (count($customer->leads) > 0)
    <li><a href="#2" data-toggle="tab">Leads</a></li>
    @endif
    @if (count($customer->orders) > 0)
      <li><a href="#3" data-toggle="tab">Orders</a></li>
    @endif
    @if ($customer->instagramThread)
      <li><a href="#igdm" data-toggle="tab">Instagram DM</a></li>
    @endif
    @if (count($customer->private_views) > 0)
      <li><a href="#private_view_tab" data-toggle="tab">Private Viewing</a></li>
    @endif
    <li>
      <a href="#suggestion_tab" data-toggle="tab">Suggestions</a>
    </li>
    <li>
      <a href="#email_tab" data-toggle="tab" data-customerid="{{ $customer->id }}" data-type="inbox">Emails</a>
    </li>
  </ul>
</div>

<div class="tab-content ">
  <div class="tab-pane active mt-3" id="one">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <strong>Name:</strong> {{ $customer->name }}
          @if ($customer->is_blocked == 1)
            <span class="badge badge-secondary">Blocked</span>
          @endif
          <button type="button" class="btn btn-image block-twilio" data-id="{{ $customer->id }}"><img src="/images/call-blocked.png" /></button>
        </div>

        <div class="form-group">
  				<input type="checkbox" name="do_not_disturb" id="do_not_disturb" {{ $customer->do_not_disturb ? 'checked' : '' }} data-id="{{ $customer->id }}">
  				<label for="do_not_disturb">Do Not Disturb</label>

          <span class="text-success change_status_message" style="display: none;">Successfully updated DND status</span>
  			</div>

        @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HOD of CRM'))
          <div class="form-group">
            @if (strlen($customer->phone) != 12 || !preg_match('/^[91]{2}/', $customer->phone))
              <span class="badge badge-danger" data-toggle="tooltip" data-placement="top" title="Number must be 12 digits and start with 91">!</span>
            @endif
            <strong>Phone:</strong> <span data-twilio-call data-context="customers" data-id="{{ $customer->id }}">{{ $customer->phone }}</span>
          </div>

          <div class="form-group">
            <strong>Instagram Handle:</strong> {{ $customer->ig_username }}
          </div>
        @endif

        <div class="form-group">
          <strong>Address:</strong> {{ $customer->address }}
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

      <div class="col-md-6">
        @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HOD of CRM'))
          <div class="form-group">
            <strong>Email:</strong> <a href="#" class="btn-link" data-toggle="modal" data-target="#emailSendModal">{{ $customer->email }}</a>
          </div>

          <div class="form-group">
    				<strong>Whatsapp Number:</strong>
    				<select name="whatsapp_number" class="form-control" id="whatsapp_change">
    					<option value>None</option>
    					{{-- @foreach ($solo_numbers as $number => $name)
    						<option value="{{ $number }}" {{ $customer->whatsapp_number == $number ? 'selected' : '' }}>{{ $name }}</option>
    					@endforeach --}}
              @foreach ($api_keys as $api_key)
    						<option value="{{ $api_key->number }}" {{ $customer->whatsapp_number == $api_key->number ? 'selected' : '' }}>{{ $api_key->number }}</option>
    					@endforeach
    				</select>
    				@if ($errors->has('whatsapp_number'))
    						<div class="alert alert-danger">{{$errors->first('whatsapp_number')}}</div>
    				@endif

            <span class="text-success change_status_message" style="display: none;">Successfully changed whatsapp number</span>
    			</div>
        @endif

        <div class="form-group">
          <strong>Rating:</strong> {{ $customer->rating }}
        </div>

        <div class="row">
          <div class="col">
            <div class="form-group">
              <strong>City:</strong> {{ $customer->city }}
            </div>
          </div>

          <div class="col">
            <div class="form-group">
              <strong>Country:</strong> {{ $customer->country }}
            </div>
          </div>

          <div class="col">
            <div class="form-group">
              <strong>Pincode:</strong> {{ $customer->pincode }}
            </div>
          </div>
        </div>

        <div class="form-group">
          <strong>Created at:</strong> {{ Carbon\Carbon::parse($customer->created_at)->format('d-m H:i') }}
        </div>
      </div>

      <div class="col-xs-12">
        <button type="button" class="btn btn-secondary mb-3" data-toggle="modal" data-target="#instructionModal">Add Instruction</button>

        <form class="form-inline mb-3" action="{{ route('instruction.category.store') }}" method="POST">
          @csrf
          <div class="form-group">
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Category" required>
          </div>

          <button type="submit" class="btn btn-secondary ml-3">Create Category</button>
        </form>

        @include('customers.partials.modal-instruction')

        <div id="exTab3" class="container">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#4" data-toggle="tab">Instructions</a>
            </li>
            <li><a href="#5" data-toggle="tab">Complete</a></li>
          </ul>
        </div>

        <div class="tab-content ">

          <div class="tab-pane active mt-3" id="4">
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
                @foreach ($customer->instructions()->where('verified', 0)->latest()->limit(3)->get() as $instruction)
                    <tr>
                      <td>
                        <span data-twilio-call data-context="customers" data-id="{{ $customer->id }}">{{ $instruction->customer->phone }}</span>
                      </td>
                      <td>{{ $users_array[$instruction->assigned_to] ?? '' }}</td>
                      <td>{{ $instruction->category->name }}</td>
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

            <div id="instructionAccordion">
                <div class="card mb-5">
                  <div class="card-header" id="headingInstruction">
                    <h5 class="mb-0">
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
                            @foreach ($customer->instructions()->where('verified', 0)->latest()->offset(3)->limit(100)->get() as $key => $instruction)
                              <tr>
                                <td>
                                  <span data-twilio-call data-context="customers" data-id="{{ $customer->id }}">{{ $instruction->customer->phone }}</span>
                                </td>
                                <td>{{ $users_array[$instruction->assigned_to] ?? '' }}</td>
                                <td>{{ $instruction->category->name }}</td>
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

          <div class="tab-pane mt-3" id="5">
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
                @foreach ($customer->instructions()->where('verified', 1)->latest()->limit(3)->get() as $instruction)
                    <tr>
                      <td>
                        <span data-twilio-call data-context="customers" data-id="{{ $customer->id }}">{{ $instruction->customer->phone }}</span>
                      </td>
                      <td>{{ $users_array[$instruction->assigned_to] ?? '' }}</td>
                      <td>{{ $instruction->category->name }}</td>
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
                            @foreach ($customer->instructions()->where('verified', 1)->latest()->offset(3)->limit(100)->get() as $key => $instruction)
                              <tr>
                                <td>
                                  <span data-twilio-call data-context="customers" data-id="{{ $customer->id }}">{{ $instruction->customer->phone }}</span>
                                </td>
                                <td>{{ $users_array[$instruction->assigned_to] ?? '' }}</td>
                                <td>{{ $instruction->category->name }}</td>
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

        <div class="row">
          <div class="col-md-6 col-12 mb-3">
            <form action="{{ route('status.report.store') }}" method="POST">
              @csrf

              <input type="hidden" name="customer_id" value="{{ $customer->id }}">

              <div class="form-group">
                <strong>Next action due</strong>
                <a href="#" data-toggle="modal" data-target="#statusModal" class="btn-link">Add Action</a>

                <select class="form-control" name="status_id" required>
                  <option value="">Select action</option>
                  @foreach ($order_status_report as $status)
                    <option value="{{ $status->id }}">{{ $status->status }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group" id="completion_form_group">
                <strong>Completion Date:</strong>
                <div class='input-group date' id='report-completion-datetime'>
                  <input type='text' class="form-control" name="completion_date" value="{{ date('Y-m-d H:i') }}" />

                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>

                @if ($errors->has('completion_date'))
                    <div class="alert alert-danger">{{$errors->first('completion_date')}}</div>
                @endif
              </div>

              <button type="submit" class="btn btn-secondary">Add Report</button>
            </form>

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
            @endif
          </div>
        </div>

        @include('customers.partials.modal-remark')

      </div>
    </div>
  </div>

  <div class="tab-pane mt-3" id="6">
    <div class="row">
      <h2> Call Recording </h2>
      <table class="table table-bordered">
        <tr>
            <th style="width: 50%">Call Recording</th>
            <th style="width: 50%">Message</th>
            <th style="width: 50%">Call Time</th>
        </tr>
        @if (count($customer->call_recordings) > 0)
          @foreach ($customer->call_recordings as $history_val)
            <tr class="">
              <td><audio src="{{$history_val['recording_url']}}" controls preload="metadata">
              <p>Alas, your browser doesn't support html5 audio.</p>
              </audio> </td>
              <td>{{$history_val['message']}}</td>
              <td>{{$history_val['created_at']}}</td>
            </tr>
          @endforeach
        @endif
    </table>
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
        @foreach ($customer->leads as $key => $lead)
          <div class="card">
            <div class="card-header" id="headingLead{{ $key + 1 }}">
              <h5 class="mb-0">
                <button class="btn btn-link collapsed collapse-fix" data-toggle="collapse" data-target="#lead{{ $key + 1 }}" aria-expanded="false" aria-controls="lead{{ $key + 1 }}">
                  Lead {{ $key + 1 }}
                  <a href="{{ route('leads.show', $lead->id) }}" class="btn-image" target="_blank"><img src="/images/view.png" /></a>
                </button>
              </h5>
            </div>
            <div id="lead{{ $key + 1 }}" class="collapse collapse-element" aria-labelledby="headingLead{{ $key + 1 }}" data-parent="#leadAccordion">
              <div class="card-body">
                <form action="{{ route('leads.update', $lead->id) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="type" value="customer">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <strong>Brand:</strong>
                        <select multiple="" name="multi_brand[]" class="form-control multi_brand">
                          @php $multi_brand = is_array(json_decode($lead->multi_brand,true) ) ? json_decode($lead->multi_brand,true) : []; @endphp
                          @foreach($brands as $brand_item)
                            <option value="{{$brand_item['id']}}" {{ in_array($brand_item['id'] ,$multi_brand) ? 'Selected=Selected':''}}>{{$brand_item['name']}}</option>
                          @endforeach
                        </select>

                      </div>

                      <div class="form-group">
                        <strong>Categories</strong>
                        @php
                        // $selected_category = $lead->multi_category ? $lead->multi_category : '';
                        $selected_categories = is_array(json_decode( $lead->multi_category,true)) ? json_decode( $lead->multi_category ,true) : [] ;
                        $category_selection = \App\Category::attr(['name' => 'multi_category','class' => 'form-control'])
                        ->selected($selected_categories)
                        ->renderAsDropdown();
                        @endphp
                        {!! $category_selection  !!}
                      </div>

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

                              $products_array[$product_id] = $product->name ? $product->name : $product->sku;
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

                      <Select name="status" class="form-control change_status" data-leadid="{{ $lead->id }}">
                        @foreach($lead_status as $key => $value)
                          <option value="{{$value}}" {{$value == $lead->status ? 'Selected=Selected':''}}>{{$key}}</option>
                        @endforeach
                      </Select>
                      <span class="text-success change_status_message" style="display: none;">Successfully changed status</span>

                      <input type="hidden" class="form-control" name="userid" placeholder="status" value="{{$lead->userid}}"/>

                    </div>

                    <div class="form-group">
                      <strong>Created by:</strong>

                      <input type="text" class="form-control" name="" placeholder="Created by" value="{{ $lead->userid != 0 ? App\Helpers::getUserNameById($lead->userid) : '' }}" readonly/>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <strong>Comments:</strong>
                      <textarea  class="form-control" name="comments" placeholder="comments">{{$lead->comments}} </textarea>
                    </div>

                    <div class="form-group">
                      <strong>Sizes:</strong>
                      <input type="text" name="size" value="{{ $lead->size }}" class="form-control" placeholder="S, M, L">
                    </div>

                    <div class="form-group">
                      <strong>Assigned To:</strong>
                      <Select name="assigned_user" class="form-control">

                        @foreach($users_array as $id => $user)
                          <option value="{{ $id }}" {{ $id == $lead->assigned_user ? 'Selected=Selected':''}}>{{ $user }}</option>
                        @endforeach
                      </Select>
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
        @endphp
        @foreach ($customer->orders as $key => $order)
          @if ($order->order_status == 'Refund to be processed' || $order->order_status_id == \App\Helpers\OrderHelper::$refundToBeProcessed)
            @php
              array_push($refunded_orders, $order);
            @endphp
          @endif

          <div class="card">
            <div class="card-header" id="headingOrder{{ $key + 1 }}">
              <h5 class="mb-0">
                <button class="btn btn-link collapsed collapse-fix" data-toggle="collapse" data-target="#order{{ $key + 1 }}" aria-expanded="false" aria-controls="order{{ $key + 1 }}">
                  Order {{ $key + 1 }}
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
                      <div class="col-md-6 col-12">

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

                      </div>
                      <div class="col-md-6 col-12">

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

                         <div id="tracking-wrapper-{{ $order->id }}" style="display: {{ ($order->order_status == 'Product shiped to Client' || $order->order_status_id == \App\Helpers\OrderHelper::$productShippedToClient)  ? 'block' : 'none' }}">
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
                      $sales_persons = \App\Helpers::getUsersArrayByRole( 'Sales' );
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

                        @if (isset($order))
                          @if (($order->order_status == 'Advance received' || $order->order_status_id == \App\Helpers\OrderHelper::$advanceRecieved) && !$order->is_sent_initial_advance())
                            <div class="form-group">
                              <a href="{{ route('order.advance.receipt.email', $order->id) }}" class="btn btn-secondary">Email Advance Receipt</a>
                            </div>
                          @elseif ($order->is_sent_initial_advance())
                            <div class="form-group">
                              Advance Receipt was emailed
                            </div>
                          @endif

                          @if (($order->order_status == 'Advance received' || $order->order_status_id == \App\Helpers\OrderHelper::$advanceRecieved) && !$order->is_sent_initial_advance())
                            <div class="form-group">
                              <a href="{{ route('order.advance.receipt.print', $order->id) }}" class="btn btn-secondary">Print Advance Receipt</a>
                            </div>
                          @endif

                          <div class="form-group">
                            <a href="{{ route('order.generate.invoice', $order->id) }}" class="btn btn-secondary">Generate Invoice</a>
                            <a href="{{ route('settings.index') }}" class="btn-link" target="_blank">Edit Consignor Details</a>
                          </div>

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

                          @if ($order->order_type == 'offline' && !$order->is_sent_offline_confirmation())
                            <div class="form-group">
                              <a href="{{ route('order.send.confirmation.email', $order->id) }}" class="btn btn-secondary">Send Confirmation Email</a>
                            </div>
                          @elseif ($order->is_sent_offline_confirmation())
                            <div class="form-group">
                              Offline Confirmation Email was sent
                            </div>
                          @endif

                          @if ($order->is_sent_online_confirmation())
                            <div class="form-group">
                              Online Confirmation Email was sent
                            </div>
                          @endif
                        @endif

                        {{-- <div class="form-group">
                          <a href="#" class="btn btn-secondary create-magento-product" data-id="{{ $order->id }}">Create Magento Product</a>
                        </div> --}}

                      </div>

                      <div class="col-xs-12">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="text-center">
                              <h3>Product Details</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="products-table-{{ $order->id }}">
                                  <thead>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Sku</th>
                                    <th>Color</th>
                                    <th>Brand</th>
                                    <th style="width: 100px">Price</th>
                                    <th style="width: 100px">Size</th>
                                    <th style="width: 80px">Qty</th>
                                    <th>Purchase Status</th>
                                    <th>Action</th>
                                  </thead>
                                  <tbody>
                                    @foreach($order->order_product  as $order_product)
                                      <tr>
                                        @if(isset($order_product->product))
                                          <td>
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
                                            </td>
                                            <td>{{ $order_product->product->name }}</td>
                                            <td>{{ $order_product->product->sku }}</td>
                                            <td>{{ $order_product->product->color }}</td>
                                            <td>{{ \App\Http\Controllers\BrandController::getBrandName($order_product->product->brand) }}</td>
                                          @else
                                            <td></td>
                                            <td></td>
                                            <td>{{$order_product->sku}}</td>
                                            <td></td>
                                            <td></td>
                                          @endif

                                          <td>
                                            <input class="form-control" type="text" value="{{ $order_product->product_price }}" name="order_products[{{ $order_product->id }}][product_price]">
                                          </td>
                                          <td>
                                            @if(!empty($order_product->product->size))
                                              <?php

                                              $sizes = \App\Helpers::explodeToArray($order_product->product->size);
                                              $size_name = 'order_products['.$order_product->id.'][size]';

                                              echo Form::select($size_name,$sizes,( $order_product->size ), ['class' => 'form-control', 'placeholder' => 'Select a size'])
                                              ?>
                                            @else
                                              <select hidden class="form-control" name="order_products[{{ $order_product->id }}][size]">
                                                <option selected="selected" value=""></option>
                                              </select>
                                              nil
                                            @endif
                                          </td>
                                          <td>
                                            <input class="form-control" type="number" min="1" value="{{ $order_product->qty }}" name="order_products[{{ $order_product['id'] }}][qty]">
                                          </td>
                                          <td>
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
                        </div>
                        {{-- {{dd($data)}} --}}
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group btn-group">
                                <a href="{{ route('attachProducts',['order',$order->id, 'fake-parameter', $customer->id]) }}" class="btn btn-image"><img src="/images/attach.png" /></a>
                                <button type="button" class="btn btn-secondary add-product-button" data-orderid="{{ $order->id }}" data-toggle="modal" data-target="#productModal">+</button>
                            </div>
                        </div>
                      </div>

                      <div class="col-xs-12 text-center">
                        <button type="submit" class="btn btn-secondary">Update</button>
                      </div>
                  </div>
                </form>

                <hr>
                <h3 class="text-center">Delivery Approval</h3>

                <form class="form-inline my-3" action="{{ route('order.upload.approval', $order->id) }}" method="POST" enctype="multipart/form-data">
                  @csrf

                  <div class="form-group">
                    <input type="file" name="images[]" required multiple>
                  </div>

                  <button type="submit" class="btn btn-xs btn-secondary ml-3">Upload for Approval</button>
                </form>

                @if (isset($order->delivery_approval))
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Uploaded Photos</th>
                          <th>Approved</th>
                          <th>Voucher</th>
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
                          <td>
                             @if(auth()->user()->checkPermission('voucher-list'))
                              @if ($order->delivery_approval->voucher)
                                <button type="button" class="btn btn-xs btn-secondary edit-voucher" data-toggle="modal" data-target="#editVoucherModal" data-id="{{ $order->delivery_approval->voucher->id }}" data-amount="{{ $order->delivery_approval->voucher->amount }}" data-travel="{{ $order->delivery_approval->voucher->travel_type }}">Edit Voucher</button>
                              @else
                                <button type="button" class="btn btn-xs btn-secondary create-voucher" data-id="{{ $order->delivery_approval->id }}">Create Voucher</button>
                              @endif
                            @endif
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
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
      <div class="row">
        <table class="table table-bordered">
          <tr>
            <th>Products</th>
            <th>Date</th>
            <th>Delivery Images</th>
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
                @elseif (\Carbon\Carbon::parse($view->date)->format('Y-m-d') == date('Y-m-d'))
                  <form action="{{ route('stock.private.viewing.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                      <input type="hidden" name="view_id" value="{{ $view->id }}">
                      <input type="file" name="images[]" required multiple>
                    </div>

                    <button type="submit" class="btn btn-xs btn-secondary">Upload</button>
                  </form>
                @endif
              </td>
            </tr>
          @endforeach
      </table>
      </div>
    </div>
  @endif

  <div class="tab-pane mt-3" id="suggestion_tab">
    <h2>Suggestions</h2>
    <div class="row">
      <div class="col-12" id="suggestion-container"></div>
    </div>
  </div>

  <div class="tab-pane mt-3" id="email_tab">
    <div id="exTab3" class="container mb-3">
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
</div>

@include('customers.partials.modal-email')

<div class="row mt-5">
  <div class="col-xs-12 col-sm-6">
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
   </div>

   @include('customers.partials.modal-suggestion')

   <div class="col-xs-12 col-sm-6">
     <form action="{{ route('whatsapp.send', 'customer') }}" method="POST" enctype="multipart/form-data">
       <div id="paste-container" style="width: 200px;">

       </div>

       <div class="d-flex">
         @csrf

         <div class="form-group">
           <div class="upload-btn-wrapper btn-group pr-0 d-flex">
             <a href="{{ route('attachImages', ['customer', $customer->id, 1]) }}" class="btn btn-image px-1"><img src="/images/attach.png" /></a>
             <button type="button" class="btn btn-image px-1" data-toggle="modal" data-target="#suggestionModal">X</button>
             <button type="submit" class="btn btn-image px-1 send-communication"><img src="/images/filled-sent.png" /></button>
           </div>
         </div>

         <div class="form-group flex-fill mr-3">
           <textarea id="message-body" class="form-control mb-3" style="height: 110px;" name="body" placeholder="Send for approval"></textarea>

           <input type="hidden" name="screenshot_path" value="" id="screenshot_path" />
           <input type="hidden" name="status" value="1" />

           <div class="paste-container"></div>

           <p class="pb-4 mt-3" style="display: block;">
             <select name="quickCategory" id="quickCategory" class="form-control mb-3">
               <option value="">Select Category</option>
               @foreach($reply_categories as $category)
                 <option value="{{ $category->approval_leads }}">{{ $category->name }}</option>
               @endforeach
             </select>

             <select name="quickComment" id="quickComment" class="form-control">
               <option value="">Quick Reply</option>
             </select>
           </p>
         </div>

         <div class="form-group">
           <input type="file" class="dropify" name="image" data-height="100" />
           <button type="button" class="btn btn-xs btn-secondary my-3" data-toggle="modal" data-target="#ReplyModal" id="approval_reply">Create Quick Reply</button>
         </div>
       </div>


     </form>
   </div>

   <hr>

   @include('customers.partials.modal-reply')

   <div class="col-xs-12 col-sm-6 mt-3">
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
   </div>

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
</div>

<h2>Messages</h2>

<div class="row">
  <div class="col-md-4">
    <div class="form-inline">
      <div class="form-group">
        <form action="{{ route('customer.initiate.followup', $customer->id) }}" method="POST">
          @csrf

          <button type="submit" class="btn btn-secondary" {{ $customer->is_initiated_followup() ? 'disabled' : '' }}>Initiate Follow Up Sequence</button>
        </form>
      </div>

      @if ($customer->is_initiated_followup())
        <div class="form-group ml-3">
          <form action="{{ route('customer.stop.followup', $customer->id) }}" method="POST">
            @csrf

            <button type="submit" class="btn btn-secondary">STOP</button>
          </form>
        </div>
      @endif
    </div>
  </div>

  @if (isset($refunded_orders) && count($refunded_orders) > 0)
    <div class="col-md-4">
      <h4 class="position-absolute" style="top: -40px;">Refund Orders Status</h4>

      <div class="form-inline">
        <div class="form-group">
          <select class="form-control refund-orders" name="">
            <option value="">Select Order</option>
            @foreach ($refunded_orders as $order)
              <option value="{{ $order->id }}" data-answer={{ $order->refund_answer }}>{{ $order->order_id }}</option>
            @endforeach
          </select>
        </div>

        <div class="d-inline ml-3">
          <button type="button" class="btn btn-secondary customer-refund-answer" id="refund_answer_yes" data-answer="yes">Yes</button>
          <button type="button" class="btn btn-secondary customer-refund-answer" id="refund_answer_no" data-answer="no">No</button>
        </div>
      </div>
    </div>
  @endif
</div>

<div class="row">
  <div class="col-12 my-3" id="message-container"></div>

  <div class="col-xs-12 text-center">
    <button type="button" id="load-more-messages" data-nextpage="1" class="btn btn-secondary">Load More</button>
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

  <script type="text/javascript">
  jQuery(document).ready(function( $ ) {
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

    console.log(selected_product_images);
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

  $(document).on('click', '.create-product-lead', function(e) {
    e.preventDefault();

    var thiss = $(this);

    if (selected_product_images.length > 0) {
      var customer_id = {{ $customer->id }};
      var created_at = moment().format('YYYY-MM-DD HH:mm');

      $.ajax({
        type: 'POST',
        url: "{{ route('leads.store') }}",
        data: {
          _token: "{{ csrf_token() }}",
          customer_id: customer_id,
          rating: 1,
          status: 1,
          assigned_user: "{{ Auth::id() }}",
          selected_product: selected_product_images,
          type: "product-lead",
          created_at: created_at
        },
        beforeSend: function() {
          $(thiss).text('Creating...');
        },
        success: function(response) {
          $.ajax({
            type: "POST",
            url: "{{ route('leads.send.prices') }}",
            data: {
              _token: "{{ csrf_token() }}",
              customer_id: customer_id,
              lead_id: response.lead.id,
              selected_product: selected_product_images
            }
          }).done(function() {
            location.reload();
          }).fail(function(response) {
            console.log(response);
            alert('Could not send product prices to customer!');
          });
        }
      }).fail(function(error) {
        console.log(error);
        alert('There was an error creating a lead');
      });
    } else {
      alert('Please select at least 1 product first');
    }
  });

  $(document).on('click', '.create-product-order', function(e) {
    e.preventDefault();

    var thiss = $(this);

    if (selected_product_images.length > 0) {
      var customer_id = {{ $customer->id }};

      $.ajax({
        type: 'POST',
        url: "{{ route('order.store') }}",
        data: {
          _token: "{{ csrf_token() }}",
          customer_id: customer_id,
          order_type: "offline",
          convert_order: 'convert_order',
          selected_product: selected_product_images
        },
        beforeSend: function() {
          $(thiss).text('Creating...');
        },
        success: function(response) {
          $.ajax({
            type: "POST",
            url: "{{ route('order.send.delivery') }}",
            data: {
              _token: "{{ csrf_token() }}",
              customer_id: customer_id,
              order_id: response.order.id,
              selected_product: selected_product_images
            }
          }).done(function() {
            location.reload();
          }).fail(function(response) {
            console.log(response);
            alert('Could not send delivery message to customer!');
          });
        }
      }).fail(function(error) {
        console.log(error);
        alert('There was an error creating a order');
      });
    } else {
      alert('Please select at least 1 product first');
    }
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

            jQuery('.multi_brand').select2({
                placeholder: 'Brand',
                width: '100%'
            });


            jQuery('.multi_category').select2({
                placeholder: 'Categories',
                width: '100%'
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
            var url = '/leads/' + id + '/changestatus';
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
            success: function(response) {
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
            }
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
                 $.post( "/whatsapp/approve/customer", { messageId: message.id })
                   .done(function( data ) {
                     // console.log(data);
                     // if (data != 'success') {
                     //   data.forEach(function(id) {
                     //     $('#waMessage_' + id).find('.btn-approve').remove();
                     //   });
                     // }

                     element.remove();
                   }).fail(function(response) {
                     console.log(response);
                     alert(response.responseJSON.message);
                   });
             }

             // function createMessageArgs() {
             //      var data = new FormData();
             //     var text = $("#waNewMessage").val();
             //     var files = $("#waMessageMedia").prop("files");
             //     var text = $("#waNewMessage").val();
             //
             //     data.append("customer_id", customerId);
             //     if (files && files.length>0){
             //         for ( var i = 0; i != files.length; i ++ ) {
             //           data.append("media[]", files[ i ]);
             //         }
             //         return data;
             //     }
             //     if (text !== "") {
             //         data.append("message", text);
             //         return data;
             //     }
             //
             //     alert("please enter a message or attach media");
             //   }

        function renderMessage(message, tobottom = null) {
            var domId = "waMessage_" + message.id;
            var current = $("#" + domId);
            var is_admin = "{{ Auth::user()->hasRole('Admin') }}";
            var is_hod_crm = "{{ Auth::user()->hasRole('HOD of CRM') }}";
            var users_array = {!! json_encode($users_array) !!};
            var leads_assigned_user = "";

            if ( current.get( 0 ) ) {
              return false;
            }

             // if (message.body) {
             //
             //   var text = $("<div class='talktext'></div>");
             //   var p = $("<p class='collapsible-message'></p>");
             //
             //   if ((message.body).indexOf('<br>') !== -1) {
             //     var splitted = message.body.split('<br>');
             //     var short_message = splitted[0].length > 150 ? (splitted[0].substring(0, 147) + '...<br>' + splitted[1]) : message.body;
             //     var long_message = message.body;
             //   } else {
             //     var short_message = message.body.length > 150 ? (message.body.substring(0, 147) + '...') : message.body;
             //     var long_message = message.body;
             //   }
             //
             //   var images = '';
             //   var has_product_image = false;
             //
             //   if (message.images !== null) {
             //     message.images.forEach(function (image) {
             //       images += image.product_id !== '' ? '<a href="/products/' + image.product_id + '" data-toggle="tooltip" data-html="true" data-placement="top" title="<strong>Special Price: </strong>' + image.special_price + '<br><strong>Size: </strong>' + image.size + '<br><strong>Supplier: </strong>' + image.supplier_initials + '">' : '';
             //       images += '<div class="thumbnail-wrapper"><img src="' + image.image + '" class="message-img thumbnail-200" /><span class="thumbnail-delete" data-image="' + image.key + '">x</span></div>';
             //       images += image.product_id !== '' ? '<input type="checkbox" name="product" class="d-block mx-auto select-product-image" data-id="' + image.product_id + '" /></a>' : '';
             //
             //       if (image.product_id !== '') {
             //         has_product_image = true;
             //       }
             //     });
             //     images += '<br>';
             //   }
             //
             //   p.attr("data-messageshort", short_message);
             //   p.attr("data-message", long_message);
             //   p.attr("data-expanded", "false");
             //   p.attr("data-messageid", message.id);
             //   p.html(short_message);
             //
             //   if (message.status == 0 || message.status == 5 || message.status == 6) {
             //     var row = $("<div class='talk-bubble'></div>");
             //
             //     var meta = $("<em>Customer " + moment(message.created_at).format('DD-MM H:mm') + " </em>");
             //     var mark_read = $("<a href data-url='/message/updatestatus?status=5&id=" + message.id + "&moduleid=" + message.moduleid + "&moduletype=leads' style='font-size: 9px' class='change_message_status'>Mark as Read </a><span> | </span>");
             //     var mark_replied = $('<a href data-url="/message/updatestatus?status=6&id=' + message.id + '&moduleid=' + message.moduleid + '&moduletype=leads" style="font-size: 9px" class="change_message_status">Mark as Replied </a>');
             //
             //     row.attr("id", domId);
             //
             //     p.appendTo(text);
             //     $(images).appendTo(text);
             //     meta.appendTo(text);
             //
             //     if (message.status == 0) {
             //       mark_read.appendTo(meta);
             //     }
             //     if (message.status == 0 || message.status == 5) {
             //       mark_replied.appendTo(meta);
             //     }
             //
             //     text.appendTo(row);
             //
             //     if (tobottom) {
             //       row.appendTo(container);
             //     } else {
             //       row.prependTo(container);
             //     }
             //
             //   } else if (message.status == 4) {
             //     var row = $("<div class='talk-bubble' data-messageid='" + message.id + "'></div>");
             //     var chat_friend =  (message.assigned_to != 0 && message.assigned_to != leads_assigned_user && message.userid != message.assigned_to) ? ' - ' + users_array[message.assigned_to] : '';
             //     var meta = $("<em>" + users_array[message.userid] + " " + chat_friend + " " + moment(message.created_at).format('DD-MM H:mm') + " <img id='status_img_" + message.id + "' src='/images/1.png' /> &nbsp;</em>");
             //
             //     row.attr("id", domId);
             //
             //     p.appendTo(text);
             //     $(images).appendTo(text);
             //     meta.appendTo(text);
             //
             //     text.appendTo(row);
             //     if (tobottom) {
             //       row.appendTo(container);
             //     } else {
             //       row.prependTo(container);
             //     }
             //   } else { // APPROVAL MESSAGE
             //     var row = $("<div class='talk-bubble' data-messageid='" + message.id + "'></div>");
             //     var body = $("<span id='message_body_" + message.id + "'></span>");
             //     var edit_field = $('<textarea name="message_body" rows="8" class="form-control" id="edit-message-textarea' + message.id + '" style="display: none;">' + message.body + '</textarea>');
             //     var meta = "<em>" + users_array[message.userid] + " " + moment(message.created_at).format('DD-MM H:mm') + " <img id='status_img_" + message.id + "' src='/images/" + message.status + ".png' /> &nbsp;";
             //
             //     if (message.status == 2 && is_admin == false) {
             //       meta += '<a href data-url="/message/updatestatus?status=3&id=' + message.id + '&moduleid=' + message.moduleid + '&moduletype=leads" style="font-size: 9px" class="change_message_status">Mark as sent </a>';
             //     }
             //
             //     if (message.status == 1 && (is_admin == true || is_hod_crm == true)) {
             //       meta += '<a href data-url="/message/updatestatus?status=2&id=' + message.id + '&moduleid=' + message.moduleid + '&moduletype=leads" style="font-size: 9px" class="change_message_status wa_send_message" data-messageid="' + message.id + '">Approve</a>';
             //       meta += ' <a href="#" style="font-size: 9px" class="edit-message" data-messageid="' + message.id + '">Edit</a>';
             //     }
             //
             //     if (has_product_image) {
             //       meta += '<a href="#" class="btn btn-xs btn-secondary ml-1 create-product-lead">+ Lead</a>';
             //       meta += '<a href="#" class="btn btn-xs btn-secondary ml-1 create-product-order">+ Order</a>';
             //     }
             //
             //     meta += "</em>";
             //     var meta_content = $(meta);
             //
             //
             //
             //     row.attr("id", domId);
             //
             //     p.appendTo(body);
             //     body.appendTo(text);
             //     edit_field.appendTo(text);
             //     $(images).appendTo(text);
             //     meta_content.appendTo(text);
             //
             //     if (message.status == 2 && is_admin == false) {
             //       var copy_button = $('<button class="copy-button btn btn-secondary" data-id="' + message.id + '" moduleid="' + message.moduleid + '" moduletype="orders" data-message="' + message.body + '"> Copy message </button>');
             //       copy_button.appendTo(text);
             //     }
             //
             //
             //     text.appendTo(row);
             //
             //     if (tobottom) {
             //       row.appendTo(container);
             //     } else {
             //       row.prependTo(container);
             //     }
             //   }
             // }
             // else {
               // CHAT MESSAGES
               var row = $("<div class='talk-bubble'></div>");
               var body = $("<span id='message_body_" + message.id + "'></span>");
               var text = $("<div class='talktext'></div>");
               var edit_field = $('<textarea name="message_body" rows="8" class="form-control" id="edit-message-textarea' + message.id + '" style="display: none;">' + message.message + '</textarea>');
               var p = $("<p class='collapsible-message'></p>");

               var forward = $('<button class="btn btn-xs btn-secondary forward-btn" data-toggle="modal" data-target="#forwardModal" data-id="' + message.id + '">Forward >></button>');



               if (message.status == 0 || message.status == 5 || message.status == 6) {
                 var meta = $("<em>Customer " + moment(message.created_at).format('DD-MM H:mm') + " </em>");
                 var mark_read = $("<a href data-url='/whatsapp/updatestatus?status=5&id=" + message.id + "' style='font-size: 9px' class='change_message_status'>Mark as Read </a><span> | </span>");
                 var mark_replied = $('<a href data-url="/whatsapp/updatestatus?status=6&id=' + message.id + '" style="font-size: 9px" class="change_message_status">Mark as Replied </a>');

                 // row.attr("id", domId);
                 p.appendTo(text);

                 // $(images).appendTo(text);
                 meta.appendTo(text);

                 if (message.status == 0) {
                   mark_read.appendTo(meta);
                 }

                 if (message.status == 0 || message.status == 5) {
                   mark_replied.appendTo(meta);
                 }

                 text.appendTo(row);

                 if (tobottom) {
                   row.appendTo(container);
                 } else {
                   row.prependTo(container);
                 }

                 forward.appendTo(meta);

               } else if (message.status == 4) {
                 var row = $("<div class='talk-bubble' data-messageid='" + message.id + "'></div>");
                 var chat_friend =  (message.assigned_to != 0 && message.assigned_to != leads_assigned_user && message.user_id != message.assigned_to) ? ' - ' + users_array[message.assigned_to] : '';
                 var meta = $("<em>" + users_array[message.user_id] + " " + chat_friend + " " + moment(message.created_at).format('DD-MM H:mm') + " <img id='status_img_" + message.id + "' src='/images/1.png' /> &nbsp;</em>");

                 // row.attr("id", domId);

                 p.appendTo(text);
                 $(images).appendTo(text);
                 meta.appendTo(text);

                 text.appendTo(row);
                 if (tobottom) {
                   row.appendTo(container);
                 } else {
                   row.prependTo(container);
                 }
               } else {
                 if (message.sent == 0) {
                   var meta_content = "<em>" + (parseInt(message.user_id) !== 0 ? users_array[message.user_id] : "Unknown") + " " + moment(message.created_at).format('DD-MM H:mm') + " </em>";
                 } else {
                   var meta_content = "<em>" + (parseInt(message.user_id) !== 0 ? users_array[message.user_id] : "Unknown") + " " + moment(message.created_at).format('DD-MM H:mm') + " <img id='status_img_" + message.id + "' src='/images/1.png' /></em>";
                 }

                 var error_flag = '';
                 if (message.error_status == 1) {
                   error_flag = "<span class='badge badge-warning'>Resent</span>";
                 } else if (message.error_status == 2) {
                   error_flag = "<span class='badge badge-danger'>Error</span>";
                 }



                 var meta = $(meta_content);

                 edit_field.appendTo(text);

                 if (!message.approved) {
                     var approveBtn = $("<button class='btn btn-xs btn-secondary btn-approve ml-3'>Approve</button>");
                     var editBtn = ' <a href="#" style="font-size: 9px" class="edit-message whatsapp-message ml-2" data-messageid="' + message.id + '">Edit</a>';
                     approveBtn.click(function() {
                         approveMessage( this, message );
                     } );
                     if (is_admin || is_hod_crm) {
                       approveBtn.appendTo( meta );
                       $(editBtn).appendTo( meta );
                     }
                 }

                 forward.appendTo(meta);

                 $(error_flag).appendTo(meta);
               }


               // if (!message.received) {
               //   if (message.sent == 0) {
               //     var meta_content = "<em>" + (parseInt(message.user_id) !== 0 ? users_array[message.user_id] : "Unknown") + " " + moment(message.created_at).format('DD-MM H:mm') + " </em>";
               //   } else {
               //     var meta_content = "<em>" + (parseInt(message.user_id) !== 0 ? users_array[message.user_id] : "Unknown") + " " + moment(message.created_at).format('DD-MM H:mm') + " <img id='status_img_" + message.id + "' src='/images/1.png' /></em>";
               //   }
               //
               //   var meta = $(meta_content);
               // } else {
               //   var meta = $("<em>Customer " + moment(message.created_at).format('DD-MM H:mm') + " </em>");
               // }

               row.attr("id", domId);

               p.attr("data-messageshort", message.message);
               p.attr("data-message", message.message);
               p.attr("data-expanded", "true");
               p.attr("data-messageid", message.id);
               // console.log("renderMessage message is ", message);
               if (message.message) {
                 p.html(message.message);
               } else if (message.media_url) {
                   var splitted = message.content_type.split("/");
                   if (splitted[0]==="image" || splitted[0] === 'm') {
                       var a = $("<a></a>");
                       a.attr("target", "_blank");
                       a.attr("href", message.media_url);
                       var img = $("<img></img>");
                       img.attr("src", message.media_url);
                       img.attr("width", "100");
                       img.attr("height", "100");
                       img.appendTo( a );
                       a.appendTo( p );
                       // console.log("rendered image message ", a);
                   } else if (splitted[0]==="video") {
                       $("<a target='_blank' href='" + message.media_url+"'>"+ message.media_url + "</a>").appendTo(p);
                   }
               }

               var has_product_image = false;

               if (message.images) {
                 var images = '';
                 message.images.forEach(function (image) {
                   images += image.product_id !== '' ? '<a href="/products/' + image.product_id + '" data-toggle="tooltip" data-html="true" data-placement="top" title="<strong>Special Price: </strong>' + image.special_price + '<br><strong>Size: </strong>' + image.size + '<br><strong>Supplier: </strong>' + image.supplier_initials + '">' : '';
                   images += '<div class="thumbnail-wrapper"><img src="' + image.image + '" class="message-img thumbnail-200" /><span class="thumbnail-delete whatsapp-image" data-image="' + image.key + '">x</span></div>';
                   images += image.product_id !== '' ? '<input type="checkbox" name="product" class="d-block mx-auto select-product-image" data-id="' + image.product_id + '" /></a>' : '';

                   if (image.product_id !== '') {
                     has_product_image = true;
                   }
                 });

                 images += '<br>';
                 $(images).appendTo(text);
               }

               p.appendTo(body);
               body.appendTo(text);

               // if (message.status == 0 || message.status == 5 || message.status == 6) {
               //
               // } else {
               //
               //
               // }

               meta.appendTo(text);


               // if (!message.received) {
               //   // if (!message.approved) {
               //   //     var approveBtn = $("<button class='btn btn-xs btn-secondary btn-approve ml-3'>Approve</button>");
               //   //     var editBtn = ' <a href="#" style="font-size: 9px" class="edit-message whatsapp-message ml-2" data-messageid="' + message.id + '">Edit</a>';
               //   //     approveBtn.click(function() {
               //   //         approveMessage( this, message );
               //   //     } );
               //   //     if (is_admin || is_hod_crm) {
               //   //       approveBtn.appendTo( text );
               //   //       $(editBtn).appendTo( text );
               //   //     }
               //   // }
               // } else {
               //   var moduleid = 0;
               //   var mark_read = $("<a href data-url='/whatsapp/updatestatus?status=5&id=" + message.id + "&moduleid=" + moduleid+ "&moduletype=leads' style='font-size: 9px' class='change_message_status'>Mark as Read </a><span> | </span>");
               //   var mark_replied = $('<a href data-url="/whatsapp/updatestatus?status=6&id=' + message.id + '&moduleid=' + moduleid + '&moduletype=leads" style="font-size: 9px" class="change_message_status">Mark as Replied </a>');
               //
               //   if (message.status == 0) {
               //     mark_read.appendTo(meta);
               //   }
               //   if (message.status == 0 || message.status == 5) {
               //     mark_replied.appendTo(meta);
               //   }
               // }

               // var forward = $('<button class="btn btn-xs btn-secondary forward-btn" data-toggle="modal" data-target="#forwardModal" data-id="' + message.id + '">Forward >></button>');

               if (has_product_image) {
                 var create_lead = $('<a href="#" class="btn btn-xs btn-secondary ml-1 create-product-lead">+ Lead</a>');
                 var create_order = $('<a href="#" class="btn btn-xs btn-secondary ml-1 create-product-order">+ Order</a>');

                 create_lead.appendTo(meta);
                 create_order.appendTo(meta);
               }

               // forward.appendTo(meta);

               // if (has_product_image) {
               //
               // }

               text.appendTo( row );

               if (message.status == 7) {
                 if (tobottom) {
                   row.appendTo(suggestion_container);
                 } else {
                   row.prependTo(suggestion_container);
                 }
               } else {
                 if (tobottom) {
                   row.appendTo(container);
                 } else {
                   row.prependTo(container);
                 }
               }

             // }

                     return true;
        }
        function pollMessages(page = null, tobottom = null, addElapse = null) {
                 var qs = "";
                 qs += "?customerId=" + customerId;
                 if (page) {
                   qs += "&page=" + page;
                 }
                 if (addElapse) {
                     qs += "&elapse=3600";
                 }
                 var anyNewMessages = false;

                 return new Promise(function(resolve, reject) {
                     $.getJSON("/whatsapp/pollMessagesCustomer" + qs, function( data ) {

                         data.data.forEach(function( message ) {
                             var rendered = renderMessage( message, tobottom );
                             if ( !anyNewMessages && rendered ) {
                                 anyNewMessages = true;
                             }
                         } );

                         if (page) {
                           $('#load-more-messages').text('Load More');
                           can_load_more = true;
                         }

                         if ( anyNewMessages ) {
                             scrollChatTop();
                             anyNewMessages = false;
                         }
                         if (!addElapse) {
                             addElapse = true; // load less messages now
                         }


                         resolve();
                     });

                 });
        }
             function scrollChatTop() {
                 // console.log("scrollChatTop called");
                 // var el = $(".chat-frame");
                 // el.scrollTop(el[0].scrollHeight - el[0].clientHeight);
             }
        function startPolling() {
          setTimeout( function() {
                     pollMessages(null, null, addElapse).then(function() {
                         startPolling();
                     }, errorHandler);
                 }, 1000);
        }
        // function sendWAMessage() {
        //   var data = createMessageArgs();
        //          //var data = new FormData();
        //          //data.append("message", $("#waNewMessage").val());
        //          //data.append("lead_id", leadId );
        //   $.ajax({
        //     url: '/whatsapp/sendMessage/customer',
        //     type: 'POST',
        //              "dataType"    : 'text',           // what to expect back from the PHP script, if anything
        //              "cache"       : false,
        //              "contentType" : false,
        //              "processData" : false,
        //              "data": data
        //   }).done( function(response) {
        //       $('#waNewMessage').val('');
        //       $('#waNewMessage').closest('.form-group').find('.dropify-clear').click();
        //       pollMessages();
        //     // console.log("message was sent");
        //   }).fail(function(errObj) {
        //     alert("Could not send message");
        //   });
        // }

        // sendBtn.click(function() {
        //   sendWAMessage();
        // } );
        startPolling();

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
               pollMessages();
               $(thiss).closest('form').find('textarea').val('');
               $('#paste-container').empty();
               $('#screenshot_path').val('');
               $(thiss).closest('form').find('.dropify-clear').click();
             }).fail(function(response) {
               console.log(response);
               alert('Error sending a message');
             });
           } else {
             $(this).closest('form')[0].reportValidity();
           }

         });

         var can_load_more = true;

         $(window).scroll(function() {
           var top = $(window).scrollTop();
           var document_height = $(document).height();
           var window_height = $(window).height();

           if (top >= (document_height - window_height - 200)) {
             if (can_load_more) {
               var current_page = $('#load-more-messages').data('nextpage');
               $('#load-more-messages').data('nextpage', current_page + 1);
               var next_page = $('#load-more-messages').data('nextpage');
               console.log(next_page);
               $('#load-more-messages').text('Loading...');

               can_load_more = false;

               pollMessages(next_page, true);
             }
           }
         });

         $(document).on('click', '#load-more-messages', function() {
           var current_page = $(this).data('nextpage');
           $(this).data('nextpage', current_page + 1);
           var next_page = $(this).data('nextpage');
           $('#load-more-messages').text('Loading...');

           pollMessages(next_page, true);
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

        // var image_container = '<div class="thumbnail-wrapper"><img src="' + image + '" class="message-img thumbnail-200" /><span class="thumbnail-delete" data-image="' + image + '">x</span></div>';
        // var new_message = message.replace(image_container, '');

        // if (new_message.indexOf('message-img') != -1) {
        //   var short_new_message = new_message.substr(0, new_message.indexOf('<div class="thumbnail-wrapper">')).length > 150 ? (new_message.substr(0, 147)) : new_message;
        // } else {
        //   var short_new_message = new_message.length > 150 ? new_message.substr(0, 147) + '...' : new_message;
        // }

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
            // $('#message_body_' + message_id).children('.collapsible-message').data('messageshort', short_new_message);
            // $('#message_body_' + message_id).children('.collapsible-message').data('message', new_message);
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

        var thiss = $(this);
        var token = "{{ csrf_token() }}";
        var url = "{{ route('leads.store') }}";
        var customer_id = {{ $customer->id }};
        var created_at = moment().format('YYYY-MM-DD HH:mm');

        $.ajax({
          type: 'POST',
          url: url,
          data: {
            _token: token,
            customer_id: customer_id,
            rating: 1,
            status: 1,
            assigned_user: "{{ Auth::id() }}",
            created_at: created_at
          },
          beforeSend: function() {
            $(thiss).text('Creating...');
          },
          success: function() {
            location.reload();
          }
        }).fail(function(error) {
          console.log(error);
          alert('There was an error creating a lead');
        });
      });

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
            order_type: "offline"
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

      $(document).on('click', '.forward-btn', function() {
        var id = $(this).data('id');
        $('#forward_message_id').val(id);
      });

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
            text: reply.reply
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

        var uid = $(this).data('uid');
        var type = $(this).data('type');
        var email_type = 'server';

        if (uid == 'no') {
          uid = $(this).data('id');
          email_type = 'local';
        }

        $.ajax({
          type: "GET",
          url: "{{ route('customer.email.fetch') }}",
          data: {
            uid: uid,
            type: type,
            email_type: email_type
          },
          beforeSend: function() {
            $('#email-content .card').html('Loading...');
          }
        }).done(function(response) {
          $('#email-content .card').html(response.email);
        }).fail(function(response) {
          $('#email-content .card').html();

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
            $('#email_tab #email-container .card').html('Loading emails');
          }
        }).done(function(response) {
          $('#email_tab #email-container').html(response.emails);
        }).fail(function(response) {
          $('#email_tab #email-container .card').html();

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
            var badge = $('<span class="badge badge-secondary">Blocked</span>');

            $(thiss).parent().append(badge);
            $(thiss).html('<img src="/images/call-blocked.png" />');
          } else {
            $(thiss).html('<img src="/images/call-blocked.png" />');
            $(thiss).parent().find('.badge').remove();
          }

          // $(thiss).remove();
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
        var checked = $(this).prop('checked');
        var id = $(this).data('id');
        var thiss = $(this);

        if (checked) {
          var option = 1;
        } else {
          var option = 0;
        }

        $.ajax({
          type: "POST",
          url: "{{ url('customer') }}/" + id + '/updateDND',
          data: {
            _token: "{{ csrf_token() }}",
            do_not_disturb: option
          }
        }).done(function() {
          $(thiss).siblings('.change_status_message').fadeIn(400);

          setTimeout(function () {
            $(thiss).siblings('.change_status_message').fadeOut(400);
          }, 2000);
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
  </script>
@endsection
