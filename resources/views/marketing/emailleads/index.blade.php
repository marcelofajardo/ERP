@extends('layouts.app')
@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@section('large_content')
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
	<div class="row">
        <div class="col-lg-12 margin-tb">
            <?php $base_url = URL::to('/');?>
            <h2 class="page-heading">Email Leads</h2>
			@if ( Session::has('message') )
			  <p class="alert {{ Session::get('flash_type') }}">{{ Session::get('message') }}</p>
			@endif
            <div class="pull-left cls_filter_box">
                <form class="form-inline" action="{{ route('emailleads') }}" method="GET">
                    <div class="form-group ml-3 cls_filter_inputbox">
						<label for="leads_email">Email</label>
						<input type="input" class="form-control" name="email" id="leads_email" value="{{request()->get('email')}}">
					</div>
					<div class="form-group ml-3 cls_filter_inputbox">
						<label for="leads_source">Source</label>
						<input type="input" class="form-control" name="source" id="leads_source" value="{{request()->get('source')}}">
					</div>
                    <button type="submit" style="margin-top: 20px;padding: 5px;" class="btn btn-image"><img src="<?php echo $base_url;?>/images/filter.png"/></button>
                </form>
            </div>
        </div>
        <div class="col-lg-12 margin-tb">
            <div class="pull-right mt-3">
                <button class="btn btn-secondary assign_list" type="button" data-toggle="modal" data-target="#assignModel">Assign Mailing List</button>
				<button class="btn btn-secondary" type="button" data-toggle="modal" data-target="#importEmailLeads">Import Email Leads</button>
				<a class="btn btn-secondary" href="{{url('/emailleads/export')}}">Download Sample File</a>
            </div>
        </div>   
    </div>
	
    
    <div class="row">
        <div class="col-md-12 margin-tb">
			<div class="panel-group" style="margin-bottom: 5px;">
                <div class="panel mt-3 panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                           Email Leads
                        </h4>
                    </div>
					<div class="panel-body">
						<table class="table table-bordered table-striped lead-table">
							<tr>
								<th>Select All</th>
								<th>Email</th>
								<th>Source</th>
								<th>Date Created</th>
								<th>Action</th>
							</tr>
							@foreach ($emailLeads as $lead )
								<tr>
									<td><input type="checkbox" name="lead_id[]" data-leadid = "{{$lead->id}}"></td>
									<td>{{$lead->email}}</td>
									<td>{{$lead->source}}</td>
									<td>{{$lead->created_at}}</td>
									<td><a href="{{url('emailleads/show',$lead->id)}}"><i class="fa fa-edit"></i></a></td>
								</tr>
							@endforeach
						</table>
						
						{{ $emailLeads->links() }}
                    </div>
                </div>
            </div>
		</div>
	</div>
	<div class="modal fade" id="assignModel" tabindex="-1" role="dialog" aria-labelledby="assignModel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
				<form action="{{url('emailleads/assign')}}" method="POST">
					@csrf
					<div class="modal-header">
						<h5 class="modal-title">Assign Mailing List</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<input type="hidden" name="lead_id" id="lead_id">
						<div class="form-group mr-3">
							<select class="form-control select-multiple" name="list_id[]" multiple>
								<optgroup label="Maling List">
								@foreach($mailingList as $list)
									<option value="{{$list->id}}">{{$list->name}}</option>
								@endforeach
								</optgroup>
							</select>
						</div>
					</div>	
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary save_list">Save changes</button>
					</div>
				</form>
            </div>
        </div>
    </div>
	<div id="importEmailLeads" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Import Leads</h4>
					<button type="button" class="close" data-dismiss="modal">×</button>
				</div>
				<form action="{{url('emailleads/import')}}" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="modal-body">
						<div class="form-group">
							<input type="file" name="file" required="">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-secondary">Import</button>
					</div>
				</form>
			</div>

		</div>
	</div>
@endsection

    
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
 <script>
	$(document).ready(function()
	{
		$(".select-multiple").selectpicker();
		
		$(".assign_list").on('click',function(){
			
			$('.select-multiple option:selected').each(function() {
				$(this).prop('selected', false);
			})
			$('.select-multiple').selectpicker('refresh');
			
			console.log("hello");
			var searchIDs = $(".lead-table input:checkbox:checked").map(function(){
			  return $(this).data('leadid');
			}).get();
			
			if(searchIDs.length)
			{
				console.log(searchIDs);
				$("#lead_id").val(searchIDs);
			}else{
				$(".close-btn").trigger('click');
				alert("Please select at least one lead");
				return false;
			}
		});
	});
</script>
@endsection
