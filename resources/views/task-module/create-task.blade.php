<div class="col-xs-12">
            <form action="{{ route('task.store') }}" method="POST" id="taskCreateForm">
                @csrf
                <input type="hidden" name="has_render" value="1">
                <div class="row">
                    <div class="col-xs-12 col-md-4">
                        <div class="form-group">
                            <input type="text" class="form-control input-sm" name="task_subject" placeholder="Task Subject" id="task_subject" value="{{ old('task_subject') }}" required/>
                            @if ($errors->has('task_subject'))
                                <div class="alert alert-danger">{{$errors->first('task_subject')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <textarea rows="1" class="form-control input-sm" name="task_details" placeholder="Task Details" id="task_details" required>{{ old('task_details') }}</textarea>
                            @if ($errors->has('task_details'))
                                <div class="alert alert-danger">{{$errors->first('task_details')}}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-4">
                        <div class="form-group">
                            <select name="is_statutory" class="form-control is_statutory input-sm">
                                <option value="0">Other Task</option>
                                <option value="1">Statutory Task</option>
                                <option value="2">Calendar Task</option>
                                <option value="3">Discussion Task</option>
                            </select>
                        </div>

                        <div id="recurring-task" style="display: none;">
                            <div class="form-group">
                                {{-- <strong>Recurring Type:</strong> --}}
                                <select name="recurring_type" class="form-control input-sm">
                                    <option value="EveryHour">EveryHour</option>
                                    <option value="EveryDay">EveryDay</option>
                                    <option value="EveryWeek">EveryWeek</option>
                                    <option value="EveryMonth">EveryMonth</option>
                                    <option value="EveryYear">EveryYear</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class='input-group date' id='sending-datetime'>
                                    <input type='text' class="form-control input-sm" name="sending_time" value="{{ date('Y-m-d H:i') }}" required/>

                                    <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                                </div>

                                @if ($errors->has('sending_time'))
                                    <div class="alert alert-danger">{{$errors->first('sending_time')}}</div>
                                @endif
                            </div>
                        </div>

                        <div id="calendar-task" style="display: none;">
                            <div class="form-group">
                                <div class='input-group date' id='completion-datetime'>
                                    <input type='text' class="form-control input-sm" name="completion_date" value="{{ date('Y-m-d H:i') }}"/>

                                    <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                                </div>

                                @if ($errors->has('completion_date'))
                                    <div class="alert alert-danger">{{$errors->first('completion_date')}}</div>
                                @endif
                            </div>
                        </div>
                        @if(auth()->user()->isAdmin())
                            <div class="form-group">
                                <select id="multi_users" class="form-control input-sm" name="assign_to[]" multiple>
                                    @foreach ($data['users'] as $user)
                                        <option value="{{ $user['id'] }}">{{ $user['name'] }} - {{ $user['email'] }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('assign_to'))
                                    <div class="alert alert-danger">{{$errors->first('assign_to')}}</div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="col-xs-12 col-md-4">
                        <div class="form-inline mb-3">
                            <div class="form-group flex-fill">
                                <select id="multi_contacts" class="form-control input-sm" name="assign_to_contacts[]" multiple>
                                    @foreach (Auth::user()->contacts as $contact)
                                        <option value="{{ $contact['id'] }}">{{ $contact['name'] }} - {{ $contact['phone'] }} ({{ $contact['category'] }})</option>
                                    @endforeach
                                </select>

                                {{-- <select class="selectpicker form-control input-sm" data-live-search="true" data-size="15" name="assign_to_contacts[]" title="Choose a Contact" multiple>
                                  @foreach (Auth::user()->contacts as $contact)
                                    <option data-tokens="{{ $contact['name'] }} {{ $contact['phone'] }} {{ $contact['category'] }}" value="{{ $contact['id'] }}">{{ $contact['name'] }} - {{ $contact['phone'] }} ({{ $contact['category'] }})</option>
                                  @endforeach
                                </select> --}}

                                @if ($errors->has('assign_to_contacts'))
                                    <div class="alert alert-danger">{{$errors->first('assign_to_contacts')}}</div>
                                @endif
                            </div>

                            <button type="button" class="btn btn-image" data-toggle="modal" data-target="#createQuickContactModal"><img src="/images/add.png"/></button>
                        </div>
                        <div class="form-inline mb-3">
                            <div class="form-group flex-fill">
                                {{-- <strong>Category:</strong> --}}
                                {!! $task_categories_dropdown !!}
                                {{-- <select class="form-control input-sm" name="category" id="required_category" required>
                                  <option value="">Select a Category</option>
                                  @foreach ($task_categories_dropdown as $category)
                                    <option value="{{ $category['id'] }}">{{ $category['title'] }}</option>

                                    @foreach ($category['child'] as $child)
                                      <option value="{{ $child['id'] }}">&nbsp;&nbsp;{{ $child['title'] }}</option>
                                    @endforeach
                                  @endforeach
                                </select> --}}
                            </div>

                            <button type="button" class="btn btn-image" data-toggle="modal" data-target="#createTaskCategorytModal"><img src="/images/add.png"/></button>
                        </div>
                    </div>

                    <div class="col-xs-4" style="display: none;" id="appointment-container">
                        <div class="form-group">
                            <?php echo Form::select("task_id",["0" => "-- Add New --"] + \App\Task::where("is_statutory",3)->where("task_subject","!=","''")->get()->pluck("task_subject","id")->toArray(),null,[
                                "class" => "form-control select2-task-disscussion"
                            ]); ?>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control input-sm" name="note[]" placeholder="Note" value="">
                        </div>
                        <div id="note-container">

                        </div>
                        <button type="button" class="btn btn-xs btn-secondary" id="addNoteButton">Add Note</button>
                        <button type="button" class="btn btn-xs btn-secondary dis-none" id="saveNewNotes">Save New Notes</button>
                    </div>

                    
                    <div class="form-group ml-3">
                        <select id="is_milestone" class="form-control" name="is_milestone" required>
                            <option value="0">Is milestone</option>
                            <option value="0" >No</option>
                            <option value="1" >Yes</option>
                        </select>

                        @if ($errors->has('is_milestone'))
                        <div class="alert alert-danger">{{$errors->first('is_milestone')}}</div>
                        @endif
                    </div>

                    <div class="form-group ml-3">
                        <input type="number" class="form-control" id="no_of_milestone" name="no_of_milestone" value="{{ old('no_of_milestone') }}" placeholder="No of milestone" />
                        </select>

                        @if ($errors->has('no_of_milestone'))
                        <div class="alert alert-danger">{{$errors->first('no_of_milestone')}}</div>
                        @endif
                    </div>

                    <div class="col-xs-12 text-center">
                        <button type="submit" class="btn btn-xs btn-secondary" id="taskCreateButton">Create</button>
                    </div>
                </div>
            </form>
        </div>