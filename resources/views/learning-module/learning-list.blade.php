@php
    $special_learning = App\Learning::find($learning->id);
    // $user = App\User::find($learning->learning_user);
    // $provider = App\User::find($learning->learning_vendor);
    // $module = App\LearningModule::find($learning->learning_module);
    // $submodule = App\LearningModule::find($learning->learning_submodule);
    // $assignment = App\Contact::find($learning->learning_assignment);
    // $status = App\TaskStatus::find($learning->learning_status);
@endphp
<tr class="learning_and_activity" data-id="{{ $learning->id }}">
    <td>{{ $learning->id }}</td>
    <td>{{ $learning->created_at->format('m/d/Y') }}</td>
    <td>
        <select class="form-control updateUser" name="user">
            @foreach(App\User::orderBy('name')->get() as $user)
                <option value="{{ $user->id }}" {{ $learning->learning_user == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <select class="form-control updateProvider" name="provider">
            @foreach(App\User::orderBy('name')->get()  as $provider)
                <option value="{{ $provider->id }}" {{ $learning->learning_vendor == $provider->id ? 'selected' : '' }}>{{ $provider->name }}</option>
            @endforeach
        </select>
    </td>
    <td><div style="display: flex"><input type="text" class="form-control send-message-textbox" name="learning_subject" value="{{ $learning->learning_subject }}"> <img src="/images/filled-sent.png" class="updateSubject"style="cursor: pointer; object-fit: contain; height: auto; width: 16px; margin-left: 4px;"></div></td>
    <td>
        <select class="form-control updateModule" name="module">
            @foreach(App\LearningModule::where('parent_id',0)->get() as $module)
                <option value="{{ $module->id }}" {{ $learning->learning_module == $module->id ? 'selected' : '' }}>{{ $module->title }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <select class="form-control updateSubmodule" name="submodule">
            <option value="">Select</option>
            @foreach(App\LearningModule::where('parent_id',$learning->learning_module)->get() as $submodule)
                <option class="submodule" value="{{ $submodule->id }}" {{ $learning->learning_submodule == $submodule->id ? 'selected' : '' }}>{{ $submodule->title }}</option>
            @endforeach
        </select>
    </td>
    <td><div style="display: flex"><input type="text" class="form-control send-message-textbox" name="learning_assignment" value="{{ $learning->learning_assignment }}" maxlength="15"> <img src="/images/filled-sent.png" class="updateAssignment" style="cursor: pointer; object-fit: contain; height: auto; width: 16px; margin-left: 4px;"></div></td>

    <td>
        <div>
            <input style="min-width: 30px;" placeholder="E.Date" 
                value="{{ $learning->learning_duedate }}" 
                type="text" 
                class="form-control learning-overdue-datetime due-date-update" 
                name="due_date_{{$learning->id}}" 
                data-id="{{$learning->id}}" 
                id="due_date_{{$learning->id}}"
            >
               
            
            <button style="float:right;padding-right:0px;" type="button" class="btn btn-xs show-due-history" title="Show Due Date" data-learningid="{{ $learning->id }}"><i class="fa fa-info-circle"></i></button>
        </div>    
    </td>

    <td>
        <div style="display: flex">
        <select class="form-control updateStatus" name="status">
            @foreach(App\TaskStatus::all() as $status)
                <option value="{{ $status->id }}" {{ $learning->learning_status == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
            @endforeach
        </select>

        <button style="float:right;padding-right:0px;" type="button" class="btn btn-xs show-time-history" title="Show History" data-learningid="{{ $learning->id }}"><i class="fa fa-info-circle"></i></button>
        </div>
    </td>
    <td class="communication-td">
        <!-- class="expand-row" -->
      
       
        <input type="text" class="form-control send-message-textbox" data-id="{{$learning->id}}" id="send_message_{{$learning->id}}" name="send_message_{{$learning->id}}" style="margin-bottom:5px;width:60%;display:inline;"/>
       
        <button style="display: inline-block;padding:0px;" class="btn btn-sm btn-image send-message-open" type="submit" id="submit_message"  data-id="{{$learning->id}}" ><img src="/images/filled-sent.png"/></button>
        <button type="button" class="btn btn-xs btn-image load-communication-modal" data-object='learning' data-id="{{ $learning->id }}" style="mmargin-top: -0%;margin-left: -2%;" title="Load messages"><img src="/images/chat.png" alt=""></button>
        {{-- <span class="{{ ($issue->message && $issue->message_status == 0) || $issue->message_is_reminder == 1 || ($issue->sent_to_user_id == Auth::id() && $issue->message_status == 0) ? '' : '' }} justify-content-between expand-row-msg" style="word-break: break-all;" data-id="{{$learning->id}}">
            <span class="td-mini-container-{{$learning->id}}" style="margin:0px;">
                            {{  \Illuminate\Support\Str::limit($issue->message, 25, $end='...') }}
            </span>
        </span> --}}
        <div class="expand-row-msg" data-id="{{$learning->id}}">
            <span class="td-full-container-{{$learning->id}} hidden">
                {{-- {{ $issue->message }} --}}
                <br>
                <div class="td-full-container">
                    <button class="btn btn-secondary btn-xs" onclick="sendImage({{ $learning->id }})">Send Attachment</button>
                    <button class="btn btn-secondary btn-xs" onclick="sendUploadImage({{$learning->id}})">Send Images</button>
                    <input id="file-input{{ $learning->id }}" type="file" name="files" style="display: none;" multiple/>
                </div> 
            </span>
        </div>
    </td>
    <td>
        {{-- @if ($special_learning->users->contains(Auth::id()) || $learning->assign_from == Auth::id()  || $learning->master_user_id == Auth::id()) --}}
            <button type="button"  data-id="{{ $learning->id }}" class="btn btn-xs btn-file-upload pd-5 p-0">
                <i class="fa fa-upload" aria-hidden="true"></i>
            </button>
        {{-- @endif --}}
        {{-- @if ($special_learning->users->contains(Auth::id()) || ($learning->assign_from == Auth::id() && $learning->is_private == 0) || ($learning->assign_from == Auth::id() && $special_learning->contacts()->count() > 0) || Auth::id() == 6) --}}
            <a href="{{ route('learning.show', $learning->id) }}" class="btn btn-xs btn-image pd-5 p-0" href=""><img src="{{asset('images/view.png')}}"/></a>
        {{-- @endif --}}
    </td>
    {{-- <td><div style="display: flex"><input type="text" class="form-control send-message-textbox"> <img src="/images/filled-sent.png" style="cursor: pointer; object-fit: contain; height: auto; width: 16px; margin-left: 4px;"></div></td> --}}
</tr>