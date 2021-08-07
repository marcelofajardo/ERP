@extends('layouts.app')

@section('favicon' , 'task.png')

@section('title', 'Task Show')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">

  <style>
    #message-wrapper {
      height: 450px;
      overflow-y: scroll;
    }

    .show-images-wrapper {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
    }

    input[type="checkbox"][id^="cb"] {
      display: none;
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

    .table-head-row {
        border: 0 !important;
        margin-bottom: 3px !important;
    }

    .btn-image img{
      width : 12px !important;
    }
    .td-style{
      padding : 0 0 !important;
    }
    .table-style {
      margin-bottom : 0px !important;
    }
    .tr-style{
      background-color: transparent !important;
    } 
    .th-remark{
      width:20%; padding-top:14px !important;
    }

    .th-created_at{
      padding:0px 10px !important;
      /* width:18%; padding-top:14px !important; */
    }

</style>
@endsection

@section('content')

<div class="row">
  <div class="col-lg-12 margin-tb">
    <div class="pull-left d-flex">
      <h3>{{ $task->is_statutory == 3 ? 'Discussion' : ($task->is_statutory == 1 ? 'Statutory' : '') }} Task Page</h3>

      @if ($task->users->contains(Auth::id()) || $task->assign_from == Auth::id())
        @if ($task->is_completed == '')
          <button type="button" class="btn btn-image task-complete mt-3" data-id="{{ $task->id }}"><img src="/images/incomplete.png" /></button>
        @else
          @if ($task->assign_from == Auth::id())
            <button type="button" class="btn btn-image task-complete mt-3" data-id="{{ $task->id }}"><img src="/images/completed-green.png" /></button>
          @else
            <button type="button" class="btn btn-image mt-3"><img src="/images/completed-green.png" /></button>
          @endif
        @endif
      @endif

      @if ($task->is_watched == 1)
        <button type="button" class="btn btn-image make-watched-task mt-3" data-taskid="{{ $task->id }}"><img src="/images/starred.png" /></button>
      @else
        <button type="button" class="btn btn-image make-watched-task mt-3" data-taskid="{{ $task->id }}"><img src="/images/unstarred.png" /></button>
      @endif

      @if ($task->is_flagged == 1)
        <button type="button" class="btn btn-image flag-task mt-3" data-id="{{ $task->id }}"><img src="/images/flagged.png" /></button>
      @else
        <button type="button" class="btn btn-image flag-task mt-3" data-id="{{ $task->id }}"><img src="/images/unflagged.png" /></button>
      @endif
     @if ($task->task_subject)<h4 style="margin-top: 23px !important;">Subject : {{ $task->task_subject }}</h4>@endif
      <span type="text" name="subject" id="task_subject_field" class="form-control span-sm hidden" value="{{ $task->task_subject }}">
      <a href="#" id="edit_subject_button" class="btn btn-secondary btn-xs">Edit</a>
      </span>
      
      
    </div>
    <div class="pull-right mt-4">
		@if (count($hiddenRemarks))
		  <button type="button" class="btn btn-default" data-toggle="modal" data-target="#hidden_remark_modal">Hidden Notes</button>
	  @endif
      {{-- <a class="btn btn-xs btn-secondary" href="{{ route('customer.index') }}">Back</a>
      <a class="btn btn-xs btn-secondary" href="#" id="quick_add_lead">+ Lead</a>
      <a class="btn btn-xs btn-secondary" href="#" id="quick_add_order">+ Order</a>
      <button type="button" class="btn btn-xs btn-secondary" data-toggle="modal" data-target="#privateViewingModal">Set Up for Private Viewing</button> --}}
    </div>
  </div>
</div>

@include('partials.flash_messages')

<p>
{{ $task->task_details ?? 'N/A' }}
  </p>
<div class="row">
	@if($task->is_statutory == 3)
		<div class="col-md-12">
      <form class="form-inline message-search-handler mb-3" method="get" action="{{ route('task.module.show', $task->id) }}">
          <div class="col-md-3 offset-md-9">
              <div class="form-group">
                  <label for="keyword">Keyword:</label>
                  <?php echo Form::text("keyword",request("keyword"),["class"=> "form-control","placeholder" => "Enter keyword"]) ?>
              </div>

              <div class="form-group">
                  <label for="button">&nbsp;</label>
                  <button type="submit" style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-search-action">
                      <img src="/images/search.png" style="cursor: default;">
                  </button>
              </div>
          </div>
      </form>
		  <div class="infinite-scroll">
        <table class="table table-striped table-bordered">
            <tr>
                <th width="25%">Update</th>
                <th width="50%">Remarks</th>
                <th width="25%">Action</th>
            </tr>
            <tr>
                <td>
                    <input type="text" id="create-note-field-for-appointment" class="form-control input-sm" name="note" placeholder="Add New Update..." value="">
                </td>
                <td></td>
                <td></td>
            </tr>
            @foreach ($taskNotes as $key=>$note)
                <tr>
                    <td>
                        {{ $note->remark }}
                        <button type="button" class="btn btn-image create-quick-task-button" data-remark="{{ $note->remark }}" data-id="{{ $note->id }}" style="padding-top: 0px !important;"><img src="/images/add.png" style="width: 12px !important;" /></button>
                          <div style="display:none;" id="div{{ $note->id }}">
                          <select class="form-control selectpicker user-list" data-live-search="true" style="display:none !important;" data-remark="{{ $note->remark }}" data-remark-id="{{ $note->id }}">
                            @foreach($users as $user)
                              <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                          </select>
                        </div>
                    </td>
                    
                      @if(isset($note->singleSubnotes)) 
                      <input type="hidden"  id="remark-text{{ $note->id }}" value="{{ $note->singleSubnotes->remark }}">
                      <input type="hidden"  id="remark-id{{ $note->id }}" value="{{ $note->singleSubnotes->id }}">
                      
                      <td class="td-style">
                        <div class="col-md-12">
                            <div class="col-md-3">
                              <table class="table table-style">  
                                  <tbody> 
                                    <tr class="tr-style"> 
                                      <th class="table-head-row expand-row table-hover-cell" id="remark{{$note->id}}">
                                        <span class="td-mini-container">
                                            {{ strlen( $note->singleSubnotes->remark  ) > 10 ? substr( $note->singleSubnotes->remark  , 0, 10).'...' :  $note->singleSubnotes->remark  }}
                                        </span>
                                        <span class="td-full-container hidden">
                                        {{ $note->singleSubnotes->remark }} 
                                        </span>
                                      </th>
                                  </tr>
                                </tbody> 
                              </table>
                            </div>
                            <div class="col-md-9">
                              <div class="row" style="margin-bottom:0px;margin-left:0px;margin-right:0px;">
                                <div class="col-md-8" style="padding:5px;">
                                    <span class="table-head-row">   <input type="text" class="form-control input-sm create-subnote-for-appointment" data-id="{{ $note->id }}" name="note" placeholder="Note" value=""> </span>
                                </div>
                                  <div class="col-md-4" style="padding:5px;">
                                  <span style="vertical-align: middle !important;" class="table-head-row th-add-user th-created_at" id="created{{$note->id}}"> {{ $note->singleSubnotes->created_at->format('d-m-Y H:i:s') }}   </span> <input type="hidden" id="current-remark-id">
                                  </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                    <button type="button" class="btn btn-image create-quick-task-note-button" onclick="createTaskNoteButton({{  $note->id }})" title="Add Task Note"><img src="/images/add.png" /></button>
                                  
                                  <div style="display:none;" id="divremark{{ $note->id }}">
                                      <select class="form-control selectpicker" data-live-search="true" style="display:none !important;" onchange="sendUserTask({{ $note->id }})" id="user-selected{{ $note->id }}">
                                        @foreach($users as $user)
                                          <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                  <button type="button" class="btn btn-image" data-toggle="modal" data-target="#chat-list-history{{ $note->id }}" title="Chat History"><img src="/images/chat.png" /></button>
                                  <button type="button" class="btn btn-image" onclick="archiveRemark({{ $note->singleSubnotes->id }} , {{ $note->id }})" title="Archive Remark"><img src="/images/archive.png" /></button>
                                  
                                  <button type="button" class="btn btn-image" data-toggle="modal" data-target="#archive-list-history{{ $note->id }}" title="Archive Remark History"><img src="/images/advance-link.png" /></button>
                                  <button type="button" class="btn remove-task-note" data-task-note-id="{{ $note->id }}" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                  <button type="button" class="btn hide-task-note" data-task-note-id="{{ $note->id }}" title="Hide"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>  
                                  @if ($note->is_flagged == 1)
                                      <button type="button" class="btn btn-image flag-task pd-5" data-id="{{ $note->id }}"><img src="{{asset('images/flagged.png')}}"/></button>
                                  @else
                                      <button type="button" class="btn btn-image flag-task pd-5" data-id="{{ $note->id }}"><img src="{{asset('images/unflagged.png')}}"/></button>
                                  @endif
                    </td>
                      @else 
                      <input type="hidden"  id="remark-text{{ $note->id }}">
                      <input type="hidden"  id="remark-id{{ $note->id }}">
                      
                      <td class="td-style">

                        <table class="table table-style">  
                          
                          <tbody> 

                            <tr class="tr-style"> 


                               
                               
                          
                          </tr>
                        </tbody> 
                      </table>

                      <div class="row" style="margin-bottom:0px;margin-left:0px;margin-right:0px;">
                      <div class="col-md-8" style="padding:5px;">

                          <span class="table-head-row">   <input type="text" class="form-control input-sm create-subnote-for-appointment" data-id="{{ $note->id }}" name="note" placeholder="Note" value=""></span>

                      </div>
                        <div class="col-md-4" style="padding:5px;">
                        <span style="vertical-align: middle !important;" class="table-head-row th-add-user th-created_at" id="created{{$note->id}}">   </span> <input type="hidden" id="current-remark-id">
                        </div>
                      </div>




                    </td> 
                    <td>
                    
                    <button type="button" class="btn btn-image create-quick-task-note-button" onclick="createTaskNoteButton({{  $note->id }})" title="Add Task Note"><img src="/images/add.png" /></button>
                                
                                 <div style="display:none;" id="divremark{{ $note->id }}">
                                    <select class="form-control selectpicker" data-live-search="true" style="display:none !important;" onchange="sendUserTask({{ $note->id }})" id="user-selected{{ $note->id }}">
                                      @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                 <button type="button" class="btn btn-image" data-toggle="modal" data-target="#chat-list-history{{ $note->id }}" title="Chat History"><img src="/images/chat.png" /></button>
                                 <button type="button" class="btn btn-image" onclick="archiveRemarkRefresh()"><img src="/images/archive.png" /></button>
                                
                                 <button type="button" class="btn btn-image" data-toggle="modal" data-target="#archive-list-history{{ $note->id }}" title="Archive Remark History"><img src="/images/advance-link.png" /></button>
								 <button type="button" class="btn remove-task-note" data-task-note-id="{{ $note->id }}" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>
								 <button type="button" class="btn hide-task-note" data-task-note-id="{{ $note->id }}" title="Hide"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                    
                    </td>
                   @endif
                    
                  </tr>
                  @include('task-module.partials.modal-remark')
                  @include('task-module.partials.modal-archieve')
            @endforeach
		</table>
		{!! $taskNotes->appends(Request::except('page'))->links() !!}
		</div>
		</div>
    @else
    <!-- http://erp.luxury.local/chat-messages/task/10666/loadMoreMessages?limit=1000&load_attached=1 -->
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Update</th>
              <th>Remarks</th>
              <th>Action</th>
            </tr>
            <tr>
              <th><input type="text" class="form-control input-sm create-subnote-for-appointment" data-id="" name="note" placeholder="Note" value=""></th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($chatMessages as $chatMessage)
              <tr>
                <td data-message="{{ $chatMessage->id }}">@php echo substr($chatMessage->message, 0, 70); @endphp</td>
                <td></td>
                <td><a title="Dialog" href="javascript:;" class="btn btn-xs btn-secondary ml-1 create-dialog"><i class="fa fa-plus" aria-hidden="true"></i></a> <button type="button" class="btn btn-xs btn-image load-communication-modal" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}" id="message-wrappers" data-message="{{$chatMessage->message}}" data-object="task" data-attached="1" data-object="task" data-id="{{ $chatMessage->task_id}}" title="Load messages"><img src="https://erp.theluxuryunlimited.com/images/chat.png" alt="" style="cursor: default;"></button> <button type="button" class="btn preview-img-btn pd-5" data-id="{{ $chatMessage->id}}"><i class="fa fa-list" aria-hidden="true"></i></button> <i class="fa fa-mail" aria-hidden="true"></i> <a title="Remove" href="javascript:;" class="btn btn-xs btn-secondary ml-1 delete-message remove-task-note" data-id="{{ $chatMessage->id}}"><i class="fa fa-trash" aria-hidden="true"></i></a> <i class="fa fa-eye" aria-hidden="true"></i></td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <!-- <div class="col-xs-12 col-md-4 py-3 border">
            <div class="row text-muted">
                <div class="col-6">
                    <div class="form-group">
                        {{ Carbon\Carbon::parse($task->created_at)->format('d-m H:i') }}
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        {{-- <select class="form-control input-sm" id="task_category" name="category">
                          <option value="">Select a Category</option>

                          @foreach ($categories as $id => $category)
                            <option value="{{ $id }}" {{ $id == $task->category ? 'selected' : '' }}>{{ $category }}</option>
                          @endforeach
                        </select> --}}
                        {!! $categories !!}

                        <span class="text-success change_status_message" style="display: none;">Successfully changed category</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('task.update', $task->id) }}" method="POST">
                @csrf
                @method('PUT')

                @if ($task->is_statutory == 1)
                    <div class="form-group">
                        <strong>Recurring:</strong>
                        {{ $task->recurring_type }}
                    </div>

                    <div class="form-group">
                        <div class='input-group date' id='sending-datetime'>
                            <input type='text' class="form-control input-sm" name="sending_time" value="{{ $task->sending_time }}" required />

                            <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
          </span>
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    @if ($task->task_subject)
                        <strong class="task-subject">{{ $task->task_subject }}</strong>
                    @endif

                    <input type="text" name="subject" id="task_subject_field" class="form-control input-sm hidden" value="{{ $task->task_subject }}">

                    <a href="#" id="edit_subject_button" class="btn-link">Edit</a>
                </div>

                <div class="form-group">
                    {{ $task->task_details }}
                </div>

                @if ($task->is_statutory == 3)
                    <div class="form-group">
                        <ul class="list-group">
                            <div id="note-list-container">
                                @foreach ($task->notes as $note)
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="">
                                            {{ $note->remark }}

                                            <ul class="pl-2">
                                                @foreach ($note->subnotes as $subnote)
                                                    <li class="d-flex justify-content-between align-items-center">
                                                        {{ $subnote->remark }}

                                                        <button type="button" class="btn btn-image create-quick-task-button" data-remark="{{ $subnote->remark }}"><img src="/images/add.png" /></button>
                                                    </li>
                                                @endforeach
                                            </ul>

                                            <input type="text" class="form-control input-sm create-subnote" data-id="{{ $note->id }}" name="note" placeholder="Note" value="">
                                        </div>

                                        <button type="button" class="btn btn-image create-quick-task-button" data-remark="{{ $note->remark }}"><img src="/images/add.png" /></button>
                                    </li>
                                @endforeach
                            </div>

                            <li class="list-group-item">
                                <input type="text" id="create-note-field" class="form-control input-sm" name="note" placeholder="Note" value="">
                            </li>
                        </ul>
                    </div>
                @endif



                {{-- <div class="form-group">
                  {{ Carbon\Carbon::parse($task->completion_date)->format('d-m H:i') }}
                </div> --}}

                <div class="form-group">
                    <strong>Assigned from:</strong> {{ array_key_exists($task->assign_from, $users_array) ? $users_array[$task->assign_from] : 'User Does Not Exist' }}
                </div>

                <div class="form-group">
                    <strong>Assigned to:</strong>
                    @foreach ($task->users as $key => $user)
                        @if ($key != 0)
                            ,
                        @endif

                        @if (array_key_exists($user->id, $users_array))
                            @if ($user->id == Auth::id())
                                <a href="{{ route('users.show', $user->id) }}">{{ $users_array[$user->id] }}</a>
                            @else
                                {{ $users_array[$user->id] }}
                            @endif
                        @else
                            User Does Not Exist
                        @endif
                    @endforeach

                    <br>

                    @foreach ($task->contacts as $key => $contact)
                        @if ($key != 0)
                            ,
                        @endif

                        {{ $contact->name }} - {{ $contact->phone }} ({{ ucwords($contact->category) }})
                    @endforeach
                </div>



                <div class="form-group">
                    <strong>Assigned To (users):</strong>
                    <select class="selectpicker form-control input-sm" data-live-search="true" data-size="15" name="assign_to[]" id="first_customer" title="Choose a User" multiple>
                        @foreach ($users as $user)
                            <option data-tokens="{{ $user->id }} {{ $user->name }}" value="{{ $user->id }}" {{ $task->users->contains($user) ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('assign_to'))
                        <div class="alert alert-danger">{{$errors->first('assign_to')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong>Assigned To (contacts):</strong>
                    <select class="selectpicker form-control input-sm" data-live-search="true" data-size="15" name="assign_to_contacts[]" title="Choose a Contact" multiple>
                        @foreach (Auth::user()->contacts as $contact)
                            <option data-tokens="{{ $contact['name'] }} {{ $contact['phone'] }} {{ $contact['category'] }}" value="{{ $contact['id'] }}" {{ $task->contacts->contains($contact) ? "selected" : '' }}>{{ $contact['name'] }} - {{ $contact['phone'] }} ({{ $contact['category'] }})</option>
                        @endforeach
                    </select>

                    @if ($errors->has('assign_to_contacts'))
                        <div class="alert alert-danger">{{$errors->first('assign_to_contacts')}}</div>
                    @endif
                </div>

                <button type="submit" class="btn btn-xs btn-secondary">Update</button>
            </form>
        </div> -->

        <!-- <div class="col-xs-12 col-md-4 mb-3">
            <div class="border">
                <form action="{{ route('whatsapp.send', 'task') }}" method="POST" enctype="multipart/form-data">
                    <div class="d-flex">
                        @csrf
                        <div class="form-group">
                            <div class="upload-btn-wrapper btn-group pr-0 d-flex">
                                <button class="btn btn-image px-1"><img src="/images/upload.png" /></button>
                                <input type="file" name="image" />

                                <button type="submit" class="btn btn-image px-1 send-communication received-customer"><img src="/images/filled-sent.png" /></button>
                            </div>
                        </div>

                        <div class="form-group flex-fill mr-3">
                            <button type="button" id="customerMessageButton" class="btn btn-image"><img src="/images/support.png" /></button>
                            <textarea  class="form-control mb-3 hidden" style="height: 110px;" name="body" placeholder="Received from User"></textarea>
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

                <form action="{{ route('whatsapp.send', 'task') }}" method="POST" enctype="multipart/form-data">
                    <div id="paste-container" style="width: 200px;">

                    </div>

                    <div class="d-flex">
                        @csrf

                        <div class="form-group">
                            <div class=" d-flex flex-column">
                                <div class="">
                                    <div class="upload-btn-wrapper btn-group px-0">
                                        <button class="btn btn-image px-1"><img src="/images/upload.png" /></button>
                                        <input type="file" name="image" />

                                    </div>
                                    <button type="submit" class="btn btn-image px-1 send-communication"><img src="/images/filled-sent.png" /></button>

                                </div>

                                <div class="">
                                    {{-- <a href="{{ route('attachImages', ['customer', $customer->id, 1]) }}" class="btn btn-image px-1"><img src="/images/attach.png" /></a> --}}


                                    {{-- <button type="button" class="btn btn-image px-1" data-toggle="modal" data-target="#suggestionModal"><img src="/images/customer-suggestion.png" /></button> --}}
                                </div>
                            </div>
                        </div>

                        <div class="form-group flex-fill mr-3">
                            <textarea id="message-body" class="form-control mb-3" style="height: 110px;" name="body" placeholder="Send for approval"></textarea>

                            <input type="hidden" name="screenshot_path" value="" id="screenshot_path" />
                            <input type="hidden" name="status" value="1" />

                            <div class="paste-container"></div>


                        </div>

                    </div>

                    {{-- <div class="pb-4 mt-3">
                      <div class="row">
                        <div class="col">
                          <select name="quickCategory" id="quickCategory" class="form-control input-sm mb-3">
                            <option value="">Select Category</option>
                            @foreach($reply_categories as $category)
                              <option value="{{ $category->approval_leads }}">{{ $category->name }}</option>
                            @endforeach
                          </select>

                          <select name="quickComment" id="quickComment" class="form-control input-sm">
                            <option value="">Quick Reply</option>
                          </select>
                        </div>
                        <div class="col">
                          <button type="button" class="btn btn-xs btn-secondary" data-toggle="modal" data-target="#ReplyModal" id="approval_reply">Create Quick Reply</button>
                        </div>
                      </div>
                    </div> --}}

                </form>

            </div>
        </div> -->

        <!-- <div class="col-xs-12 col-md-4">
            <div class="border">
                {{-- <h4>Messages</h4> --}}

                <div class="row">
                    <div class="col-12 my-3 load-communication-modal" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}" id="message-wrapper" data-object="task" data-attached="1" data-id="{{ $task->id }}">
                        <div id="chat-history" >

                        </div>
                    </div>

                    <div class="col-xs-12 text-center hidden">
                        <button type="button" id="load-more-messages" data-nextpage="1" class="btn btn-secondary">Load More</button>
                    </div>
                </div>
            </div>
        </div> -->
    @endif
</div>

@include('task-module.partials.modal-reminder')

<div id="hidden_remark_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
  
	  <!-- Modal content-->
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title">Hidden Notes</h4>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		  <div class="modal-body">
			<div class="row">
			  <div class="col-md-12">
				@forelse ($hiddenRemarks as $item)
					<p>{{ $item->remark }}</p>
				@empty
					
				@endforelse
			  </div>
			</div>
		  </div>
  
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		  </div>
	  </div>
  
	</div>
  </div>

  <div id="message_show" class="modal fade" role="dialog">
  <div class="modal-dialog">
  
    <!-- Modal content-->
    <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Message</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">
          <p class="displayMessage"></p>
        </div>
      </div>
      </div>
  
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  
  </div>
  </div>

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.7/jquery.jscroll.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>	

  <script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click','.load-communication-modal', function(){
          var $html = $(this).attr('data-message');
          $('.displayMessage').html($html);
          $('#message_show').modal('show');
        });

        $(document).on('click','.delete-message', function(){
          var $id = $(this).attr('data-id');
          
        });

        $(document).on('click', '.flag-task', function () {
            var remark_id = $(this).data('id');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ route('remark.flag') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    remark_id: remark_id
                },
                beforeSend: function () {
                    $(thiss).text('Flagging...');
                }
            }).done(function (response) {
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
            }).fail(function (response) {
                $(thiss).html('<img src="/images/unflagged.png" />');

                alert('Could not flag task!');

                console.log(response);
            });
        });
    });

    $(function() {
      $('.selectpicker').selectpicker();
    });

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

    $('#date, #report-completion-datetime, #reminder-datetime, #sending-datetime').datetimepicker({
      format: 'YYYY-MM-DD HH:mm'
    });


        $(document).on('click', '.add-product-button', function() {
          $('input[name="order_id"]').val($(this).data('orderid'));
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
        var suggestion_container = $("div#suggestion-container");
        // var sendBtn = $("#waMessageSend");
        var taskId = "{{ $task->id }}";
             var addElapse = false;
             function errorHandler(error) {
                 console.error("error occured: " , error);
             }
             function approveMessage(element, message) {
               if (!$(element).attr('disabled')) {
                 $.ajax({
                   type: "POST",
                   url: "/whatsapp/approve/task",
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
        
         $(document).on('click', '.send-communication', function(e) {
           e.preventDefault();

           var thiss = $(this);
           var url = $(this).closest('form').attr('action');
           var token = "{{ csrf_token() }}";
           var file = $($(this).closest('form').find('input[type="file"]'))[0].files[0];
           var status = $(this).closest('form').find('input[name="status"]').val();
           var screenshot_path = $('#screenshot_path').val();
           var task_id = {{ $task->id }};
           var formData = new FormData();

           formData.append("_token", token);
           formData.append("image", file);
           formData.append("message", $(this).closest('form').find('textarea').val());
           // formData.append("moduletype", $(this).closest('form').find('input[name="moduletype"]').val());
           formData.append("task_id", task_id);
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
              moduletype: "task",
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

      $('#customerMessageButton').on('click', function() {
        $(this).siblings('textarea').removeClass('hidden');
        $(this).addClass('hidden');
      });

      $('#updateCustomerButton').on('click', function() {
        var id = {{ $task->id }};
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

      $('#showActionsButton').on('click', function() {
        $('#actions-container').toggleClass('hidden');
      });

      $(document).on('click', '.show-images-button', function() {
        $(this).siblings('.show-images-wrapper').toggleClass('hidden');
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
        }).done(function(response) {
          $(thiss).text('Resend (' + response.resent + ")");
        }).fail(function(response) {
          $(thiss).text('Resend');

          console.log(response);

          alert('Could not resend message');
        });
      });

      $(document).on('click', '.reminder-message', function() {
        var id = $(this).data('id');

        $('#reminderMessageModal').find('input[name="message_id"]').val(id);
      });

      $(document).on('click', '.make-private-task', function() {
        var task_id = $(this).data('taskid');
        var thiss = $(this);

        $.ajax({
          type: "POST",
          url: "{{ url('task') }}/" + task_id + "/makePrivate",
          data: {
            _token: "{{ csrf_token() }}",
          },
          beforeSend: function() {
            $(thiss).text('Changing...');
          }
        }).done(function(response) {
          if (response.task.is_private == 1) {
            $(thiss).html('<img src="/images/private.png" />');
          } else {
            $(thiss).html('<img src="/images/not-private.png" />');
          }
        }).fail(function(response) {
          $(thiss).html('<img src="/images/not-private.png" />');

          console.log(response);

          alert('Could not make task private');
        });
      });

      $(document).on('click', '.make-watched-task', function() {
        var task_id = $(this).data('taskid');
        var thiss = $(this);

        $.ajax({
          type: "POST",
          url: "{{ url('task') }}/" + task_id + "/isWatched",
          data: {
            _token: "{{ csrf_token() }}",
          },
          beforeSend: function() {
            $(thiss).text('Changing...');
          }
        }).done(function(response) {
          if (response.task.is_watched == 1) {
            $(thiss).html('<img src="/images/starred.png" />');
          } else {
            $(thiss).html('<img src="/images/unstarred.png" />');
          }
        }).fail(function(response) {
          $(thiss).html('<img src="/images/unstarred.png" />');

          console.log(response);

          alert('Could not make task watched');
        });
      });

      var timer = 0;
      var delay = 200;
      var prevent = false;

      $(document).on('click', '.task-complete', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var thiss = $(this);

        timer = setTimeout(function () {
          if (!prevent) {
            var task_id = $(thiss).data('id');
            var image = $(thiss).html();
            var url = "/task/complete/" + task_id;
            var current_user = {{ Auth::id() }};

            if (!$(thiss).is(':disabled')) {
              $.ajax({
                type: "GET",
                url: url,
                data: {
                  type: 'complete'
                },
                beforeSend: function () {
                  $(thiss).text('Completing...');
                }
              }).done(function(response) {
                if (response.task.is_verified != null) {
                  $(thiss).html('<img src="/images/completed.png" />');
                } else if (response.task.is_completed != null) {
                  $(thiss).html('<img src="/images/completed-green.png" />');
                } else {
                  $(thiss).html('<img src="/images/incomplete.png" />');
                }

                if (response.task.assign_from != current_user) {
                  $(thiss).attr('disabled', true);
                }
              }).fail(function(response) {
                $(thiss).html(image);

                alert('Could not mark as completed!');

                console.log(response);
              });
            }
          }

          prevent = false;
        }, delay);
      });

      $(document).on('dblclick', '.task-complete', function(e) {
        e.preventDefault();
        e.stopPropagation();

        clearTimeout(timer);
        prevent = true;

        var thiss = $(this);
        var task_id = $(this).data('id');
        var image = $(this).html();
        var url = "/task/complete/" + task_id;

        $.ajax({
          type: "GET",
          url: url,
          data: {
            type: 'clear'
          },
          beforeSend: function () {
            $(thiss).text('Clearing...');
          }
        }).done(function(response) {
          if (response.task.is_verified != null) {
            $(thiss).html('<img src="/images/completed.png" />');
          } else if (response.task.is_completed != null) {
            $(thiss).html('<img src="/images/completed-green.png" />');
          } else {
            $(thiss).html('<img src="/images/incomplete.png" />');
          }
        }).fail(function(response) {
          $(thiss).html(image);

          alert('Could not clear the task!');

          console.log(response);
        });
      });

      $(document).on('click', '.create-quick-task-button', function() {
        var remark = $(this).data('remark');
        var id = $(this).data('id');
        $('#div'+id).toggle();
        });

      function createTaskNoteButton(id){
        //Toggle User Table
        $('#divremark'+id).toggle();
      }

      $('#create-note-field').keypress(function(e) {
        var key = e.which;
        var thiss = $(this);

        if (key == 13) {
          e.preventDefault();
          var note = $(thiss).val();
          var id = "{{ $task->id }}";

          if (note != '') {
            $.ajax({
              type: 'POST',
              url: "{{ url('task') }}/" + id + '/addNote',
              data: {
                _token: "{{ csrf_token() }}",
                note: note,
              }
            }).done(function() {
              $(thiss).val('');
              var note_html = `<li class="list-group-item d-flex justify-content-between align-items-center">
                                ` + note + `
                                <button type="button" class="btn btn-image create-quick-task-button" data-remark="` + note + `"><img src="/images/add.png" /></button>
                              </li>`;

              $('#note-list-container').append(note_html);
            }).fail(function(response) {
              console.log(response);

              alert('Could not add note');
            });
          } else {
            alert('Please enter note first!')
          }
        }
      });

  $('#create-note-field-for-appointment').keypress(function(e) {
      var key = e.which;
      var thiss = $(this);

      if (key == 13) {
          e.preventDefault();
          var note = $(thiss).val();
          var id = "{{ $task->id }}";

          if (note != '') {
              $.ajax({
                  type: 'POST',
                  url: "{{ url('task') }}/" + id + '/addNote',
                  data: {
                      _token: "{{ csrf_token() }}",
                      note: note,
                  }
              }).done(function() {
                  $(thiss).val('');
                  location.reload();
                  // var note_html = `<li class="list-group-item d-flex justify-content-between align-items-center">
                  //               ` + note + `
                  //               <button type="button" class="btn btn-image create-quick-task-button" data-remark="` + note + `"><img src="/images/add.png" /></button>
                  //             </li>`;

                  // $('#note-list-container').append(note_html);
              }).fail(function(response) {
                  console.log(response);

                  alert('Could not add note');
              });
          } else {
              alert('Please enter note first!')
          }
      }
  });

      $(document).on('keypress', '.create-subnote', function(e) {
        var key = e.which;
        var thiss = $(this);
        var id = $(this).data('id');

        if (key == 13) {
          e.preventDefault();
          var note = $(thiss).val();

          if (note != '') {
            $.ajax({
              type: 'POST',
              url: "{{ url('task') }}/" + id + '/addSubnote',
              data: {
                _token: "{{ csrf_token() }}",
                note: note,
              }
            }).done(function() {
              $(thiss).val('');
              var note_html = `<li class="d-flex justify-content-between align-items-center">` + note + `<button type="button" class="btn btn-image create-quick-task-button" data-remark="` + note + `"><img src="/images/add.png" /></button></li>`;

                $(thiss).siblings('ul').append(note_html);
            }).fail(function(response) {
              console.log(response);

              alert('Could not add note');
            });
          } else {
            alert('Please enter note first!')
          }
        }
      });

  $(document).on('keypress', '.create-subnote-for-appointment', function(e) {
      var key = e.which;
      var thiss = $(this);
      var id = $(this).data('id');
      if (key == 13) {
          e.preventDefault();
          var note = $(thiss).val();

          if (note != '') {
              $.ajax({
                  type: 'POST',
                  url: "{{ url('task') }}/" + id + '/addSubnote',
                  data: {
                      _token: "{{ csrf_token() }}",
                      note: note,
                  }
              }).done(function(response) {
                   $(thiss).val(''); 
                   $('#remark'+id).text(note);
                   $('#created'+id).text("{{ now()->format('d-m-Y H:i:s') }}");
                   $('#add-user'+id).attr('data-remark',note);
                   $('#add-user'+id).attr('data-id',response.success);
                   $('#remark-text'+id).val(note);
                   $('#remark-id'+id).val(response.success);
                   $('#note-remark-details'+id).append("<div class='bubble alt'> <div class='txt'><p class='name alt'></p><p class='message'>"+note+"</p> </div></div>");
              
              }).fail(function(response) {
                  console.log(response);

                  alert('Could not add note');
              });
          } else {
              alert('Please enter note first!')
          }
      }
  });

      $('#task_category').on('change', function() {
        var category = $(this).val();
        var id = "{{ $task->id }}";
        var thiss = $(this);

        $.ajax({
          type: "POST",
          url: "{{ url('task') }}/" + id + '/updateCategory',
          data: {
            _token: "{{ csrf_token() }}",
            category: category
          }
        }).done(function() {
          $(thiss).siblings('.change_status_message').fadeIn(400);

          setTimeout(function () {
            $(thiss).siblings('.change_status_message').fadeOut(400);
          }, 2000);
        }).fail(function(response) {
          alert('Could not change the category');
          console.log(response);
        });
      });

      $('#edit_subject_button').on('click', function(e) {
        e.preventDefault();

        $(this).siblings('input').removeClass('hidden');
        $(this).siblings('.task-subject').addClass('hidden');
      });

      $(document).on('keypress', '#task_subject_field', function(e) {
        var key = e.which;
        var thiss = $(this);
        var id = "{{ $task->id }}";

        if (key == 13) {
          e.preventDefault();
          var subject = $(thiss).val();

          if (subject != '') {
            $.ajax({
              type: 'POST',
              url: "{{ url('task') }}/" + id + '/updateSubject',
              data: {
                _token: "{{ csrf_token() }}",
                subject: subject,
              }
            }).done(function() {
              $(thiss).addClass('hidden');
              $(thiss).siblings('.task-subject').text(subject);
              $(thiss).siblings('.task-subject').removeClass('hidden');
            }).fail(function(response) {
              console.log(response);

              alert('Could not change the subject');
            });
          } else {
            alert('Please enter subject first!')
          }
        }
      });

      $(document).on('click', '.flag-task', function() {
        var task_id = $(this).data('id');
        var thiss = $(this);

        $.ajax({
          type: "POST",
          url: "{{ route('task.flag') }}",
          data: {
            _token: "{{ csrf_token() }}",
            task_id: task_id
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

          alert('Could not flag task!');

          console.log(response);
        });
      });

      $('.user-list').on('change', function() {
        var id = $(this).data('id');
        var remark_id = $(this).data('remark-id');
        $('#div'+remark_id).hide();
        var remark = $(this).data('remark');
        var ids = $(this).val();
        sendTo = [];
        sendTo.push(ids);
        if (!$(this).is(':disabled')) {
          $.ajax({
            type: "POST",
            url: "{{ route('task.store') }}",
            data: {
              _token: "{{ csrf_token() }}",
              task_subject: 'Appointment Task',
              task_details: remark,
              assign_to: sendTo,
        		},
            beforeSend: function () {
              $('#div'+remark_id).hide();
          }
          }).done(function() {
              
          }).fail(function(response) {
              alert('Could not create task!');
          });
        }
      });

      function sendUserTask(id){
        remark_id = $('#remark-id'+id).val();
        remark = $('#remark-text'+id).val();
        seleted_user = $('#user-selected'+id).val();
        sendTo = [];
        sendTo.push(seleted_user);
        $.ajax({
            type: "POST",
            url: "{{ route('task.store') }}",
            data: {
              _token: "{{ csrf_token() }}",
              task_subject: 'Appointment Task',
              task_details: remark,
              assign_to: sendTo,
            },
            beforeSend: function () {
              $('#divremark'+id).hide();
          }
          }).done(function() {
              
          }).fail(function(response) {
              alert('Could not create task!');
          });
        
      }

      function archiveRemark(id,noteId) {
        
        $.ajax({
            type: "POST",
            url: "{{url('/')}}/task-remark/"+id+"/delete",
            
            data: {
              _token: "{{ csrf_token() }}",
              id : id,
        		},
            beforeSend: function () {
              
          }
          }).done(function(response) {
              //location.reload();
              $('#archive-remark-details'+noteId).append("<div class='bubble alt'> <div class='txt'><p class='name alt'></p><p class='message'>"+response.success+"</p> </div></div>");
          }).fail(function(response) {
              alert('Could not create task!');
          });
      }

      //Expand Row
         $(document).on('click', '.expand-row', function () {
            var selection = window.getSelection();
            if (selection.toString().length === 0) {
                $(this).find('.td-mini-container').toggleClass('hidden');
                $(this).find('.td-full-container').toggleClass('hidden');
            }
        });

         function archiveRemarkRefresh() {
           location.reload();
         }

	$('ul.pagination').hide();
	$('.infinite-scroll').jscroll({
        autoTrigger: true,
		// debug: true,
        loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
        padding: 20,
        nextSelector: '.pagination li.active + li a',
        contentSelector: 'div.infinite-scroll',
        callback: function () {
            $('ul.pagination').first().remove();
			      $('ul.pagination').hide();
        }
    });

	$(document).on('click', '.remove-task-note', function() {
    var $this = $(this);
    var noteId = $(this).data('task-note-id');
		swal({
			title: "Are you sure?",
			text: "You will not be able to recover this!",
			icon: "warning",
			buttons: [
				'No',
				'Yes'
			],
			dangerMode: true,
			}).then(function(isConfirm) {
			if (isConfirm) {
				$.ajax({
					url: "{{ route('delete/task/note') }}",
					type: 'GET',
					data: {note_id: noteId},
					success: function() {
            $this.closest("tr").remove();
						//location.reload();
            toastr['success']('data updated successfully!');
					}
				})
				.fail(function(response) {
					alert('Could not delete task note');
				});
			}
		});
	});

	$(document).on('click', '.hide-task-note', function() {
    var noteId = $(this).data('task-note-id');
    var $this = $(this);
		swal({
			title: "Are you sure?",
			// text: "You will not be able to recover this imaginary file!",
			icon: "warning",
			buttons: [
				'No',
				'Yes'
			],
			dangerMode: true,
			}).then(function(isConfirm) {
			if (isConfirm) {
				$.ajax({
					url: "{{ route('hide/task/remark') }}",
					type: 'GET',
					data: {note_id: noteId},
					success: function() {
						$this.closest("tr").remove();
            toastr['success']('data updated successfully!');
					}
				})
				.fail(function(response) {
					alert('Could not hide task note');
				});
			}
		});
	});
  </script>
@endsection
