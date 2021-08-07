@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endsection

@section('large_content')
	<?php $base_url = URL::to('/');?>
	<div class = "row">
		<div class="col-lg-12 margin-tb">
			<h2 class="page-heading">Custom Charity Order List</h2>
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
                        <h4 class="panel-title">Charity Order</h4>
                    </div>
					<div class="panel-body">
						<table class="table table-bordered table-striped">
							<tr>
								<th>Order ID</th>
								<th>Customer</th>
								<th>Email</th>
								<th>Customer Contribution</th>
								<th>Our Contribution</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
							@foreach ($charityOrder as $data )
								<tr>
									<td>{{$data['orderData']['amount']}}</td>
									<td>{{$data['userData']['name']}}</td>
									<td>{{$data['userData']['email']}}</td>
									<td>{{$data['orderData']['customer_contribution']}}</td>
									<td>{{$data['orderData']['our_contribution']}}</td>
									<td>{{$data['orderData']['status']}}</td>
									<td><a href="{{url('charity/view-order-history',$data['orderData']['id'])}}" class="btn btn-image create_history"><img src="images/view.png" style="cursor: default;"></a></td>
								</tr>
							@endforeach
						</table>
						
						{{ $charityoOrderPagination->links() }}
                    </div>
                </div>
            </div>
		</div>
	</div>
	
	<div id="createStatusModel" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Status</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{url('/charity/add-status')}}" method="POST">
					@csrf
					<input type='hidden' class="form-control" name="charity_id" id="charity_id" value="{{Request::get('id')}}">
                    <div class="modal-body">
                        <div class="form-group">
							<strong>Charity Status</strong>
							<input type='text' class="form-control" name="charity_status" id="charity_status" required/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-secondary">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
	
@endsection
