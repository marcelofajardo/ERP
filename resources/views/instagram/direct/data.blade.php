 <?php
    $srno=1;
    ?>
     @if(isset($threads) && !empty($threads))
        @foreach ($threads as $thread)
            @if(!$thread->account)
                <?php continue; ?>
            @endif
            <tr>
                <td>{{ $srno }} @if($thread->account->new_message == 1) <p id="circle"></p> @endif </td>
                <td>@if( $thread->account->storeWebsite) {{ $thread->account->storeWebsite->title }} @endif</td>
                <td>
                    <a href="https://www.instagram.com/{{ $thread->instagramUser->username }}" target="_blank">@if($thread->instagramUser->fullname){{ $thread->instagramUser->fullname }}@else {{ $thread->instagramUser->username }} @endif</a>
                </td>
                <td>
                    <p>{{ $item->last_name }}
                        <!-- <select class="from_account_list form-control" id="from_account_id{{ $thread->id }}">
                            @foreach ($accounts as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $thread->account->id ? 'selected' : null  }}> {{ $item->last_name }} </option>
                            @endforeach
                        </select> -->
                     </p>
                </td>
                <td style="">
                    @php
                    $path = storage_path('/');
                    $content = File::get($path."languages.json");
                    $language = json_decode($content, true);
                    @endphp
                    <div class="selectedValue">
                        <select id="autoTranslate" class="form-control auto-translate">
                            <option value="">Translation Language</option>
                            @foreach ($language as $key => $value)
                                <option value="{{$value}}">{{$key}}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div class="row">
                        <div class="col-md-12 cls_remove_rightpaddings">
                            <input type="text" name="" class="form-control type_msg message_textareas cls_message_textareas" placeholder="Type your message..." id="message{{ $thread->id }}">
                            <input type="hidden" id="message-id" name="message-id" />
                            <div class="input-group-appends">
                                <a href="{{ route('attachImages', ['direct', @$thread->id, 1]) .'?'.http_build_query(['return_url' => 'instagram/direct'])}}" class="btn btn-image px-1 attach-media-btn" data-target="{{ $thread->id }}" ><img src="{{asset('images/attach.png')}}"/></a>
                                <a class="btn btn-image px-1" href="javascript:;" onclick="sendMessage('{{ $thread->id }}')"><span class="send_btn" ><i class="fa fa-location-arrow"></i></span></a>
                            </div>
                        </div>
                        <div class="col-md-2 cls_remove_paddings">
                        </div>                                          
                    </div>
                    
                    <div onclick="getLiveChats('{{ $thread->account->id }}')" class="card-body msg_card_body" style="display: none;" id="live-message-recieve">
                        @if(isset($message) && !empty($message))
                            @foreach($message as $msg)
                                {!! $msg !!}
                            @endforeach
                        @endif
                    </div>
                </td>
                <td>
                    <div class="row">
                        <div class="col-6 d-inline form-inline">
                            <input style="width: 87%" type="text" name="category_name" placeholder="Enter New Category" class="form-control mb-3 quick_category">
                            <button class="btn btn-secondary quick_category_add ml-3 position-absolute" >+</button>
                        </div>
                        <div class="col-6 d-inline form-inline pl-0 float-left" >
                            <div style="width: 86%">
                                @php
                                    $all_categories = \App\ReplyCategory::all();
                                @endphp
                                <select name="quickCategory" class="form-control mb-3 quickCategory">
                                    <option value="">Select Category</option>
                                    @foreach($all_categories as $category)
                                    <option value="{{ $category->approval_leads }}" data-id="{{$category->id}}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="float: right; width: 14%;">
                                <a class="btn btn-image delete_category"><img src="/images/delete.png"></a>
                            </div>
                        </div>
                        <div class="col-6 d-inline form-inline">
                            <input style="width: 87%" type="text" name="quick_comment" placeholder="Enter New Quick Comment" class="form-control mb-3 quick_comment">
                            <button class="btn btn-secondary quick_comment_add ml-3 position-absolute">+</button>
                        </div>
                        <div class="col-6 d-inline form-inline pl-0 float-left">
                            <div style="width: 86%">
                                <select name="quickComment" class="form-control quickComment">
                                    <option value="">Quick Reply</option>
                                </select>
                            </div>
                            <div class="float-right" style="width: 14%;">
                                <a class="btn btn-image delete_quick_comment"><img src="/images/delete.png"></a>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    @if($thread->erpUser && !empty($thread->erpUser))
                        Existing Customer
                    @else
                        <button type="button" id="{{ $thread->instagramUser->username }}" class="btn btn-img btn-sm instagramHandle" data-toggle="modal"
                    data-target="#customerCreate"
                    title="Add new Customer"><i class="fa fa-plus"></i></button>
                    @endif
                    <button type="button" class="btn btn-xs btn-image load-direct-chat-model" data-object="direct" data-id="{{ $thread->id }}" title="Load messages"><img src="/images/chat.png" alt="" style="cursor: default;"></button>
                    <button type="button" class="btn btn-xs btn-image task-history" data-object="direct" data-id="{{ $thread->thread_id }}" title="Show history"><i class="fa fa-repeat" aria-hidden="true"></i></button>
                    <!-- <button type="button" class="btn btn-xs btn-image shortcuts" title="Shortcut Actions"><i class="fa fa-sign-out" aria-hidden="true"></i></button> -->
                    <div class="typing-indicator" id="typing-indicator" @if($thread->lastMessage) @if($thread->lastMessage->sent == 1)  @else  @endif>{{ $thread->lastMessage->message }}@endif </div>
                </td>
               </tr>
            <?php $srno++;?>
        @endforeach
    @endif



