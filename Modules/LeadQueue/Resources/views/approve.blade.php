@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'List | Lead Queue')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')

<div class="row" id="lead-queue-approve-page">
	<div class="col-lg-12 margin-tb">
		<h2 class="page-heading">Lead Queue Approve</h2>
	</div>
	<br>
	<div class="panel-group">
		<div id="collapse-lead-queue" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
			<div class="panel-body">
				<div class="row">
					<form id="lead-fiter-handler" action="{{ route('lead-queue.report') }}" method="GET">
						<div class="pull-left">
							<div class="form-group">
								<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
									<input type="hidden" name="customrange" id="custom" value="{{ isset($customrange) ? $customrange : '' }}">
									<i class="fa fa-calendar"></i>&nbsp;
									<span @if(isset($customrange)) style="display:none;" @endif id="date_current_show"></span>
									<p style="display:contents;" id="date_value_show"> {{ isset($customrange) ? $from .' '.$to : '' }}</p><i class="fa fa-caret-down"></i>
								</div>
							</div>
						</div>
						<div class="pull-right">
							<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-filter-report">
								<img src="/images/search.png" style="cursor: default;">
							</button>
						</div>
					</form>
				</div>
				<div class="row send-lead-report">

				</div>
			</div>
		</div>
		<div class="collapse" id="pending-queue-no-wise" style="margin-top: 5px;">
			<div class="card card-body">
				<?php if (!empty($countQueue)) { ?>
					<div class="row col-md-12">
						<?php foreach ($countQueue as $queue) { ?>
							<div class="col-md-2">
								<div class="card">
									<div class="card-header">
										<?php echo $queue->whatsapp_number; ?>
									</div>
									<div class="card-body">
										<?php echo $queue->total_message; ?>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
				<?php } else {
					echo "Sorry , No data available";
				} ?>
			</div>
		</div>
	</div>
	<div class="col-lg-12 margin-tb">
		<div class="panel-group">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<div class="collapse" id="collapse-show-queue">
							<div class="card card-body">
								<?php if (!empty($waitingMessages)) { ?>
									<div class="row col-md-12">
										<?php foreach ($waitingMessages as $no => $queue) { ?>
											<div class="col-md-2">
												<div class="card">
													<div class="card-header">
														<?php echo $no; ?>
													</div>
													<div class="card-body">
														<a class="recall-api" data-no="<?php echo $no; ?>" href="javascript:;"><img title="Recall" src="/images/icons-refresh.png"></img></a>&nbsp;
														<?php echo $queue; ?>
													</div>
												</div>
											</div>
										<?php } ?>
									</div>
								<?php } else {
									echo "Sorry , No data available";
								} ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-12 margin-tb">
		<div class="row" style="margin-bottom:20px;">
			<div class="col">
				<div class="h" style="margin-bottom:10px;">
					<form class="form-inline" action="{{ route('lead-queue.approve') }}" method="GET">
						<div class="row">
							<div class="col">
								<!-- <div class="form-group">
									<label for="from">Customer Name:</label>
									<?php echo Form::text("customer_name", request("customer_name"), ["class" => "form-control", "placeholder" => "Enter Customer Name"]) ?>
								</div> -->
								<div class="form-group">
									<label for="from">Message:</label>
									<?php echo Form::text("message", request("message"), ["class" => "form-control", "placeholder" => "Enter message"]) ?>
								</div>
								<div class="form-group">
									<label for="action">Lead Group:</label>
									@php
									$listOfValues = [];
									$listOfValues[null] = 'Please Select';
									foreach($leadList as $item):
									if($item->lead_id):
									$listOfValues[$item->lead_id] = $item->lead_id;
									endif;
									endforeach;
									@endphp
									{{ Form::select('lead_id',$listOfValues,(($lead_id)?$lead_id:null),['class' => 'form-control']) }}
								</div>
								<div class="form-group">
								<label for="action">Customer:</label>
								<select name="customer_id" type="text" class="form-control" placeholder="Search" id="customer-search" data-allow-clear="true">
                            <?php 
                                if (request()->get('customer_id')) {
                                    echo '<option value="'.request()->get('customer_id').'" selected>'.request()->get('customer_id').'</option>';
                                }
                            ?>
                        </select>
								</div>
								<div class="form-group">
									<label for="button">&nbsp;</label>
									<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image">
										<img src="/images/search.png" style="cursor: default;">
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- <div class="col-md-12 margin-tb infinite-scroll" id="page-view-result">

		</div> -->
	</div>
</div>
<div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif')
          50% 50% no-repeat;display:none;">
</div>

<div class="row">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th width="5%"><input type="checkbox" id="sel-multiplelead-checkbox">
				<button class="btn btn-xs btn-primary submit-multileads-approval" title="approve selected leads group"><i class="fa fa-check-circle" aria-hidden="true"></i></button>
				</th>
				<th width="5%">Customer Id</th>
				<th width="10%">Customer Name</th>
				<th width="10%">Phone</th>
				<th width="10%">Lead Group</th>
				<th width="10%">Message</th>
				<th width="25%">Media</th>
				<th width="10%">Created At</th>
				<th width="30%">Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($messageData as $data)
			@php
				$media = $chat_array[$data->customer_id];
				$medias = explode(",",$media);
			@endphp
			<tr>
				<td><input class="approve-sel-checkbox" type="checkbox" value="{{$data->lead_id}}"></td>
				<td>{{$data->cust_id}}</td>
				<td>{{$data->name}}</td>
				<td>{{$data->phone}}</td>
				@if(array_key_exists($data->customer_id,$lead_group_array))
					<td>{{$lead_group_array[$data->customer_id]}}</td>
				@else
					<td>-</td>
				@endif

				@if(array_key_exists($data->customer_id,$message_array))
					<td>{{$message_array[$data->customer_id]}}</td>
				@else
					<td>-</td>
				@endif
				
				{{-- <td>{{$data->message}}</td> --}}
				<td>
					@foreach($medias as $media)
						@php
							$chat = App\ChatMessage::find($media);
						@endphp
						<img width="75px" heigh="75px" src="{{$chat->media_url}}">
					@endforeach
				</td>
				<td>{{$data->created_at}}</td>
				<td style="width:300px"><input type="button" id="approve-lead-group" data-lead-id="{{$lead_group_array[$data->customer_id]}}" value="approve" onsubmit="return false" />
				<button title="Remove Multiple products" type="button" class="btn btn-xs btn-secondary remove-leads mr-3" data-id="{{$chat_array[$data->customer_id]}}"><i class="fa fa-trash" aria-hidden="true"></i></button>
				<button title="Send Images" type="button" class="btn btn-image send-message no-pd" data-id="{{$data->cust_id}}"><img src="../images/filled-sent.png" /></button>

				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>

@endsection

@section('scripts')
<script>
  $(document).on("click", ".remove-leads", function (event) {
	event.preventDefault();
	var $this = $(this);
	var chat_id = $(this).data("id");
            $.ajax({
                url: "{{ route('lead-queue.delete.record') }}",
                type: 'get',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
					chat_id: chat_id,
                },
                // beforeSend: function () {  
                //     $("#loading-image").show();
                // },
                success: function(result){
					if (result.code == 200) {
				toastr["success"](result.message);
				$this.closest("tr").remove();
			}
             }
         });
        });
	$(document).on('click', '#approve-lead-group', function(e) {
		e.preventDefault();
		var lead_id = $(this).data('lead-id');
		var $this = $(this);
		$.ajax({
			type: "get",
			url: '/lead-queue/approve/approved',
			data: {
				_token: "{{ csrf_token() }}",
				lead_id: lead_id,
			}
		}).done(function(data) {
			if (data.code == 200) {
				toastr["success"](data.message);
				$this.closest("tr").remove();
			}
			//location.reload();
		}).fail(function(error) {

		})
	});
	$(document).on('click', '#approve-sel-lead-group', function(e) {
		e.preventDefault();
		var lead_id = $(this).data('lead-id');
		var $this = $(this);
		$.ajax({
			type: "get",
			url: '/lead-queue/approve/approved',
			data: {
				_token: "{{ csrf_token() }}",
				lead_id: lead_id,
			}
		}).done(function(data) {
			if (data.code == 200) {
				toastr["success"](data.message);
				$this.closest("tr").remove();
			}
			//location.reload();
		}).fail(function(error) {

		})
	});
	$(document).on('click','#sel-multiplelead-checkbox',function(){
		var checkBoxes = $('.approve-sel-checkbox');
        checkBoxes.prop("checked", !checkBoxes.prop("checked"));
	});
	$(document).on('click','.submit-multileads-approval',function(e){
		e.preventDefault();
		var sel_lead_length = $('.approve-sel-checkbox:checked').length;
		if(sel_lead_length==0){
			toastr["error"]('Please select at least one lead for approval !');
			return;
		}
		var sel_leads = [];		
		$('.approve-sel-checkbox:checked').each(function ()
		{
			sel_leads.push(parseInt($(this).val()));
		});

		$.ajax({
			type: "get",
			url: '/lead-queue/approve/approved',
			data: {
				_token: "{{ csrf_token() }}",
				lead_id: sel_leads,
			}
		}).done(function(data) {
			if (data.code == 200) {
				toastr["success"](data.message);
				$('.approve-sel-checkbox:checked').closest("tr").remove();
				$('sel-multiplelead-checkbox').removeAttr('checked');
			}
			//location.reload();
		}).fail(function(error) {

		})

	});
	$('#customer-search').select2({
            tags: true,
            width : '100%',
            ajax: {
                url: '/erp-leads/customer-search',
                dataType: 'json',
                delay: 750,
                data: function (params) {
                    return {
                        q: params.term, // search term
                    };
                },
                processResults: function (data, params) {
                    for (var i in data) {
                        if(data[i].name) {
                            var combo = data[i].name+'/'+data[i].id;
                        }
                        else {
                            var combo = data[i].text;
                        }
                        data[i].id = combo;
                    }
                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
            },
            placeholder: 'Search for Customer by id, Name, No',
            escapeMarkup: function (markup) {
                return markup;
            },
            minimumInputLength: 1,
            templateResult: function (customer) {
                if (customer.loading) {
                    return customer.name;
                }
                if (customer.name) {
                    return "<p> " + (customer.name ? " <b>Name:</b> " + customer.name : "") + (customer.phone ? " <b>Phone:</b> " + customer.phone : "") + "</p>";
                }
            },
            templateSelection: (customer) => customer.text || customer.name,
        });
	
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/common-helper.js"></script>
<script type="text/javascript" src="/js/lead-queue.js"></script>


@endsection