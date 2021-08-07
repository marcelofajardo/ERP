@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endsection

@section('large_content')
	<?php $base_url = URL::to('/');?>
	<div class = "row">
		<div class="col-lg-12 margin-tb">
			<h2 class="page-heading">Routes Data</h2>
        </div>
	</div>
	@if(Session::has('message'))
		<p class="alert alert-info">{{ Session::get('message') }}</p>
	@endif

	<div class = "row">
		<div class="col-lg-6 margin-tb">
			<div class="pull-left cls_filter_box">
                <form class="form-inline" action="{{ route('routes.index') }}" method="GET">
                    <div class="form-group ml-3 cls_filter_inputbox">
                        <label for="with_archived">Search</label>
                       <input type="text" name="search" class="form-control-sm cls_commu_his form-control" value="{{request()->get('search')}}">
                    </div>
                </form>
            </div>
		</div>	
        <div class="col-lg-6 margin-tb">
            <div class="pull-right mt-3">
                <a class="btn btn-default" href="{{ route('routes.sync') }}">Route Sync</a>
            </div>
        </div>
	</div>
   
    <div class="row">
        <div class="col-lg-12 margin-tb">
			<div class="panel-group" style="margin-bottom: 5px;">
                <div class="panel mt-3 panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                           Routes
                        </h4>
                    </div>
					<div class="panel-body">
						<table class="table table-bordered table-striped">
							<tr>
								<th>Page URI</th>
								<th>Page Title</th>
								<th>Page Description</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
							@foreach ($routesData as $data )
								<tr>
									<td width="40%"><a href="{{$base_url.'/'.$data->url}}" target="_blank" >{{$base_url.'/'.$data->url}}</a></td>
									<td width="20%">{{$data->page_title}}</td>
									<td width="20%">{{$data->page_description}}</td>
									<td width="15%">
										<select class="form-control status-change" name="status" data-url="{{ route('routes.update',$data->id) }}">
						                  <option value="">Select Status</option>
						                  <option value="active" {{ 'active' == $data->status ? 'selected' : '' }}>Active</option>
						                  <option value="inactive" {{ 'inactive' == $data->status ? 'selected' : '' }}>InActive</option>
						                </select>
									</td>
									<td width="5%"><a class="btn btn-default" href="{{ route('routes.update',$data->id) }}">Edit</a></td>
								</tr>
							@endforeach
						</table>
						
						{{ $routesData->links() }}
                    </div>
                </div>
            </div>
		</div>
	</div>
@endsection


@section('scripts')
    <script type="text/javascript">

        //Change Status
        $(document).on('change', '.status-change', function () {
           	var _this = jQuery(this);
            $.ajax({
            	headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: jQuery(_this).data('url'),
                type: 'POST',
                data: {status: jQuery(_this).val()},
                dataType: "JSON",
                success: function (resp) {
                    console.log(resp);
                    if(resp.status == 'ok') {
                        $("body").append(resp.html);
                        $('#newTaskModal').modal('show');
                    }
                }
            });
        });
    </script>
@endsection