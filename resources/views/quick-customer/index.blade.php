@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', $title)

@section('large_content')
@section('link-css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
<link href="/css/bootstrap-toggle.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/css/dialog-node-editor.css">
@endsection
<style type="text/css">
	.preview-category input.form-control {
	  width: auto;
	}
	.daterangepicker .ranges li.active {
		background-color : #08c !important;
	}
    .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover 
    {
        background-color :  #fff ;
        border-color : #6c757d;
    }
    .page-item.active .page-link {
        background-color :  #6c757d ;
        border-color : #6c757d;
    }
    .pagination>li>a, .pagination>li>span {
        color: #6c757d;
    }
    .pagination>li>a:focus, .pagination>li>a:hover, .pagination>li>span:focus, .pagination>li>span:hover {
        color: #6c757d;   
    }

    .pagination>li>a, .pagination>li>span {
        color: #6c757d;   
    }

    .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
        color: #6c757d;   
    }

</style>

<div class="row" id="common-page-layout">
	<div class="col-lg-12 margin-tb">
        <h2 class="page-heading">{{$title}} <span class="count-text"></span></h2>
    </div>
    <br>
    @if(session()->has('success'))
	    <div class="col-lg-12 alert alert-success">
	        {{ session()->get('success') }}
	    </div>
	@endif
	@if(session()->has('error'))
	    <div class="col-lg-12 alert alert-danger">
	        {{ session()->get('error') }}
	    </div>
	@endif
    <div class="col-lg-12 margin-tb">
    	<div class="row">
	    	<div class="col col-md-3">
		    	<div class="row">
	    			 <button class="btn btn-secondary btn-add-whatsapp-list">+ Whatsapp List</button>
				 </div>
		    </div>
		    <div class="col">
		    	<div class="h">
		    		<form class="form-inline message-search-handler mb-2 fr" method="post" style="float: right;">
					  <div class="row">
				  			<div class="form-group" style="margin-left: 2px">
							    <label for="customer_id">Search Customer ID:</label>
							    <?php echo Form::select("customer_id",[],request("customer_id"),["class"=> "form-control customer-id-select","placeholder" => "Enter customer id","style" => "width:350px;"]) ?>
						  	</div>
						  	<div class="form-group" style="margin-left: 2px">
							    <label for="customer_name">Search Name:</label>
							    <?php echo Form::text("customer_name",request("customer_name"),["class"=> "form-control","placeholder" => "Enter customer name"]) ?>
						  	</div>
						  	<div class="form-group" style="margin-left: 2px">
							    <label for="status">Sort By:</label>
							    <?php echo Form::select("type",[
							    	"unread" => "Unread",
							    	"last_communicated" => "Last Communicated",
							    	"last_received" => "Last Received",
							    ],request("type","last_received"),["class"=> "form-control","placeholder" => "Type"]) ?>
						  	</div>

                            <div class="form-group" style="margin-left: 2px">
                                <label for="status">Sort By:</label>
                                <?php echo Form::select("next_action",$nextActionList,request("next_action"),["class"=> "form-control","placeholder" => "Select Next Action"]) ?>
                            </div>

                            
                            <div class="form-group" style="margin-left: 2px">
                                <label for="status">DND:</label>
                                <select class="form-control" name="do_not_disturb">
                                    <option value="" {{(request()->get('do_not_disturb')=='')?'selected':''}}>DND Status: ALL</option>
                                    <option value="0" {{(request()->get('do_not_disturb')=='0')?'selected':''}}>Without DND</option>
                                    <option value="1" {{(request()->get('do_not_disturb')=='1')?'selected':''}}>With DND</option>
                                 </select>
                             </div>
                             <div class="form-group" style="margin-left: 2px">
                                <label for="status">Page:</label>
                                <?php echo Form::text("page",request("page"),["class"=> "form-control","placeholder" => "Enter page no"]) ?>
                             </div>
						  	<div class="form-group">
						  		<label for="button">&nbsp;</label>
						  		<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-search-action">
						  			<img src="/images/search.png" style="cursor: default;">
						  		</button>
						  	</div>
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
<div id="task_statistics" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header">
                    <h2>Task statistics</h2>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="task_statistics_content">
                    
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="chat-list-history" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Communication</h4>
                <input type="text" name="search_chat_pop"  class="form-control search_chat_pop" placeholder="Search Message" style="width: 200px;">
                <input type="text" name="search_chat_pop_time"  class="form-control search_chat_pop_time" placeholder="Search Time" style="width: 200px;">
            </div>
            <div class="modal-body" style="background-color: #999999;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="addPhrases" class="modal fade" role="dialog">
    <div class="modal-dialog <?php echo (!empty($type) && $type = 'scrap') ? 'modal-lg' : ''  ?>">

        <!-- Modal conten1t-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Intent</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <form method="post" action="<?php echo route('chatbot.question.saveAjax'); ?>" id="add-phrases">
                    {{csrf_field()}}

                    <div class="form-group">
<?php echo Form::select("group_id",[],null,["class" => "form-control select-phrase-group", "id" => "select-phrase-group-box" , "style"=> "width:100%", "placeholder" => "Choose Existing"]); ?>
{{--                        <select class="multiselect-2" name="group" multiple data-placeholder="Select Group">--}}
{{--                            @foreach($groups as $group)--}}
{{--                                <option value="{{ $group->id }}">@if($group->name != null) {{ $group->name }} @else {{ $group->group }}@endif</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
                    </div>

                    <div class="form-group">
                        <?php echo Form::select("category_id",[],null,["class" => "form-control select-phrase-category", "id" => "select-phrase-category" , "style"=> "width:100%", "placeholder" => "Choose Category"]); ?>
                    </div>

                    <div class="form-group">
                        <?php echo Form::text("question",null,["class" => "form-control question", "placeholder" => "Enter User Intent"]); ?>
                    </div>
                    <div class="form-group">
                        <?php echo Form::text("suggested_reply",null,["class" => "form-control suggested_reply", "placeholder" => "Enter Suggested reply"]); ?>
                    </div>
                    <div class="form-group">
                        <select name="erp_or_watson" id="" class="form-control">
                            <option value="">Push to</option>
                            <option value="watson">Watson</option>
                            <option value="erp">Erp</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-secondary btn-block mt-2" id="add-phrases-btn">Add</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="leaf-editor-model" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save-dialog-btn">Save changes</button>
            </div>
        </div>
    </div>
</div>
<?php include_once(app_path()."/../Modules/ChatBot/Resources/views/dialog/includes/template.php"); ?>

@include("quick-customer.templates.list-template")
@include("partials.customer-new-ticket")
@include('customers.partials.modal-category-brand')
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/common-helper.js"></script>
<script type="text/javascript" src="/js/site-helper.js"></script>
<script type="text/javascript" src="/js/quick-customer.js"></script>
<script src="/js/bootstrap-toggle.min.js"></script>
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/dialog-build.js"></script>

<script type="text/javascript">
	page.init({
		bodyView : $("#common-page-layout"),
		baseUrl : "<?php echo url("/"); ?>"
	});
</script>

<script type="text/javascript">
	
    $('.select-multiple').select2({width: '100%'});
	$(document).on("click",".add_next_action",function() {
        siteHelpers.addNextAction($(this));
    });

    $(document).on("click",".add_next_action_btn",function() {
        siteHelpers.addNextAction($(this));
    });

    $(document).on("click",".delete_next_action",function() {
        siteHelpers.deleteNextAction($(this));
    });

    $(document).on("change",".next_action",function() {
        siteHelpers.changeNextAction($(this));
    });

    $(document).on('submit', "#send_message", function (e) {
        e.preventDefault();
        siteHelpers.erpLeadsSendMessage();
    });

    $(document).on('click', '.send-message', function () {
        siteHelpers.sendMessage($(this));
    });

    $(document).on('click', '.do_not_disturb', function() {
        var id = $(this).data('id');
        var thiss = $(this);
        $.ajax({
            type: "POST",
            url: "/customer/" + id + '/updateDND',
            data: {
                _token: "{{ csrf_token() }}",
                // do_not_disturb: option
            },
            beforeSend: function() {
                $(thiss).text('DND...');
            }
        }).done(function(response) {
          if (response.do_not_disturb == 1) {
            $(thiss).html('<img src="/images/do-not-disturb.png" />');
          } else {
            $(thiss).html('<img src="/images/do-disturb.png" />');
          }
        }).fail(function(response) {
          alert('Could not update DND status');
          console.log(response);
        })
   });

	$(document).on("click",".count-customer-tasks",function() {
        var $this = $(this);
        var customer_id = $(this).data("id");
        $.ajax({
            type: 'get',
            url: BASE_URL+'/erp-customer/task/count/'+customer_id,
            dataType : "json",
            success: function(data) {
                $("#task_statistics").modal("show");
                var table = '';
                table = table + '<div class="table-responsive"><table class="table table-bordered table-striped"><tr><th>Name</th><th>Pending</th><th>Completed</th></tr><tr><td>Devtask</td><td>'+data.taskStatistics.Devtask.pending+'</td><td>'+data.taskStatistics.Devtask.completed+'</td></tr><tr><td>Task</td><td>'+data.taskStatistics.Task.pending+'</td><td>'+data.taskStatistics.Task.completed+'</td></tr></table></div>';
                $("#task_statistics_content").html(table);
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    $(document).on("click",".create-customer-related-task",function() {
        var $this = $(this);
        var user_id = $(this).closest("tr").find(".ucfuid").val();
        var customer_id = $(this).data("id");

        var modalH = $("#quick-create-task");
            modalH.find(".task_asssigned_to").select2('destroy');
            modalH.find(".task_asssigned_to option[value='"+user_id+"']").prop('selected', true);
            modalH.find(".task_asssigned_to").select2({});
            modalH.find("#task_subject").val("Customer #"+customer_id+" : ");
            modalH.find("#hidden-category-id").remove();
            modalH.find("form").append('<input id="hidden-category-id" type="hidden" name="category_id" value="42" />');
            modalH.find("form").append('<input id="hidden-customer-id" type="hidden" name="customer_id" value="'+customer_id+'" />');
            modalH.modal("show");  
    });

</script>

<script>
    
    var siteHelpers = {
            verifyInstruction :  function(ele) {
                let instructionId = ele.attr('data-instructionId');
                var params = {
                    data : {id: instructionId,_token  : $('meta[name="csrf-token"]').attr('content')},
                    url: '/instruction/verify',
                    method : 'post',
                    dataType: "html"
                }
                siteHelpers.sendAjax(params,"afterVerifyInstrunction",ele);
            },
            afterVerifyInstrunction : function (ele) {
                toastr['success']('Instruction verified successfully', 'success');
                $(ele).html('Verified');
            },
            completeInstruction :  function(ele) {
                var params = {
                    data : {id: ele.data('id'), _token  : $('meta[name="csrf-token"]').attr('content')},
                    url: '/instruction/complete',
                    method : 'post',
                    beforeSend : function() {
                        ele.text('Loading');
                    },
                    doneAjax : function(response) {
                        ele.parent().append(moment(response.time).format('DD-MM HH:mm'));
                        ele.remove();
                    },
                }
                siteHelpers.sendAjax(params);
            },
            changeMessageStatus : function(ele) {
                var params = {
                    url: ele.data('url'),
                    dataType: "html"
                };
                siteHelpers.sendAjax(params,"afterChangeMessageStatus", ele);
            },
            afterChangeMessageStatus : function(ele) {
                ele.closest('tr').removeClass('text-danger');
                ele.closest('td').html('Read');
                ele.remove();
            },
            approveMessage : function(ele) {
                var params = {
                    method : 'post',
                    data : {messageId: ele.data('id'), _token  : $('meta[name="csrf-token"]').attr('content')},
                    url: "/whatsapp/approve/customer"
                };
                siteHelpers.sendAjax(params,"afterApproveMessage", ele);
            },
            afterApproveMessage : function(ele) {
                ele.parent().html('Approved');
                ele.closest('tr').removeClass('row-highlight');
            },
            changeLeadStatus :  function(ele) {
                var lead_id = ele.data('leadid');
                var params = {
                    method : 'post',
                    data : {status: ele.data('id'), _token  : $('meta[name="csrf-token"]').attr('content')},
                    url: "/leads/" + lead_id + "/changestatus",
                    dataType: "html"
                };
                siteHelpers.sendAjax(params,"afterChangeLeadStatus", ele);
            },
            afterChangeLeadStatus : function(ele) {
                ele.parent('div').children().each(function (index) {
                    $(this).removeClass('active-bullet-status');
                });

                ele.addClass('active-bullet-status');
            },
            changeOrderStatus :  function(ele) {
                var orderId = ele.data('orderid');
                var params = {
                    method : 'post',
                    data : {status: ele.attr('title'), _token  : $('meta[name="csrf-token"]').attr('content')},
                    url: "/order/" + orderId + "/changestatus",
                    dataType: "html"
                };
                siteHelpers.sendAjax(params,"afterChangeLeadStatus", ele);
            },
            afterChangeOrderStatus : function(ele) {
                toastr['success']('Status changed successfully!', 'Success');
                ele.siblings('.change-order-status').removeClass('active-bullet-status');
                ele.addClass('active-bullet-status');
                if (ele.attr('title') == 'Product shiped to Client') {
                    $('#tracking-wrapper-' + id).css({'display': 'block'});
                }
            },
            sendPdf : function(ele) {
                var selectedBox = ele.closest(".send_pdf_selectbox_box");
                var allPdfs = selectedBox.find(".send_pdf_selectbox").select2("val");
                    if(allPdfs.length > 0) {
                        var params = {
                            method : 'post',
                            data : {
                                _token : $('meta[name="csrf-token"]').attr('content'),
                                send_pdf: true,
                                customer_id : ele.data("customerid"),
                                images: JSON.stringify([allPdfs]),
                                status: 1,
                                json:1
                            },
                            url: "/attachImages/queue"
                        };
                        siteHelpers.sendAjax(params,"afterSendPdf", ele);
                    }
            },
            afterSendPdf : function(response) {
                var closestSelect = response.closest(".send_pdf_selectbox_box");
                    if(closestSelect.length > 0) {
                        var selectbox = closestSelect.find(".send_pdf_selectbox");
                        /*selectbox.val("");
                        selectbox.select2("val", "");*/
                    }
                toastr["success"]("Message sent successfully!", "Message");
            },
            sendGroup : function(ele, send_pdf) {
                $("#confirmPdf").modal("hide");
                var customerId = ele.data('customerid');
                var groupId = $('#group' + customerId).val();
                var params = {
                    method : 'post',
                    data : {
                        groupId: groupId,
                        customerId: customerId,
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        status: 1,
                        send_pdf: send_pdf
                    },
                    url: "/whatsapp/sendMessage/quicksell_group_send"
                };
                siteHelpers.sendAjax(params,"afterSendGroup", ele);
            },
            afterSendGroup : function(ele) {
                $('#group' + ele.data('customerid')).val('').trigger('change');
                toastr["success"]("Group Message sent successfully!", "Message");
            },
            quickCategoryAdd : function(ele) {
                var textBox = ele.closest("div").find(".quick_category");

                if (textBox.val() == "") {
                    alert("Please Enter Category!!");
                    return false;
                }

                var params = {
                    method : 'post',
                    data : {
                        _token : $('meta[name="csrf-token"]').attr('content'),
                        name : textBox.val()
                    },
                    url: "/add-reply-category"
                };
                siteHelpers.sendAjax(params,"afterQuickCategoryAdd");
            },
            afterQuickCategoryAdd : function(response) {
                $(".quick_category").val('');
                $(".quickCategory").append('<option value="[]" data-id="' + response.data.id + '">' + response.data.name + '</option>');
            },
            deleteQuickCategory : function(ele) {
                var quickCategory = ele.closest(".communication").find(".quickCategory");

                if (quickCategory.val() == "") {
                    alert("Please Select Category!!");
                    return false;
                }

                var quickCategoryId = quickCategory.children("option:selected").data('id');
                if (!confirm("Are sure you want to delete category?")) {
                    return false;
                }

                var params = {
                    method : 'post',
                    data : {
                        _token : $('meta[name="csrf-token"]').attr('content'),
                        id : quickCategoryId
                    },
                    url: "/destroy-reply-category"
                };
                siteHelpers.sendAjax(params,"pageReload");
            },
            deleteQuickComment : function(ele) {
                var quickComment = ele.closest(".communication").find(".quickComment");

                if (quickComment.val() == "") {
                    alert("Please Select Quick Comment!!");
                    return false;
                }

                var quickCommentId = quickComment.children("option:selected").data('id');
                if (!confirm("Are sure you want to delete comment?")) {
                    return false;
                }

                var params = {
                    method : 'DELETE',
                    data : {
                        _token : $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/reply/" + quickCommentId,
                };
                siteHelpers.sendAjax(params,"pageReload");
            },
            pageReload : function(response) {
                location.reload();
            },
            quickCommentAdd : function(ele) {
                var textBox = ele.closest("div").find(".quick_comment");
                var quickCategory = ele.closest(".communication").find(".quickCategory");

                if (textBox.val() == "") {
                    alert("Please Enter New Quick Comment!!");
                    return false;
                }

                if (quickCategory.val() == "") {
                    alert("Please Select Category!!");
                    return false;
                }

                var quickCategoryId = quickCategory.children("option:selected").data('id');

                var formData = new FormData();

                formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
                formData.append("reply", textBox.val());
                formData.append("category_id", quickCategoryId);
                formData.append("model", 'Approval Lead');

                var params = {
                    method : 'post',
                    data : formData,
                    url: "/reply"
                };
                siteHelpers.sendFormDataAjax(params,"afterQuickCommentAdd");
            },
            afterQuickCommentAdd : function(reply) {
                $(".quick_comment").val('');
                $('.quickComment').append($('<option>', {
                    value: reply,
                    text: reply
                }));
            },
            changeQuickCategory : function (ele) {
                if (ele.val() != "") {
                    var replies = JSON.parse(ele.val());
                    ele.closest(".communication").find('.quickComment').empty();
                    ele.closest(".communication").find('.quickComment').append($('<option>', {
                        value: '',
                        text: 'Quick Reply'
                    }));

                    replies.forEach(function (reply) {
                        ele.closest(".communication").find('.quickComment').append($('<option>', {
                            value: reply.reply,
                            text: reply.reply,
                            'data-id': reply.id
                        }));
                    });
                }
            },
            changeQuickComment : function (ele) {
                ele.closest('tr').find('.quick-message-field').html(ele.val());
                ele.closest('tr').find('.quick-message-field').val(ele.val());
            },
            leadsChart : function () {
                var params = {
                    url: '/erp-customer/lead-data?pageType='+pageType,
                };
                siteHelpers.sendAjax(params,"afterLeadsChart");
            },
            afterLeadsChart : function(datasets) {
                var leadsChart = $('#leadsChart');

                var leadsChartExample = new Chart(leadsChart, {
                    type: 'horizontalBar',
                    data: {
                        labels: [
                            'Status'
                        ],
                        datasets: datasets
                    },
                    options: {
                        scaleShowValues: true,
                        responsive: true,
                        scales: {
                            xAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontFamily: "'Open Sans Bold', sans-serif",
                                    fontSize: 11
                                },
                                stacked: true
                            }],
                            yAxes: [{
                                ticks: {
                                    fontFamily: "'Open Sans Bold', sans-serif",
                                    fontSize: 11
                                },
                                stacked: true
                            }]
                        },
                        tooltips: {
                            enabled: false
                        },
                        animation: {
                            onComplete: function () {
                                var chartInstance = this.chart;
                                var ctx = chartInstance.ctx;
                                ctx.textAlign = "left";
                                ctx.fillStyle = "#fff";
                                Chart.helpers.each(this.data.datasets.forEach(function (dataset, i) {
                                    var meta = chartInstance.controller.getDatasetMeta(i);
                                    Chart.helpers.each(meta.data.forEach(function (bar, index) {
                                        data = dataset.data[index];
                                        if (i == 0) {
                                            ctx.fillText(data, 50, bar._model.y + 4);
                                        } else {
                                            ctx.fillText(data, bar._model.x - 25, bar._model.y + 4);
                                        }
                                    }), this)
                                }), this);
                            }
                        },
                    }
                });
            },
            orderStatusChart : function () {
                var params = {
                    url: '/erp-customer/order-status-chart?pageType='+pageType,
                    dataType: "html"
                };
                siteHelpers.sendAjax(params,"afterOrderStatusChart");
            },
            afterOrderStatusChart : function (html) {
                $('.order-status-chart').html(html);
            },
            blockTwilio : function(ele) {
                var params = {
                    method : 'post',
                    data : {
                        _token : $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/erp-customer/block/"+ele.data('id'),
                    beforeSend : function() {
                        ele.text('Blocking...');
                    },
                    doneAjax : function(response) {
                        if (response.is_blocked == 1) {
                            ele.html('<img src="/images/blocked-twilio.png" />');
                        } else {
                            ele.html('<img src="/images/unblocked-twilio.png" />');
                        }
                    },
                };
                siteHelpers.sendAjax(params);
            },
            customerSearch : function(ele) {
                ele.select2({
                    tags: true,
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
                            return "<p> <b>Id:</b> " + customer.id + (customer.name ? " <b>Name:</b> " + customer.name : "") + (customer.phone ? " <b>Phone:</b> " + customer.phone : "") + "</p>";
                        }
                    },
                    templateSelection: (customer) => customer.text || customer.name,

                });
            },
            userSearch : function(ele) {
                ele.select2({
                    ajax: {
                        url: '/user-search',
                        dataType: 'json',
                        delay: 750,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                            };
                        },
                        processResults: function (data, params) {

                            params.page = params.page || 1;

                            return {
                                results: data,
                                pagination: {
                                    more: (params.page * 30) < data.total_count
                                }
                            };
                        },
                    },
                    placeholder: 'Search for User by Name',
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                    minimumInputLength: 2,
                    width: '100%',
                    templateResult: function (user) {
                        return user.name;

                    },
                    templateSelection: function (user) {
                        return user.name;
                    },

                });
            },
            productSearch : function (ele) {
                ele.select2({
                    ajax: {
                        url: '/productSearch/',
                        dataType: 'json',
                        delay: 750,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                            };
                        },
                        processResults: function (data, params) {

                            params.page = params.page || 1;

                            return {
                                results: data,
                                pagination: {
                                    more: (params.page * 30) < data.total_count
                                }
                            };
                        },
                    },
                    placeholder: 'Search for Product by id, Name, Sku',
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                    minimumInputLength: 2,
                    width: '100%',
                    templateResult: function (product) {
                        if (product.loading) {
                            return product.sku;
                        }

                        if (product.sku) {
                            return "<p> <b>Id:</b> " + product.id + (product.name ? " <b>Name:</b> " + product.name : "") + " <b>Sku:</b> " + product.sku + " </p>";
                        }

                    },
                    templateSelection: function (product) {
                        return product.text || product.name;
                    },

                });
            },
            loadCustomers : function (ele) {
                var first_customer = $('#first_customer').val();
                var second_customer = $('#second_customer').val();

                if (first_customer == second_customer) {
                    alert('You selected the same customers');

                    return;
                }
                var params = {
                    data : {
                        first_customer: first_customer,
                        second_customer: second_customer
                    },
                    url: "/customers-load",
                    beforeSend : function() {
                        ele.text('Loading...');
                    },
                    doneAjax : function(response) {
                        $('#first_customer_id').val(response.first_customer.id);
                        $('#second_customer_id').val(response.second_customer.id);

                        $('#first_customer_name').val(response.first_customer.name);
                        $('#first_customer_email').val(response.first_customer.email);
                        $('#first_customer_phone').val(response.first_customer.phone ? (response.first_customer.phone).replace(/[\s+]/g, '') : '');
                        $('#first_customer_instahandler').val(response.first_customer.instahandler);
                        $('#first_customer_rating').val(response.first_customer.rating);
                        $('#first_customer_address').val(response.first_customer.address);
                        $('#first_customer_city').val(response.first_customer.city);
                        $('#first_customer_country').val(response.first_customer.country);
                        $('#first_customer_pincode').val(response.first_customer.pincode);

                        $('#second_customer_name').val(response.second_customer.name);
                        $('#second_customer_email').val(response.second_customer.email);
                        $('#second_customer_phone').val(response.second_customer.phone ? (response.second_customer.phone).replace(/[\s+]/g, '') : '');
                        $('#second_customer_instahandler').val(response.second_customer.instahandler);
                        $('#second_customer_rating').val(response.second_customer.rating);
                        $('#second_customer_address').val(response.second_customer.address);
                        $('#second_customer_city').val(response.second_customer.city);
                        $('#second_customer_country').val(response.second_customer.country);
                        $('#second_customer_pincode').val(response.second_customer.pincode);

                        $('#customers-data').show();
                        $('#mergeButton').prop('disabled', false);

                        ele.text('Load Data');
                    },
                };
                siteHelpers.sendAjax(params);
            },
            createBroadcast : function (model_id) {
                var customers = [];
                $(".customer_message").each(function () {
                    if ($(this).prop("checked") == true) {
                        customers.push($(this).val());
                    }
                });

                if (all_customers.length != 0) {
                    customers = all_customers;
                }

                if (customers.length == 0) {
                    alert('Please select customer');
                    return false;
                }
                $("#"+model_id).modal("show");
            },
            erpLeadsSendMessage : function () {
                var customers = [];
                $(".customer_message").each(function () {
                    if ($(this).prop("checked") == true) {
                        customers.push($(this).val());
                    }
                });

                if (all_customers.length != 0) {
                    customers = all_customers;
                }

                if (customers.length == 0) {
                    alert('Please select customer');
                    return false;
                }

                if ($("#send_message").find("#message_to_all_field").val() == "") {
                    alert('Please type message ');
                    return false;
                }

                if ($("#send_message").find(".ddl-select-product").val() == "" && $("#send_message").find("#product_start_date").val() == "") {
                    alert('Please select product');
                    return false;
                }

                var params = {
                    method : 'post',
                    data : {
                        _token : $('meta[name="csrf-token"]').attr('content'),
                        products: $("#send_message").find(".ddl-select-product").val(),
                        sending_time: $("#send_message").find("#sending_time_field").val(),
                        message: $("#send_message").find("#message_to_all_field").val(),
                        product_start_date:$("#send_message").find("#product_start_date").val(),
                        product_end_date:$("#send_message").find("#product_end_date").val(),
                        customers: customers
                    },
                    url: "/erp-leads-send-message",
                    doneAjax : function(response) {
                        window.location.reload();
                    },
                };
                siteHelpers.sendAjax(params);
            },
            instructionStore : function(ele) {
                var customer_id = ele.closest('form').find('input[name="customer_id"]').val();
                var instruction = ele.closest('form').find('input[name="instruction"]').val();
                var category_id = ele.closest('form').find('input[name="category_id"]').val();
                var assigned_to = ele.closest('form').find('input[name="assigned_to"]').val();
                var params = {
                    method : 'post',
                    data : {
                        _token : $('meta[name="csrf-token"]').attr('content'),
                        customer_id: customer_id,
                        instruction: instruction,
                        category_id: category_id,
                        assigned_to: assigned_to,
                    },
                    url: ele.closest('form').attr('action')
                };
                siteHelpers.sendAjax(params);
            },
            updateBroadCastList : function (customerId, needtoShowModel) {
                var params = {
                    data : {
                        customer_id: customerId
                    },
                    url: "/customer/broadcast",
                    doneAjax : function(response) {
                        var html = "Sorry, There is no available broadcast";
                        if (response.code == 1) {
                            html = "";
                            if (response.data.length > 0) {
                                $.each(response.data, function (k, v) {
                                    html += '<button class="badge badge-default broadcast-list-rndr" data-customer-id="' + customerId + '" data-id="' + v.id + '">' + v.id + '</button>';
                                });
                            } else {
                                html = "Sorry, There is no available broadcast";
                            }
                        }
                        $("#broadcast-list").find(".modal-body").html(html);
                        if (needtoShowModel && typeof needtoShowModel != "undefined") {
                            $("#broadcast-list").modal("show");
                        }
                    },
                };
                siteHelpers.sendAjax(params);
            },
            broadcastListCreateLead : function(ele) {
                var $this = ele;

                var checkedProducts = $("#broadcast-list").find("input[name='selecte_products_lead[]']:checked");
                var checkedProdctsArr = [];
                if (checkedProducts.length > 0) {
                    $.each(checkedProducts, function (e, v) {
                        checkedProdctsArr += "," + $(v).val();
                    })
                }

                var selectionLead = $("#broadcast-list").find(".selection-broadcast-list").first();

                $("#broadcast-list-approval").find(".broadcast-list-approval-btn").data("customer-id", selectionLead.data("customer-id"));
                $("#broadcast-list-approval").modal("show");

                $(".broadcast-list-approval-btn").unbind().on("click", function () {
                    var $this = $(this);
                    var params = {
                        data : {
                            customer_id: $this.data("customer-id"),
                            product_to_be_run: checkedProdctsArr
                        },
                        url: "/customer/broadcast-send-price",
                        beforeSend : function() {
                            $this.html('Sending Request...');
                        },
                        doneAjax : function(response) {
                            $this.html('Yes');
                            $("#broadcast-list-approval").modal("hide");
                            $("#broadcast-list").modal("hide");
                        },
                    };
                    siteHelpers.sendAjax(params);
                });
            },
            sendInstock : function(ele) {
                var customer_id = ele.data('id');
                var params = {
                    method : 'post',
                    data : {
                        _token : $('meta[name="csrf-token"]').attr('content'),
                        customer_id: customer_id
                    },
                    url: "/customer/send/instock",
                    beforeSend : function() {
                        ele.text('Sending...');
                    },
                    doneAjax : function(response) {
                        ele.text('Send In Stock');
                    },
                };
                siteHelpers.sendAjax(params);
            },
            sendScraped : function (ele) {
                var formData = $('#categoryBrandModal').find('form').serialize();
                var thiss = ele;

                if (!ele.is(':disabled')) {
                    var params = {
                        method : 'post',
                        dataType: "html",
                        data : formData,
                        url: "/customer/sendScraped/images",
                        beforeSend : function() {
                            ele.text('Sending...');
                            ele.attr('disabled', true);
                        },
                        doneAjax : function(response) {
                            $('#categoryBrandModal').find('.close').click();
                            ele.text('Send');
                            ele.attr('disabled', false);
                        },
                    };
                    siteHelpers.sendAjax(params);
                }
            },
            changeStatus : function (ele) {
                var status = ele.val();
                if (ele.hasClass('order_status')) {
                    var id = ele.data('orderid');
                    var url = '/order/' + id + '/changestatus';
                } else {
                    var id = ele.data('leadid');
                    var url = '/erp-leads/' + id + '/changestatus';
                }
                var params = {
                    method : 'POST',
                    data : {
                        _token : $('meta[name="csrf-token"]').attr('content'),
                        status: status
                    },
                    dataType: "html",
                    url: url,
                    doneAjax : function(response) {
                        if (ele.hasClass('order_status') && status == 'Product shiped to Client') {
                            $('#tracking-wrapper-' + id).css({'display': 'block'});
                        }

                        ele.siblings('.change_status_message').fadeIn(400);

                        setTimeout(function () {
                            ele.siblings('.change_status_message').fadeOut(400);
                        }, 2000);
                    },
                };
                siteHelpers.sendAjax(params);
            },
            sendMessage : function(ele){
                var message = ele.siblings('textarea').val();
                var customer_id = ele.data('customerid');
                if (message.length > 0 && !ele.is(':disabled')) {

                    var data = new FormData();

                    data.append("_token", $('meta[name="csrf-token"]').attr('content'));
                    data.append("customer_id", customer_id);
                    data.append("message", message);
                    data.append("status", 1);

                    var params = {
                        method : 'post',
                        data : data,
                        url: '/whatsapp/sendMessage/customer',
                        beforeSend : function() {
                            ele.attr('disabled', true);
                        },
                        doneAjax : function(response) {
                            ele.siblings('textarea').val('');
                            ele.attr('disabled', false);
                        }
                    };
                    siteHelpers.sendFormDataAjax(params);
                }
            },
            sendMessageMaltiCustomer : function(ele){
                var customers = [];
                $(".customer_message").each(function () {
                    if ($(this).prop("checked") == true) {
                        customers.push($(this).val());
                    }
                });

                if (all_customers.length != 0) {
                    customers = all_customers;
                }

                if (customers.length == 0) {
                    alert('Please select customer');
                    return false;
                }

                var form = ele.closest('form');

                var message = form.find('.quick-message-field').val();

                if (!ele.is(':disabled')) {

                    var data = new FormData();

                    data.append("_token", $('meta[name="csrf-token"]').attr('content'));
                    data.append("customers_id", customers.join());
                    data.append("message", message);
                    data.append("status", 1);
                    data.append("brand", form.find("#product-brand").val());
                    data.append("category", form.find("#category").val());
                    data.append("number_of_products", form.find("#number_of_products").val());
                    data.append("quick_sell_groups", form.find("#product-quick-sell-groups").val());


                    var params = {
                        method : 'post',
                        data : data,
                        url: '/selected_customer/sendMessage',
                        beforeSend : function() {
                            ele.attr('disabled', true);
                        },
                        doneAjax : function(response) {
                            ele.attr('disabled', false);
                            $("#sendCustomerMessage").modal("hide");
                        }
                    };
                    siteHelpers.sendFormDataAjax(params);
                }
            },
            flagCustomer : function (ele) {
                var customer_id = ele.data('id');
                var params = {
                    method : 'post',
                    data : {
                        _token : $('meta[name="csrf-token"]').attr('content'),
                        customer_id: customer_id
                    },
                    url: "/customer/flag",
                    beforeSend : function() {
                        ele.text('Flagging...');
                    },
                    doneAjax : function(response) {
                        if (response.is_flagged == 1) {
                            ele.html('<img src="/images/flagged.png" />');
                        } else {
                            ele.html('<img src="/images/unflagged.png" />');
                        }
                    },
                };
                siteHelpers.sendAjax(params);
            },
            addInWhatsappList : function (ele) {
                var customer_id = ele.data('id');
                var params = {
                    method : 'post',
                    data : {
                        _token : $('meta[name="csrf-token"]').attr('content'),
                        customer_id: customer_id
                    },
                    url: "/customer/in-w-list",
                    beforeSend : function() {
                        ele.text('Sending...');
                    },
                    doneAjax : function(response) {
                        if (response.in_w_list == 1) {
                            ele.html('<img src="/images/completed-green.png" />');
                        } else {
                            ele.html('<img src="/images/completed.png" />');
                        }
                    },
                };
                siteHelpers.sendAjax(params);
            },
            priorityCustomer : function (ele) {
                var customer_id = ele.data('id');
                var params = {
                    method : 'post',
                    data : {
                        _token : $('meta[name="csrf-token"]').attr('content'),
                        customer_id: customer_id
                    },
                    url: "/customer/prioritize",
                    beforeSend : function() {
                        ele.text('Prioritizing...');
                    },
                    doneAjax : function(response) {
                        if (response.is_priority == 1) {
                            ele.html('<img src="/images/customer-priority.png" />');
                        } else {
                            ele.html('<img src="/images/customer-not-priority.png" />');
                        }
                    },
                };
                siteHelpers.sendAjax(params);
            },
            storeReminder : function (ele) {
                var reminderModal = $('#reminderModal');
                var customerIdToRemind = reminderModal.find('input[name="customer_id"]').val();
                var frequency = reminderModal.find('#frequency').val();
                var message = reminderModal.find('#reminder_message').val();
                var reminder_from = reminderModal.find('#reminder_from').val();
                var reminder_last_reply = (reminderModal.find('#reminder_last_reply').is(":checked")) ? 1 : 0;
                var params = {
                    method : 'post',
                    data : {
                        _token : $('meta[name="csrf-token"]').attr('content'),
                        customer_id: customerIdToRemind,
                        frequency: frequency,
                        message: message,
                        reminder_from: reminder_from,
                        reminder_last_reply : reminder_last_reply
                    },
                    url: "/customer/reminder",
                    doneAjax : function(response) {
                        $(".set-reminder[data-id='" + customerIdToRemind + "']").data('frequency', frequency);
                        $(".set-reminder[data-id='" + customerIdToRemind + "']").data('reminder_message', message);
                        toastr['success']('Reminder updated successfully!');
                        $("#reminderModal").modal("hide");
                    },
                };
                siteHelpers.sendAjax(params);
            },
            sendContactUser : function (ele) {
                var $form = $("#send-contact-to-user");
                var params = {
                    method : 'post',
                    data : $form.serialize(),
                    url: "/customer/send-contact-details",
                    beforeSend : function() {
                        ele.text('Sending message...');
                    },
                    doneAjax : function(response) {
                        ele.html('<img style="width: 17px;" src="/images/filled-sent.png">');
                        $("#sendContacts").modal("hide");
                    },
                };
                siteHelpers.sendAjax(params);
            },
            approveMessageSession : function (ele) {
                var params = {
                    method : 'post',
                    data : {_token : $('meta[name="csrf-token"]').attr('content'), text : ele.text()},
                    url: "/erp-customer/approve-message-session",
                    doneAjax : function(response) {
                        ele.text(response.text);
                        ele.removeClass('btn-success').removeClass('btn-default').addClass(response.class);
                    },
                };
                siteHelpers.sendAjax(params);
            },
            autoRefreshColumn : function() {
                var params = {
                    method : 'post',
                    data : {_token : $('meta[name="csrf-token"]').attr('content'), customers_id : $('input[name="paginate_customer_ids"]').val(),
                        type : "{{ request()->get('type','any') }}"
                    },
                    url: "/erp-customer/auto-refresh-column",
                    doneAjax : function(response) {
                        $.each(response, function(k,customer) {
                            $.each(customer, function(k,td_data) {
                                var needaBox = false;
                                if(typeof td_data.last_message != "undefined" && typeof td_data.last_message.full_message != "undefined") {
                                        var box = $(td_data.class).find(".message-chat-txt");
                                        if(box.length > 0 ) {
                                            box.attr("data-content",td_data.last_message.full_message);
                                            $(td_data.class).find(".add-chat-phrases").attr("data-message",td_data.last_message.full_message);
                                            box.html(td_data.last_message.short_message);
                                        }else{
                                            $(td_data.class).html(td_data.html);
                                        }
                                }else{
                                    $(td_data.class).html(td_data.html);
                                }
                            });
                        });
                        $('[data-toggle="popover"]').popover();
                        setTimeout(function(){
                            if(!isTextMessageFocused) siteHelpers.autoRefreshColumn();
                        }, 10000);
                    },
                };
                siteHelpers.sendAjax(params);
            },
            selectAllCustomer : function(ele){
                if (ele.text() == 'Unselect All Customers') {
                    all_customers = [];
                    $(".customer_message").prop("checked", false);
                    ele.text('Select All Customers');
                    return false;
                }

                var params = {
                    method : 'get',
                    data : $('#search_frm').serialize(),
                    url: "/erp-customer/customer-ids?get_customer_ids=1&pageType="+pageType,
                    beforeSend : function() {
                        ele.text('Select...');
                        ele.attr('disabled', true);
                    },
                    doneAjax : function(response) {
                        $(".customer_message").prop("checked", true);
                        all_customers = response;
                        ele.text('Unselect All Customers');
                        ele.attr('disabled', false);
                    },
                };
                siteHelpers.sendAjax(params);
            },
            updatedShoeSize : function(ele) {
                var params = {
                    method : 'post',
                    data : {_token : $('meta[name="csrf-token"]').attr('content'), shoe_size : ele.val()},
                    url: "/erp-customer/"+ele.data('id')+"/update",
                    beforeSend : function() {
                        ele.attr('disabled', true);
                    },
                    doneAjax : function(response) {
                        ele.attr('disabled', false);
                    },
                };
                siteHelpers.sendAjax(params);
            },
            addErpLead : function (ele, thiss) {
                var url = ele.attr('action');

                if (ele.find('.multi_brand').val() == "") {
                    alert('Please Select Brand');
                    return false;
                }

                if (ele.find('input[name="category_id"]').val() == "") {
                    alert('Please Select Category');
                    return false;
                }

                if (ele.find('input[name="lead_status_id"]').val() == "") {
                    alert('Please Select Status');
                    return false;
                }

                var formData = new FormData(thiss);
                var params = {
                    method : 'POST',
                    data : formData,
                    url: url,
                    doneAjax : function(response) {
                        toastr['success']('Lead add successfully!');
                        $('#add_lead').modal('hide');
                        if ($('#add_lead').find('input[name="product_id"]').length > 0 && $('#add_lead').find('input[name="product_id"]').val()) {
                            var dataSending = $('#add_lead').find('input[name="product_id"]').data('object');
                            if (typeof dataSending != 'object'){
                                dataSending = {};
                            }

                            var params = {
                                method : 'post',
                                data : $.extend({
                                    _token:  $('meta[name="csrf-token"]').attr('content'),
                                    customer_id: $('#add_lead').find('input[name="customer_id"]').val(),
                                    selected_product: [$('#add_lead').find('input[name="product_id"]').val()],
                                    auto_approve: 1
                                },dataSending),
                                url: "/leads/sendPrices",
                            };
                            siteHelpers.sendAjax(params);
                        }
                    }
                };
                siteHelpers.sendFormDataAjax(params);
            },
            addNextAction : function(ele) {
                var textBox = ele.closest(".row_next_action").find(".add_next_action_txt");

                if (textBox.val() == "") {
                    alert("Please Enter New Next Action!!");
                    return false;
                }

                var params = {
                    method : 'post',
                    data : {
                        _token : $('meta[name="csrf-token"]').attr('content'),
                        name : textBox.val()
                    },
                    doneAjax : function(response) {
                        toastr['success']('Successfully add!');
                        textBox.val('');
                        $(".next_action").append('<option value="'+response.id+'">' + response.name + '</option>');
                    },
                    url: "/erp-customer/add-next-actions"
                };
                siteHelpers.sendAjax(params);
            },
            deleteNextAction : function(ele) {
                var nextAction = ele.closest(".row_next_action").find(".next_action");

                if (nextAction.val() == "") {
                    alert("Please Select Next Action!!");
                    return false;
                }

                var nextActionId = nextAction.val();
                if (!confirm("Are sure you want to delete Next Action?")) {
                    return false;
                }

                var params = {
                    method : 'post',
                    data : {
                        _token : $('meta[name="csrf-token"]').attr('content'),
                        id : nextActionId
                    },
                    url: "/erp-customer/destroy-next-actions"
                };
                siteHelpers.sendAjax(params,"pageReload");
            },
            changeNextAction :  function(ele) {
                var params = {
                    method : 'post',
                    data : {
                        customer_next_action_id: ele.val(),
                        _token  : $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/erp-customer/"+ele.data('id')+"/update",
                    doneAjax : function(response) {
                        toastr['success']('Next Action changed successfully!', 'Success');
                    },
                };
                siteHelpers.sendAjax(params);
            },
            addOrderFrm : function (ele, thiss) {
                var url = ele.attr('action');
                var formData = new FormData(thiss);
                var params = {
                    method : 'POST',
                    data : formData,
                    url: url,
                    doneAjax : function(response) {
                        toastr['success']('Order add successfully!');
                        if ($('#add_order').find('input[name="selected_product[]"]').length > 0 && $('#add_order').find('input[name="selected_product[]"]').val()) {
                            var params = {
                                method : 'post',
                                data : {
                                    _token:  $('meta[name="csrf-token"]').attr('content'),
                                    customer_id: $('#add_order').find('input[name="customer_id"]').val(),
                                    order_id: response.order.id,
                                    selected_product: [$('#add_order').find('input[name="selected_product[]"]').val()]
                                },
                                url: "/order/send/Delivery",
                            };
                            siteHelpers.sendAjax(params);
                        }
                        $('#add_order').modal('hide');
                    }
                };
                siteHelpers.sendFormDataAjax(params);
            }
        };

    $.extend(siteHelpers, common);

    $(document).on('click', '.quick_category_add', function () {
        siteHelpers.quickCategoryAdd($(this));
    });

    $(document).on('click', '.delete_category', function () {
        siteHelpers.deleteQuickCategory($(this));
    });

    $(document).on('click', '.delete_quick_comment', function () {
        siteHelpers.deleteQuickComment($(this));
    });

    $(document).on('click', '.quick_comment_add', function () {
        siteHelpers.quickCommentAdd($(this));
    });

    $(document).on('change', '.quickCategory', function () {
        siteHelpers.changeQuickCategory($(this));
    });

    $(document).on('change', '.quickComment', function () {
        siteHelpers.changeQuickComment($(this));
    });

    siteHelpers.customerSearch($(".customer-id-select"));

    $(document).on("click",".add-chat-phrases",function(e) {
        e.preventDefault();
        // $("#addPhrases").find(".question").val($(this).data("message"));
        $("#addPhrases").modal("show");
    });

    $(document).on('click', '.latest-scraped-shortcut', function () {
            var id = $(this).data('id');
            $('#categoryBrandModal').find('input[name="customer_id"]').val(id);
});
        
    $('.select-phrase-group').select2({
        tags : true,
        allowClear: true,
        placeholder: "",
        ajax: {
            url: '/chatbot/question/search',
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data.items
                };
            }
        }
    });
    $(document).on("click","#add-phrases-btn",function() {
        var form  = $(this).closest("form");
        $.ajax({
            type: "POST",
            url: form.attr("action"),
            data :form.serialize(),
            dataType : "json",
        }).done(function (response) {
            toastr['success']('Message dialog update successfully', 'success');
        }).fail(function (response) {
            toastr['error']('Oops, Something went wrong!', 'success');
        });
    });
    $(document).on("click", ".create-dialog",function() {

        $("#leaf-editor-model").modal("show");

        var myTmpl = $.templates("#add-dialog-form");
        var question = $(this).closest(".message").data("message");
        var assistantReport = [];
        assistantReport.push({"response" : "" , "condition_sign" : "" , "condition_value" : "" , "condition" : "","id" : 0});
        var json = {
            "create_type": "intents_create",
            "intent"  : {
                "question" : question,
            },
            "assistant_report" : assistantReport,
            "response" :  "",
            "allSuggestedOptions" : JSON.parse('<?php echo json_encode(\App\ChatbotDialog::allSuggestedOptions()) ?>')
        };
        var html = myTmpl.render({
            "data": json
        });

        window.buildDialog = json;

        $("#leaf-editor-model").find(".modal-body").html(html);
        $("[data-toggle='toggle']").bootstrapToggle('destroy')
        $("[data-toggle='toggle']").bootstrapToggle();
        $(".search-alias").select2({width : "100%"});

        var eleLeaf = $("#leaf-editor-model");
        searchForIntent(eleLeaf);
        searchForCategory(eleLeaf);
        searchForDialog(eleLeaf);
        previousDialog(eleLeaf);
        parentDialog(eleLeaf);

    });


    $(document).on('click', '.send-to-approval-btn', function (e) {
            e.preventDefault();
            var id = $('#categoryBrandModal').find('input[name="customer_id"]').val();
            $('#categoryBrandModal').find('input[name="submit_type"]').val('send-to-approval');
            $('#customerSendScrap').attr('action', '/attachImages/customer/' + id);
            $("#customerSendScrap").submit();
        });

        $("#customerSendScrap").on('submit', function(e) {
                e.preventDefault();
                
                var url = $('#customerSendScrap').attr('action');

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: $(this).serialize(),
                    beforeSend: function () {
                    $("#loading-image").show();
                    },
                    success: function (response) {
                        $("#loading-image").hide();
                        $("#categoryBrandModal").modal('hide');
                        toastr["success"](response.message);
                    },
                    error: function (error) {
                        toastr["error"](error.responseJSON.message);
                        $("#loading-image").hide();
                    }
                }); 
                
        });

        $(document).on('click', '.old-send-btn', function (e) {
            e.preventDefault();
            var id = $('#categoryBrandModal').find('input[name="customer_id"]').val();
            $('#categoryBrandModal').find('input[name="submit_type"]').val('old-submit');
            $('#customerSendScrap').attr('action', '/attachImages/customer/' + id);
            $("#customerSendScrap").submit();
        });


</script>
<script>
     var time = new Date().getTime();
     $(document.body).bind("mousemove keypress", function(e) {
         time = new Date().getTime();
     });

     function refresh() {
         if(new Date().getTime() - time >= 300000) 
             window.location.reload(true);
         else 
             setTimeout(refresh, 10000);
     }

     setTimeout(refresh, 10000);
</script>
@endsection

