<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-12 form-inline">
                    <textarea rows="1" style="width: 90%" class="form-control send-message-textbox" name="message" data-customerid="{{ $customer->id }}" placeholder="Message"></textarea>
                    <button style="display: inline-block;width: 10%" class="btn btn-sm btn-image send-message-open" data-customerid="{{ $customer->id }}"><img src="/images/filled-sent.png"/></button>
                    <button style="display: inline-block;width: 10%" class="btn btn-sm btn-image send-with-audio-message" data-customerid="{{ $customer->id }}">
                        <img src="/images/customer-call-recording.png"/>
                    </button>
        		    <button type="button" class="btn btn-xs btn-image load-communication-modal" data-object="customer" data-load-type="text" data-limit="10" data-id="{{$customer->id}}" data-is_admin="1" data-is_hod_crm="" data-all="1" title="Load messages"><img src="/images/chat.png" alt="">
                    </button>
                    @if($customer->do_not_disturb == 0)
                        <button type="button" class="btn btn-xs btn-image add_to_customer_dnd" data-id="{{$customer->id}}">
                            <img src="/images/do-disturb.png"/></button>
                        </button>
                    @else
                        <button type="button" class="btn btn-xs btn-image add_to_customer_dnd" data-id="{{$customer->id}}">
                            <img src="/images/do-not-disturb.png"/></button>
                        </button>
                    @endif
        		    <a class="create-customer-ticket-modal ml-3" href="javascript:;" data-customer_id="{{$customer->id}}" data-user-id="{{$customer->user_id}}" data-toggle="modal" data-target="#create-customer-ticket-modal" title="Create Ticket">Ticket</a>
                    <button style="float:right;padding-right:0px;margin-top: -5px;" type="button" class="btn btn-xs show-customer-tickets-modal ml-3" title="Show Tickets" data-toggle="modal" data-customer_id="{{$customer->id}}" data-target="#show-customer-tickets-modal"><i class="fa fa-info-circle"></i></button>
                    
                </div>
			</div>
		</div>
	</div>
	<div class="col-md-12 expand-row dis-none">
	</div>
</div>