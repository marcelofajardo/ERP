@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'Intent | Chatbot')

@section('large_content')
<style type="text/css">
	table.dataTable thead .sorting:after,
	table.dataTable thead .sorting:before,
	table.dataTable thead .sorting_asc:after,
	table.dataTable thead .sorting_asc:before,
	table.dataTable thead .sorting_asc_disabled:after,
	table.dataTable thead .sorting_asc_disabled:before,
	table.dataTable thead .sorting_desc:after,
	table.dataTable thead .sorting_desc:before,
	table.dataTable thead .sorting_desc_disabled:after,
	table.dataTable thead .sorting_desc_disabled:before {
	bottom: .5em;
	}
	.table>tbody>tr>td {
		padding:4px;
	}
	.pd-3 {
		padding: 3px;
	}
	.select2-container .select2-selection--single {
	height:33px !important;
	}
</style>
<div class="row">
	<div class="col-lg-12 margin-tb">
	    <h2 class="page-heading">Dialog grid | Error log</h2>
	</div>
</div>
<div class="row">
    <div class="col-lg-12 margin-tb" style="margin-bottom: 10px;padding:0px;">
    	<div class="col-md-7 pull-left">
    		<form action="{{route('chatbot.dialog.local-error-log')}}" method="get">
	            <div class="row">
					<div class="col">
				      <select name="store_website_id" class="form-control">
						<option value="">Select Website</option>
						@foreach($watson_accounts as $acc)
						<option value="{{$acc->store_website_id}}" {{request()->get('store_website_id') == $acc->store_website_id ? 'selected' : ''}}>{{$acc->storeWebsite->title}}</option>
						@endforeach
					  </select>
				    </div>
				    <div class="col">
				      <button type="submit" class="btn btn-image"><img src="/images/filter.png"></button>
				    </div>
				</div>
			</form>
        </div>
    </div>
</div>
<div class="tab-pane">
	<div class="row">
	    <div class="col-lg-12 margin-tb">
	        <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
			  <thead>
			    <tr>
			      <th class="th-sm">Id</th>
			      <th class="th-sm">Website</th>
			      <th class="th-sm">Dialog</th>
			      <th class="th-sm">Message</th>
			      <th class="th-sm">Status</th>
			      <th class="th-sm">Action</th>
			    </tr>
			  </thead>
			  <tbody>
			    @foreach($logs as $log)
				    <tr>
				      <td>{{$log->id}}</td>
				      <td>{{isset($log->storeWebsite) ? $log->storeWebsite->title : ''}}</td>
				      <td>{{isset($log->chatbot_dialog) ? $log->chatbot_dialog->title : ''}}</td>
					  <td>{{$log->response}}</td>
                      <td>
					  	@if($log->status)
						  <span>Success</span>
						@else 
						<span style="color:red;">Error</span>
						@endif
					  </td>
				      <td></td>
				    </tr>
                    @endforeach
			  </tbody>
			</table>
	    </div>
	    <div class="col-lg-12 margin-tb">
	    	<?php echo $logs->appends(request()->except("page"))->links(); ?>
	    </div>	
	</div>
</div>
@endsection