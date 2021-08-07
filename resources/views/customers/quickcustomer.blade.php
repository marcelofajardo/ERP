@extends('layouts.app')

@section('styles')
    <style>
       
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <style type="text/css">
        .cls_btn_tasks .btn-image{
            padding: 6px 4px;
        }
        .cls_remove_right{
            padding-right: 0px !important;
        }
        .cls_remove_left{
            padding-right: 0px !important;
        }
        .cls_remove_all{
            padding-right: 0px !important;
            padding-left: 0px !important;
        }
        .delete_next_action{
            padding: 6px 3px;
        }
        .quick-message-field{
            height: 35px !important;
        }
        .cls_last_mesg_div{
            margin-left: -20px;
            margin-top: 8px;
        }
        .cls_btn_tasks {
            margin-top: -8px;
        }
        .cls_filter_inputbox{
	        width: 14%;
	        text-align: center;
	    }
	    .cls_commu_his{
	    	width: 100% !important;
	    }
    </style>
@endsection

@section('large_content')


    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Quick Customers List</h2>
        </div>
    </div>
    <div class="row">
    	<div class="col-lg-12">
    		<form class="form-inline" action="{{ route('quickcustomer') }}" method="GET">
                <div class="form-group ml-3 cls_filter_inputbox">
                    <label for="with_archived">Search Customer ID</label>
                    <input placeholder="Customer ID" type="text" name="customer_id" value="{{request()->get('customer_id')}}" class="form-control-sm cls_commu_his form-control">
                </div>
                <div class="form-group ml-3 cls_filter_inputbox">
                    <label for="with_archived">Search Customer Name</label>
                    <input placeholder="Customer Name" type="text" name="customer_name" value="{{request()->get('customer_name')}}" class="form-control-sm cls_commu_his form-control">
                </div>
                <div class="form-group ml-3 cls_filter_inputbox">
                	<label for="with_archived">Sort By</label>
                	<select class="form-control" name="type">
                        <optgroup label="Type">
                            <option value="">Select</option>
                            <optgroup label="Messages">
                                <option value="unread" {{ isset($type) && $type == 'unread' ? 'selected' : '' }}>Unread</option>
                                <option value="unapproved" {{ isset($type) && $type == 'unapproved' ? 'selected' : '' }}>Unapproved</option>
                            </optgroup>

                            <optgroup label="Leads">
                                <option value="0" {{ isset($type) && $type == '0' ? 'selected' : '' }}>No lead</option>
                                <option value="1" {{ isset($type) && $type == '1' ? 'selected' : '' }}>Cold</option>
                                <option value="2" {{ isset($type) && $type == '2' ? 'selected' : '' }}>Cold / Important</option>
                                <option value="3" {{ isset($type) && $type == '3' ? 'selected' : '' }}>Hot</option>
                                <option value="4" {{ isset($type) && $type == '4' ? 'selected' : '' }}>Very Hot</option>
                                <option value="5" {{ isset($type) && $type == '5' ? 'selected' : '' }}>Advance Follow Up</option>
                                <option value="6" {{ isset($type) && $type == '6' ? 'selected' : '' }}>High Priority</option>
                            </optgroup>

                            <optgroup label="Old">
                                <option value="new" {{ isset($type) && $type == 'new' ? 'selected' : '' }}>New</option>
                                <option value="delivery" {{ isset($type) && $type == 'delivery' ? 'selected' : '' }}>Delivery</option>
                                <option value="Refund to be processed" {{ isset($type) && $type == 'Refund to be processed' ? 'selected' : '' }}>Refund</option>
                            </optgroup>
                        </optgroup>
                    </select>
                </div>
                <button type="submit" style="margin-top: 20px;padding: 5px;" class="btn btn-image"><img src="{{asset('images/filter.png')}}"/></button>
            </form>
    	</div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
        	<div class="infinite-scroll">
        		<div class="table-responsive mt-3">
	                <table class="table table-bordered">
	                    <thead>
	                        <th style="width: 5%;">Customer Id</th>
	                        <th style="width: 15%;">Customer Name</th>
	                        <th style="width: 20%;">Next Action</th>
	                        <th style="width: 50%;">Communication box</th>
	                    </thead>
	                    <tbody>
	                        @foreach ($customers as $key => $customer)
	                            <tr>
	                                <td><?php echo $customer->id;?></td>
	                                <td>
	                                    <div class="row">
	                                        <div class="col-md-6 cls_remove_right expand-row">

	                                                <span class="td-mini-container">
	                                                {{ strlen($customer->name) > 10 ? substr($customer->name, 0, 10) : $customer->name }}
	                                              </span>

	                                                <span class="td-full-container hidden">
	                                                {{ $customer->name }}
	                                              </span>
	                                        </div>
	                                        <div class="col-md-6 cls_btn_tasks cls_remove_all">
	                                        	<?php
	                                        	if($customer->do_not_disturb == 1)
	                                        	{ ?>
	                                        		<a class="btn btn-image cls_dnt_btn do_not_disturb" href="javascript:;" data-id="<?php echo $customer->id;?>" data-user-id="">
	                                                <img src="{{asset('images/do-not-disturb.png')}}" />
	                                            	</a>
	                                        	<?php 
	                                        	}
	                                        	else{ ?>
	                                        		<a class="btn btn-image cls_dnt_btn do_not_disturb" href="javascript:;" data-id="<?php echo $customer->id;?>" data-user-id="">
	                                                	<img src="{{asset('images/do-disturb.png')}}" />
	                                            	</a>
	                                        	<?php }	 
	                                        	?>
	                                            
	                                            <a class="btn btn-image  create-customer-related-task" title="Task" href="javascript:;" data-id="<?php echo $customer->id;?>" data-user-id=""><i class="fa fa-plus" aria-hidden="true"></i></a>
	                                            <a class="btn btn-image  count-customer-tasks" title="Task Count" href="javascript:;" data-id="<?php echo $customer->id;?>" data-user-id=""><img src="{{asset('images/remark.png')}}" /></a>
	                                        </div>
	                                    </div>
	                                    
	                                        
	                                </td>
	                                <td>
	                                    <div class="row">
    	                                    <div class="col-md-12">
    	                                        <div class="row row_next_action">
    	                                            <div class="col-6 d-inline form-inline">
    	                                                <input style="width: 87%" type="text" name="add_next_action" placeholder="Add New Next Action" class="form-control add_next_action_txt">
    	                                                <button class="btn btn-secondary add_next_action" style="position: absolute;  margin-left: 8px;">+</button>
    	                                            </div>
    	                                            <div class="col-6 d-inline form-inline">
    	                                                <div style="float: left; width: 88%">
    	                                                    <select name="next_action" class="form-control next_action" data-id="{{$customer->id}}">
    	                                                        <option value="">Select Next Action</option> 
    	                                                        <?php foreach ($nextActionArr as $option) { ?>
    	                                                            <option value="<?php echo $option->id;?>"><?php echo $option->name;?></option>
    	                                                        <?php } ?>
    	                                                    </select>
    	                                                </div>
    	                                                <div style="float: right; width: 12%;">
    	                                                    <a class="btn btn-image delete_next_action"><img src="{{asset('images/delete.png')}}"></a>
    	                                                </div>
    	                                            </div>
    	                                        </div>
    	                                    </div>
	                                   </div>  
	                                </td>
	                                <td class="expand-row">
	                                    <div class="row">
	                                        <div class="col-md-12">
	                                            <div class="row">
	                                                <div class="col-md-8 form-inline cls_remove_right">
	                                                    <textarea rows="1" style="width: 100%" class="form-control quick-message-field" name="message" placeholder="Message"></textarea>
	                                                </div>
	                                                <div class="col-md-1 form-inline cls_remove_all">    
	                                                    <button style="display: inline-block;width: 10%" class="btn btn-sm btn-image send-message" data-customerid="{{ $customer->id }}"><img src="{{asset('images/filled-sent.png')}}"/></button>
	                                                </div>
	                                                <div class="col-md-3 cls_remove_all cls_last_mesg_div">
	                                                    <?php
	                                                    $chat_messages = DB::select('
	                                                              SELECT *
	                                                              FROM (SELECT id,message,customer_id FROM chat_messages where customer_id="'.$customer->id.'")
	                                                              AS chat_mess
	                                                              ORDER BY chat_mess.id DESC LIMIT 1;
	                                                          ');
	                                                    ?>
	                                                    <span class="cls_last_chat_message" id="lastchat_<?php echo $customer->id;?>">
	                                                        <span class="td-mini-container">
	                                                            {{ strlen(@$chat_messages[0]->message) > 20 ? substr(@$chat_messages[0]->message, 0, 20) : @$chat_messages[0]->message }}
	                                                          </span>

	                                                            <span class="td-full-container hidden">
	                                                            {{ @$chat_messages[0]->message }}
	                                                          </span>
	                                                    </span>
	                                                </div>
	                                            </div>
	                                        </div>
	                                        <div class="col-md-12 expand-row dis-none">
	                                        </div>
	                                    </div>
	                                </td>
	                            </tr>
	                        @endforeach
	                    </tbody>
	                </table>
	            </div>

                <form action="{{ route('attachImages', ['customers']) }}" id="attachImagesForm" method="GET">
                    <input type="hidden" name="message" id="attach_message" value="">
                    <input type="hidden" name="sending_time" id="attach_sending_time" value="">
                </form>

                {!! $customers->appends(Request::except('page'))->links() !!}
        	</div>        
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
    <script type="text/javascript" src="{{asset('js/common-helper.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.7/jquery.jscroll.min.js"></script>
    <script type="text/javascript">
        window.buildDialog = {};
        window.pageLocation = "autoreply";

        $(document).on('click', '.expand-row-btn', function () {
            $(this).closest("tr").find(".expand-row").toggleClass('dis-none');
        });

        var pageType = '{{!empty($pageType) ? $pageType : 0 }}';
        $(window).on('hashchange', function () {
            if (window.location.hash) {
                var page = window.location.hash.replace('#', '');
                if (page == Number.NaN || page <= 0) {
                    return false;
                } else {
                    getData(page);
                }
            }
        });

        $(document).on('click', '.expand-row', function () {
            var selection = window.getSelection();
            if (selection.toString().length === 0) {
                $(this).find('.td-mini-container').toggleClass('hidden');
                $(this).find('.td-full-container').toggleClass('hidden');
            }
        });

        $('.quick-message-field').on('focus', function() {
            //$(this).attr('rows', '6');
        });

        $('.quick-message-field').on('blur', function() {
            $(this).attr('rows', '1');
        });

        $('.multiselect-2').select2({width:'92%'});
        $('.select-multiple').select2({width: '100%'});
        /*$(document).ready(function () {
            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();

                $('li').removeClass('active');
                $(this).parent('li').addClass('active');

                var myurl = $(this).attr('href');
                var page = $(this).attr('href').split('page=')[1];

                getData(page);
            });

        });*/

        /*$('#search_frm').submit(function(e){
            e.preventDefault();
            getData(1);
        })*/

        /*function getData(page) {
            $.ajax(
                {
                    url: '?page=' + page+'&pageType='+pageType,
                    type: "get",
                    data: $('#search_frm').serialize(),
                    //dataType: "json"
                }).done(function (html) {
                    $("#customer-list").html();
                    //$(".page-heading").empty().html(data.heading);
                    location.hash = page;
                    $('.multiselect-2').select2({width:'92%'});
                    $(".customer_message").prop("checked", all_customers.length != 0);
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
        }*/

        // this is helper class we need to move to another location
        // @todo
        var siteHelpers = {
            
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
                        url: BASE_URL+'whatsapp/sendMessage/customer',
                        beforeSend : function() {
                            ele.attr('disabled', true);
                        },
                        doneAjax : function(response) {
                            ele.siblings('textarea').val('');
                            ele.attr('disabled', false);
                            $("#lastchat_"+customer_id).html(message);
                        }
                    };
                    siteHelpers.sendFormDataAjax(params);
                }
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
                    url: BASE_URL+"erp-customer/add-next-actions"
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
                    url: BASE_URL+"erp-customer/destroy-next-actions"
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
            }
        };

        $.extend(siteHelpers, common);

        var all_customers = [];
        var isTextMessageFocused = false;

        
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

        $(document).on('click', '.load-customers', function () {
            siteHelpers.loadCustomers($(this));
        });

        $(document).on('submit', "#send_message", function (e) {
            e.preventDefault();
            siteHelpers.erpLeadsSendMessage();
        });

        $(document).on('click', ".quick-shortcut-button", function (e) {
            e.preventDefault();
            siteHelpers.instructionStore($(this));
        });

        $(document).on('change', '.change_status', function () {
            siteHelpers.changeStatus($(this));
        });

        $(document).on('click', '.send-message', function () {
            siteHelpers.sendMessage($(this));
        });


        // started the return exchange code
        // need to move on partial
        // @todo use for multiple place
    

        $(document).on('click', '.add_next_action', function (e) {    
            siteHelpers.addNextAction($(this));
        });

        $(document).on('click', '.delete_next_action', function (e) {    
            siteHelpers.deleteNextAction($(this));
        });

        $(document).on('change', '.next_action', function (e) {    
            siteHelpers.changeNextAction($(this));
        });

        /*$(document).on("focusin",".quick-message-field",function(){
            $(".message-strong").removeClass("message-strong");
            $(this).addClass("message-strong");
        });*/

        $(document).on('click', '.do_not_disturb', function() {
            var id = $(this).data('id');
            var thiss = $(this);
            $.ajax({
                type: "POST",
                url: "{{ url('customer') }}/" + id + '/updateDND',
                data: {
                    _token: "{{ csrf_token() }}",
                    // do_not_disturb: option
                },
                beforeSend: function() {
                    //$(thiss).text('DND...');
                }
            }).done(function(response) {
              if (response.do_not_disturb == 1) {
              	var img_url = BASE_URL+"images/do-not-disturb.png";
                $(thiss).html('<img src="'+img_url+'" />');
              } else {
              	var img_url = BASE_URL+"images/do-disturb.png";
                $(thiss).html('<img src="'+img_url+'" />');
              }
            }).fail(function(response) {
              alert('Could not update DND status');
              console.log(response);
            })
      });

        $('[data-toggle="popover"]').popover();

        $("#suggestionModal").on("click",".submit-suggestion-modal",function(e){
            e.preventDefault();
            var form = $(this).closest("form");
            $.ajax({
                type: form.attr("method"),
                url: form.attr("action"),
                dataType : "json",
                data : form.serialize(),
                success: function(data) {
                    if(data.code == 200) {
                        toastr['success']('Record has been update successfully', 'success');
                        $("#suggestionModal").modal("hide");
                    }
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


        $(document).on("click",".count-customer-tasks",function() {
            
            var $this = $(this);
            // var user_id = $(this).closest("tr").find(".ucfuid").val();
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
            
            // var modalH = $("#quick-create-task");
            //     modalH.find(".task_asssigned_to").select2('destroy');
            //     modalH.find(".task_asssigned_to option[value='"+user_id+"']").prop('selected', true);
            //     modalH.find(".task_asssigned_to").select2({});
            //     modalH.find("#task_subject").val("Customer #"+customer_id+" : ");
            //     modalH.find("#hidden-category-id").remove();
            //     modalH.find("form").append('<input id="hidden-category-id" type="hidden" name="category_id" value="42" />');
            //     modalH.modal("show");  

        });
        
        $(function () {
            $('.infinite-scroll').jscroll({
                autoTrigger: true,
                loadingHtml: '<img class="center-block" src="'+BASE_URL+'images/loading.gif" alt="Loading..." />',
                padding: 2500,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.infinite-scroll',
                callback: function () {
                   $('.multiselect-2').multiselect({
                    enableFiltering: true,
                    filterBehavior: 'value'
                    });
                }
            });
        });
    </script>
@endsection