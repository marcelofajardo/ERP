<form action="<?php echo route('chatbot.dialog.saveajax'); ?>" method="post" id="dialog-save-response-form">
		<?php echo csrf_field(); ?>

		<hr>
			<h4>Dialog Section : </h4>
		<hr>
		<div class="form-row">
		    <div class="form-group col-md-9">
		      <select class="form-control search-dialog" name="title" id="keyword_search" placeholder="Enter your keyword" name="keyword" value="{{$details['name']}}">
		      	@foreach($details['dialog'] as $prop)
		      		<option value="{{$prop['name']}}" selected>{{$prop['name']}}</option>
		      	@endforeach
		      </select>					    
		      <small class="form-text text-muted">Node name will be shown to customers for disambiguation so use something descriptive</small>
		  	</div>
		</div>
		<div class="dialog-editor-section">
			<input type="hidden" name="id" value="{{$details['id']}}"/>
			<input type="hidden" id="parent_id_form" name="parent_id" value="{{$details['parent_id']}}"/>
			<hr>
				<h4>If assistant recognizes</h4>
			<hr>
			<div class="form-row">
			    <div class="form-group col-md-3">
			      <select class="form-control search-alias" name="conditions[]">
                      @foreach($details['allSuggestedOptions'] as $sugg)
			      		<option {{$details['first_condition'] == $sugg ? 'selected' : ''}} value="{{$sugg}}">{{$sugg}}</option>
			      	@endforeach
			      </select>
			    </div>
			  	<div class="form-group col-md-3">
				  	<a href="javascript:;" class="btn btn-secondary btn-sm add-more-condition-btn">
			          <span class="glyphicon glyphicon-plus"></span> 
			        </a>	
			  	</div>
			</div>
			<div class="show-more-conditions">
                @foreach($details['extra_condition'] as $extra)
					<div class="form-row">
						<div class="form-group col-md-3">
					      <select name="conditions[]" class="form-control">
					      	<option {{ $extra[0] == "&&" ? 'selected' : ''}} value="&&">AND</option>
					      	<option {{ $extra[0] == "||" ? 'selected' : ''}} value="||">OR</option>
					      </select>
					  	</div>
					  	<div class="form-group col-md-3">
					      <select class="form-control search-alias" name="conditions[]">
							@foreach($details['allSuggestedOptions'] as $selectedValue)
					      		<option value="{{$selectedValue}}">{{$selectedValue}}</option>
					      	@endforeach
					      </select>
					  	</div>
					  	<div class="form-group col-md-3">
						  	<a href="javascript:;" class="btn btn-secondary btn-sm add-more-condition-btn">
					          <span class="glyphicon glyphicon-plus"></span> 
					        </a>
					        <a href="javascript:;" class="btn btn-secondary btn-sm remove-more-condition-btn">
					          <span class="glyphicon glyphicon-minus"></span> 
					        </a>	
					  	</div>
					</div>
				@endforeach
			</div>
			@if($details['dialog_type'] != "folder")
				<hr>
					<h4>Assistant responds</h4>
				<hr>
				<div class="form-row">
					<div class="col-md-9">
						<input type="checkbox" name="response_type" value="response_condition" @if($details['response_condition']) checked @endif class="multiple-conditioned-response" data-toggle="toggle">
						<small class="form-text text-muted">Multiple conditioned responses</small>
					</div>
				</div>	
				<div class="assistant-response-based">
					@if($details['assistant_report'] && count($details['assistant_report']) > 0)
                    @foreach($details['assistant_report'] as $key => $report)
							<div class="form-row">
								@if($report['condition'] != '')
									<div class="form-group col-md-3">
									<select class="form-control search-alias" name="response_condition[{{$report['id']}}][condition]">
										@foreach($details['allSuggestedOptions'] as $key => $sugg)
											<option  value="{{$sugg}}">{{$sugg}}</option>
										@endforeach
									</select>
									<small id="emailHelp_{{$key}}" class="form-text text-muted">IF ASSISTANT RECOGNIZES</small>
									</div>
								@endif
								<div class="form-group col-md-3 extra_condtions @if($report['condition_sign']) dis-none @endif">
								<select class="form-control" name="response_condition[{{$report['id']}}][condition_sign]">
									<option value="">Any</option>
									<option @if($report['condition_sign'] == ':') selected @endif value=":">Is</option>
									<option @if($report['condition_sign'] == '!=') selected @endif value="!=">Is Not</option>
									<option @if($report['condition_sign'] == '>') selected @endif value=">">Greater than</option>
									<option @if($report['condition_sign'] == '<') selected @endif value="<">Less than</option>
								</select>
								</div>
								<div class="form-group col-md-6 extra_condtions @if($report['condition_value'] == '') dis-none @endif">
									<input class="form-control response-value" id="condition_value_{{$key}}" placeholder="Enter a response" name="response_condition[{{$report['id']}}][condition_value]" value="{{$report['condition_value']}}" type="text">
								</div>
								<div class="form-group col-md-9">
									<input class="form-control response-value" id="value_{{$key}}" placeholder="Enter a value" name="response_condition[{{$report['id']}}][value]" value="{{$report['response']}}" type="text">
								</div>
								<div class="form-group col-md-3">
									<button type="button" data-id="{{$report['id']}}" class="btn btn-image btn-delete-mul-response"><img src="/images/delete.png"></button>
									<button type="button" class="btn btn-image btn-add-mul-response"><img src="/images/add.png"></button>
								</div>	
							</div>
						@endforeach
					@else
						<div class="form-row">
							<div class="form-group col-md-9">
							<input class="form-control response-value" placeholder="Enter a response" name="response_condition[0][value]" type="text">
							</div>
						</div>
					@endif
				</div>
			@endif
		</div>
		
	</form>