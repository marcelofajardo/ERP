@extends('layouts.app')

@section('title', 'Auto Replies - ERP Sololuxury')

@section('styles')
    <link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css">
    <link href="/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/dialog-node-editor.css">
    <style type="text/css">
        .dis-none {
            display: none;
        }
        .fixed_header{
            table-layout: fixed;
            border-collapse: collapse;
        }

        .fixed_header tbody{
          display:block;
          width: 100%;
          overflow: auto;
          height: 250px;
        }

        .fixed_header thead tr {
           display: block;
        }

        .fixed_header thead {
          background: black;
          color:#fff;
        }

        .fixed_header th, .fixed_header td {
          padding: 5px;
          text-align: left;
        }
    </style>
@endsection

@section('content')
    <div class="row margin-tb">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Most Used Words | Auto Replies</h2>
            <div class="pull-left">
                <form>
                    <div class="form-group">
                        <input type="text" class="form-control" value="{{ request('keyword') }}" name="keyword" id="search-by-word" aria-describedby="search-by-word" placeholder="Enter Keyword">
                    </div>
                </form>
            </div>
            <div class="pull-right">
                <button type="button" class="btn btn-secondary ml-3" data-toggle="modal" data-target="#autoReplyCreateModal">Create</a>
                <button type="button" class="btn btn-secondary ml-3" onclick="addGroup()">Keyword Group</a>
                <button type="button" class="btn btn-secondary ml-3" onclick="addGroupPhrase()">Phrase Group</a>
            </div>
        </div>
    </div>

    @include('partials.flash_messages')
    <div class="col-md-12 margin-tb" style="margin-top:10px;">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th width="25%">Words</th>
                <th width="25%">Total</th>
                <th width="25%">Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($mostUsedWords as $key => $words)
                    <tr>
                        <td><input type="checkbox" name="keyword" value="{{ $words->id }}">  {{ $words->word }}</td>
                        <td>{{ $words->total }}</td>
                        <td>
                            <button data-id="{{ $words->id }}" class="btn btn-image expand-row-btn"><img src="/images/forward.png"></button>
                            <button data-id="{{ $words->id }}" class="btn btn-image delete-row-btn"><img src="/images/delete.png"></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-12 margin-tb">
        {{ $mostUsedWords->appends(request()->except('page'))->links() }}
    </div>    
    @include('partials.chat-history')
    @include('autoreplies.partials.group')
    <div class="modal fade" id="leaf-editor-model" role="dialog" style="z-index: 3000;">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editor</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary save-dialog-btn">Save changes</button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="phrase-editor-model" role="dialog" style="z-index: 1041;">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="allPhrases">All Phrases</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            
          </div>
        </div>
      </div>
    </div>
    <?php include_once(app_path()."/../Modules/ChatBot/Resources/views/dialog/includes/template.php"); ?>
@endsection

@section('scripts')
    <script src="/js/bootstrap-datetimepicker.min.js"></script>
    <script src="/js/bootstrap-toggle.min.js"></script>
    <script type="text/javascript" src="/js/jsrender.min.js"></script>
    <script type="text/javascript" src="/js/dialog-build.js"></script>
    <script type="text/javascript">
        window.buildDialog = {};
        window.pageLocation = "autoreply";

        $('.modal').on("hidden.bs.modal", function (e) { 
            if ($('.modal:visible').length) { 
                $('body').addClass('modal-open');
            }
        });

        $(document).on("click",".expand-row-btn",function() {
            var dataId = $(this).data("id");
            $.ajax({
                type: 'GET',
                url: "/autoreply/get-phrases",
                data: {
                    id: dataId
                }
            }).done(function (response) {
                if(response.code == 200) {
                    $("#phrase-editor-model").find(".modal-body").html(response.html);
                    $("#phrase-editor-model").modal("show");
                }
            }).fail(function (response) {
            });
        });

        $("#phrase-editor-model").on("click",".page-link",function(e) {
            e.preventDefault();
            var $this =  $(this);
            if(typeof $this.attr("href") != "undefined") {
                $.ajax({
                    type: 'GET',
                    url: $this.attr("href")
                }).done(function (response) {
                    if(response.code == 200) {
                        $("#phrase-editor-model").find(".modal-body").html(response.html);
                        //$("#phrase-editor-model").modal("show");
                    }
                }).fail(function (response) {
                });
            }
        });

        $(document).on("focusout","#search-by-phrases",function() {
            var dataId = $(this).data("id");
            $.ajax({
                type: 'GET',
                url: "/autoreply/get-phrases",
                data: {
                    id: dataId,
                    keyword : $(this).val()
                }
            }).done(function (response) {
                if(response.code == 200) {
                    $("#phrase-editor-model").find(".modal-body").html(response.html);
                    //$("#phrase-editor-model").modal("show");
                }
            }).fail(function (response) {
            });
        });

        $(document).on("click",".delete-row-btn",function() {
            var $this = $(this);
            var dataId = $(this).data("id");
            $.ajax({
                type: 'POST',
                url: "autoreply/delete-chat-word",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: dataId,
                }
            }).done(function () {
                $this.closest("tr").remove();
                $("#phrases_"+dataId).remove();
            }).fail(function (response) {
            });
        });

        var callingMoreChat = function(chatId, pageId) {
            $.ajax({
                type: 'GET',
                url: "autoreply/replied-chat/"+chatId,
                data : {
                    page : pageId
                },
                dataType : "json"
            }).done(function (response) {
                var html = "";
                $.each(response.data,function(k,v) {
                    html += '<div class="bubble">';
                    html += '<div class="txt">';
                    html += '<p class="name"></p>';
                    html += '<p class="message" data-message="'+v.message+'">'+v.message+'</p><br>';
                    html += '<span class="timestamp">'+v.created_at+'</span><span>';
                    html += '<a href="javascript:;" class="btn btn-xs btn-default ml-1 set-autoreply" data-q="'+response.question+'" data-a="'+v.message+'">+ Auto Reply</a></span>';
                    html += '</div>';
                    html += '</div>';
                });

                if(html != "") {
                    html += '<div class="row"><a onclick="callingMoreChat('+chatId+','+response.page+')" href="javascript:;" class="load-more-chat">Load More</a></div>';
                }
                $(".load-more-chat").parent().remove();
                $("#chat-list-history").find(".modal-body").find(".speech-wrapper").append(html);

            }).fail(function (response) {
            });
        }

        $(document).on("click",".get-chat-details",function() {
            var chatId = $(this).data("id");
            $.ajax({
                type: 'GET',
                url: "autoreply/replied-chat/"+chatId,
                dataType : "json"
            }).done(function (response) {
                var html = "";
                $.each(response.data,function(k,v) {
                    html += '<div class="bubble">';
                    html += '<div class="txt">';
                    html += '<p class="name"></p>';
                    html += '<p class="message" data-message="'+v.message+'">'+v.message+'</p><br>';
                    html += '<span class="timestamp">'+v.created_at+'</span><span>';
                    html += '<a href="javascript:;" class="btn btn-xs btn-default ml-1 set-autoreply" data-q="'+response.question+'" data-a="'+v.message+'">+ Auto Reply</a></span>';
                    html += '</div>';
                    html += '</div>';
                });

                if(html != "") {
                    html += '<div class="row"><a href="javascript:;" onclick="callingMoreChat('+chatId+','+response.page+')" class="load-more-chat">Load More</a></div>';
                }

                $("#chat-list-history").find(".modal-body").find(".speech-wrapper").html(html);
                $("#chat-list-history").find(".modal-title").html(response.question);
                $("#chat-list-history").modal("show");
                

            }).fail(function (response) {
            });
        });

        $(document).on("click",".set-autoreply",function() {

            $("#leaf-editor-model").modal("show");
            
            var myTmpl = $.templates("#add-dialog-form");
            var assistantReport = [];
                assistantReport.push({"response" : $(this).data("a") , "condition_sign" : "" , "condition_value" : "" , "condition" : "","id" : 0});
            var json = {
                "create_type": "intents_create",
                "intent"  : {
                    "question" : $(this).data("q"),
                },
                "assistant_report" : assistantReport,
                "response" :  $(this).data("a"),
                "allSuggestedOptions" : JSON.parse('<?php echo json_encode($allSuggestedOptions) ?>')
            };
            var html = myTmpl.render({
                "data": json
            });

            window.buildDialog = json;
            
            $("#leaf-editor-model").find(".modal-body").html(html);
            $("[data-toggle='toggle']").bootstrapToggle('destroy')
            $("[data-toggle='toggle']").bootstrapToggle();
            $(".search-alias").select2({width : "100%"});
            
            var eleLeaf = $("#leaf-editor-model");
            searchForIntent(eleLeaf);
            searchForDialog(eleLeaf);
            previousDialog(eleLeaf);
            parentDialog(eleLeaf);

            /*$.ajax({
                type: 'POST',
                url: "autoreply/save-by-question",
                data: {
                    _token: "{{ csrf_token() }}",
                    q: $(this).data("q"),
                    a: $(this).data("a")
                }
            }).done(function () {
                toastr['success']('Auto Reply added successfully', 'success');
            }).fail(function (response) {
            });*/
        });


        function addGroup(){
            var id = [];
            $.each($("input[name='keyword']:checked"), function(){
                id.push($(this).val());
            });
            if(id.length == 0){
                alert('Please Select Keyword');
            }else{
                $('#groupCreateModal').modal('show');
            }
        }

        function addGroupPhrase(){
             var phraseId = [];
            $.each($("input[name='phrase']:checked"), function(){
                phraseId.push($(this).val());
            });
           if(phraseId.length == 0){
                alert('Please Select Phrase From Keywords');
            }else{
                $('#groupPhraseCreateModal').modal('show');
            }
        }

        function createGroup(){
             var id = [];
             name = $('#keywordname').val();
             keyword_group = $('#keywordGroup').val();
             
            $.each($("input[name='keyword']:checked"), function(){
                id.push($(this).val());
            });
            if(id.length == 0){
                alert('Please Select Keyword');
            }else{
                $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('autoreply.save.group') }}',
                data: {
                    id: id,
                    name : name,
                    keyword_group : keyword_group,
                },
                }).done(response => {
                    alert('Added Group');
                }).fail(function (response) {
                    alert('Could not add group!');
                });
            }
        }


        function createGroupPhrase() {
            var phraseId = [];
            name = $('#phrasename').val();
            phrase_group = $('#phraseGroup').val();
            $.each($("input[name='phrase']:checked"), function(){
                phraseId.push($(this).val());
                keyword = $(this).attr("data-keyword")
            });
            
            if(phraseId.length == 0){
                alert('Please Select Phrase From Keywords');
            }else{
                $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('autoreply.save.group.phrases') }}',
                data: {
                    phraseId: phraseId,
                    keyword : keyword,
                    name : name,
                    phrase_group : phrase_group,
                },
                }).done(response => {
                    alert('Added Phrase Group');
                }).fail(function (response) {
                    alert('Could not add Phrase group!');
                });
            }
            
            $(function() {
                $('.selectpicker').selectpicker();
            });
        }
    </script>
@endsection
