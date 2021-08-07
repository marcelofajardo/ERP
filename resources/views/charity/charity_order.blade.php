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
		<div class="col-lg-12 margin-tb">
			<div class="margin-tb pull-right">
				<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#createStatusModel">Create Charity Status</button>
			</div>
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
									<td>
										<select class="form-control" data-orderid="{{$data['orderData']['id']}}" name="charity_status" id="charity_status">
											<option value="">Select Status</option>
											@foreach($allCharityStatus as $status)
												<option value="{{$status->charity_status}}" {{$data['orderData']['status']==$status->charity_status ? 'selected': ''}}>{{ $status->charity_status }}</option>
											@endforeach
										</select>
									</td>
									<td>
										<!--button type="button" class="btn btn-default create_history" data-toggle="modal" data-id="{{$data['orderData']['id']}}" data-target="#createHistoryModel">Create History</button-->
										<a href="javascript:void(0)" data-id="{{$data['orderData']['id']}}" data-toggle="modal" data-target="#createHistoryModel" class="btn btn-image create_history"><img src="images/edit.png" style="cursor: default;"></a>
										
										<a href="{{url('charity/view-order-history',$data['orderData']['id'])}}" class="btn btn-image create_history"><img src="images/view.png" style="cursor: default;"></a>
									</td>
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
	
	<div id="createHistoryModel" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create History</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{url('/charity/create-history')}}" method="POST">
					@csrf
					<input type='hidden' class="form-control" name="customer_order_charity_id" id="customer_order_charity_id">
                    <div class="modal-body">
                        <div class="form-group">
							<strong>Amount</strong>
							<input type='text' class="form-control" name="amount" id="amount" required/>
                        </div>
						<div class="form-group">
							<strong>Comment</strong>
							<input type='text' class="form-control" name="comment" id="comment" required/>
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

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$("#charity_status").on('change',function(){
			var orderId = $(this).data('orderid');
			var status = $(this).val();
			if(status)
			{
				$.ajax({
					url: '/erp/charity/update-charity-order-status/',
					method: 'POST',
					headers: {
						'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
					},
					data: {orderId:orderId, status:status},
					success: function (response) {
						console.log(response)
						if(response.code == 200) {
							toastr["success"](response.message);
						}
						if(response.code == 500) {
							toastr["error"](response.message);
						}
					},
				});
			}
		});
		
		$(".create_history").on('click',function(){
			var customer_order_charity_id = $(this).data('id');
			$("#customer_order_charity_id").val(customer_order_charity_id);
		});
		
	});
</script>
@endsection