@foreach($comments as $comment)
	<tr>
		<td>{{ $comment->id }}</td>
		<td>{{ $comment->instagram_post_id }}</td>
        <td>@if(isset($comment->instagramPost->hashTags->hashtag)) <a href="https://instagram.com/{{ $comment->instagramPost->username }}"  target=”_blank”> 
		{{ $comment->instagramPost->username }}</a>
		@endif
		</td>
      	<td>
      		@if(isset($comment->instagramPost->hashTags->hashtag))
      		<a href="https://www.instagram.com/p/{{ $comment->instagramPost->code }}"  target=”_blank”>Visit Post</a>
      		@endif
      	</td>
        <td style="word-wrap: break-word;text-align: justify;">
        							@if(isset($comment->instagramPost->caption))
                                    <div class="expand-row" style="width:100px;text-align: justify">
                                        <span class="td-mini-container">
                                            {{ strlen($comment->instagramPost->caption) > 20 ? substr($comment->instagramPost->caption, 0, 20).'...' : $comment->instagramPost->caption }}
                                          </span>

                                        <span class="td-full-container hidden">
                                            {{ $comment->instagramPost->caption }}
                                        </span>
                                    </div>
                                    @endif
                                </td>
        <td style="word-wrap: break-word;text-align: justify;">
        	@if(isset($comment->comment))
                                    <div class="expand-row" style="width:150px;text-align: justify">
                                        <span class="td-mini-container">
                                            {{ strlen($comment->comment) > 40 ? substr($comment->comment, 0, 40).'...' : $comment->comment }}
                                          </span>

                                        <span class="td-full-container hidden">
                                            {{ $comment->comment }}
                                        </span>
                                    </div>
                                    @endif
                                </td>
        <td>
                                   @if(isset($comment->instagramPost->location))
                                    <div>
                                         <span class="td-mini-container">
                                            {{ strlen($comment->instagramPost->location) > 5 ? substr($comment->instagramPost->location, 0, 5).'...' : $comment->instagramPost->location }}
                                          </span>
                                        <span class="td-full-container hidden">
                                            {{ $comment->instagramPost->location }}
                                        </span>
                                    </div>
                                    @endif
                                </td>

                                <td> @if(isset($comment->instagramPost->created_at)) {{ $comment->instagramPost->created_at->format('d-m-y') }} @endif </td>
                                <td style="width: 600px;">
                                    @if($comment->instagramPost)
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select class="form-control selectpicker" name="account_id" id="account_id_{{$comment->instagramPost->id}}" data-live-search="true">
                                                <option>Select User</option>
                                                @foreach($accs as $cc)
                                                    <option value="{{ $cc->id }}">{{ $cc->last_name }}</option>
                                                @endforeach
                                            </select>
                                            <select class="form-control" name="narrative_{{$comment->instagramPost->id}}" id="narrative_{{$comment->instagramPost->id}}">
                                                <option value="common">Common</option>
                                                <option value="promotion">Promotion</option>
                                                <option value="victim">Victim</option>
                                                <option value="troll">Troll</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <textarea type="text" rows="4" class="form-control"   placeholder="Type comment..." id="textbox_{{$comment->instagramPost->id}}"></textarea>
                                            <div class="pull-right">
                                                <button type="button" class="btn btn-xs btn-image comment-it" data-id="{{$comment->instagramPost->id}}" data-post-id="{{$comment->instagramPost->post_id}}" data-hashtag="{{ $comment->instagramPost->hashTags->hashtag }}"><img src="/images/filled-sent.png" ></button>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    @endif
                                </td>
                                                        	
	</tr>
@endforeach      
                                
                            

                    