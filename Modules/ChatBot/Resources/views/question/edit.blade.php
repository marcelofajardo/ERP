@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'Intent | Chatbot')

@section('content')
<style type="text/css">
	.border-div {
		border-style: dashed;
	    margin: 2px;
	    text-align: center;
	    float: left;
	    border-width: 3px;
	    cursor: pointer;
	}

	.dashed-question.selected {
		border-color: coral
	}
</style>
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css">
<div class="row">
	<div class="col-lg-12 margin-tb">
		<h2 class="page-heading">Edit {{ $chatbotQuestion->value }} ({{$chatbotQuestion->erp_or_watson}}) | Chatbot</h2>
	</div>
</div>
<div class="tab-pane">
		<div class="row">
			<div class="col-lg-12 margin-tb">
			@if ($errors->any())
			    <div class="alert alert-danger">
			        <ul>
			            @foreach ($errors->all() as $error)
			                <li>{{ $error }}</li>
			            @endforeach
			        </ul>
			    </div>
			@endif
		</div>
	    <div class="col-lg-12 margin-tb">
			<button class="btn btn-xs btn-primary" style="float:right;margin-right:10px;margin-top:10px;">
				<a href="/chatbot/question" style="color:white;">Back</a>
			</button>
	    	<div class="well">
	    		<form action="{{ route('chatbot.question.update',[$chatbotQuestion->id]) }}" method="post">
    				  <?php echo csrf_field(); ?>
					  @if($chatbotQuestion->keyword_or_question == 'intent')
					  <div class="form-row">
					    <div class="form-group col-md-3">
					      <label for="value">Intent</label>
					      <?php echo Form::text("value", $chatbotQuestion->value, ["class" => "form-control", "id" => "value", "placeholder" => "Enter your value"]); ?>
					      <small style="font-size:10px;" id="emailHelp" class="form-text text-muted">Name your intent to match the category of values that it will detect.</small>
					    </div>
					    <div class="form-group col-md-3">
					      <label for="question">Category</label>
					      <!-- <?php echo Form::select("category_id",$allCategoryList, null, ["class" => "form-control select-chatbot-category", "id" => "chatbot-category-id"]); ?> -->
						  <select name="category_id" id="" class="form-control question-category" data-id="{{$chatbotQuestion->id}}">
						  <option value="">Select</option>
						  @foreach($allCategoryList as $cat)
						  	<option {{$cat['id'] == $chatbotQuestion->category_id ? 'selected' : ''}} value="{{$cat['id']}}">{{$cat['text']}}</option>
						  @endforeach
						</select>
					    </div>
					    <div class="form-group col-md-3">
					      <label for="question">User Intent</label>
					      <?php echo Form::text("question", null, ["data-type" => "intent","class" => "form-control user-intent-exist-search", "id" => "question", "placeholder" => "Enter your question"]); ?>
					    </div>
					    <div class="form-group col-md-3">
					      <label for="value">Auto Approve</label>
					      <select name="auto_approve" id="" class="form-control">
							<option value="0" {{$chatbotQuestion->auto_approve == 0 ? 'selected' : ''}}>No</option>
							<option value="1" {{$chatbotQuestion->auto_approve == 1 ? 'selected' : ''}}>Yes</option>
						</select>
					    </div>
					  </div>
					  @if($chatbotQuestion->task_type && $chatbotQuestion->task_type != '')
						  <div class="form-row">
						  	<div class="form-group col-md-2">
								<label for="value">Task Category</label>
								<select name="task_category_id" id="" class="form-control">
									<option value="">Select</option>
									@foreach($task_category as $taskcat)
									<option value="{{$taskcat->id}}" {{$taskcat->id == $chatbotQuestion->task_category_id ? 'selected' : ''}}>{{$taskcat->title}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-2">
								<label for="value">Task Type</label>
								<select name="task_type" id="task_type" class="form-control change-task-type">
									<option value="task" {{$chatbotQuestion->task_type == 'task' ? 'selected' : ''}}>Task</option>
									<option value="devtask" {{$chatbotQuestion->task_type == 'devtask' ? 'selected' : ''}}>Devtask</option>
								</select>
							</div>
							<div class="form-group col-md-2">
								<label for="value">Assign to</label>
								<select name="assigned_to" id="" class="form-control">
									<option value="">Select</option>
									@foreach($userslist as $user)
									<option value="{{$user->id}}" {{$user->id == $chatbotQuestion->assigned_to ? 'selected' : ''}}>{{$user->name}}</option>
									@endforeach
								</select>
							</div>
							<div class="col-md-6" id="repo-details">
							<div class="form-group col-md-6" >
										<label for="repository_id">Repository:</label>
										<br>
										<select style="width:100%" class="form-control select2" id="repository_id" name="repository_id">
										<option value="">Select</option>
											@foreach ($respositories as $repository)
												<option value="{{ $repository->id }}" {{$repository->id == $chatbotQuestion->repository_id ? 'selected' : ''}}>{{ $repository->name }}</option>
											@endforeach
										</select>
									</div>
									<div class="form-group col-md-6">
										<label for="module_id">Module:</label>
										<br>
										<select style="width:100%" class="form-control" id="module_id" name="module_id" required>
											<option value>Select a Module</option>
											@foreach ($modules as $module)
											<option value="{{ $module->id }}" {{$module->id == $chatbotQuestion->module_id ? 'selected' : ''}}>{{ $module->name }}</option>
											@endforeach
										</select>
									</div>
									</div>
								</div>
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="value">Task Description</label>
									<textarea name="task_description" class="form-control" rows="2" required>{{$chatbotQuestion->task_description}}</textarea>
								</div>
							</div>
						  @endif
					  @endif
					  @if($chatbotQuestion->keyword_or_question == 'entity')
					  <div class="form-row">
					    <div class="form-group col-md-3">
					      <label for="keyword">Entity</label>
						  <?php echo Form::text("value", $chatbotQuestion->value, ["class" => "form-control", "id" => "keyword", "placeholder" => "Enter your entity"]); ?>
					      <small style="font-size: 10px;" id="emailHelp" class="form-text text-muted">Name your entity to match the category of values that it will detect.</small>
					    </div>
					    <div class="form-group col-md-3">
					      <label for="question">Category</label>
					      <!-- <?php echo Form::select("category_id",$allCategoryList, null, ["class" => "form-control select-chatbot-category", "id" => "chatbot-category-id"]); ?> -->
						  <select name="category_id" id="" class="form-control question-category" data-id="{{$chatbotQuestion->id}}">
						  <option value="">Select</option>
						  @foreach($allCategoryList as $cat)
						  	<option {{$cat['id'] == $chatbotQuestion->category_id ? 'selected' : ''}} value="{{$cat['id']}}">{{$cat['text']}}</option>
						  @endforeach
						</select>
					    </div>
					    <div class="form-group col-md-3">
					      <label for="value">User Entity</label>
					      <?php echo Form::text("question", null, ["data-type" => "entity","class" => "form-control user-intent-exist-search", "id" => "value", "placeholder" => "Enter your value"]); ?>
					    </div>
						<div class="form-group col-md-3">
					      <label for="value">Auto Approve</label>
					      <select name="auto_approve" id="" class="form-control">
							<option value="0" {{$chatbotQuestion->auto_approve == 0 ? 'selected' : ''}}>No</option>
							<option value="1" {{$chatbotQuestion->auto_approve == 1 ? 'selected' : ''}}>Yes</option>
						</select>
					    </div>
					</div>
					<div class="form-row align-items-end">
					    <div class="form-group col-md-2">
						    <label for="type">Type</label>
						    <?php echo Form::select("types",["synonyms" => "synonyms", "patterns" => "patterns"] ,null, ["class" => "form-control", "id" => "types"]); ?>
					    </div>
						<div class="form-group col-md-2">
							<div class="row align-items-end" id="typeValue_1">
								<div class="col-md-9">
									<?php echo Form::text("type[]", null, ["class" => "form-control", "id" => "type", "placeholder" => "Enter value", "maxLength"=> 64]); ?>
								</div>
							</div>
						</div>
						<div class="form-group col-md-2" id="add-type-value-btn">
				  	        <a href="javascript:;" class="btn btn-secondary btn-sm add-more-condition-btn">
			                    <span class="glyphicon glyphicon-plus"></span> 
			                </a>	
			      	    </div>
					</div>
					  @endif
					  @if($chatbotQuestion->keyword_or_question == 'simple' || $chatbotQuestion->keyword_or_question == 'priority-customer')
					  <div class="form-row">
					    <div class="form-group col-md-2">
					      <label for="value">ERP Entity</label>
					     
					      <?php echo Form::text("value", $chatbotQuestion->value, ["class" => "form-control", "id" => "value", "placeholder" => "Enter your value"]); ?>
						  <small style="font-size:10px;" id="emailHelp" class="form-text text-muted">Name your erp entity to match the category of values that it will detect.</small>
					    </div>
					    <div class="form-group col-md-2">
					      <label for="question">Category</label>
						  <select name="category_id" id="" class="form-control question-category" data-id="{{$chatbotQuestion->id}}">
						  <option value="">Select</option>
						  @foreach($allCategoryList as $cat)
						  	<option {{$cat['id'] == $chatbotQuestion->category_id ? 'selected' : ''}} value="{{$cat['id']}}">{{$cat['text']}}</option>
						  @endforeach
						</select>
					    </div>
					    <div class="form-group col-md-2">
					      <label for="question">Keyword</label>
					      <?php echo Form::text("keyword", null, ["class" => "form-control", "id" => "question", "placeholder" => "Enter your keyword"]); ?>
					    </div>
						<div class="form-group col-md-2">
							<strong>Repeat:</strong>
							<select class="form-control" name="repeat" style="margin-top: 5px;">
								<option value="">Don't Repeat</option>
								<option value="Every Day" {{$chatbotQuestion->repeat == 'Every Day' ? 'selected'  : ''}}>Every Day</option>
								<option value="Every Week" {{$chatbotQuestion->repeat == 'Every Week' ? 'selected'  : ''}}>Every Week</option>
								<option value="Every Month" {{$chatbotQuestion->repeat == 'Every Month' ? 'selected'  : ''}}>Every Month</option>
								<option value="Every Year" {{$chatbotQuestion->repeat == 'Every Year' ? 'selected'  : ''}}>Every Year</option>
							</select>
						</div>
					  <div class="form-group col-md-2">
                        <strong>Completion Date:</strong>
                        <div class='input-group date' id='sending-datetime'>
								<input style="margin-top: 5px;" type='text' class="form-control" name="sending_time" value="{{ $chatbotQuestion->sending_time }}"/>

								<span style="margin-top: 5px;" class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>

							@if ($errors->has('sending_time'))
								<div class="alert alert-danger">{{$errors->first('sending_time')}}</div>
							@endif
						</div>
					    <div class="form-group col-md-2">
					      <label for="value">Auto Approve</label>
					      <select name="auto_approve" id="" class="form-control">
							<option value="0" {{$chatbotQuestion->auto_approve == 0 ? 'selected' : ''}}>No</option>
							<option value="1" {{$chatbotQuestion->auto_approve == 1 ? 'selected' : ''}}>Yes</option>
						</select>
					    </div>
					  </div>
					  @endif
					  @if($chatbotQuestion->keyword_or_question == 'entity')
					  <center><button type="submit" class="btn btn-primary">Add Entity</button></center>
					  @else 
					  <center><button type="submit" class="btn btn-primary">Add Intent</button></center>
					@endif
				</form>
	    	</div>
	    	<div class="well show-search-result">
	    		<h4>Suggestion(s)</h4>
	    		<table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%" style="table-layout:fixed;">
				   <tbody>
				      
				   </tbody>
				</table>
	    	</div>	
		</div>
		<div class="col-lg-12 margin-tb">
		@if($chatbotQuestion->keyword_or_question == 'intent')
			<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%" style="table-layout:fixed;">
			  <thead>
			    <tr>
			      <th class="th-sm" style="width:5%">Sl no</th>
			      <th class="th-sm" style="width:65%">User Intent</th>
			      <th class="th-sm" style="width:20%">Annotation</th>
			      <th class="th-sm" style="width:10%">Action</th>
			    </tr>
			  </thead>
			  <tbody>
			    <?php foreach ($chatbotQuestion->chatbotQuestionExamples as $key => $value) {?>
				    <tr>
				      <td><?php echo ++$key; ?></td>
				      <td class="text-question" data-question-id="<?php echo $value->id; ?>" data-question="<?php echo $value->question; ?>">
				      	<?php echo $value->question; ?>
				     </td>
				     <td>
				     	<?php foreach ($value->highLightQuestion() as $key => $valueRaw) { ?>
				     			<div class="delete-annotation-raw" style="display: inline-block;padding-left: 2px;">
				     				<?php echo $valueRaw; ?>
					     			<span data-id="<?php echo $key; ?>" class="close delete-annotation" aria-label="Close"><span aria-hidden="true">&times;</span></span>
					     		</div>
				     	<?php } ?>
				     </td>	
				      <td>
                        <a class="btn btn-image delete-button" data-id="<?php echo $value->id; ?>" href="<?php echo route("chatbot.question-example.delete", [$chatbotQuestion->id, $value->id]); ?>">
                        	<img src="/images/delete.png">
                        </a>
                        <a class="btn btn-image annotation-button">
                        	<img src="/images/starred.png">
                        </a>
				      </td>
				    </tr>
				<?php }?>
			  </tbody>
			</table>
			@endif
			@if($chatbotQuestion->keyword_or_question == 'entity')
			<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%" style="table-layout:fixed;">
				  <thead>
				    <tr>
				      <th class="th-sm" style="width:5%">Sl no</th>
				      <th class="th-sm" style="width:40%">Value</th>
				      <th class="th-sm" style="width:10%">Type</th>
				      <th class="th-sm" style="width:35%">Extra Values</th>
				      <th class="th-sm" style="width:10%">Action</th>
				    </tr>
				  </thead>
				  <tbody>
				    <?php foreach ($chatbotQuestion->chatbotQuestionExamples as $key => $value) {?>
					    <tr>
					      <td><?php echo ++$key; ?></td>
					      <td><?php echo $value->question; ?></td>
					      <td><?php echo $value->types; ?></td>
					      <td><?php 
					      	$insertKeywords = [];
					      	if(!$value->chatbotKeywordValueTypes->isEmpty()) {
					      		foreach($value->chatbotKeywordValueTypes as $chWordVal) {
					      			$insertKeywords[] = $chWordVal->type;
					      		}
					      	}
					      	echo implode(",", $insertKeywords);
				       		?>
				       	 </td>	
					      <td>
	                        <a class="btn btn-image delete-button" data-id="<?php echo $value->id; ?>" href="<?php echo route("chatbot.question-example.delete", [$chatbotQuestion->id, $value->id]); ?>">
	                        	<img src="/images/delete.png">
	                        </a>
					      </td>
					    </tr>
					<?php }?>
				  </tbody>
				</table>
				@endif
				@if($chatbotQuestion->keyword_or_question == 'simple' || $chatbotQuestion->keyword_or_question == 'priority-customer')
				<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%" style="table-layout:fixed;">
				  <thead>
				    <tr>
				      <th class="th-sm" style="width:5%">Sl no</th>
				      <th class="th-sm" style="width:85%">Value</th>
				      <th class="th-sm" style="width:10%">Action</th>
				    </tr>
				  </thead>
				  <tbody>
				    <?php foreach ($chatbotQuestion->chatbotQuestionExamples as $key => $value) {?>
					    <tr>
					      <td><?php echo ++$key; ?></td>
					      <td><?php echo $value->question; ?></td>	
					      <td>
	                        <a class="btn btn-image delete-button" data-id="<?php echo $value->id; ?>" href="<?php echo route("chatbot.question-example.delete", [$chatbotQuestion->id, $value->id]); ?>">
	                        	<img src="/images/delete.png">
	                        </a>
					      </td>
					    </tr>
					<?php }?>
				  </tbody>
				</table>

				@endif
					
		</div>

		

<div class="col-md-12" style="margin-bottom:15px;">
<div class="col-md-7">
</div>
<div class="col-md-4" style="padding:0px;">
<form action="<?php echo route("chatbot.question.edit",[$chatbotQuestion->id]); ?>">
<div class="d-flex">
<select name="store_website_id" class="form-control">
						<option value="">Select Website</option>
						@foreach($watson_accounts as $acc)
						<option value="{{$acc->store_website_id}}" {{request()->get('store_website_id') == $acc->store_website_id ? 'selected' : ''}}>{{$acc->storeWebsite->title}}</option>
						@endforeach
					  </select>
<button type="submit" class="btn btn-image"><img src="/images/filter.png"></button>
</div>
</form>

</div>
<div class="col-md-1 pull-right" style="padding:0px;">
<button type="button" class="btn btn-image add-website-reply" data-id="{{$chatbotQuestion->id}}"><img src="/images/add.png"></button>

</div>
</div>
		<div class="col-lg-12 margin-tb">
			<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%" style="table-layout:fixed;">
			  <thead>
			    <tr>
			      <th class="th-sm" style="width:5%">Sl no</th>
			      <th class="th-sm" style="width:20%">Website</th>
			      <th class="th-sm" style="width:70%">Suggested Response</th>
			      <th class="th-sm" style="width:5%">Action</th>
			    </tr>
			  </thead>
			  <tbody>
			    <?php foreach ($replies as $key => $reply) {?>
				    <tr>
				      <td><?php echo ++$key; ?></td>
				      <td class="">
					  {{$reply->storeWebsite->title}}
				     </td>
				     <td>
				     	{{$reply->suggested_reply}}
				     </td>	
				      <td>
                        <a class="btn btn-image edit-reply-button" data-id="{{$reply->id}}" data-reply="{{$reply->suggested_reply}}">
                        	<img src="/images/edit.png">
                        </a>
				      </td>
				    </tr>
				<?php }?>
			  </tbody>
			</table>
		</div>
	</div>
</div>


<div class="col-lg-12 margin-tb" style="padding:0px;">
			@if($chatbotQuestion->keyword_or_question == 'intent' || $chatbotQuestion->keyword_or_question == 'entity')
			<p>Chatbot Error logs</p>

			<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%" style="table-layout:fixed;">
			  <thead>
			    <tr>
			      <th class="th-sm" style="width:15%;">Website</th>
			      <th class="th-sm" style="width:75%;">Message</th>
			      <th class="th-sm" style="width:5%;">Status</th>
			      <th class="th-sm" style="width:5%;">Action</th>
			    </tr>
			  </thead>
			  <tbody>
			    <?php foreach ($chatbotQuestion->chatbotErrorLogs as $value) {?>
				    <tr>
				      <td class="text-question">
				      	<?php echo $value->storeWebsite->title; ?>
				     </td>
				     <td>
				     	{{$value->response}}
				     </td>	
				      <td>
					  @if($value->status)
					  <span>Success</span>
					  @else 
					  <span style="color:red;">Error</span>
					  @endif
				      </td>
					  <td>
					  @if(!$value->status)
					  	<a class="btn btn-image edit-data-button" data-id="{{$value->id}}">
                        	<img src="/images/edit.png" style="cursor: nwse-resize;">
                    	</a>
					@endif
					  </td>
				    </tr>
				<?php }?>
			  </tbody>
			</table>
			@endif
</div>

@include('chatbot::partial.create_question_annotation')
@include('chatbot::partial.chatbot_reply_edit')
@include('chatbot::partial.chatbot_reply_add')
<script src="/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">

 $('#sending-datetime, #edit-sending-datetime').datetimepicker({
            format: 'YYYY-MM-DD HH:mm'
 });
	var searchForKeyword = function(ele) {
    	var keywordBox = ele.find(".search-keyword");
	    if (keywordBox.length > 0) {
	        keywordBox.select2({
	            placeholder: "Enter entity name or create new one",
	            width: "100%",
	            tags: true,
	            allowClear: true,
	            ajax: {
	                url: '/chatbot/question/keyword/search',
	                dataType: 'json',
	                processResults: function(data) {
	                    return {
	                        results: data.items
	                    };
	                }
	            }
	        });
	    }
	};
	
	var annotationButton = $(".annotation-button");
		annotationButton.on("click",function() {
			var text =  $(this).closest("tr").find(".text-question");
			var words = text.data("question").split(" ");
			var html = "";
			var char = 0; 
			var start = 0; 
			$.each(words,function(k,v) {
				char += v.length;
				var ln = (k == 0) ? 0 : char;

				if(k != 0) {
					start = ln - v.length;
				}

				html += "<div class='border-div dashed-question' data-start='"+start+"' data-end='"+ln+"'>"+v+"</div>";
				char += 1;
			})
			text.html(html);
		});

		$(document).on("click",".dashed-question",function() {
			var $this = $(this);
			var question = $this.closest(".text-question");
				if($this.hasClass("selected")) {
					$this.removeClass("selected")
				}else{
					$this.addClass("selected");
				}
				if(question.find(".selected").length > 1) {
					$.each($this.prevAll(),function(k,v){
						if($(v).hasClass("selected")) {
							return false;
						}else{
							$(v).addClass("selected");
						}
					})
					
					searchForKeyword($("#create-question-annotation"));

					var startRange = question.find(".selected").first().data("start");
					var endRange   = question.find(".selected").last().data("end");

					var keywordValue = question.data("question").substring(startRange, endRange);
					
					var modelBox = $("#create-question-annotation");
						modelBox.find("#question-example-id").val(question.data("question-id"));
						modelBox.find("#start-char-range").val(startRange);
						modelBox.find("#end-char-range").val(endRange);
						modelBox.find("#keyword-value").val(keywordValue);
						modelBox.modal("show");
				}
				$this.nextAll().removeClass("selected");
		});

		$("#create-question-annotation").on("click",".form-save-btn",function() {
			var form = $(this).closest("form");
			$.ajax({
				type: form.attr("method"),
	            url: form.attr("action"),
	            data: form.serialize(),
	            dataType : "json",
	            success: function (response) {
	               if(response.code == 200) {
	               	  toastr['success']('data updated successfully!');
	               	  location.reload();
	               }else{
	               	  toastr['error']('data is not correct or duplicate!');
	               } 
	            },
	            error: function () {
	               toastr['error']('Could not change module!');
	            }
	        });
		});

		$(document).on("click",".edit-reply-button",function() {
			var reply = $(this).data("reply");
			var id = $(this).data("id");
			$("#reply-hidden-data").val(reply);
			$("#reply-hidden-id").val(id);
			$("#chatbotReplyEditModal").show();
		});

		$(document).on("click",".reply-update-save-btn",function() {
			var form = $(this).closest("form");
			$.ajax({
				type: form.attr("method"),
	            url: form.attr("action"),
	            data: form.serialize(),
	            dataType : "json",
	            success: function (response) {
	               if(response.code == 200) {
	               	  toastr['success']('data updated successfully!');
	               	  location.reload();
	               }else{
	               	  toastr['error']('data is not correct or duplicate!');
	               } 
	            },
	            error: function () {
	               toastr['error']('Could not change module!');
	            }
	        });
		});

		$(document).on("click",".edit-data-button",function() {
			var id = $(this).data("id");
			$.ajax({
				type: 'POST',
	            url: '/chatbot/question/online-update/'+id,
	            dataType : "json",
				data: {
                        _token: "{{ csrf_token() }}"
                },
	            success: function (response) {
	               if(response.code == 200) {
	               	  toastr['success'](response.message);
	               	  location.reload();
	               }else{
	               	  toastr['error'](response.message);
	               } 
	            },
	            error: function () {
	               toastr['error']('Could not save correctly!');
	            }
	        });
		});
		$(document).on("click",".delete-annotation",function() {
			var $this = $(this);
			var anntid = $this.data("id");
			$.ajax({
				type: "GET",
	            url: "/chatbot/question/annotation/delete",
	            data: {id : anntid},
	            dataType : "json",
	            success: function (response) {
	               if(response.code == 200) {
	               	  toastr['success']('data updated successfully!');
	               	  $this.closest(".delete-annotation-raw").remove();
	               }else{
	               	  toastr['error']('Oops, something went wrong!');
	               } 
	            },
	            error: function () {
	               toastr['error']('Could not change module!');
	            }
	        });
		});

		$(".select-chatbot-category").select2({
            placeholder: "Enter category name or existing",
            width: "100%",
            tags: true,
            allowClear: true,
            ajax: {
                url: '/chatbot/question/search-category',
                dataType: 'json',
                processResults: function(data) {
                    return {
                        results: data.items
                    };
                }
            }
        });
    var idValue=1;
	$(".add-more-condition-btn").on("click", function(e){
		idValue++;
		var removeBtnId = '#typeValue_'+(idValue-1);
		var selectedType = $(this).closest("form").find("select[name = 'types']").val();
		if ( selectedType == "synonyms" || idValue<=5 ){
			$(removeBtnId).append('<input type="button" value="-" class="btn btn-secondary" onclick="remove(this)"/>');
		    $("<div class='form-group col-md-2' ><div class='row align-items-end' id='typeValue_"+idValue+"' ><div class='col-md-9'><label for='type'>&nbsp</label><input type='text' name='type[]' class='form-control' placeholder='Enter value' maxLength = 64/><div/></div></div>").insertBefore('#add-type-value-btn')
		} else {
			alert("maximum pattern value limit reached : 5")
			idValue--;
		}
	});
	$("#types").on("change", function(e) {
		var typeValueCount = $(this).closest("form").find("input[name = 'type[]']").length;
		if(e.target.value == 'patterns' && typeValueCount>5) {
			alert('You are changing a synonym value to a pattern value. You currently have '+ typeValueCount+ ' synonyms associated with this value, but patterns may only have 5');
			$(this).closest("form").find("select[name = 'types']").val('synonyms').change()
			e.preventDefault();
		}
	});
	function remove(ele) {
		$(ele).parents('div.col-md-2').remove()
	}


	
		$(document).on('change', '.change-task-type', function () {
            var type = $(this).val();
			if(type =='task') {
				$('#repo-details').hide();
			}
			else if(type =='devtask') {
				$('#repo-details').show();
			}
        });
		$( document ).ready(function() {
			if($("#task_type").val() == 'devtask') {
				$('#repo-details').show();
			}
			else {
				$('#repo-details').hide();
			}
		});

		$(document).on('click', '.add-website-reply', function () {
            var id = $(this).data('id');
			$("#add-reply-hidden-id").val(id);
			$("#chatbotReplyAddModal").show();
			
        });

        $(document).on("keyup",".user-intent-exist-search",function() {
        	let $this = $(this);
        	var q = $this.val();
        	$.ajax({
				type: "get",
	            url: "/chatbot/question/search-suggestion",
	            dataType : "json",
				data: {
					q : q,
					type :$this.data("type")
				},
	            success: function (response) {
	            	if(response.code == 200) {
	            		var td = `<thead>
								      <tr>
								         <th class="th-sm" style="width:5%">Sl no</th>
								         <th class="th-sm" style="width:65%">User Intent</th>
								         <th class="th-sm" style="width:20%">Intent name</th>
								         <th class="th-sm" style="width:20%">Erp or Watson</th>
								         <th class="th-sm" style="width:10%">Action</th>
								      </tr>
								   </thead>
								   <tbody>`;
	            		$.each(response.data,function(k,v) {
	            			td += `<tr>
							         <td>`+v.id+`</td>
							         <td class="text-question">`+v.question+`</td>
							         <td>`+v.value+`</td>
							         <td>`+v.erp_or_watson+`</td>
							         <td>
							            <a class="btn btn-image delete-intent-button" data-id="`+v.id+`"><img src="/images/delete.png"></a>
							         </td>
							      </tr>`;
	            		});

	            		td += `</tbody>`;

	            		$(".show-search-result").find("table").html(td);
	            	}
	               
	            },
	            error: function () {
	               toastr['error']('Request could not finished please check server log!');
	            }
	        });

        });

        $(document).on("click",".delete-intent-button",function() {
        	let $this = $(this);
        	if(confirm("Are you sure you want to delete this ?")) {
	        	$.ajax({
					type: "post",
		            url: "/chatbot/question/search-suggestion-delete",
		            dataType : "json",
					data: {
						id : $this.data("id"),
						"_token" : "<?php echo csrf_token(); ?>"
					},
		            success: function (response) {
		               if(response.code == 200) {
		               	  toastr['success'](response.message);
		               	  $this.closest("tr").remove();
		               }else{
		               	  toastr['error'](response.message);
		               } 
		            },
		            error: function () {
		               toastr['error']('Please check the server log file for more information!');
		            }
		        });
        	}
        });

		$(document).on('submit', '#add-website-reply-form', function (e) {
            e.preventDefault();
			var url = $('#add-website-reply-form').attr('action');
			var method = $('#add-website-reply-form').attr('method');
			$.ajax({
				type: method,
	            url: url,
	            dataType : "json",
				data: $(this).serialize(),
	            success: function (response) {
	               if(response.code == 200) {
	               	  toastr['success'](response.message);
	               	  location.reload();
	               }else{
	               	  toastr['error'](response.message);
	               } 
	            },
	            error: function () {
	               toastr['error']('Could not save correctly!');
	            }
	        });
			
        });
		
</script>

@endsection