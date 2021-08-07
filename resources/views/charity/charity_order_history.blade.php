@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endsection

@section('large_content')
	<?php $base_url = URL::to('/');?>
	<div class = "row">
		<div class="col-lg-12 margin-tb">
			<h2 class="page-heading">Charity Order History</h2>
			@if(Session::has('flash_type'))
				<p class="alert alert-{{Session::get('flash_type')}}">{{ Session::get('message') }}</p>
			@endif
        </div>
		
	</div>
	
   
    <div class="row">
        <div class="col-lg-12 margin-tb">
			<div class="panel-group" style="margin-bottom: 5px;">
                <div class="panel mt-3 panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Order History</h4>
                    </div>
					<div class="panel-body">
						<table class="table table-bordered table-striped">
							<tr>
								<th>User</th>
								<th>Amount</th>
								<th>Comment</th>
								<th>Created Date</th>
							</tr>
							@foreach ($historyOrderData as $historydata )
								<tr>
									<td>{{$userData->name}}</td>
									<td>{{$historydata->amount}}</td>
									<td>{{$historydata->comment}}</td>
									<td>{{$historydata->created_at}}</td>
									
								</tr>
							@endforeach
						</table>
                    </div>
                </div>
            </div>
		</div>
	</div>
	
@endsection
