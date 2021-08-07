@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endsection

@section('large_content')
	<?php $base_url = URL::to('/');?>
	<div class = "row">
		<div class="col-lg-12 margin-tb">
			<h2 class="page-heading">Project Directory Manager</h2>
        </div>
	</div>
	@if(Session::has('message'))
		<p class="alert alert-info">{{ Session::get('message') }}</p>
	@endif

	<div class = "row">
		<div class="col-lg-6 margin-tb">
			<div class="pull-left cls_filter_box">
                <form class="form-inline" action="{{ route('project-file-manager.index') }}" method="GET">
                    <div class="form-group ml-3 cls_filter_inputbox">
                        <label for="with_archived">Search</label>
                       <input type="text" name="search" class="form-control-sm cls_commu_his form-control" value="{{request()->get('search')}}">
                    </div>
                </form>
            </div>
		</div>	
	</div>
   
    <div class="row">
        <div class="col-lg-12 margin-tb">
			<div class="panel-group" style="margin-bottom: 5px;">
                <div class="panel mt-3 panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                           Directory
                        </h4>
                    </div>
					<div class="panel-body">
						<table class="table table-bordered table-striped">
							<tr>
								<th>Directory Name</th>
								<th>Parent Directory</th>
								<th>Size</th>
								<th>Expected Size</th>
								<th>Created</th>
							</tr>
							@foreach ($projectDirectoryData as $data )
								<tr>
									<td>{{$data->name}}</td>
									<td>{{$data->parent}}</td>
									<td>{{$data->size}}</td>
									<td>
										<div class="col-md-8">
											<div class="col-md-6">
												<input class="form-control" id="expected_{{$data->id}}" name="notification_at" placeholder="Expected Size" value="{{$data->notification_at}}">
											</div>
											<div class="col-md-2">
												<button class="btn btn-sm btn-image send-message1" data-id="{{$data->id}}"><img src="images/filled-sent.png"></button>
											</div>
										</div>
									</td>
									<td>{{$data->created_at}}</td>
								</tr>
							@endforeach
						</table>
						
						{{ $projectDirectoryData->links() }}
                    </div>
                </div>
            </div>
		</div>
	</div>
@endsection

@section('scripts')
<script type="text/javascript">
$(document).on('click', '.send-message1', function () {
	console.log("Hello");
	var thiss = $(this);
	var id = $(this).data('id');
	var size = $("#expected_"+id).val();
	
	
	console.log(size);
	
	if (!$(thiss).is(':disabled')) {
		$.ajax({
			url: "/project-file-manager/update",
			type: 'POST',
			data: {"_token": "{{ csrf_token() }}", id: id, size:size},
			beforeSend: function () {
				$(thiss).attr('disabled', true);
			},
			success: function (response) {
				$(thiss).attr('disabled', false);
				toastr['success'](response, 'success');
			},
			error: function (response) {
				console.log(response.responseText);
				$(thiss).attr('disabled', false);
				alert('Oops, Something went wrong!!');
			}
		});
	}
});
</script>
@endsection