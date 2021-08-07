@extends('layouts.app')

@section('styles')
<style>
    .instagram-post{
        display: flex; 
        width: auto; height: 
        auto; margin: 0px auto; 
        border: 1px solid #eeeeee;
    }
</style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Instagram Posts</h2>
        </div>
        <div class="col-md-12 mt-4">
            {{ $posts->render() }}
            @foreach($posts as $key=>$post)
                <div class="instagram-post">
                    @if($post->media_url )
                        @if($post->media_type == 1)
                            <div style="display: flex; width: 598px; height: 598px; background: url('{{ json_decode($post->media_url)[0] }}'); background-size: cover;">
                            &nbsp;
                            </div>  
                        @elseif($post->media_type == 2)
                            <video controls src="{{ json_decode($post->media_url)[0] }}" style="display: flex; width: 598px; height: 598px; background-size: cover;"></video>
                        @elseif($post->media_type == 8)
                            <?php $count = 0; ?>
                            @foreach(json_decode($post->media_url) as $m)
                                @if($count == 0)
                                    {{ $count = 1 }}
                                @else
                                    @break;    
                                @endif
                                @if ($m->media_type == 1)
                                    <div style="display: flex; width: 598px; height: 598px; background: url('{{ $m->url }}'); background-size: cover;">
                                        &nbsp;
                                    </div>
                                @elseif($m->media_type == 2)
                                    <video controls src="{{ $m->url }}" style="display: flex; width: 598px; height: 598px; background-size: cover;"></video>

                                @endif
                            @endforeach
                        @endif

                        
                    @else
                        <div style="display: flex; width: 598px; height: 598px; background-color: #eee;">
                            &nbsp;
                        </div>
                    @endif
                    <div style='display: inline-block; width: 336px; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;'>
                        <span style="padding-left: 20px; float: right;"><a href="https://www.instagram.com/p/{{ $post->code }}" target="_blank"><i class="fa fa-external-link fa-2x"></i></a></span>
                        <p style="padding: 20px;">
                            <a style="font-weight: bold; color: #000;" title="{{ $post->username }}" target="_blank" href="https://instagram.com/{{ $post->username }}/">{{ $post->username }}</a><br/>
                            <span style="color: #999;">{{ date('d-M-Y H:i:s', strtotime($post->posted_at)) }}</span><br/>
                            <span style="color: red;">Likes {{ $post->likes }}</span>
                            <br/>
                            <span style="color: red;">Comments {{ $post->comments_count }}</span><br/>
                            <span style="color: red;">User Posts @if(isset($post->userDetail->posts)){{ $post->userDetail->posts }} @endif</span><br/>
                            <span style="color: red;">User Followers @if(isset($post->userDetail->followers)){{ $post->userDetail->followers }} @endif</span><br/>
                            <span style="color: red;">User Following @if(isset($post->userDetail->following)){{ $post->userDetail->following }} @endif</span><br/>
                            <span style="color: red;">Location @if(isset($post->userDetail->location)){{ $post->userDetail->location }} @endif</span><br/>
                            
                            <span style="color: red;">Engagement % @if(isset($post->likes) && isset($post->userDetail->followers)){{ number_format(($post->likes/$post->userDetail->followers) * 100,2) }} @endif</span>
                            
                        </p>
                        <hr style="width: 100%;"/>
                        <p style="padding: 20px; font-size: 0.9em;">
                            <a style="font-weight: bold; color: #000;" title="{{ $post->username }}" target="_blank" href="https://instagram.com/{{ $post->username }}/">{{ $post->username }}</a> 
                            @if($post->caption) 
                            <div class="expand-row" style="padding: 20px; font-size: 0.9em;">
                                <div class="td-mini-container">
                                  {!! strlen($post->caption) > 200 ? substr($post->caption, 0, 200).'  <span style="color:blue;">Load More...</a>' : $post->caption !!}
                                </div>
                                <div class="td-full-container hidden">
                                    {{ $post->caption }}
                                </div>
                            </div>
                            <br/>
                            @endif
                            <span style="color: #999; padding: 20px; font-size: 0.9em;">{{ date('d-M-Y', strtotime($post->posted_at)) }}</span>
                        </p>
                        @php
                            $instagramPostsComments = \App\InstagramPostsComments::where('instagram_post_id', $post->id)->get();
                            $count = 0;
                        @endphp
                        @if ($instagramPostsComments != null)
                            <div class="expand-row">
                                 <div class="td-mini-container">
                            @foreach($instagramPostsComments as $comment)
                                 <p style="padding: 0 20px 0 20px; font-size: 0.9em;">
                                    <a style="font-weight: bold; color: #000;" title="{{ $comment->username }}" target="_blank" href="https://instagram.com/{{ $comment->username }}/">{{ $comment->username }}</a> {{ $comment->comment }}<br/>
                                    <span style="color: #999;">{{ date('d-M-Y', strtotime($post->posted_at)) }}</span>
                                 </p>
                             @if($count == 3)
                                <p style="padding: 0 20px 0 20px; font-size: 0.9em; color: blue">Load More Comments</p>
                                @break
                             @endif

                            @php
                            $count++
                            @endphp
                            @endforeach
                            </div>  
                            <div class="td-full-container hidden">
                            @foreach($instagramPostsComments as $comment)
                                <p style="padding: 0 20px 0 20px; font-size: 0.9em;">
                                    <a style="font-weight: bold; color: #000;" title="{{ $comment->username }}" target="_blank" href="https://instagram.com/{{ $comment->username }}/">{{ $comment->username }}</a> {{ $comment->comment }}<br/>
                                    <span style="color: #999;">{{ date('d-M-Y', strtotime($post->posted_at)) }}</span>
                                </p>
                            
                            @endforeach
                            </div>
                            </div>
                        @endif
                    </div>
                </div>
                <br/>
                <br/>
            @endforeach
            {{ $posts->render() }}
        </div>
    </div>
@endsection

@section('scripts')
    @if(Session::has('message'))

    @endif

    <script type="text/javascript">
        $(document).on('click', '.expand-row', function() {
        var selection = window.getSelection();
        if (selection.toString().length === 0) {
            $(this).find('.td-mini-container').toggleClass('hidden');
            $(this).find('.td-full-container').toggleClass('hidden');
        }
    });
    </script>
@endsection