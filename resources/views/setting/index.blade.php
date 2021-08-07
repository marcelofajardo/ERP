@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection
@section('large_content')
   <div class = "row">
		<div class="col-lg-12 margin-tb">
			<?php $base_url = URL::to('/');?>
			<h2 class="page-heading">Setting Data</h2>
			@if ($message = Session::get('message'))
				<div class="alert alert-success">
					<p>{{ $message }}</p>
				</div>
			@endif
            <div class="pull-left cls_filter_box">
                <form class="form-inline" action="{{ url('settings') }}" method="GET">
                    <div class="form-group ml-3 cls_filter_inputbox">
                        <label for="with_archived">Setting Name</label>
                       <input type="text" name="name" class="form-control-sm cls_commu_his form-control" value="{{request()->get('name')}}">
                    </div>
					<div class="form-group ml-3 cls_filter_inputbox">
                        <label for="with_archived">Setting Value</label>
                       <input type="text" name="value" class="form-control-sm cls_commu_his form-control" value="{{request()->get('value')}}">
                    </div>
					<div class="form-group ml-3 cls_filter_inputbox">
                        <label for="with_archived">Setting Type</label>
                       <input type="text" name="type" class="form-control-sm cls_commu_his form-control" value="{{request()->get('type')}}">
                    </div>
					<div class="form-group ml-3 cls_filter_inputbox margin-top">
						<button type='submit' class="btn btn-default">Search</button>
                    </div>
				</form>
            </div>
			<div class="pull-right mt-3">
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#create_setting_model">Create Setting</button>
            </div>
        </div>
	</div>
	
    <div class="row">
        <div class="col-lg-12 margin-tb">
			<div class="panel-group" style="margin-bottom: 5px;">
                <div class="panel mt-3 panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                           Setting Data
                        </h4>
                    </div>
					<div class="panel-body">
						<table class="table table-bordered table-striped">
							<tr>
								<th>ID</th>
								<th>Setting Name</th>
								<th>Setting Value</th>
								<th>Setting Type</th>
								<th>Welcome Message</th>
								<th>Action</th>
							</tr>
							@foreach ($data as $key => $val )
								<tr id = "row_{{$val->id}}">
									<td>{{$val->id}}</td>
									<td class="name">{{$val->name}}</td> 
									<td class="val">{{$val->val}}</td>
									<td class="type">{{$val->type}}</td>
									<td class="msg">{{$val->welcome_message}}</td>
									<td><button type="button" class="btn btn-default edit_setting" data-id="{{$val->id}}" data-toggle="modal" data-target="#create_setting_model">Edit Setting</button></td>
								</tr>
							@endforeach
						</table>
						{{ $data->links() }}
                    </div>
                </div>
            </div>
		</div>
	</div>
	
	<div id="create_setting_model" class="modal fade" role="dialog" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<form action="{{ url('settings/update') }}" method="POST">
				@csrf
				<div class="modal-header">
					<h4 class="modal-title">Setting</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" name="id" id = "setting_id">
					<div class="form-group">
						<strong>Name:</strong>
						<input type="text" name="name" id = "setting_name" class="form-control" required>
						@if ($errors->has('name'))
							<div class="alert alert-danger">{{$errors->first('name')}}</div>
						@endif
					</div>
					<div class="form-group">
						<strong>Type:</strong>
						<input type="text" name="type" id="setting_type" class="form-control" required>
						@if ($errors->has('type'))
							<div class="alert alert-danger">{{$errors->first('type')}}</div>
						@endif
					</div>
					<div class="form-group">
						<strong>Value:</strong>
						<input type="text" name="val" id="setting_val" class="form-control" required>
						@if ($errors->has('val'))
							<div class="alert alert-danger">{{$errors->first('val')}}</div>
						@endif
					</div>
					<div class="form-group">
						<strong>Welcome Message:</strong>
						<textarea name="welcome_message" id = "setting_welcome_message" class="form-control" rows="8" cols="80"></textarea>
						@if ($errors->has('description'))
							<div class="alert alert-danger">{{$errors->first('welcome_message')}}</div>
						@endif
					</div>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default close-setting" data-dismiss="modal">Close</button>
				  <button type="submit" class="btn btn-secondary">Submit</button>
				</div>
			</form>
        </div>
      </div>
    </div>
	
@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	$('.edit_setting').on('click', function() {
		var id = $(this).data('id');
		$("#setting_id").val(id);
		$("#setting_name").val($("#row_"+id+" td.name").text());
		$("#setting_val").val($("#row_"+id+" td.val").text());
		$("#setting_type").val($("#row_"+id+" td.type").text());
		$("#setting_welcome_message").text($("#row_"+id+" td.msg").text());
    });
	
	$('.close-setting').on('click', function() {
		console.log("hello");
		$("#setting_id, #setting_name, #setting_val, #setting_type").val('');
		$("#setting_welcome_message").text('');
	});
	
});
</script>
@endsection
