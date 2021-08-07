@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'List | Return Exchange')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
	<style>
	.form-group-extended{
		margin-bottom: 5px !important;
		width:19.5%  !important;
	}
	.form-group-extended-fifteen{
		margin-bottom: 5px !important;
		width:15%  !important;
	}
	.form-group-extended-four{
		margin-bottom: 5px !important;
		width:4%  !important;
	}
	.form-group-extended input[type=text]{
		width:99%  !important;
	}

	.modal-dialog-wide{ 
		max-width: 100%;
		width: auto !important;
		/*display: inline-block;*/
	}
	.select2-container {
		width:100% !important;
	}.no_pd {
		padding: 0px !important;
	}
	</style>
@endsection

@section('large_content')

<div class="row" id="return-exchange-page">
	<div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Return Exchange <span id="total-counter"></span></h2>
    </div>
    <br>
    <div class="col-lg-12 margin-tb">
    	<div class="row" style="margin-bottom:0px;">
	    	<div class="col-md-12">
		    	<div class="h" style="margin-bottom:0px;">
		    		<form class="form-inline return-exchange-handler" method="post">
					  <div class="row">
				  		<div class="col pr-0">
				  			<div class="form-group form-group-extended">
							    <!--<label for="from">Customer Name:</label>-->
							    <?php echo Form::text("customer_name",request("customer_name"),["class"=> "form-control","placeholder" => "Enter Customer Name"]) ?>
						  	</div>
						    <div class="form-group form-group-extended">
							    <!--<label for="from">Customer Email:</label>-->
							    <?php echo Form::text("customer_email",request("customer_email"),["class"=> "form-control","placeholder" => "Enter Customer Email"]) ?>
						  	</div>
							<div class="form-group form-group-extended">
							    <!--<label for="from">Customer Id:</label>-->
							    <?php echo Form::text("customer_id",request("customer_id"),["class"=> "form-control","placeholder" => "Enter Customer Id"]) ?>
						  	</div>
							<div class="form-group form-group-extended">
							    <!--<label for="from">Order Id:</label>-->
							    <?php echo Form::text("order_id",request("order_id"),["class"=> "form-control","placeholder" => "Enter Order Id"]) ?>
						  	</div>
						  	<div class="form-group form-group-extended">
							    <!--<label for="from">Product:</label>-->
							    <?php echo Form::text("product",request("product"),["class"=> "form-control","placeholder" => "Enter product sku/id/name"]) ?>
						  	</div>
							<div class="form-group form-group-extended">
							    <!--<label for="from">Product:</label>-->
							    <?php echo Form::text("website",request("website"),["class"=> "form-control","placeholder" => "Website"]) ?>
						  	</div>
				  			<div class="form-group form-group-extended">
							    <!--<label for="action">Status:</label>-->
							    <?php /*?><?php echo Form::select("status",\App\ReturnExchange::STATUS,request("limti"),[
							    	"class" => "form-control select2",
							    	"placeholder" => "-- Select Status --"
							    ]) ?><?php */?>
								<?php echo Form::select("status",\App\ReturnExchangeStatus::pluck("status_name","id")->toArray(),request("limti"),[
							    	"class" => "form-control",// select2
							    	"placeholder" => "-- Select Status --"
							    ]) ?>
						  	</div>
						  	<div class="form-group form-group-extended">
							    <!--<label for="action">Type:</label>-->
							    <?php echo Form::select("type",[
                                    "refund" => "Refund", 
                                    "exchange" => "Exchange",
                                    "buyback" => "Buyback",
                                    "return" => "Return",
                                    "cancellation" => "Cancellation"
                                ],request("limti"),[
							    	"class" => "form-control",//select2
							    	"placeholder" => "-- Select Type --"
							    ]) ?>
						  	</div>
						  	<div class="form-group form-group-extended">
							    <!--<label for="action">Number of records:</label>-->
							    <?php echo Form::select("limit",[10 => "10", 20 => "20", 30 => "30" , 50 => "50", 100 => "100" , 500 => "500" , 1000 => "1000"],request("limti"),[
									"class" => "form-control recCount",//select2
									"placeholder" => "Number of records"
									]) ?>
						  	</div>
							  <div class="form-group form-group-extended-fifteen">
							  <input style="width: 100%;" placeholder="Est Return / Exch. date" value="" type="text" class="form-control search-est-return-exch-date" name="est_completion_date">
						  	</div>
						  	<div class="form-group form-group-extended-four">
						  		<!--<label for="button">&nbsp;</label>-->
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
		<div class="row">
			<div class="col-md-12 mt-2 mb-2">
				<div class="pull-right">
				  <a href="#" class="btn btn-xs btn-secondary delete-orders" id="bulk_delete">
						Delete
				  </a>
				  <a href="#" class="btn btn-xs update-customer btn-secondary" id="bulk_update">
						Update
				  </a>
				  <a href="#" class="btn btn-xs update-customer btn-secondary" id="create_status">
						Create Status
				  </a>
				  <a href="#" class="btn btn-xs update-customer btn-secondary" id="create_refund">
						Create Refund
				  </a>
				</div>
			</div>
		</div>
		<!-- a -->
		<div class="margin-tb infinite-scroll" id="page-view-result">

		</div>
	</div>
</div>
<div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
          50% 50% no-repeat;display:none;">
</div>
<div class="common-modal modal" role="dialog">
  	<div class="modal-dialog modal-lg" role="document">
  	</div>	
</div>

@include("return-exchange.templates.list-template")
@include("return-exchange.templates.modal-emailToCustomer")
@include("return-exchange.templates.modal-createstatus")
@include("return-exchange.templates.modal-productDetails")
@include("return-exchange.templates.create-refund")
@include("return-exchange.templates.update-refund-modal")

@endsection

@section('scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<script type="text/javascript" src="/js/jsrender.min.js"></script>
	<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
	<script src="/js/jquery-ui.js"></script>
	<script type="text/javascript" src="/js/common-helper.js"></script>
	<script type="text/javascript" src="/js/return-exchange.js"></script>
	<script type="text/javascript">
		msQueue.init({
			bodyView : $("#return-exchange-page"),
			baseUrl : "<?php echo url("/"); ?>"
		});
		$('#date_of_request').datetimepicker({
		format: 'YYYY-MM-DD HH:mm'
		});

		$('#date_of_dispatched').datetimepicker({
			format: 'YYYY-MM-DD HH:mm'
		});

		$('.search-est-return-exch-date').datetimepicker({
			format: 'YYYY-MM-DD'
		});
		
		$(document).ready(function () {
		// $(".estimate-date").each(function() {
        //         $(this).datetimepicker({
		// 			format: 'YYYY-MM-DD'
		// 		});
		// });

		$('body').on('focus',".estimate-date", function(){
        		$(this).datetimepicker({
					format: 'YYYY-MM-DD'
				});
    	});
	});

	    $(document).on('click', '.expand-row', function () {
			var id = $(this).data('id');
			console.log(id);
            var full = '.expand-row .td-full-container-'+id;
            var mini ='.expand-row .td-mini-container-'+id;
            $(full).toggleClass('hidden');
            $(mini).toggleClass('hidden');
        });


		$(document).on('click', '#dispatch_date', function (e) {
		if ($(this).prop('checked')) {
			$('#additional-fields').show();
		} else {
			$('#additional-fields').hide();
		}
    	});
		$(document).on('click', '.send-email-to-customer', function () {
            $('#emailToCustomerModal').find('form').find('input[name="customer_id"]').val($(this).data('id'));
            $('#emailToCustomerModal').modal("show");
        });
				
		$(document).on('change', '.recCountproduct', function () {
            $(this).parent().closest("form").submit();
        });
		
		$(document).on('submit', '#customerUpdateForm', function (e) {
			e.preventDefault();
			var data = $(this).serializeArray();
			$.ajax({
				url: "{{route('return-exchange.updateCusromer')}}",
				type: 'POST',
				data: data,
				success: function (response) {
					toastr['success']('Successful', 'success');
					$('#emailToCustomerModal').modal('hide');
					$("#customerUpdateForm").trigger("reset");
					$("tr").find('.select-id-input').each(function () {
					  if ($(this).prop("checked") == true) {
						$(this).prop("checked", false);
					  }
					});
					window.location.reload();
				},
				error: function () {
					alert('There was error loading priority task list data');
				}
			});
		});


		$(document).on('submit', '#createRefundForm', function (e) {
			e.preventDefault();
			var data = $(this).serializeArray();
			$.ajax({
				url: "{{route('return-exchange.createRefund')}}",
				type: 'POST',
				data: data,
				success: function (response) {
					toastr['success'](response.message, 'success');
					$('#createRefundModal').modal('hide');
					$("#createRefundForm").trigger("reset");
					$("tr").find('.select-id-input').each(function () {
					  if ($(this).prop("checked") == true) {
						$(this).prop("checked", false);
					  }
					});
					window.location.reload();
				},
				error: function (error) {
					toastr['error'](error.responseJSON.message, 'error');
				}
			});
		});


		$(document).on('submit', '#updateRefundForm', function (e) {
			e.preventDefault();
			var data = $(this).serializeArray();
			$.ajax({
				url: "{{route('return-exchange.updateRefund')}}",
				type: 'POST',
				data: data,
				success: function (response) {
					toastr['success'](response.message, 'success');
					$('#updateRefundModal').modal('hide');
					$("#updateRefundForm").trigger("reset");
					$("tr").find('.select-id-input').each(function () {
					  if ($(this).prop("checked") == true) {
						$(this).prop("checked", false);
					  }
					});
					// window.location.reload();
				},
				error: function (error) {
					toastr['error'](error.responseJSON.message, 'error');
				}
			});
		});
		
		$(document).on('submit', '#createStatusForm', function (e) {
			e.preventDefault();
			var data = $(this).serializeArray();
			$.ajax({
				url: "{{route('return-exchange.createStatus')}}",
				type: 'POST',
				data: data,
				success: function (response) {
					toastr['success']('Successful', 'success');
					$('#createstatusModal').modal('hide');
					window.location.reload();
				},
				error: function () {
					alert('There was error loading priority task list data');
				}
			});
		});

		$(document).on('click', '.expand-row-msg', function () {
            var name = $(this).data('name');
			var id = $(this).data('id');
            var full = '.expand-row-msg .show-short-'+name+'-'+id;
            var mini ='.expand-row-msg .show-full-'+name+'-'+id;
            $(full).toggleClass('hidden');
            $(mini).toggleClass('hidden');
		});
		
		$(document).on('click', '.estimate-date-submit', function () {
			let exchange_id = $(this).data('id');
			let estimate_date = $("#estimate_date_" + exchange_id).val();
            $.ajax({
				url: "{{route('return-exchange.update-estimated-date')}}",
				type: 'POST',
                data: {
                    estimate_date : estimate_date,
					exchange_id: exchange_id,
					_token: "{{csrf_token()}}"
                },
                success: function (response) {
                    toastr["success"]("Estimated Date updated successfully!", "Message");
                }
            });

        });
		$(document).on('click','.addnewreplybtn',function(e){
          e.preventDefault();
          var replybox  = $(this).parentsUntil('#customerUpdateForm').find('.addnewreply');
          var selectreplybox = $(this).parentsUntil('#customerUpdateForm').find('.quickreply');
          var reply = $(this).parentsUntil('#customerUpdateForm').find('.addnewreply').val();
          if(!reply){
            alert('please add reply to input box !');
            return false;
          }
          $.ajax({
                type: "POST",
                url: "{{route('returnexchange.addNewReply')}}",
                data: {reply:reply},
                headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType:"json",
                beforeSend:function(data){
                  $('.ajax-loader').show();
                }
            }).done(function (response) {
              $('.ajax-loader').hide();
              if(response.status==200){
                replybox.val('');
                selectreplybox.html(response.html);
              }
            }).fail(function (response) {
              $('.ajax-loader').hide();
                console.log(response);
            });
          
        })
        $('.quickreply').on('change',function(){
          var reply = $(this).find('option:selected').text();
          var replyval = $(this).find('option:selected').attr('value');
          if(replyval!=''){
            $(this).parentsUntil('#customerUpdateForm').find('textarea[name="customer_message"]').val(reply);
          }else{
            $(this).parentsUntil('#customerUpdateForm').find('textarea[name="customer_message"]').val('');
          }
        });
		
	</script>
@endsection


