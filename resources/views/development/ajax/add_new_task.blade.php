<!-- Modal -->
<div id="newTaskModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div style="padding: 10px;border-bottom: 1px solid #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Task</h4>
            </div>
            <form action="{{ route('development.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @if(auth()->user()->checkPermission('development-list'))
                        <div class="form-group">
                            <strong>Assigned To:</strong>
                            <select class="form-control" name="assigned_to" required>
                                @foreach ($users as $key => $obj)
                                    <option value="{{ $key }}" {{ old('assigned_to') == $key ? 'selected' : '' }}>{{ $obj }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('assigned_to'))
                                <div class="alert alert-danger">{{$errors->first('assigned_to')}}</div>
                            @endif
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="repository_id">Repository:</label>
                        <br>
                        <select style="width:100%" class="form-control select2" id="repository_id" name="repository_id">
                            @foreach ($respositories as $repository)
                                <option value="{{ $repository->id }}" {{ $repository->id == $defaultRepositoryId ? 'selected' : '' }}>{{ $repository->name }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('repository_id'))
                            <div class="alert alert-danger">{{$errors->first('repository_id')}}</div>
                        @endif
                    </div>
<!-- 
                    <div class="form-group">
                        <strong>Attach files:</strong>
                        <input type="file" name="images[]" class="form-control" multiple>
                        @if ($errors->has('images'))
                        <div class="alert alert-danger">{{$errors->first('images')}}</div>
                        @endif
                    </div> -->

                    <div class="form-group">
                        <label for="module_id">Module:</label>
                        <br>
                        <select style="width:100%" class="form-control" id="module_id" name="module_id" required>
                            <option value>Select a Module</option>
                            @foreach ($modules as $module)
                            <option value="{{ $module->id }}" {{ $module->id == old('module_id',9) ? 'selected' : '' }}>{{ $module->name }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('module_id'))
                        <div class="alert alert-danger">{{$errors->first('module_id')}}</div>
                        @endif
                    </div>

                    <!-- <div class="form-group">
                        <label for="priority">Priority:</label>
                        <select class="form-control" name="priority" id="priority" required>
                            <option value="3" {{ old('priority') == '3' ? 'selected' : '' }}>Normal</option>
                            <option value="2" {{ old('priority') == '2' ? 'selected' : '' }}>Urgent</option>
                            <option value="1" {{ old('priority') == '1' ? 'selected' : '' }}>Critical</option>
                        </select>

                        @if ($errors->has('priority'))
                        <div class="alert alert-danger">{{$errors->first('priority')}}</div>
                        @endif
                    </div> -->

                    <div class="form-group">
                        <label for="priority">Type:</label>
                        <select class="form-control" name="task_type_id" id="task_type_id" required>
                            @foreach($tasksTypes as $taskType)
                            <option value="{{$taskType->id}}">{{$taskType->name}}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('priority'))
                        <div class="alert alert-danger">{{$errors->first('priority')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Subject:</strong>
                        <input type="text" class="form-control" name="subject" value="{{ old('subject') }}" />
                        </select>

                        @if ($errors->has('subject'))
                        <div class="alert alert-danger">{{$errors->first('subject')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Task:</strong>
                        <textarea class="form-control" name="task" rows="8" cols="80" required>{{ old('task') }}</textarea>
                        </select>

                        @if ($errors->has('task'))
                        <div class="alert alert-danger">{{$errors->first('task')}}</div>
                        @endif
                    </div>

                    <!-- <div class="form-group">
                        <strong>Cost:</strong>
                        <input type="number" class="form-control" name="cost" value="{{ old('cost') }}" />
                        </select>

                        @if ($errors->has('cost'))
                        <div class="alert alert-danger">{{$errors->first('cost')}}</div>
                        @endif
                    </div> -->

                    <div class="form-group">
                        <strong>Status:</strong>
                        <select class="form-control" name="status" required>
                            <!-- <option value="Planned" {{ old('status') == 'Planned' ? 'selected' : '' }}>Planned</option>
                            <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Done" {{ old('status') == 'Done' ? 'selected' : '' }}>Done</option> -->
                            @foreach($statusList  as $key => $status)
                            <option value="{{$key}}" {{ old('status','In Progress') == $status ? 'selected' : '' }}>{{$status}}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('status'))
                        <div class="alert alert-danger">{{$errors->first('status')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Is Milestone ?:</strong>
                        <select id="is_milestone" class="form-control" name="is_milestone" required>
                            <option value="0" {{ old('is_milestone') == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('is_milestone') == 1 ? 'selected' : '' }}>Yes</option>
                        </select>

                        @if ($errors->has('is_milestone'))
                        <div class="alert alert-danger">{{$errors->first('is_milestone')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>No of milestone:</strong>
                        <input type="number" class="form-control" id="no_of_milestone" name="no_of_milestone" value="{{ old('no_of_milestone') }}" />
                        </select>

                        @if ($errors->has('no_of_milestone'))
                        <div class="alert alert-danger">{{$errors->first('no_of_milestone')}}</div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary">Add</button>
                </div>
            </form>
        </div>

    </div>
</div>