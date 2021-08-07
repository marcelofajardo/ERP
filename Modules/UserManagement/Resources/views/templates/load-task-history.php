<script type="text/x-jsrender" id="template-task-history">
<form name="template-create-goal1" method="post"> 
		<div class="modal-content tasks_list_tbl" style=" width: 150%; margin-left: -23%; ">
		   <div class="modal-header">
		      <h5 class="modal-title">Task</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">

           <div class="task_hours_section" style="text-align:center;">
            <div class="row mb-2">
            <div class="col-md-3">
                  <p style="margin:0px;text-align:left"><strong>Total Priority Task Hours:</strong>  <span>{{:userTiming.total_priority_hours}} Hours</span></p>
            </div>
            <div class="col-md-3">
                  <p style="text-align:left"><strong>Today Available Hours:</strong>  <span>{{:userTiming.total_available_time}} Hours</span> <a title="Add Avaibility" class="btn btn-image load-time-modals" data-id="{{:user.id}}"><i style="font-size: 1.5em;" class="fa fa-clock-o" aria-hidden="true"></i></a></p>
            </div>
            <div class="col-md-3 p-0">
                 <p style="margin:0px;text-align:right"><strong>Pending Task Estimated Hours:</strong> <span>{{:userTiming.total_pending_hours}} Hours</span></><br>
            </div>
            <div class="col-md-3">
                 <p style="margin:0px;text-align:right"><strong>Total Available Hours:</strong>  <span>{{:userTiming.total_avaibility_hour}} Hours</span></p>
            </div>
            </div>


            </div>

           
           <table class="table table-bordered" style="table-layout:fixed;">
		    <thead>
		      <tr>
		      	<th style="width:19%">Task</th>
                <th style="width:15%">Status</th>
				<th style="width:25%">Description</th>
		      	<th style="width:33%">Approximate time</th>
				<th style="width:28%">Action</th>
			</tr>
		    </thead>
		    <tbody>
				{{props taskList}}
			      <tr>
			      	<td>
					  {{if prop.type == 'TASK'}}
					  #TASK-{{:prop.task_id}} => {{:prop.subject}}
					  {{else}}
					  #DEVTASK-{{:prop.task_id}} => {{:prop.subject}}
					  {{/if}}
                      {{if prop.has_flag == 1}}
                          <button type="button" class="btn btn-image flag-task pd-5" data-task_type="{{:prop.type}}" data-id="{{:prop.task_id}}"><img src="/images/flagged.png" style=""></button>
                      {{else}}
                            <button type="button" class="btn btn-image flag-task pd-5" data-task_type="{{:prop.type}}" data-id="{{:prop.task_id}}"><img src="/images/unflagged.png" style=""></button>
                      {{/if}}
					  </td>
                    <td>
                        <select class="form-control data-status" data-type="{{:prop.type}}" data-status="{{:prop.status_falg}}" 
                        onchange="resolveIssue(this,{{:prop.task_id}})">
                            <option value="">Select status</option>
                            <?php foreach ($statusList as $key => $value) { ?>
                                <option value="<?php echo $value->name; ?>" data-id="<?php echo $value->id; ?>" ><?php echo $value->name; ?></option>
                            <?php } ?>
                        </select>
<!--                        {{:prop.status_falg}}-->
                    </td>
					<td> 
						{{if prop.details.length > 100}}
							<div class="description">
								{{:prop.details.substr(0, 100)}}...
							</div>
						{{else}}
							{{:prop.details}}
						{{/if}}
						
						<div class="full_description d-none">
						{{:prop.details}}
						</div> 
					</td>
			      	<td>
					  <div class="form-group">

							<div class='input-group estimate_minutes'>

<!--                                <input style="min-width: 30px;margin-right: 3px;" type="text" data-id="{{:prop.task_id}}" data-type="{{:prop.type}}" class="form-control priority-no-field-change input-sm" name="priority_no" placeholder="Priority no" value="{{:prop.priority_no}}">-->

								<input style="min-width: 30px;padding: 5px 10px;font-size: 12px;height: 30px;" placeholder="E.minutes" value="{{:prop.approximate_time}}" type="text" class="form-control estimate-time-change" name="estimate_minutes_{{:prop.task_id}}" data-id="{{:prop.task_id}}" id="estimate_minutes_{{:prop.task_id}}" data-type={{:prop.type}}>

								<button style="float:right;padding-right:0px;" type="button" class="btn btn-xs show-time-history" title="Show History" data-id="{{:prop.task_id}}" data-type={{:prop.type}}><i class="fa fa-info-circle"></i></button>
							
									<input style="width: 135px;margin-left: 10px;" type="text" class="form-control input-sm due_date_cls" name="due_date" data-type={{:prop.type}} value="{{:prop.due_date}}"/>
								
                            <button class="btn btn-sm btn-image set-due-date" title="Set due date" data-taskid="{{:prop.task_id}}" style="padding:0px;"><img style="padding: 0;margin-left: 5px;" src="/images/filled-sent.png"/></button>
                            </div>
							</div>
                        </div>
					  </td>
					  <td>
					  <div class="row">
					    <div class="col-md-9 pr-0">
					    	<!-- input style="width: 105px;float: left;" type="text" class="form-control quick-message-field input-sm" name="message" placeholder="Message" value="" -->
                        <input type="text" style="width: 100%;" class="form-control quick-message-field input-sm" id="getMsg{{:prop.task_id}}" name="message" placeholder="Message" value="">
                          <!-- <div class="d-flex" style="float:right;"> -->
					    </div>
					    <div class="col-md-3 pl-1">
					      <button style="padding:2px;" class="btn btn-sm btn-image task-send-message-btn" data-type="{{:prop.type}}" data-id="{{:prop.task_id}}"><img src="/images/filled-sent.png"/></button>
                          {{if prop.type == 'TASK'}}
                          <button style="padding:2px;" type="button" class="btn btn-xs btn-image load-communication-modal" data-object="task" data-id="{{:prop.task_id}}" title="Load messages"><img src="/images/chat.png" alt="" style="cursor: nwse-resize;"></button>
					  {{else}}
					  <button type="button" class="btn btn-xs btn-image load-communication-modal" data-object="developer_task" data-id="{{:prop.task_id}}" title="Load messages"><img src="/images/chat.png" alt="" style="cursor: nwse-resize;"></button>
					  {{/if}}
					    </div>
					  </div>


                    <!-- </div> -->
					  </td>
				  </tr>
				  {{/props}}
		    </tbody>
		</table>
		   </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		   </div>
		</div>
	<!-- </form> -->

</script>
<script>
	// $('.due-datetime').datetimepicker({
    //     format: 'YYYY-MM-DD HH:mm'
    // }); 

//     $('#time_from').datetimepicker({
//     format: 'YYYY-MM-DD HH:mm:ss'
// });

    function resolveIssue(obj, task_id) {
        let id = task_id;
        let status = $(obj).val();
        let self = this;

        $.ajax({
        	type: 'POST',
            url: "/user-management/update-status",
            data: {
            	_token: "<?php echo csrf_token(); ?>",
                issue_id: id,
                is_resolved: status,
                type: $(obj).data('type')
            },
            success: function () {
                toastr["success"]("Status updated!", "Message")
            },
            error: function (error) {
                toastr["error"](error.responseJSON.message);
            }
        });
    }
    
	// $(document).on('click', '.task-send-message-btn', function () {
	// 	var cached_suggestions = localStorage['message_suggestions'];
 //            var thiss = $(this);
 //            var data = new FormData();
 //            var task_id = $(this).data('id');
 //            var type = $(this).data('type');
 //            var message = $(this).closest("td").find(".quick-message-field").val();
 //            var msgInput = $(this).closest("td").find(".quick-message-field");
 //            data.append("task_id", task_id);
 //            data.append("message", message);
 //            data.append("status", 1);
 //            if(type == 'DEVTASK') {
 //                $.ajax({
 //                            url: "/whatsapp/sendMessage/issue",
 //                            type: 'POST',
 //                            data: {
 //                                "issue_id": task_id,
 //                                "message": message,
 //                                "status": 2
 //                            },
 //                            dataType: "json",
 //                            success: function (response) {
 //                                toastr["success"]("Message sent successfully!", "Message");
 //                                msgInput.val('');
 //                                msgInput.removeAttr('disabled');
                                
 //                            },
 //                            beforeSend: function () {
 //                                msgInput.attr('disabled', true);
 //                            },
 //                            error: function () {
 //                                alert('There was an error sending the message...');
 //                                msgInput.removeAttr('disabled', true);
 //                            }
 //                        });
 //            }
 //            else {
 //                if (message.length > 0) {
 //                            if (!$(thiss).is(':disabled')) {
 //                                $.ajax({
 //                                    url: '/whatsapp/sendMessage/task',
 //                                    type: 'POST',
 //                                    "dataType": 'json',           // what to expect back from the PHP script, if anything
 //                                    "cache": false,
 //                                    "contentType": false,
 //                                    "processData": false,
 //                                    "data": data,
 //                                    beforeSend: function () {
 //                                        msgInput.attr('disabled', true);
 //                                    }
 //                                }).done(function (response) {
 //                                    msgInput.val('');
 //                                    msgInput.attr('disabled', false);
 //                                }).fail(function (errObj) {
 //                                    msgInput.attr('disabled', false);

 //                                    alert("Could not send message");
 //                                });
 //                            }
 //                        } else {
 //                            alert('Please enter a message first');
 //                        }
 //            }
 //        });

$(document).on('click','.load-time-modals',function(e){
	e.preventDefault();
	var id= $(this).data('id');
	$('.load-time-modal[data-id="'+id+'"]').trigger('click');
})
	</script>
   <script>
        $(document).on('click','.description',function(){
            $('#logMessageModel').modal('show');
            $('#logMessageModel p').text($(this).next().html());
        })
    </script>