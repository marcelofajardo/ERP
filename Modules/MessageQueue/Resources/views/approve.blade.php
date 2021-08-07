@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'List | Message Queue')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
@endsection

@section('content')

<div class="row" id="message-queue-approve-page">
	<div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Message Queue Approve</h2>
    </div>
    <br>
		<div class="panel-group">
			<div id="collapse-message-queue" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
				<div class="panel-body">
					<div class="row">
			    		<form id="message-fiter-handler" action="{{ route('message-queue.report') }}" method="GET">
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
		    <div class="col">
		    	<div class="h" style="margin-bottom:10px;">
		    		<form class="form-inline" action="{{ route('message-queue.approve') }}" method="GET">
					  <div class="row">
				  		<div class="col">
				  			<div class="form-group">
							    <label for="from">Customer Name:</label>
							    <?php echo Form::text("customer_name",request("customer_name"),["class"=> "form-control","placeholder" => "Enter Customer Name"]) ?>
						  	</div>
						  	<div class="form-group">
							    <label for="action">Group:</label>
                  <select name="group_id" class="form-control">
                        <option value="">Select<option>
                        @foreach ($groupList as  $item)
                            <option value="{{ $item->group_id }}" {{ ($item->group_id == $group_id) ? 'selected' : ''}} >{{ $item->group_id }}</option>
                        @endforeach
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
		      	<th width="5%">Id</th>
		        <th width="10%">Customer Name</th>
		        <th width="5%">Group</th>
		        <th width="10%">Message</th>
                <th width="30%">Media</th>
		        <th width="10%">Created At</th>
		        <th width="10%">Action</th>
		      </tr>
		    </thead>
		    <tbody>
          @foreach($messageData as $data)
			      <tr>
			      	<td>{{$data->id}}</td>
			      	<td>{{$data->name}}</td>
			        <td>{{$data->group_id}}</td>
			        <td>{{$data->message}}</td>
                    <td>
                        @foreach($data->getMedia(config("constants.attach_image_tag")) as $media)
                            <img width="75px" heigh="75px" src="{{ $media->getUrl() }}">
                        @endforeach
                    </td>
			        <td>{{$data->created_at}}</td>
              <td><input type="button" id="approve-group" data-group-id="{{$data->group_id}}" value="approve" onsubmit="return false" /></td>
            </tr>
          @endforeach
		    </tbody>
		</table>
	</div>

@endsection

@section('scripts')
<script>
    $(document).on('click', '#approve-group', function(e) {
        e.preventDefault();
        var group_id = $(this).data('group-id');
        var $this = $(this);
        $.ajax({
            type: "POST",
            url: '/message-queue/approve/approved',
            data: {
                _token: "{{ csrf_token() }}",
                group_id: group_id,
            }
        }).done(function(data){
            if(data.code == 200) {
                toastr["success"](data.message);
                $this.closest("tr").remove();
            }
            //location.reload();
        }).fail(function(error) {

        })
      });
  </script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<script type="text/javascript" src="/js/jsrender.min.js"></script>
	<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
	<script src="/js/jquery-ui.js"></script>
	<script type="text/javascript" src="/js/common-helper.js"></script>
	<script type="text/javascript" src="/js/message-queue.js"></script>


@endsection


