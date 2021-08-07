<div class="modal" id="create-dynamic-task" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" action="<?php echo route("chatbot.question.save_dymanic_task"); ?>">
      	 <?php echo csrf_field(); ?>
	      <div class="modal-header">
	        <h5 class="modal-title">Create Intent for quick Task</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
            <div class="form-group">
                <label for="value">Name of the intent</label>
                <?php echo Form::text("value",isset($value) ?: "", ["class" => "form-control" , "placeholder" => "Enter your value"]); ?>
            </div>
            <div class="form-group">
                <label for="value">User intent</label>
                <?php echo Form::text("question",null, ["class" => "form-control" , "placeholder" => "Enter your value"]); ?>
            </div>
            <div class="form-group">
                <label for="value">Intent Category</label>
                <select name="category_id" id="" class="form-control">
                    <option value="">Select</option>
                    @foreach($allCategoryList as $cat)
                    <option value="{{$cat['id']}}">{{$cat['text']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="value">Task Category</label>
                <select name="task_category_id" id="" class="form-control">
                    <option value="">Select</option>
                    @foreach($task_category as $taskcat)
                    <option value="{{$taskcat->id}}">{{$taskcat->title}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="value">Task Type</label>
                <select name="task_type" id="" class="form-control change-task-type">
                    <option value="task">Task</option>
                    <option value="devtask">Devtask</option>
                </select>
            </div>
            <div class="form-group">
                <label for="value">Assign to</label>
                <select name="assigned_to" id="" class="form-control">
                    <option value="">Select</option>
                    @foreach($userslist as $user)
                    <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="value">Select watson account</label>
                <select name="watson_account" class="form-control" required>
                    <option value="0">All account</option>
                    @foreach($watson_accounts as $acc)
                    	<option value="{{$acc->id}}" >{{ $acc->id }} - {{ $acc->storeWebsite->title }}</option>
                    @endforeach
                </select>
            </div>
            <div id="repo-details">
            <div class="form-group" >
                        <label for="repository_id">Repository:</label>
                        <br>
                        <select style="width:100%" class="form-control select2" id="repository_id" name="repository_id">
                        <option value="">Select</option>
                            @foreach ($respositories as $repository)
                                <option value="{{ $repository->id }}">{{ $repository->name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="module_id">Module:</label>
                        <br>
                        <select style="width:100%" class="form-control" id="module_id" name="module_id" required>
                            <option value>Select a Module</option>
                            @foreach ($modules as $module)
                            <option value="{{ $module->id }}">{{ $module->name }}</option>
                            @endforeach
                        </select>
                    </div>
                  </div>
            <div class="form-group">
                <label for="value">Task Description</label>
                <textarea name="task_description" class="form-control" rows="8" cols="80" required></textarea>
            </div>




                    <div class="form-group">
                        <strong>Reply:</strong>
                        <textarea name="suggested_reply" class="form-control" rows="8" cols="80" required>{{ old('suggested_reply') }}</textarea>
                    </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary form-task-btn">Save</button>
	      </div>
	  </form>
    </div>
  </div>
</div>