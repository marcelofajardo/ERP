<form action="{{ url("development/list") }}" method="get">
    <div class="row">
       
        <div class="col-md-3 pd-sm">
            <select name="module" id="module_id" class="form-control">
                <option value="">Module</option>
                @foreach($modules as $module)
                    <option {{ $request->get('module') == $module->id ? 'selected' : '' }} value="{{ $module->id }}">{{ $module->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="col-md-3 pd-sm">
            <input type="text" name="subject" id="subject_query" placeholder="Issue Id / Subject" class="form-control" value="{{ (!empty(app('request')->input('subject'))  ? app('request')->input('subject') : '') }}">
        </div>
        <div class="col-md-3 pd-sm status-selection">
            <?php echo Form::select("task_status[]",$statusList,request()->get('task_status', ['In Progress']),["class" => "form-control multiselect","multiple" => true]); ?>
        </div>
        </div>
        <div class="row" style="margin-top:10px;">
        <div class="col-md-3 pd-sm">
            <select name="order" id="order_query" class="form-control">
                <option {{$request->get('order')== "" ? 'selected' : ''}} value="">Latest Communication</option>
                <option {{$request->get('order')== "latest_task_first" ? 'selected' : ''}} value="latest_task_first">Latest Task First</option>
                <option {{$request->get('order')== "priority" ? 'selected' : ''}} value="priority">Sort by priority</option>
            </select>
        </div>
        <div class="col-md-3 pd-sm">
            <select name="tasktype" id="tasktype" class="form-control">
                <option {{$type == "all" ? 'selected' : ''}} value="all">All</option>
                <option {{$type == "devtask" ? 'selected' : ''}} value="devtask">Devtask</option>
                <option {{$type == "issue" ? 'selected' : ''}} value="issue">Issue</option>
            </select>
        </div>
        <div class="col-md-3 pd-sm">
        <div class="form-control">
        <label class="for">Last Communicated &nbsp;&nbsp;
        <?php echo Form::checkbox("last_communicated","on",request()->get('last_communicated', "off") == "on",["class" => ""]); ?>
        </label>
        </div>
        </div>
        <div class="col-md-1 pd-sm">
            {{--
            @if ( isset($_REQUEST['show_resolved']) && $_REQUEST['show_resolved'] == 1 )
                <input type="checkbox" name="show_resolved" value="1" checked> incl.resolved
            @else
                <input type="checkbox" name="show_resolved" value="1"> incl.resolved
            @endif
             --}}
            <button class="btn btn-image">
                <img src="{{ asset('images/search.png') }}" alt="Search">
            </button>
        </div>
       
        <!-- <div class="col-md-1">
            <a class="btn btn-secondary d-inline priority_model_btn">Priority</a>
        </div> -->
    </div>
</form>