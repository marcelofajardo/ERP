@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'Entities | Chatbot')

@section('content')
<div class="row">
	<div class="col-lg-12 margin-tb">
	    <h2 class="page-heading">Edit {{ $chatbotKeyword->keyword }} | Chatbot</h2>
	</div>
</div>
<div class="tab-pane">
	<div class="row">
	    <div class="col-lg-12 margin-tb">
	    	<div class="well">
	    		<form action="{{ route('chatbot.keyword.update',[$chatbotKeyword->id]) }}" method="post">
    				  <?php echo csrf_field(); ?>
					  <div class="form-row">
					    <div class="form-group col-md-6">
					      <label for="keyword">Entity</label>
					      <small id="emailHelp" class="form-text text-muted">Name your entity to match the category of values that it will detect.</small>
					      <!-- <?php echo Form::text("keyword", $chatbotKeyword->keyword, ["class" => "form-control", "id" => "keyword", "placeholder" => "Enter your keyword"]); ?> -->
						  <?php echo Form::text("value", $chatbotKeyword->value, ["class" => "form-control", "id" => "keyword", "placeholder" => "Enter your keyword"]); ?>
					    </div>
					  </div>
					  <div class="form-row">
					    <div class="form-group col-md-6">
					      <label for="value">Value</label>
					      <?php echo Form::text("value_name", null, ["class" => "form-control", "id" => "value", "placeholder" => "Enter your value"]); ?>
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
					<button type="submit" class="btn btn-primary">Add Value</button>
				</form>
	    	</div>
		</div>
		<div class="col-lg-12 margin-tb">
			<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
				  <thead>
				    <tr>
				      <th class="th-sm">Id</th>
				      <th class="th-sm">Value</th>
				      <th class="th-sm">Type</th>
				      <th class="th-sm">Extra Values</th>
				      <th class="th-sm">Action</th>
				    </tr>
				  </thead>
				  <tbody>
				    <?php foreach ($chatbotKeyword->chatbotKeywordValues as $value) {?>
					    <tr>
					      <td><?php echo $value->id; ?></td>
					      <td><?php echo $value->value; ?></td>
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
	                        <a class="btn btn-image delete-button" data-id="<?php echo $value->id; ?>" href="<?php echo route("chatbot.value.delete", [$chatbotKeyword->id, $value->id]); ?>">
	                        	<img src="/images/delete.png">
	                        </a>
					      </td>
					    </tr>
					<?php }?>
				  </tbody>
				  <tfoot>
				    <tr>
				      <th>Id</th>
				      <th>Value</th>
				      <th>Type</th>
				      <th>Extra Values</th>
				      <th>Action</th>
				    </tr>
				  </tfoot>
				</table>
		</div>
	</div>
</div>
<script type="text/javascript">
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
</script>
@endsection