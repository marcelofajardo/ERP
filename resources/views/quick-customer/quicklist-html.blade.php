		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th id="sno"><input type="checkbox" class="checkbox-select-all">&nbsp;#</th>
				  <th width="3%">Customer Name</th>
				  <th width="1%">Next Action</th>
				  <th width="18%">Shortcuts</th>
				  <th width="12%">Communication box</th>
		      </tr>
		    </thead>
		    <tbody>
				@foreach($items as $item)
				<tr>
					<td><input type="checkbox" name="items[]" value="{{$item->id}}" class="items-id">&nbsp;{{$item->id}}</td>
					<td>{{$item->short_name}} <br> M : {{ $item->phone }}</td>
					<td id="new_action_td">
			        	<div class="row">
                            <div class="col-md-12">
                                <div class="row row_next_action">
                                    <div class="col-12 d-inline form-inline">
                                        <input style="width: 75%;float:left;" type="text" name="add_next_action" placeholder="Add New Next Action" class="form-control add_next_action_txt">
                                        <button class="btn btn-secondary add_next_action_btn">+</button>
                                    </div>
                                    <div class="col-12 d-inline form-inline next_action_div">
                                        <div style="float: left; width: 75%">
                                            <select name="next_action" class="form-control next_action" data-id="{{$item->id}}">
                                                <option value="">Select Next Action</option> 
                                                 @foreach ($nextActionArr as $option) 
                                                    <option {{ ($item->customer_next_action_id ==$option->id)?'selected':''}} value="{{$option->id}}">{{$option->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div style="float: left; width: 12%;">
                                            <a class="btn btn-image delete_next_action"><img src="/images/delete.png"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       </div>
					</td>
					
					<td class="communication">			        	
						<div class="row">
						    <div class="col-md-12">
						        <div class="row">
						            <div class="col-6 d-inline form-inline">
						                <input style="width: 87%" type="text" name="category_name" placeholder="Enter New Category" class="form-control mb-3 quick_category">
						                <button class="btn btn-secondary quick_category_add" style="position: absolute;  margin-left: 8px;">+</button>
						            </div>
						            <div class="col-6 d-inline form-inline" style="padding-left: 0px;">
						                <div style="float: left; width: 86%">
						                    <select name="quickCategory" class="form-control mb-3 quickCategory ml-2">
						                        <option value="">Select Category</option>
													@foreach($reply_categories as $category)
						                            <option value="{{$category->approval_leads}}" data-id="{{$category->id}}">{{$category->name}}</option>
						                        	@endforeach
						                    </select>
						                </div>
						                <div style="float: right; width: 14%;">
						                    <a class="btn btn-image delete_category"><img src="/images/delete.png"></a>
						                </div>
						            </div>
						            <div class="col-6 d-inline form-inline">
						                <input style="width: 87%" type="text" name="quick_comment" placeholder="Enter New Quick Comment" class="form-control mb-3 quick_comment">
						                <button class="btn btn-secondary quick_comment_add" style="position: absolute;  margin-left: 8px;">+</button>
						            </div>
						            <div class="col-6 d-inline form-inline" style="padding-left: 0px;">
						                <div style="float: left; width: 86%">
						                    <select name="quickComment" class="form-control quickComment ml-2">
						                        <option value="">Quick Reply</option>
						                    </select>
						                </div>
						                <div style="float: right; width: 14%;">
						                    <a class="btn btn-image delete_quick_comment"><img src="/images/delete.png"></a>
						                </div>
						            </div>
						        </div>
						    </div>
						    <div class="col-md-12 expand-row dis-none">
								<?php 
									$csrf_str='@csrf';
									$action="{{ route('instruction.store') }}";
									$customer_id='{{$customer->id}}';
									$settingShortCuts='{{$settingShortCuts["image_shortcut"]}}';
								?>
						        <form class="d-inline" action="<?php echo $action; ?>" method="POST">
						            <?php echo $csrf_str; ?>
						            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
						            <input type="hidden" name="instruction" value="Send images">
						            <input type="hidden" name="category_id" value="6">
						            <input type="hidden" name="assigned_to" value="<?php echo $settingShortCuts; ?>">
						            <button type="submit" class="btn btn-image quick-shortcut-button" title="Send Images"><img src="/images/attach.png"/></button>
						        </form>
								<form class="d-inline" action="<?php echo $action; ?>" method="POST">
								<?php
									$settingShortCuts='{{$settingShortCuts["price_shortcut"]}}';
								?>
						            <?php echo $csrf_str; ?>
						            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
						            <input type="hidden" name="instruction" value="Send price">
						            <input type="hidden" name="category_id" value="3">
						            <input type="hidden" name="assigned_to" value="<?php echo $settingShortCuts; ?>">

						            <button type="submit" class="btn btn-image quick-shortcut-button" title="Send Price"><img src="/images/price.png"/></button>
						        </form>

								<form class="d-inline" action="<?php echo $action; ?>" method="POST">
								<?php
									$user_settingShortCuts='{{$users_array[$settingShortCuts["call_shortcut"]]}} call this client';
									$settingShortCuts='{{$settingShortCuts["call_shortcut"]}}';
								?>
						            <?php echo $csrf_str; ?>
						            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
						            <input type="hidden" name="instruction" value="<?php echo $user_settingShortCuts; ?>">
						            <input type="hidden" name="category_id" value="10">
						            <input type="hidden" name="assigned_to" value="<?php echo $settingShortCuts; ?>">

						            <button type="submit" class="btn btn-image quick-shortcut-button" title="Call this Client"><img src="/images/call.png"/></button>
						        </form>

								<form class="d-inline" action="<?php echo $action; ?>" method="POST">
								<?php  $settingShortCuts='{{$settingShortCuts["screenshot_shortcut"]}}'; ?>
						            <?php echo $csrf_str; ?>
						            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
						            <input type="hidden" name="instruction" value="Attach image">
						            <input type="hidden" name="category_id" value="8">
						            <input type="hidden" name="assigned_to" value="<?php echo $settingShortCuts;  ?>">

						            <button type="submit" class="btn btn-image quick-shortcut-button" title="Attach Images"><img src="/images/upload.png"/></button>
						        </form>

								<form class="d-inline" action="<?php echo $action; ?>" method="POST">
								<?php  $settingShortCuts='{{ $settingShortCuts["screenshot_shortcut"] }}'; ?>
						            <?php echo $csrf_str; ?>
						            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
						            <input type="hidden" name="instruction" value="Attach screenshot">
						            <input type="hidden" name="category_id" value="12">
						            <input type="hidden" name="assigned_to" value="<?php echo $settingShortCuts; ?>">

						            <button type="submit" class="btn btn-image quick-shortcut-button" title="Attach Screenshot"><img src="/images/screenshot.png"/></button>
						        </form>

								<form class="d-inline" action="<?php echo $action; ?>" method="POST">
								<?php $settingShortCuts='{{ $settingShortCuts["details_shortcut"] }}'; ?>
						            <?php echo $csrf_str; ?>
						            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
						            <input type="hidden" name="instruction" value="Give details">
						            <input type="hidden" name="category_id" value="14">
						            <input type="hidden" name="assigned_to" value="<?php echo $settingShortCuts; ?>">

						            <button type="submit" class="btn btn-image quick-shortcut-button" title="Give Details"><img src="/images/details.png"/></button>
						        </form>

								<form class="d-inline" action="<?php echo $action; ?>" method="POST">
								<?php $settingShortCuts='{{ $settingShortCuts["purchase_shortcut"] }}'; ?>
						            <?php echo $csrf_str; ?>
						            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
						            <input type="hidden" name="instruction" value="Check for the Purchase">
						            <input type="hidden" name="category_id" value="7">
						            <input type="hidden" name="assigned_to" value="<?php echo $settingShortCuts; ?>">

						            <button type="submit" class="btn btn-image quick-shortcut-button" title="Check for the Purchase"><img src="/images/purchase.png"/></button>
						        </form>

								<form class="d-inline" action="<?php echo $action; ?>" method="POST">
								<?php $settingShortCuts='{{ $settingShortCuts["purchase_shortcut"] }}'; ?>
						            <?php echo $csrf_str; ?>
						            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
						            <input type="hidden" name="instruction" value="Please Show Client Chat">
						            <input type="hidden" name="category_id" value="13">
						            <input type="hidden" name="assigned_to" value="<?php echo $settingShortCuts; ?>">

						            <button type="submit" class="btn btn-image quick-shortcut-button" title="Show Client Chat"><img src="/images/chat.png"/></button>
						        </form>
						        <div class="d-inline">
						            <button type="button" class="btn btn-image btn-broadcast-send" data-id="<?php echo $customer_id; ?>">
						                <img src="/images/broadcast-icon.png"/>
						            </button>
						        </div>
						        <div class="d-inline">
									<?php $a_href='{{ route("customer.download.contact-pdf",[$customer->id]) }}';?>
						            <a href="<?php echo $a_href; ?>" target="_blank">
						              <button type="button" class="btn btn-image"><img src="/images/download.png" /></button>
						            </a>
						        </div>
						        <div class="d-inline">
						            <button type="button" class="btn btn-image send-instock-shortcut" data-id="<?php echo $customer_id; ?>">Send In Stock</button>
						        </div>
						        <div class="d-inline">
						            <button type="button" class="btn btn-image latest-scraped-shortcut" data-id="<?php echo $customer_id; ?>" data-toggle="modal" data-target="#categoryBrandModal" style="padding: 6px 0px !important">Send 20 Scraped</button>
						        </div>
						    </div>    
						</div>  
					</td>

					<td class="communication">
					   <div class="row">
					      <div class="col-md-12">
					         <div class="row">
					            <div class="col-md-12">
					               <textarea rows="1" style="width: 85%;float: left;vertical-align:middle;display:inline-block;" class="form-control quick-message-field" name="message" placeholder="Message"></textarea>
					               <button style="display: inline-block;width: 10%;float:left;vertical-align:middle;" class="btn btn-sm btn-image send-message" data-customerid="{{$item->id}}"><img src="/images/filled-sent.png"></button>
					            </div>
					            <div class="col-md-12 form-inline">
                                   <button type="button" class="btn btn-xs btn-image load-communication-modal" data-object="customer" data-limit="10" data-id="{{$item->id}}" data-is_admin="1" data-is_hod_crm="" data-load-type="text" data-all="1" title="Load messages"><img src="/images/chat.png" alt=""></button>
                                        <button type="button" class="btn btn-xs btn-image load-communication-modal" data-object="customer" data-id="{{$item->id}}" data-attached="1" data-limit="10" data-load-type="images" data-all="1" data-is_admin="1" data-is_hod_crm="" title="Load Auto Images attacheds"><img src="/images/archive.png" alt=""></button>
                                        <button type="button" class="btn btn-xs btn-image load-communication-modal" data-object="customer" data-id="{{$item->id}}" data-attached="1" data-load-type="pdf" data-all="1" data-is_admin="1" data-is_hod_crm="" title="Load PDF"><img src="/images/icon-pdf.svg" alt=""></button>
                                        <button type="button" class="btn btn-xs btn-image load-communication-modal" data-object="customer" data-id="{{$item->id}}" data-attached="1" data-load-type="broadcast" data-all="1" data-is_admin="1" data-is_hod_crm="" title="Load Broadcast"><img src="/images/customer-suggestion.png" alt=""></button>

										
										@if($item->do_not_disturb==1)
			                        		<a class="btn btn-image cls_dnt_btn do_not_disturb" href="javascript:;" data-id="{{$item->id}}" data-user-id="">
			                            		<img src="/images/do-not-disturb.png" />
			                        		</a>
			                        	@else
			                        		<a class="btn btn-image cls_dnt_btn do_not_disturb" href="javascript:;" data-id="{{$item->id}}" data-user-id="">
			                                	<img src="/images/do-disturb.png" />
			                            	</a>
										@endif

			                            <a class="btn btn-image  create-customer-related-task" title="Task" href="javascript:;" data-id="{{$item->id}}" data-user-id="">
			                            	<i class="fa fa-plus" aria-hidden="true"></i>
			                            </a>
			                            <a class="btn btn-image count-customer-tasks" title="Task Count" href="javascript:;" data-id="{{$item->id}}" data-user-id="">
			                            	<img src="/images/remark.png" />
			                            </a>
			                            @if($item->in_w_list==1)
			                        		<a class="btn btn-image" href="javascript:;" data-id="{{$item->id}}" data-user-id="">
			                                	<img src="/images/2.png" />
			                            	</a>
										@endif
			                            <button type="button" class="btn btn-xs btn-image create-customer-ticket-modal" title="Create Ticket" data-toggle="modal" data-customer_id="{{$item->id}}" data-target="#create-customer-ticket-modal"><img src="/images/new.png" alt=""></button>
										<button type="button" class="btn btn-xs btn-image show-customer-tickets-modal" title="Show Tickets" data-toggle="modal" data-customer_id="{{$item->id}}" data-target="#show-customer-tickets-modal"><img src="/images/show-ticket.png" alt=""></button>
										<a href="javascript:;" class="btn btn-xs btn-image add-chat-phrases" title="Create Question"><img src="/images/add.png" alt=""></a>
										<a href="javascript:;" class="btn btn-xs btn-image latest-scraped-shortcut" data-toggle="modal" data-target="#categoryBrandModal" data-id="{{$item->id}}" title="Send Latest Scrapped"><i class="fa fa-paper-plane" aria-hidden="true"></i></a></div>
					            </div>
					         </div>
					      </div>
					      <div class="col-md-12">
						   <div class="communication-div-{{$item->id}}">
						      <div class="row">
						         <div class="col-md-12">
						            <span class="message-chat-txt" data-toggle="popover" data-placement="top" data-content="{{$item->message}}" data-original-title="" title="">{{$item->short_message}}</span>
						         </div>
						      </div>
						   </div>
					   </div>
					</td>
					
				</tr>
				@endforeach
		    </tbody>
		</table>


