<form action="{{ url("development/list") }}" method="get" class="search">
    <div class="row">
        @if(auth()->user()->isReviwerLikeAdmin())
            <div class="col-md-2 pd-sm">
                <select class="form-control" name="assigned_to" id="assigned_to">
                    <option value="">Assigned To</option>
                    @foreach($users as $id=>$user)
                        <option {{$request->get('assigned_to')==$id ? 'selected' : ''}} value="{{$id}}">{{ $user }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        {{--
        <div class="col-md-2 pd-sm">
            <select class="form-control" name="responsible_user" id="responsible_user">
                <option value="">Responsible User...</option>
                @foreach($users as $id=>$user)
                    <option {{$request->get('responsible_user')==$id ? 'selected' : ''}} value="{{$id}}">{{ $user }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 pd-sm">
            <select class="form-control" name="corrected_by" id="corrected_by">
                <option value="">Correction by</option>
                @foreach($users as $id=>$user)
                    <option {{$request->get('corrected_by')==$id ? 'selected' : ''}} value="{{$id}}">{{ $user }}</option>
                @endforeach
            </select>
        </div>
        --}}
        <div class="col-md-2 pd-sm">
            <select name="module" id="module_id" class="form-control">
                <option value="">Module</option>
                @foreach($modules as $module)
                    <option {{ $request->get('module') == $module->id ? 'selected' : '' }} value="{{ $module->id }}">{{ $module->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="col-md-2 pd-sm">
            <input type="text" name="subject" id="subject_query" placeholder="Issue Id / Subject" class="form-control" value="{{ (!empty(app('request')->input('subject'))  ? app('request')->input('subject') : '') }}">
        </div>
        <div class="col-md-2 pd-sm status-selection">
            <?php echo Form::select("task_status[]",$statusList,request()->get('task_status', []),["class" => "form-control multiselect","multiple" => true]); ?>
        </div>
        <div class="col-md-2 pd-sm">
            <select name="order" id="order_query" class="form-control">
                <option {{$request->get('order')== "" ? 'selected' : ''}} value="">Latest Communication</option>
                <option {{$request->get('order')== "latest_task_first" ? 'selected' : ''}} value="latest_task_first">Latest Task First</option>
                <option {{$request->get('order')== "priority" ? 'selected' : ''}} value="priority">Sort by priority</option>
            </select>
        </div>
        <div class="col-md-2 pd-sm">
            <select name="tasktype" id="tasktype" class="form-control">
                <option {{$type == "all" ? 'selected' : ''}} value="all">All</option>
                <option {{$type == "devtask" ? 'selected' : ''}} value="devtask">Devtask</option>
                <option {{$type == "issue" ? 'selected' : ''}} value="issue">Issue</option>
            </select>
        </div>
        </div>
        <div class="row" style="margin-top:10px;">
        <div class="col-md-2 pd-sm">
        <div class="form-control">
        <label class="for">Last Communicated &nbsp;&nbsp;
        <?php echo Form::checkbox("last_communicated","on",request()->get('last_communicated', "off") == "on",["class" => ""]); ?>
        </label>
        </div>
        </div>
        @if(auth()->user()->isReviwerLikeAdmin())
            <div class="col-md-2 pd-sm">
                <select class="form-control" name="master_user_id" id="master_user_id">
                    <option value="">Lead Developer</option>
                    @foreach($users as $id=>$user)
                        <option {{$request->get('master_user_id')==$id ? 'selected' : ''}} value="{{$id}}">{{ $user }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        @if(auth()->user()->isReviwerLikeAdmin())
            <div class="col-md-2 pd-sm">
                <select class="form-control" name="team_lead_id" id="team_lead_id">
                    <option value="">Team lead</option>
                    @foreach($users as $id=>$user)
                        <option {{$request->get('team_lead_id')==$id ? 'selected' : ''}} value="{{$id}}">{{ $user }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        @if(auth()->user()->isReviwerLikeAdmin())
            <div class="col-md-2 pd-sm">
                <select class="form-control" name="tester_id" id="tester_id">
                    <option value="">Tester</option>
                    @foreach($users as $id=>$user)
                        <option {{$request->get('tester_id')==$id ? 'selected' : ''}} value="{{$id}}">{{ $user }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="col-md-2 pd-sm">
            <input placeholder="E. Date" type="text" class="form-control estimate-date_picker" id="estimate_date_picker" name="estimate_date" >
        </div>

        <div class="col-md-2 pd-sm">
            <select class="form-control" name="is_estimated" id="is_estimated">
                <option {{$request->get('is_estimated')=='' ? 'selected' : ''}} value="">All</option>
                <option {{$request->get('is_estimated')=='null' ? 'selected' : ''}} value="null">Not Estimated Yet</option>
                <option {{$request->get('is_estimated')=='not_approved' ? 'selected' : ''}} value="not_approved">Not Approved By Admin</option>
            </select>
        </div>

        <div class="col-md-2 pd-sm">
            <select class="form-control" name="repo_id" id="repo_id">
                <option value="">Select repository</option>
                @foreach ($respositories as $repository)
                    <option value="{{ $repository->id }}" {{ $repository->id == request()->get('repo_id') ? 'selected' : '' }}>{{ $repository->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-1 pd-sm">
            {{--
            @if ( isset($_REQUEST['show_resolved']) && $_REQUEST['show_resolved'] == 1 )
                <input type="checkbox" name="show_resolved" value="1" checked> incl.resolved
            @else
                <input type="checkbox" name="show_resolved" value="1"> incl.resolved
            @endif
             --}}
             <button type="submit" class="btn btn-image search" onclick="document.getElementById('download').value = 1;">
                <img src="{{ asset('images/search.png') }}" alt="Search">
            </button>
            <input type="hidden" id="download" name="download" value="1">
        </div>
       
        <!-- <div class="col-md-1">
            <a class="btn btn-secondary d-inline priority_model_btn">Priority</a>
        </div> -->
    </div>
</form>