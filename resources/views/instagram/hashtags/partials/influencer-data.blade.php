@foreach($influencers as $influencer)
    <tr>
        
        <td>
            <input    type="checkbox" 
                      class="selectedInfluencers" 
                      name="selectedInfluencers" 
                      value="{{$influencer->id}}">
         </td>
	      
        <td style="white-space: nowrap;">
          {{date('d-m-y', strtotime($influencer->created_at))}}
        </td>

        <td>
            <a href="{{ $influencer->url }}" target="_blank">
              {{ str_limit($influencer->name, 12, '...')}}
            </a>
        </td>

        <td class="expand-row-msg" data-name="email" data-id="{{$influencer->id}}">
	            	<span onclick="showModal('{{$influencer->email}}','Email')" class="show-short-email-{{$influencer->id}}">
                  {{ str_limit($influencer->email, 12, '...')}}
                </span>
	            	<span style="word-break:break-all;" class="show-full-email-{{$influencer->id}} hidden">
                  {{$influencer->email}}
                </span>
        </td>

        <td class="expand-row-msg" data-name="keyword" data-id="{{$influencer->id}}">
	      	<span onclick="showModal('{{$influencer->keyword}}','Influencer Name')" class="show-short-keyword-{{$influencer->id}}">
            {{ str_limit($influencer->keyword, 12, '...')}}
          </span>
		      <span style="word-break:break-all;" class="show-full-keyword-{{$influencer->id}} hidden">
            {{$influencer->keyword}}
          </span>
        </td>    

        <td>{{ $influencer->posts }}</td>

        <td>{{ $influencer->followers }}</td>

        <td>{{ $influencer->following }}</td>


        <!-- <td>{{ $influencer->phone }}</td> -->
        <!-- <td>{{ $influencer->website }}</td> -->
        <!-- <td>{{ $influencer->twitter }}</td> -->
        <!-- <td>{{ $influencer->facebook }}</td> -->
        <td class="expand-row-msg" data-name="country" data-id="{{$influencer->id}}">
            <span 
                  onclick="showModal('{{$influencer->country}}' , 'Country')"
                  class="show-short-country-{{$influencer->id}}"
                  style="cursor:pointer"
                  >
                  {{ str_limit($influencer->country, 5, '...')}}
            </span>
        <span style="word-break:break-all;" class="show-full-country-{{$influencer->id}} hidden">{{$influencer->country}}</span>
        </td>

        <td class="expand-row-msg" data-name="description" data-id="{{$influencer->id}}">

        <span onclick="showModal( '{{$influencer->description}}' , 'Description' )" class="show-short-description-{{$influencer->id}}">{{ str_limit($influencer->description, 6, '...')}}</span>
        <span style="word-break:break-all;" class="show-full-description-{{$influencer->id}} hidden">{{$influencer->description}}</span>
        </td>  
        
        <td>
          <div class="row" style="margin-bottom:8px">
            <div class="col-md-12">
                <select class="form-control account-search-{{$influencer->id}} select2" name="account_id" data-placeholder="Sender...">
                    <option value="">Select sender...</option>
                    @foreach ($accounts as $key => $account)
                      <option value="{{ $key }}" {{ isset($thread) && $thread->account_id == $key ? 'selected' : '' }}>{{ $account }}</option>
                    @endforeach
                </select>
            </div>
          </div>
        </td>

        <td>
		        <div class="d-flex" style="flex-direction: column;">
              @php 
                   $thread =\App\InstagramThread::where('scrap_influencer_id', $influencer->id)->first();
              @endphp

              @if($thread) 
                @if($thread->lastMessage)
                   <div class="typing-indicator" id="typing-indicator"> 
                       @if($thread->lastMessage->sent == 1) style="color: green;" 
                       @else style="color: red;" 
                       @endif>
                        {{ str_limit($thread->lastMessage->message, 20, '...')}}
                    </div>
                @endif
              @endif


		        

            

            <div class="row">

              <div class="col-md-12 form-inline d-flex ">
                  <textarea placeholder="Message..." style="width: 110px;
                  /* border: none; */
                  padding: 2px;
                  height: 30px;
    border-radius: 5px;" name="" class="quick-message-field" 
                            id="message{{ $influencer->id }}"></textarea>
                 <input type="hidden" id="message-id" name="message-id" />
                  <a style="margin-right: -16px;
                  margin-left: 18px;" class="btn btn-sm btn-image send-message" href="javascript:void(0)">
                    <span class="send_btn" data-id="{{$influencer->id}}">
                      <img src="{{asset('images/filled-sent.png')}}"/>
                    </span>
                  </a>
                  <a class="btn btn-image delete_quick_comment">
                    <img src="<?php echo url('/');?>/images/delete.png" style="cursor: default; width: 16px;">
                  </a>
              </div>

              <div class="col-md-4"  style="margin-top:10px" >
              
                
  
                @if($thread)
                          <a href="{{ route('attachImages', ['direct', @$thread->id, 1]) .'?'.http_build_query(['return_url' => 'instagram/influencers'])}}" 
                             class="btn btn-image px-1">
                            <img src="{{asset('images/attach.png')}}"/>
                          </a>
                @endif
  
                @if($thread)
                  <button type="button" class="btn btn-xs btn-image load-direct-chat-model" data-object="direct" data-id="{{ $thread->id  }}" title="Load messages"><img src="{{asset('images/chat.png')}}" alt=""></button>
                @endif
              </div>

              {{-- <div class="col-md-3 " tyle="padding:0px;">
                  <div class="input-group-append">
                     @if($thread)
                      	<a href="{{ route('attachImages', ['direct', @$thread->id, 1]) .'?'.http_build_query(['return_url' => 'instagram/influencers'])}}" 
                           class="btn btn-image px-1">
                          <img src="{{asset('images/attach.png')}}"/>
                        </a>
                      @endif
                     <a class="btn btn-image " href="javascript:;"><span class="send_btn" data-id="{{$influencer->id}}"><img src="{{asset('images/filled-sent.png')}}"/></span></a>
                     @if($thread)
                      	<button type="button" class="btn btn-xs btn-image load-direct-chat-model" data-object="direct" data-id="{{ $thread->id  }}" title="Load messages"><img src="{{asset('images/chat.png')}}" alt=""></button>
                     @endif
                  </div>
              </div>   

            </div> --}}

	  	</div>
    </td>
    
    <td>
        <select style="border:1px;padding:2px" name="quickComment">
          <option  data-vendorid="{{ $influencer->id }}"  value="">Auto Reply</option>
          <?php
          foreach ($replies ?? [] as $key_r => $value_r) { ?>
            <option title="<?php echo $value_r;?>" data-vendorid="{{ $influencer->id }}" value="<?php echo $key_r;?>">
              <?php
              $reply_msg = strlen($value_r) > 12 ? substr($value_r, 0, 12) : $value_r;
              echo $reply_msg;
              ?>
            </option>
          <?php }
          ?>
        </select>
    </td> 

    <td>
      <div class="d-flex">

        <button title="Retrieve latest post and comment." 
                class="btn btn-image latest-post pd-2 action-icon" data-id="{{ $influencer->id }}">
          <img src="{{asset('images/add.png')}}"/>
        </button>
            
        <button class="btn btn-image expand-row-btn pd-2 action-icon" 
                data-id="{{ $influencer->id }}">
                <img src="{{asset('images/forward.png')}}">
        </button>

      </div>
    </td>
</tr>



<tr class="dis-none" id="expand-{{ $influencer->id }}">
@php 
  $media = json_decode($influencer->post_media_url);
@endphp
  <td colspan="6">
  @if($media)
  <div class="row">
  @foreach($media as $m)
    @if($m->media_type == 1)
    &nbsp;<img style="width:75px;height:75px;" src="{{$m->url}}" alt="">&nbsp;&nbsp;
    @endif
  @endforeach
  </div>
  @endif
  </td>
  <td colspan="5">
    @if(isset($influencer->comment))
    <p>Latest comment : {{$influencer->comment}} by {{$influencer->comment_user_full_name}} on {{date('d-m-Y', strtotime($influencer->comment_posted_at))}}
    @endif
  </td>
</tr> 
@endforeach





<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal-body-content">
     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<script>
  function showModal( body , name ){
      $('#exampleModalLabel').html( name );
      $('#modal-body-content').html( body );
      $('#exampleModal').modal();
  }
</script>