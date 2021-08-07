@php
        $isAdmin            = auth()->user()->isAdmin();
        $isHod              = auth()->user()->hasRole('HOD of CRM');
        $hasSiteDevelopment = auth()->user()->hasRole('Site-development');
        $userId             = auth()->user()->id;
    @endphp
    @foreach($subjects as $subject)
		<?php 
            if($isAdmin || $hasSiteDevelopment) {
        ?>
    	<tr>
    		<td>
    			@if($website) {{ $website->website }} @endif
    			<br>
    			{{ $subject->title }}
    			<br>
				<button onclick="editSubject({{$subject->id}})" style="background-color: transparent;border: 0;"><i class="fa fa-edit"></i></button>
    		</td>
            <td>
            <input type="text" class="form-control save-item" data-subject="{{ $subject->id }}" data-type="description" value="@if($subject->strategy){{$subject->strategy->description}}@endif" data-site="@if($website){{ $website->id }}@endif"></td>
    		<td>

      			<select style="margin-top: 5px;" class="form-control save-item-select" data-subject="{{ $subject->id }}" data-type="execution" data-site="@if($website){{ $website->id }}@endif" id="user-@if($subject->strategy){{ $subject->strategy->id }}@endif">
    				<option>Select Execution</option>
    				@foreach($users as $user)
    					<option value="{{ $user->id }}" @if($subject->strategy && $subject->strategy->execution_id == $user->id) selected @endif >{{ $user->name }}</option>
    				@endforeach
    			</select>
                <select style="margin-top: 5px;" name="content" class="form-control save-item-select" data-subject="{{ $subject->id }}" data-type="content" data-site="@if($website) {{ $website->id }} @endif" id="user-@if($subject->strategy){{ $subject->strategy->id }}@endif">
                    <option>Select Content</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @if($subject->strategy && $subject->strategy->content_id == $user->id) selected @endif >{{ $user->name }}</option>
                    @endforeach
                </select>
    		</td>
            <td>
    			@if($subject->strategy)
    				<div class="chat_messages expand-row table-hover-cell">
    					<button type="button" class="btn btn-xs btn-image load-communication-modal" data-is_admin="{{ $isAdmin }}" data-is_hod_crm="{{ $isHod }}" data-object="social_strategy" data-id="{{$subject->strategy->id}}" data-load-type="text" data-all="1" title="Load messages"><img src="/images/chat.png" alt=""></button>
    					<span class="chat-mini-container"> @if($subject->strategy->lastChat) {{ $subject->strategy->lastChat->message }} @endif</span>
    			     	<span class="chat-full-container hidden"></span>
    				</div>
    			@endif
    			<div class="d-flex">
                    <input type="text" class="form-control quick-message-field" name="message" placeholder="Message" value="" id="message-@if($subject->strategy){{ $subject->strategy->id }}@endif">
                    <button class="btn btn-sm btn-image send-message-site" data-id="@if($subject->strategy){{ $subject->strategy->id }}@endif"><img src="/images/filled-sent.png"/></button>
                    <br/>
                </div>
    		</td>
            <td></td>
			<td>
                <button type="button" data-site-id="@if($subject->strategy){{ $subject->strategy->id }}@endif" data-site-subject-id="{{ $subject->id }}" data-store-website-id="@if($website) {{ $website->id }} @endif" class="btn btn-file-upload">
                    <i class="fa fa-upload" aria-hidden="true"></i>
                </button>
                @if($subject->strategy)
                    <button type="button" data-site-id="@if($subject->strategy){{ $subject->strategy->id }}@endif" data-site-subject-id="{{ $subject->id }}" data-store-website-id="@if($website) {{ $website->id }} @endif" class="btn btn-file-list">
                        <i class="fa fa-list" aria-hidden="true"></i>
                    </button>
                    <button type="button" data-site-id="@if($subject->strategy){{ $subject->strategy->id }}@endif" data-site-subject-id="{{ $subject->id }}" data-store-website-id="@if($website) {{ $website->id }} @endif" class="btn btn-store-development-remark">
                        <i class="fa fa-comment" aria-hidden="true"></i>
                    </button>
                @endif
            </td>
    	</tr>
    <?php } ?>	
	@endforeach  
