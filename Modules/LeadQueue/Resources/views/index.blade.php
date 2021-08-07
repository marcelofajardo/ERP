@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'List | Lead Queue')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
@endsection

@section('content')

<div class="row" id="lead-queue-page">
	<div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Lead Queue <span id="total-counter"></span></h2>
    </div>
    <br>
    <div class="col-lg-12 margin-tb">
		<button data-toggle="collapse" href="#collapse-message-queue" class="collapsed btn btn-secondary" aria-expanded="false">Search message queue count</button>
		<button data-toggle="collapse" href="#collapse-show-queue" class="collapsed btn btn-secondary" aria-expanded="false">Show Chat API Queue Count</button>
		<button data-toggle="collapse" href="#pending-queue-no-wise" class="collapsed btn btn-secondary" aria-expanded="false">Show Message Queue Count</button>
		<div class="panel-group">
			<div id="collapse-message-queue" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
				<div class="panel-body">
					<div class="row">
			    		<form id="lead-fiter-handler" action="{{ route('lead-queue.report') }}" method="GET">
		                	<div class="pull-left">
		                		<div class="form-group">
	                    	    	<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
		                                <input type="hidden" name="customrange" id="custom" value="{{ isset($customrange) ? $customrange : '' }}">
		                                <i class="fa fa-calendar"></i>&nbsp;
		                                <span @if(isset($customrange)) style="display:none;" @endif id="date_current_show"></span> <p style="display:contents;" id="date_value_show"> {{ isset($customrange) ? $from .' '.$to : '' }}</p><i class="fa fa-caret-down"></i>
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
					<div class="row send-message-report">
							
					</div>
				</div>
			</div>
			<div class="collapse" id="pending-queue-no-wise" style="margin-top: 5px;">
	            <div class="card card-body">
	              <?php if(!empty($countQueue)) { ?>
	                <div class="row col-md-12">
	                    <?php foreach($countQueue as $queue) { ?>
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
	              <?php } else  { 
	                echo "Sorry , No data available";
	              } ?>
	            </div>
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
				              <?php if(!empty($waitingMessages)) { ?>
				                <div class="row col-md-12">
				                    <?php foreach($waitingMessages as $no => $queue) { ?>
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
				              <?php } else  { 
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
	    	<div class="col col-md-5">
		    	<div class="h" style="margin-bottom:20px;">
		    		<form class="form-inline lead-queue-handler" method="post">
					  <div class="row">
				  		<div class="col">
				  			<div class="form-group">
							    <label for="action">Action:</label>
							    <select class="form-control" id="action-to-run">
							    	<option value="">-- Select --</option>
							    	<option value="change_to_broadcast">Change to Broadcast</option>
							    	<option value="delete_records">Delete Records</option>
							    	<option value="delete_all">Delete All Records</option>
							    	<option value="change_customer_number">Change Customer Number</option>	
							    </select>
						  	</div>
						  	<div class="form-group sending-number-section" style="display: none;">
							    <label for="action">Sending Number:</label>
							    <select class="form-control" name="sending-number" id="sending-number">
								    @foreach(array_filter(config("apiwha.instances")) as $number => $apwCate)
				                        @if($number != "0")
				                            <option value="{{ $number }}">{{ $number }}</option>
				                        @endif
				                    @endforeach
							    </select>
						  	</div>
						  	<div class="form-group">
						  		<label for="button">&nbsp;</label>
						  		<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-send-action">
						  			<img src="/images/filled-sent.png" style="cursor: default;">
						  		</button>
						  	</div>		
				  		</div>
					  </div>	
					</form>
					<form class="form-inline lead-queue-limit-handler" method="post">
						<?php echo csrf_field(); ?>
					  	<div class="row">
					  		<div class="col">
					  			@foreach(array_filter(config("apiwha.instances")) as $number => $apwCate)
			                        @if($number != "0")
				                        <div class="form-group">
										    <label for="message_sending_limit">Limit for {{ $number }}:</label>
										    {{ Form::text("message_sending_limit[{$number}]",isset($sendingLimit[$number]) ? $sendingLimit[$number] : 0,["class" => "form-control message_sending_limit"] ) }}
									  	</div>
			                        @endif
			                    @endforeach		
					  		</div>
					  	</div>
					  	<div class="row">
					  		<div class="col">
					  			<div class="form-group">
								    <label for="send_start_time">Start Time:</label>
								    {{ Form::time("send_start_time",isset($sendStartTime) ? $sendStartTime : 0,["class" => "form-control message_sending_start" , "datetime" => "hh:mm"] ) }}
							  	</div>
							  	<div class="form-group">
								    <label for="send_end_time">End Time:</label>
								    {{ Form::time("send_end_time",isset($sendEndTime) ? $sendEndTime : 0,["class" => "form-control message_sending_end" , "datetime" => "hh:mm"] ) }}
							  	</div>
							  	<div class="form-group">
							  		<label for="button">&nbsp;</label>
							  		<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-send-limit">
							  			<img src="/images/filled-sent.png" style="cursor: default;">
							  		</button>
							  	</div>		
					  		</div>
					  	</div>	
					</form>	
		    	</div>
		    </div>
		    <div class="col">
		    	<div class="h" style="margin-bottom:10px;">
		    		<form class="form-inline lead-search-handler" method="post">
					  <div class="row">
				  		<div class="col">
				  			<div class="form-group">
							    <label for="from">Customer Name:</label>
							    <?php echo Form::text("customer_name",request("customer_name"),["class"=> "form-control","placeholder" => "Enter Customer Name"]) ?>
						  	</div>
				  			<div class="form-group">
							    <label for="from">From:</label>
							    <select class="form-control" name="from" id="sending-number-from">
							    	<option value="">-- Select --</option>
								    @foreach(array_filter(config("apiwha.instances")) as $number => $apwCate)
				                        @if($number != "0")
				                            <option value="{{ $number }}">{{ $number }}</option>
				                        @endif
				                    @endforeach
							    </select>
						  	</div>
						  	<div class="form-group">
							    <label for="to">To:</label>
							    <?php echo Form::text("to",request("to"),["class"=> "form-control","placeholder" => "Enter Number to"]) ?>
						  	</div>
						  	<div class="form-group">
							    <label for="action">Lead Group:</label>
							    <?php echo Form::select("lead_id",$leadList,request("lead_id"),["class" => "form-control select2","placeholder" => "Lead Group"]) ?>
						  	</div>
						  	<div class="form-group">
							    <label for="action">Number of records:</label>
							    <?php echo Form::select("limit",[10 => "10", 20 => "20", 30 => "30" , 50 => "50", 100 => "100" , 500 => "500" , 1000 => "1000"],request("limti"),["class" => "form-control select2","placeholder" => "Page limit"]) ?>
						  	</div>
						  	<div class="form-group">
						  		<label for="button">&nbsp;</label>
						  		<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-search-action">
						  			<img src="/images/search.png" style="cursor: default;">
						  		</button>
						  	</div>		
				  		</div>
					  </div>	
					</form>	
		    	</div>
		    </div>
	    </div>	
		<div class="col-md-12 margin-tb infinite-scroll" id="page-view-result">

		</div>
	</div>
</div>
<div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
          50% 50% no-repeat;display:none;">
</div>

@include("leadqueue::templates.list-template")


@endsection

@section('scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<script type="text/javascript" src="/js/jsrender.min.js"></script>
	<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
	<script src="/js/jquery-ui.js"></script>
	<script type="text/javascript" src="/js/common-helper.js"></script>
	<script type="text/javascript" src="/js/lead-queue.js"></script>
	<script type="text/javascript">
		msQueue.init({
			bodyView : $("#lead-queue-page"),
			baseUrl : "<?php echo url("/"); ?>"
		});
	</script>
	<script>
		$(document).on('click','.chat_short_message',function(){
			$(this).hide();
			$(this).siblings('.chat_long_message').show();
		})

		$(document).on('click','.chat_long_message',function(){
			$(this).hide();
			$(this).siblings('.chat_short_message').show();
		})
		$(document).on('click','.delete-lead-chat-messages',function(){
			var chat_id_array = $(this).data('chat_ids');
			if(!confirm("Are you sure you want to delete record?")) {
                return false;
            }else {
				$.ajax({
					url: "{{route('lead-queue.delete.record')}}",
					data:{'chat_id':chat_id_array},
					method: "get",
					success:function(response)
					{
						if(response.code == 200){
							toastr['success']('Message deleted successfully', 'success');
							location.reload();
						}else{
							toastr['error']('Oops.something went wrong', 'error');
						}
					}
				})
			}
		})
	</script>
@endsection


