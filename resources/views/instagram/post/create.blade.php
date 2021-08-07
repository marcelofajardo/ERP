@extends('layouts.app')

@section('title', __('New post'))

@section('content')

<link rel="stylesheet" href="{{ asset('/css/instagram.css') }}?v={{ config('pilot.version') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropme@latest/dist/cropme.min.css">   
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">


<style>
    .imagecheck-image{
        height: 100px !important;
        width: 100px !important
    }
    .light-blue.lighten-2 {
        background-color: #4fc3f7 !important;
        margin: 0px 4px;
    }   
    .light-blue.lighten-2 a {
        color: #fff;
        
    }
    div#auto {
        padding: 15px 0px;
    }
    div#show_hashtag_field {
        padding: 15px 0px;
    }
    #loading-image {
        position: fixed;
        top: 50%;
        left: 50%;
        margin: -50px 0px 0px -50px;
        z-index: 60;
    }
    .toast-success {
        background-color: rgb(81, 163, 81);
    }
</style>

<?php /* @includeWhen($accounts->count() == 0, 'partials.no-accounts') */ ?>


<div class = "row">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Instagram Posts</h2>
    </div>
</div>
@if(Session::has('message'))
    <p class="alert alert-info">{{ Session::get('message') }}</p>
@endif
<div class = "row">
    <div class="col-md-10 margin-tb">
        <div class="pull-left cls_filter_box">
            <form class="form-inline" action="{{ url('instagram/post/create') }}" method="GET">
                <div class="form-group ml-3 cls_filter_inputbox">
                    <input type="text" id="select_date" name="select_date" value="Select Date"  class="form-control">
                </div>
                <div class="form-group ml-3 cls_filter_inputbox">
                    <select name="acc" class="form-control">
                        <option value="">Select Account</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" @if(request()->get('acc')==$account->id) selected @endif>{{ $account->last_name }}</option>
                        @endforeach
                   </select>
                </div>
                <div class="form-group ml-3 cls_filter_inputbox">
                    <input type="text" name="comm" class="form-control" value="{{request()->get('comm')}}" placeholder="Comment">
                </div>
                <div class="form-group ml-3 cls_filter_inputbox">
                    <input type="text" name="tags" class="form-control" value="{{request()->get('tags')}}" placeholder="Hashtags">
                </div>
                <div class="form-group ml-3 cls_filter_inputbox">
                    <select name="type" class="form-control">
                        <option value="">Select Type</option>
                        <option value="post" @if(request()->get('type')=='post') selected @endif>Post</option>
                        <option value="album" @if(request()->get('type')=='album') selected @endif>Album</option>
                        <option value="story" @if(request()->get('type')=='story') selected @endif>Story</option>
                    </select>
                </div>
                <div class="form-group ml-3 cls_filter_inputbox">
                    <input type="text" name="loc" class="form-control" value="{{request()->get('loc')}}" placeholder="Location">
                </div>
                <button type="submit" class="btn btn-image"><img src="/images/filter.png"></button>
            </form> 
        </div>
    </div>  
    <div class="col-md-2 margin-tb">
        <div class="pull-right mt-3">

            <button type="button" class="class=" btn="" btn-success="" btn-block="" btn-publish="" mt-0="" data-toggle="modal" data-target="#add-vendor-info-modal" title="" data-id="1">Create Post</button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="panel-group" style="margin-bottom: 5px;">
            <div class="panel mt-3 panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                       Posts
                    </h4>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th width="9%">Date</th>
                            <th width="8%">Account</th>
                            <th width="15%">Comment</th>
                            <th width="6%">Hash Tags</th>
                            <th width="9%">Schedule date</th>
                            <th width="5%">Type</th>
                            <th width="8%">Location</th>
                            <th width="20%">Instagram Link</th>
                            <th width="20%">Status</th>
                            <th>Action</th>
                        </tr>
                        @foreach ($posts as $post)
                            
                            <tr id="{{$post->id}}" class="post-row">
                                <td>{{$post->created_at}}</td>

                                <td>
                                    <select class="form-control post_account_id">
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" @if($post->account_id==$account->id) selected @endif>{{ $account->last_name }}</option>
                                        @endforeach
                                   </select>
                                </td>
                                <td>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control post_comment" value="{{$post->comment}}">
                                    </div>
                                    <button class="btn btn-sm btn-image btn-update-comment"><img src="/images/filled-sent.png"></button>
                                </td>
                                <td>
                                    <button type="button" data-hashtag = '{{$post->hashtags}}' data-id = '{{$post->id}}' class="btn-modal-hashtag" data-toggle="modal" data-target="#show-hashtag-model" title="">Show Hashtags</button>
                                </td>
                                <td>{{$post->scheduled_at}}</td>
                                <td>
                                    <select name="post_type" class="form-control post-type-select">
                                        <option value="post" @if($post->type=='post') selected @endif>Post</option>
                                        <option value="album" @if($post->type=='album') selected @endif>Album</option>
                                        <option value="story" @if($post->type=='story') selected @endif>Story</option>
                                    </select>
                                </td>
                                <td>{{$post->location}}</td>
                                <!--td>{{$post->ig}}</td-->
                               
                                <td>
                                    @foreach($accounts as $account)
                                        @if($post->account_id==$account->id)
                                            <a href='https://www.instagram.com/{{$account->last_name}}'>https://www.instagram.com/{{$account->last_name}}</a>
                                            @php
                                            break;
                                            @endphp
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{$post->status == 1 ? "Published" : "Not Published"}}</td>
                                <td>
                                    <a href="{{url('instagram/post/publish-post')}}/{{$post->id}}" class="btn btn-primary" >Publish</a>
                                    <!--button type="button" class="btn-post-save" data-toggle="modal" title="" data-id="{{$post->id}}">Update</button-->
                                    <!--button type="button" class="btn-post-publish" data-toggle="modal" data-id="{{$post->id}}">Publish</button-->
                                    
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@include('instagram.partials.publish-post')
@include('instagram.partials.attachment-media')
<div id="add-insta-feed-model" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <body class="post-create">
                <div class="modal-header">
                    <h2>Profile</h2>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="post-create">
                        <div class="col-md-12">
                            <div class="card preview-story d-none"></div>
                            <div class="card preview-timeline">
                                <div class="pt-5 pb-2 text-center">
                                    <img src="{{ asset('/images/ig-logo.png') }}" alt="Instagram">
                                </div>
                                <div class="p-3 d-flex align-items-center px-2">
                                    <div class="avatar avatar-md mr-3"></div>
                                    <div>
                                        <div class="preview-username active"></div>
                                        <small class="d-block text-muted preview-location active"></small>
                                    </div>
                                </div>
                                <div class="image-preview">
                                    <div id="carousel" class="carousel slide">
                                        <ol class="carousel-indicators"></ol>
                                        <div class="carousel-inner"></div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="preview-caption active">
                                        <span></span>
                                        <span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
        </div>
    </div>
</div>
<div id="show-hashtag-model" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Select Hashtag</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="show_post_id" value="">

                <div class="form-group">
                    <label for="show_hashtag_field">Enter Hashtag</label>
                    <input class="form-control" type="text" id="show_hashtag_field" value="">
                    <div id="update_hashtag_auto"></div>
                </div>
                
                <div class="form-group">
                    <button type="button" class="btn btn-success btn-block btn-publish mt-0 btn-save-hashtag" data-toggle="modal">Save</button>
                </div>             
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


@endsection
    
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="{{ asset('/js/instagram.js') }}?v={{ config('pilot.version') }}" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/cropme@latest/dist/cropme.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
<script src="{{ asset('js/bootstrap-notify.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
    $("#select_date").datepicker({
    format: 'yyyy-mm-dd'
});
</script>

<script>
$(document).ready(function(){

    @if(isset($imagesHtml) && $imagesHtml)
      $('#add-vendor-info-modal').modal('show');
      $('#add-vendor-info-modal').addClass('in');
        $attachImages='{!!$imagesHtml!!}';
        console.log( $attachImages );
      $('.media-files-container').prepend($attachImages);

    @endif
   
     $(".btn-modal-hashtag").on("click",function(){
        var hashtag = $(this).data("hashtag");
        var id = $(this).data("id");

        $("#show_hashtag_field").val(hashtag);
        $("#show_post_id").val(id);
        
    });


    $('.post_account_id').on("change",function(){
        var postId = $(this).closest('tr').attr('id');
        var account_id = $(this).val();

        var dataString = {account_id:account_id,post_id:postId};
        savePost(dataString);
    });
    $('.btn-update-comment').on("click",function(){
        var postId = $(this).closest('tr').attr('id');
        var comment = $(this).closest('tr').find('.post_comment').val();
        var dataString = {comment:comment,post_id:postId};
        savePost(dataString);
    });
    $('.post-type-select').on("change",function(){
        var postId = $(this).closest('tr').attr('id');
        var type = $(this).val()
        var dataString = {type:type,post_id:postId};
        savePost(dataString);
    });
    $(".btn-save-hashtag").on('click',function(){
        var hashtags = $("#show_hashtag_field").val();
        var postId = $("#show_post_id").val();
        var dataString = {post_hashtags:hashtags,post_id:postId};
        savePost(dataString);
    });





    $("#search_hashtag").on("keyup", function() {
        var text = $(this).val();

        var n = text.split(" ");
        var lastWord = n[n.length - 1];

        //Checking if string starts with hash to send ajax
        var startWith = lastWord.charAt(0);
        //console.log("Starts With: "+startWith);
        //console.log("last word: "+lastWord);
        if(startWith=="#")
        {
            console.log("last word: "+lastWord);
            var wordToSearch = lastWord.substring(1);
            if(wordToSearch)
            {
                $.ajax({
                    type: "get",
                    url: "/instagram/get/hashtag/"+wordToSearch,
                    async: true,
                    dataType: 'json',
                    beforeSend: function () {
                        $("#search_hashtag").attr('contenteditable','false');
                        $("#loading-image").show();
                    },
                    success: function(data){
                        console.log(data)
                        $("#loading-image").hide();
                        $("#search_hashtag").attr('contenteditable','true');
                        $('#auto').html('');
                        results = data.results
                        if(results.length != 0){
                            html = ''
                            for(result of results){
                                console.log(result)

                                html += '<a class="form-control add-name" data-hashtag="'+wordToSearch+'" data-caption="'+result.name+'">'+result.name+'('+result.popular+')</a>'
                                
                                arrs = result.variants

                                for(arr of arrs){
                                    html += '<a class="form-control add-name" data-hashtag="'+wordToSearch+'" data-caption="'+arr+'">'+arr+'</a>'
                                }

                                

                                arrs = result.influencers

                                for(arr of arrs){
                                    html += '<a class="form-control add-name" data-hashtag="'+wordToSearch+'" data-caption="'+arr+'">'+arr+'</a>'
                                }

                               
                                
                            }
                             $('#auto').append(html)
                        }
                        
                        
                        
                    }
                });
            }else{
                console.log("No Hashtag work entered");
            }
        }else{
            console.log("Typing normal caption");
        }
    });

    $(document).on('click',"#auto a", function(){
        var hashtag= $(this).data('hashtag');
        var caption = $(this).data('caption');
        
        var lastIndex = caption.lastIndexOf(" ");
        var stringWithoutLastHashtag = caption.substring(0, lastIndex);

        //var stringWithoutLastHashtag = caption.substring(0, caption.lastIndexOf(" "));
        var finalString = stringWithoutLastHashtag.concat(" #"+caption);
        console.log("\n "+finalString);
        $("#search_hashtag").val(finalString);
    });



    $("#show_hashtag_field").on("keyup", function() {
        var text = $(this).val();

        var n = text.split(" ");
        var lastWord = n[n.length - 1];

        //Checking if string starts with hash to send ajax
        var startWith = lastWord.charAt(0);
        //console.log("Starts With: "+startWith);
        //console.log("last word: "+lastWord);
        if(startWith=="#")
        {
            console.log("last word: "+lastWord);
            var wordToSearch = lastWord.substring(1);
            if(wordToSearch)
            {
                $.ajax({
                    type: "get",
                    url: "/instagram/get/hashtag/"+wordToSearch,
                    async: true,
                    dataType: 'json',
                    beforeSend: function () {
                        $("#show_hashtag_field").attr('contenteditable','false');
                        $("#loading-image").show();
                    },
                    success: function(data){
                        $("#loading-image").hide();
                        $("#show_hashtag_field").attr('contenteditable','true');
                        //console.log(data.length);
                        //console.log(data[2]);
                        $('#update_hashtag_auto').html('');

                        for(x = 0; x < data.length; x++)
                        {
                            $('#update_hashtag_auto').append("<div class=chip light-blue lighten-2 white-text waves-effect'><a href='#' data-hashtag='"+data[x]+"' data-caption ='"+text+"' >"+data[x]+"</a></div>"); //Fills the #auto div with the options
                        }
                    }
                });
            }else{
                console.log("No Hashtag word entered");
            }
        }else{
            console.log("Typing normal caption");
        }

    });
    $(document).on('click',"#update_hashtag_auto a", function(){
        var hashtag= $(this).data('hashtag');
        var caption = $(this).data('caption');
        
        var lastIndex = caption.lastIndexOf(" ");
        var stringWithoutLastHashtag = caption.substring(0, lastIndex);

        //var stringWithoutLastHashtag = caption.substring(0, caption.lastIndexOf(" "));
        var finalString = stringWithoutLastHashtag.concat(" #"+hashtag);
        console.log("\n "+finalString);
        $("#show_hashtag_field").val(finalString);
        //$('#update_hashtag_auto').html('');
    });
      
    
    function savePost(dataString)
    {
        $.ajax({
            type: "post",
            url: "/instagram/post/update-hashtag-post",
            async: true,
            dataType: 'json',
            data: dataString,
            headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {

            },
            success: function(data){
                toastr["success"](data.message);
                console.log(data.message);
            }
        });
    }

    
    $('.attachInstagramMedia').on('click', function()
    {
        // if($('#add-vendor-info-modal').hasClass('in')){
        // $('#add-vendor-info-modal').removeClass('in');
        // $('#instagramAttachmentMedia').modal('show');
        // $('#instagramAttachmentMedia').addClass('in');
        // }
    });
    $('#instagramAttachmentMedia').on('hidden.bs.modal', function () {
        if(!$('#add-vendor-info-modal').hasClass('in')){
            $('#add-vendor-info-modal').addClass('in');
        }
    });
    $('.attachInstaMediaBtn').on('click', function(){
        var selectedMedia = $(this).parentsUntil('tr').find('.selectInstaAttachMedia:checked');
        var checkAttachSelected = selectedMedia.length;
        if(checkAttachSelected ==0){
            alert('please select image to attach');
            return;
        }
        var id = selectedMedia.data('id');
        var original = selectedMedia.data('original');
        var thumb = selectedMedia.data('thumb');
        var file_name = selectedMedia.data('file_name');

        $media_container = $('.media-manager .media-files-container');

         $media_container.empty();
        
       
        if(id !=''){
            $media_container.append(
	                              '<div class="media-file">'
	                            + '    <label class="imagecheck m-1">'
	                            + '        <input name="media[]" type="checkbox" value="' + id + '" data-original="' + original + '" class="imagecheck-input" />'
	                            + '        <figure class="imagecheck-figure">'
	                            + '            <img src="' + thumb + '" alt="' + file_name + '" class="imagecheck-image">'
	                            + '        </figure>'
	                            + '    </label>'
	                            + '</div>'
	                        );
        }
        $('#instagramAttachmentMedia').modal('hide');
    });
});

    $('.add-name').on('click', function(){
        name = $(this).val();
        console.log(name)
    });
</script>
@endsection
