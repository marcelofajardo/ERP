@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', $title)

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
<style type="text/css">
	.preview-category input.form-control {
	  width: auto;
	}
</style>

<div class="row" id="common-page-layout">
	<div class="col-lg-12 margin-tb">
        <h2 class="page-heading">{{$title}} <span class="count-text"></span></h2>
    </div>
    <br>
    <div class="col-lg-12 margin-tb">
    	<div class="row">
	    	<div class="col col-md-6">
		    	<div class="row">
	    			<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-add-action">
		  				<img src="/images/add.png" style="cursor: default;">
		  			</button>
		  		</div>
		    </div>
		    <div class="col-md-6">
		    	<div class="h" style="margin-bottom:10px;">
		    		<form class="form-inline message-search-handler" action="{{route('hubstaff-acitivties.notification.download')}}"  method="post">
					  <div class="row">
			  			<div class="form-group mr-2">
						    <label for="keyword">Keyword:</label>
						    <?php echo Form::text("keyword",request("keyword"),["class"=> "form-control","placeholder" => "Enter keyword"]) ?>
					  	</div>
					  	<div class="form-group">
		                    <strong>Date Range</strong>
		                    <input type="text" value="<?php echo date("Y-m-d"); ?>" name="start_date" hidden/>
		                    <input type="text" value="<?php echo date("Y-m-d"); ?>" name="end_date" hidden/>
		                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
		                        <i class="fa fa-calendar"></i>&nbsp;
		                        <span></span> <i class="fa fa-caret-down"></i>
		                    </div>
		                </div>
		               	<div class="form-group">
					  		<label for="button">&nbsp;</label>
					  		<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-search-action">
					  			<img src="/images/search.png" style="cursor: default;">
					  		</button>
					  	</div>	
					  </div>
					  <div class="form-group ml-5">
                        <button type="submit" name="submit" value="report_download" title="Download report" class="btn btn-sm btn-secondary"><i class="fa fa-file-excel-o"></i>Download report</button>
                      </div>	
					</form>	
		    	</div>
		    </div>
	    </div>	
		<div class="col-md-12 margin-tb" id="page-view-result">

		</div>
	</div>
</div>
<div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
          50% 50% no-repeat;display:none;">
</div>
<div class="common-modal modal" role="dialog">
  	<div class="modal-dialog" role="document">
  	</div>	
</div>

<div id="chat-list-history" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Communication</h4>
				<input type="text" name="search_chat_pop"  class="form-control search_chat_pop" placeholder="Search Message" style="width: 200px;">
				<input type="hidden" id="chat_obj_type" name="chat_obj_type">
				<input type="hidden" id="chat_obj_id" name="chat_obj_id">
				<button type="submit" class="btn btn-default downloadChatMessages">Download</button>
			</div>
			<div class="modal-body" style="background-color: #999999;">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

@include("hubstaff.activities.notification.templates.list-template")
@include("hubstaff.activities.notification.templates.create-website-template")
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/common-helper.js"></script>
<script type="text/javascript" src="/js/hubstaff-activities-notification.js"></script>
<script type="text/javascript">
	page.init({
		bodyView : $("#common-page-layout"),
		baseUrl : "<?php echo url("/"); ?>"
	});
</script>
<script>
	$(document).on('click', '.send-message1', function () {
		var thiss = $(this);
		var data = new FormData();
		var hubstuff_id = $(this).data('hubstuffid');

		var message = $("#messageid_"+hubstuff_id).val();

		data.append("hubstuff_id", hubstuff_id);
		data.append("message", message);
		data.append("status", 1);

		if (message.length > 0) {
			if (!$(thiss).is(':disabled')) {
				$.ajax({
					url: BASE_URL+'/whatsapp/sendMessage/hubstuff',
					type: 'POST',
					"dataType": 'json',           // what to expect back from the PHP script, if anything
					"cache": false,
					"contentType": false,
					"processData": false,
					"data": data,
					beforeSend: function () {
						$(thiss).attr('disabled', true);
					}
				}).done(function (response) {
					//thiss.closest('tr').find('.message-chat-txt').html(thiss.siblings('textarea').val());
					if(message.length > 30)
					{
						var res_msg = message.substr(0, 27)+"..."; 
						$("#message-chat-txt-"+hubstuff_id).html(res_msg);
						$("#message-chat-fulltxt-"+hubstuff_id).html(message);    
					}
					else
					{
						$("#message-chat-txt-"+hubstuff_id).html(message); 
						$("#message-chat-fulltxt-"+hubstuff_id).html(message);      
					}
					
					$("#messageid_"+hubstuff_id).val('');
					
					$(thiss).attr('disabled', false);
				}).fail(function (errObj) {
					$(thiss).attr('disabled', false);

					// alert("Could not send message");
					console.log(errObj);
				});
			}
		} else {
			alert('Please enter a message first');
		}
	});
</script>
@endsection

