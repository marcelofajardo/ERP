@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'Dialog | Chatbot')

@section('content')
<div class="row">
	<div class="col-lg-12 margin-tb">
	    <h2 class="page-heading">Edit {{ $chatbotDialog->name }} | Chatbot</h2>
	</div>
</div>
<div class="tab-pane">
	<div class="row">
	    <div class="col-lg-12 margin-tb">
	    	<div class="well">
	    		<form action="{{ route('chatbot.dialog.update',[$chatbotDialog->id]) }}" method="post">
    				  <?php echo csrf_field(); ?>
					  <div class="form-row">
					    <div class="form-group col-md-6">
					      <label for="name">Name</label>
					      <small id="dialog_name" class="form-text text-muted">Name your entity to match the category of values that it will detect.</small>
					      <?php echo Form::text("name", $chatbotDialog->name, ["class" => "form-control", "id" => "name", "placeholder" => "Enter dialog name"]); ?>
					    </div>
					  </div>
					  <div class="form-row">
					    <div class="form-group col-md-6">
					      <label for="match_condition">Condition</label>
					      <?php echo Form::select("match_condition",$allSuggestedOptions, $chatbotDialog->match_condition, ["class" => "form-control select2", "id" => "match_condition", "placeholder" => "Enter Condition"]); ?>
					    </div>
					  </div>
					  <div class="form-row">
					    <div class="form-group col-md-6">
					      <label for="question">Response</label>
					      <?php echo Form::textarea("value", null, ["class" => "form-control", "id" => "value", "placeholder" => "Enter Response"]); ?>
					    </div>
					  </div>
					  <button type="submit" class="btn btn-primary">Add Response</button>
				</form>
	    	</div>
		</div>
		<div class="col-lg-12 margin-tb">
			<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
				  <thead>
				    <tr>
				      <th class="th-sm">Response type</th>
				      <th class="th-sm">Value</th>
				      <th class="th-sm">Message to human</th>
				      <th class="th-sm">Action</th>
				    </tr>
				  </thead>
				  <tbody>
				    <?php foreach ($chatbotDialog->response as $value) {?>
					    <tr>
					      <td><?php echo $value->response_type; ?></td>
					      <td><?php echo $value->value; ?></td>
					      <td><?php echo $value->message_to_human_agent; ?></td>
					      <td>
	                        <a class="btn btn-image delete-button" data-id="<?php echo $value->id; ?>" href="<?php echo route("chatbot.dialog-response.delete", [$chatbotDialog->id, $value->id]); ?>">
	                        	<img src="/images/delete.png">
	                        </a>
					      </td>
					    </tr>
					<?php }?>
				  </tbody>
				  <tfoot>
				    <tr>
				      <th>Response type</th>
				      <th>Value</th>
				      <th>Message to human</th>
				      <th>Action</th>
				    </tr>
				  </tfoot>
				</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(".select2").select2();
</script>
@endsection