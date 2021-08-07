@php
        $isAdmin            = auth()->user()->isAdmin();
        $isHod              = auth()->user()->hasRole('HOD of CRM');
        $hasSiteDevelopment = auth()->user()->hasRole('Site-development');
        $userId             = auth()->user()->id;
    @endphp
    @foreach($categories as $category)
		<?php
            $site = $category->getContent($category->id,$website->id);
            if($isAdmin || $hasSiteDevelopment || ($site && $site->creator_id == $userId) || ($site && $site->publisher_id == $userId)) {
        ?>
    	<tr>
    		<td>
    			@if($website) {{ $website->website }} @endif
    			<br>
    			{{ $category->title }}
    			<br>
    			<button onclick="editCategory({{$category->id}})" style="background-color: transparent;border: 0;"><i class="fa fa-edit"></i></button>
    		</td>
    		<td>
    			<input type="hidden" id="website_id" value="@if($website) {{ $website->id }} @endif">
                <div class="d-flex">
                    <input type="text" name="request_date" id="request_date" class="form-control request-date-@if($site){{ $site->id }}@endif request_date" value="@if($site) {{ $site->request_date }} @endif">
                    <button class="btn btn-sm btn-image save-item pd-3" data-category="{{ $category->id }}" data-type="request_date" data-site="@if($site){{ $site->id }}@endif" data-id="@if($site){{ $site->id }}@endif"><img src="/images/filled-sent.png"/></button>
                    <button style="float:right;padding-right:0px;" type="button" class="btn btn-xs show-history pd-3" title="Show History" data-type="request_date" data-id="{{$category->id}}"><i class="fa fa-info-circle"></i></button>
                    <br/>
                </div>
            </td>
    		<td>
                <div class="d-flex">
                <input type="text" name="due_date" id="due_date" class="form-control due-date-@if($site){{ $site->id }}@endif due_date" value="@if($site) {{ $site->due_date }} @endif">
                    <button class="btn btn-sm btn-image save-item pd-3" data-category="{{ $category->id }}" data-type="due_date" data-site="@if($site){{ $site->id }}@endif" data-id="@if($site){{ $site->id }}@endif"><img src="/images/filled-sent.png"/></button>
                <button style="float:right;padding-right:0px;" type="button" class="btn btn-xs show-history pd-3" title="Show History" data-type="due_date" data-id="{{$category->id}}"><i class="fa fa-info-circle"></i></button>

                    <br/>
                </div>
            </td>
    		<td>
                <div class="d-flex">
                <input type="text" name="publish_date" id="publish_date" class="form-control publish-date-@if($site){{ $site->id }}@endif publish_date" value="@if($site) {{ $site->publish_date }} @endif">
                    <button class="btn btn-sm btn-image save-item pd-3" data-type="publish_date" data-site="@if($site){{ $site->id }}@endif" data-category="{{ $category->id }}" data-id="@if($site){{ $site->id }}@endif"><img src="/images/filled-sent.png"/></button>
                <button style="float:right;padding-right:0px;" type="button" class="btn btn-xs show-history pd-3" title="Show History" data-type="publish_date" data-id="{{$category->id}}"><i class="fa fa-info-circle"></i></button>

                    <br/>
                </div>
            </td>
    		<td>
    	
                  	<select style="margin-top: 5px;" class="form-control save-item-select store_social_content_status_id" data-category="{{ $category->id }}" data-type="status" data-site="@if($site){{ $site->id }}@endif" name="store_social_content_status_id" id="store_social_content_status_id-@if($site){{ $site->id }}@endif">
    				<option>Select Status</option>
    				@foreach($allStatus as $status)
    					<option value="{{ $status->id }}" @if($site && $site->store_social_content_status_id == $status->id) selected @endif >{{ $status->name }}</option>
    				@endforeach
    			</select>

      			<select  class="form-control save-item-select creator select2" data-category="{{ $category->id }}" data-type="creator" data-site="@if($site){{ $site->id }}@endif" name="creator_id" id="creator-@if($site){{ $site->id }}@endif">
    				<option value="">Select Creator</option>
    				@foreach($users as $user)
    					<option value="{{ $user->id }}" @if($site && $site->creator_id == $user->id) selected @endif >{{ $user->name }}</option>
    				@endforeach
    			</select>
                <select  name="publisher_id" class="form-control save-item-select publisher select2" data-category="{{ $category->id }}" data-type="publisher" data-site="@if($site) {{ $site->id }} @endif" id="publisher-@if($site){{ $site->id }}@endif">
                    <option value="">Select Publisher</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"@if($site && $site->publisher_id == $user->id) selected @endif >{{ $user->name }}</option>
                    @endforeach
                </select>
    		</td>
            <td>
    			@if($site)
    				<div class="chat_messages expand-row table-hover-cell">
    					<button type="button" class="btn btn-xs btn-image load-communication-modal" data-is_admin="{{ $isAdmin }}" data-is_hod_crm="{{ $isHod }}" data-object="content_management" data-id="{{$site->id}}" data-load-type="text" data-all="1" title="Load messages"><img src="/images/chat.png" alt=""></button>
    					<span class="chat-mini-container"> @if($site->lastChat) {{ $site->lastChat->message }} @endif</span>
    			     	<span class="chat-full-container hidden"></span>
    				</div>
    			@endif
    			<div class="d-flex">
                    <input type="text" class="form-control quick-message-field" name="message" placeholder="Message" value="" id="message-@if($site){{ $site->id }}@endif">
                    <button class="btn btn-sm btn-image send-message-site" data-id="@if($site){{ $site->id }}@endif"><img src="/images/filled-sent.png"/></button>
                    <br/>
                </div>
                <input type="checkbox" id="creator_message_{{$category->id}}" name="creator" value="creator">
                <label for="creator">Creator</label><br>
                <input type="checkbox" id="publisher_message_{{$category->id}}" name="publisher" value="publisher">
                <label for="publisher">Publisher</label><br>
                
    		</td>
    		<td>
            <select style="margin-top: 5px;" name="platform" class="form-control save-item-select platform" data-category="{{ $category->id }}" data-type="platform" data-site="@if($site) {{ $site->id }} @endif" id="platform-@if($site){{ $site->id }}@endif">
                    <option value="">Select Platform</option>
                    <option value="facebook"@if($site && $site->platform == 'facebook') selected @endif >Facebook</option>
                    <option value="instagram"@if($site && $site->platform == 'instagram') selected @endif >Instagram</option>
            </select>
            </td>
            <td>
                <button type="button" data-site-id="@if($site){{ $site->id }}@endif" data-site-category-id="{{ $category->id }}" data-store-website-id="@if($website) {{ $website->id }} @endif" class="btn btn-file-upload pd-3">
                    <i class="fa fa-upload" aria-hidden="true"></i>
                </button>
                @if($site)
                    <button type="button" data-site-id="@if($site){{ $site->id }}@endif" data-site-category-id="{{ $category->id }}" data-store-website-id="@if($website) {{ $website->id }} @endif" class="btn btn-file-list pd-3">
                        <i class="fa fa-list" aria-hidden="true"></i>
                    </button>
                    <button type="button" data-site-id="@if($site){{ $site->id }}@endif" data-site-category-id="{{ $category->id }}" data-store-website-id="@if($website) {{ $website->id }} @endif" class="btn btn-store-development-remark pd-3">
                        <i class="fa fa-comment" aria-hidden="true"></i>
                    </button>
                @endif
                <button type="button" class="btn preview-img-btn pd-3" data-id="@if($site){{ $site->id }}@endif">
					<i class="fa fa-eye" aria-hidden="true"></i>
                </button>
                
                @if($isAdmin || $hasSiteDevelopment)
                    <button title="Approve Milestone" type="button" class="btn approve-popup-btn pd-3" data-id="@if($site){{ $site->id }}@endif">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </button>
                @endif



            </td>
    	</tr>
    <?php } ?>
    @include("content-management.edit-modal")
  @endforeach
