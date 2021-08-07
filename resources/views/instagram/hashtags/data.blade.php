 @php $count = 1; @endphp
                    @foreach($medias as $key=>$post)
                   
                       <tr>
                                <td>{{ $count }} @php $count++ @endphp </td>
                                <td>#{{ $hashtag->hashtag }} <br> <a href="https://www.instagram.com/p/{{$post['code']}}">Visit Post</a><br/>
                                Posted At : <br>{{ date('d-M-Y H:i:s', strtotime($post['posted_at'])) }}<br/><br/>
                                Likes : <span style="color: red;">{{ $post['likes'] }}</span><br/>
                                Comments : <span style="color: red;">{{ $post['comments_count'] }}</span><br/>
                                </td>
                                <td><a href="https://instagram.com/{{$post['username']}}" target="_blank">{{ $post['username'] }}</a><br>
                                Location : <br>{{ $post['location'] }} <br>
                                User Posts <span style="color: red;">@if(isset($post['userDetail']->posts)){{ $post['userDetail']->posts }} @endif</span><br/>
                                User Followers : <span style="color: red;">@if(isset($post['userDetail']->followers)){{ $post['userDetail']->followers }} @endif</span><br/>
                                User Following : <span style="color: red;">@if(isset($post['userDetail']->following)){{ $post['userDetail']->following }} @endif</span><br/>
                                Engagement <span style="color: red;">% @if(isset($post['likes']) && isset($post['userDetail']->followers)){{ number_format(($post['likes']/$post['userDetail']->followers) * 100,2) }} @endif</span>
                                </td>
                                <td>@if($post->media_url && isset($post->media_type))
                                @if($post->media_type == 1)
                                    <?php 

                                    if($post->hasMedia('instagram')){
                                        foreach ($post->getMedia('instagram') as $media) {
                                            $image = $media->getUrl();

                                            break;
                                        }
                                    }

                                    if(!isset($image)){
                                        //geeting url from json
                                        try {
                                            $datas = json_decode($post->media_url);
                                        
                                            foreach ($datas as $data) {
                                                $image = $data->url;
                                                break;
                                            }

                                        } catch (\Exception $e) {
                                            
                                            $image = '';
                                        }
                                    }
                                    
                                    
                                    //dd($image);

                                    ?>

                                    <div style="display: flex; width: 150px; height: 150px; background: url('{{$image}}'); background-size: cover;">
                                    &nbsp;
                                    </div>  
                                @elseif($post->media_type == 2 && isset($post->media_type))
                                    <?php 

                                    if($post->hasMedia('instagram')){
                                        foreach ($post->getMedia('instagram') as $media) {
                                            $image = $media->getUrl();
                                            
                                            break;
                                        }
                                    }

                                    if(!isset($image)){
                                        //geeting url from json
                                        try {
                                            $datas = json_decode($post->media_url);
                                        
                                            foreach ($datas as $data) {
                                                $image = $data->url;
                                                break;
                                            }

                                        } catch (\Exception $e) {
                                            
                                            $image = '';
                                        }
                                    }
                                        

                                   
                                    ?>
                                    <video controls src="{{ $image }}" style="display: flex; width: 150px; height: 150px; background-size: cover;"></video>
                                @elseif($post->media_type == 8 && isset($post->media_type))
                                    @if(isset($post->media_url))
                                    <?php $count = 0; ?>
                                    @foreach(json_decode($post->media_url) as $m)
                                        @if($count == 0)
                                            <?php $count = 1; ?>
                                        @else
                                            @break;    
                                        @endif
                                        @if(isset($m->media_type))
                                        <?php 

                                            if($post->hasMedia('instagram')){
                                                foreach ($post->getMedia('instagram') as $media) {
                                                    $image = $media->getUrl();
                                                    
                                                    break;
                                                }
                                            }

                                            if(!isset($image)){
                                                //geeting url from json
                                                try {
                                                    $image = $m->url;

                                                } catch (\Exception $e) {
                                                    
                                                    $image = '';
                                                }
                                            }
                                                
                                            ?>
                                            @if ($m->media_type == 1)
                                                <div style="display: flex; width: 150px; height: 150px; background: url('{{ $image }}'); background-size: cover;">
                                                    &nbsp;
                                                </div>
                                            @elseif($m->media_type == 2)
                                                <video controls src="{{ $image }}" style="display: flex; width: 150px; height: 150px; background-size: cover;"></video>

                                            @endif
                                        @endif
                                    @endforeach
                                    @endif
                                @endif
                            
                        
                                @else
                                    <div style="display: flex; width: 150px; height: 150px; background-color: #eee;">
                                        &nbsp;
                                    </div>
                                @endif
                                @if($post->hasMedia('instagram'))
                                    <br />

                                    <button type="button" class="btn btn-primary open-post-modal" data-post="{{  $post['id'] }}">
                                        Post to Instagram
                                      </button>
                                @endif
                                
                                </td>
                                <td style="word-wrap: break-word;text-align: justify;">
                                    <div class="expand-row" style="width:150px;text-align: justify">
                                        <span class="td-mini-container">
                                            {{ strlen($post['caption']) > 40 ? substr($post['caption'], 0, 40).'...' : $post['caption'] }}
                                          </span>

                                        <span class="td-full-container hidden">
                                            {{ $post['caption'] }}
                                        </span>
                                    </div>
                                </td>
                                <td> @if(isset($post->send_comment[0])) 
                                     <span class="td-mini-container" style="color: red;">
                                        
                                            {{ strlen($post->send_comment[0]->comment) > 30 ? substr($post->send_comment[0]->comment, 0, 30).'...' : $post->send_comment[0]->comment }}
                                          </span>
                                        <span class="td-full-container hidden">
                                            {{ $post->send_comment[0]->comment }}
                                        </span>
                                @endif
                                <button type="button" class="btn btn-xs btn-image" onclick="loadComments({{ $post->id }})" title="Load messages"><img src="/images/chat.png" alt=""></button>
                                </td>
                                <td style="width: 600px;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            @if ($post->comments)
                                            <select class="form-control selectpicker" data-live-search="true" onchange="addUserToTextArea(this,{{$post['id']}})">
                                                <option>Select User</option>
                                               @foreach($post->comments as $keyy=>$comment)
                                                    <option value="{{ $comment->username }}">{{ $comment->username }}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                            <select class="form-control selectpicker" name="account_id" id="account_id_{{$post['id']}}" data-live-search="true">
                                                <option>Select User</option>
                                                @foreach($accs as $cc)
                                                    @if($cc->is_customer_support == 1)
                                                        <option value="{{ $cc->id }}">{{ $cc->last_name }} (Manager)</option>
                                                    @else
                                                        <option value="{{ $cc->id }}">{{ $cc->last_name }}</option>
                                                    @endif
                                                    
                                                @endforeach
                                            </select>
                                            <select class="form-control" name="narrative_{{$post['id']}}" id="narrative_{{$post['id']}}">
                                                <option value="common">Common</option>
                                                <option value="promotion">Promotion</option>
                                                <option value="victim">Victim</option>
                                                <option value="troll">Troll</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <textarea type="text" rows="4" class="form-control"   placeholder="Type comment..." id="textbox_{{$post['id']}}"></textarea>
                                            <div class="pull-right">
                                                <button type="button" class="btn btn-xs btn-image comment-it" data-id="{{$post['id']}}" data-post-id="{{$post['post_id']}}"><img src="/images/filled-sent.png" ></button>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @include('instagram.hashtags.partials.comments') 
                    @endforeach
