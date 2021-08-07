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

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Auto Replies</h2>
            <div class="pull-left">
                <div class="form-inline">
                    <input type="checkbox" id="turn_off_automated" name="show_automated_messages" value="" {{ $show_automated_messages == 1 ? 'checked' : '' }}>
                    <label for="#turn_off_automated">Show Automated Messages</label>

                    <span class="text-success change_status_message" style="display: none;">Successfully saved</span>
                </div>
                
                <form action="{{ route('autoreply.index') }}" method="GET">
                    <div class="row">
                        <div class="col">
                          <div class="form-group ml-3">
                                <div class='input-group'>
                                    <input type='text' class="form-control" name="keyword" value="{{ request()->get('keyword') }}" />
                                </div>
                          </div>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
                        </div>
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

    <div id="exTab2" class="container">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#text-autoreplies" data-toggle="tab">Text Auto Replies</a>
            </li>
            <li>
                <a href="#priority-customers" data-toggle="tab">Priority Customers</a>
            </li>
            <li>
                <a href="#auto-replies" data-toggle="tab">Auto Replies</a>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        <div class="tab-pane active mt-3" id="text-autoreplies">
            <div class="row" style="margin:10px;"> 
                <div class="col-12">
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="10%">Keyword</th>
                                <th width="50%">Reply</th>
                                <th width="10%">Actions</th>
                            </tr>
                            </thead>

                            <tbody>
                            @php
                                $count = 0;
                            @endphp
                            @foreach ($simple_auto_replies as $reply => $data)
                                <tr>
                                    <td>{{ $count + 1 }}</td>
                                    <td>
                                        <ul>
                                            @foreach ($data as $key => $auto_reply)
                                                <li>{{ $auto_reply['keyword'] }}</li>
                                            @endforeach
                                        </ul>        
                                    </td>
                                    <td>{{ $reply }}</td>
                                    <td>
                                        @foreach ($data as $key => $auto_reply)
                                                
                                               <button type="button" class="btn btn-image edit-auto-reply" data-toggle="modal" data-target="#autoReplyEditModal" data-reply="{{ json_encode($auto_reply) }}"><img src="/images/edit.png"/></button>

                                                {!! Form::open(['method' => 'DELETE','route' => ['autoreply.destroy', $auto_reply['id']],'style'=>'display:inline']) !!}
                                                <button type="submit" class="btn btn-image"><img src="/images/delete.png"/></button>
                                                {!! Form::close() !!}
                                                </br>    
                                        @endforeach
                                    </td>

                                    @php
                                        $count++;
                                    @endphp
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $simple_auto_replies->appends(Request::except('page'))->links() !!}
                </div>
            </div>            
        </div>

        <div class="tab-pane mt-3" id="priority-customers">
            <div class="table-responsive mt-3">
                <div class="row" style="margin:10px;"> 
                    <div class="col-12">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Keyword</th>
                                <th>Sending Time</th>
                                <th>Repeat</th>
                                <th>Actions</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($priority_customers_replies as $key => $reply)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $reply->reply }}</td>
                                    <td>{{ $reply->sending_time ? \Carbon\Carbon::parse($reply->sending_time)->format('H:i d-m') : '' }}</td>
                                    <td>{{ $reply->repeat }}</td>
                                    <td>
                                        <button type="button" class="btn btn-image edit-auto-reply" data-toggle="modal" data-target="#autoReplyEditModal" data-reply="{{ $reply }}"><img src="/images/edit.png"/></button>

                                        {!! Form::open(['method' => 'DELETE','route' => ['autoreply.destroy', $reply->id],'style'=>'display:inline']) !!}
                                        <button type="submit" class="btn btn-image"><img src="/images/delete.png"/></button>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $priority_customers_replies->appends(Request::except('priority-page'))->links() !!}
                </div>
            </div>

        </div>

        <div class="tab-pane mt-3" id="auto-replies">
            <div class="table-responsive mt-3">
                <div class="row" style="margin:10px;"> 
                    <div class="col-12">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Used For</th>
                                <th width="60%">Reply</th>
                                <th width="10%">Actions</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($auto_replies as $key => $reply)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $reply->keyword }}</td>
                                    <td>
                            <span class="auto-reply-reply">
                              @php
                                  preg_match_all('/({\w*})/', $reply->reply, $match);

                                  $new_reply = $reply->reply;
                                  foreach ($match[0] as $variable) {
                                    $exploded_reply = explode($variable, $new_reply);
                                    $new_variable = '<strong>' . $variable . '</strong>';
                                    $new_reply = implode($new_variable, $exploded_reply);
                                  }

                                  // $new_reply = preg_replace('/\[/', '<strong>[</strong>', $new_reply);
                                  // $new_reply = preg_replace('/\//', '<strong>/</strong>', $new_reply);
                                  // $new_reply = preg_replace('/\]/', '<strong>]</strong>', $new_reply);
                              @endphp

                                {!! $new_reply !!}
                            </span>

                                        <textarea name="reply" class="form-control auto-reply-textarea hidden" rows="4" cols="80" data-id="{{ $reply->id }}">{{ strip_tags($reply->reply) }}</textarea>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-image edit-auto-reply-button"><img src="/images/edit.png"/></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $auto_replies->appends(Request::except('autoreply-page'))->links() !!}
                </div>        
            </div>

        </div>
    </div>

    @include('autoreplies.partials.autoreply-modals')
    @include('partials.chat-history')
    <div class="modal fade" id="leaf-editor-model" role="dialog">
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
        $('#sending-datetime, #edit-sending-datetime').datetimepicker({
            format: 'YYYY-MM-DD HH:mm'
        });

        $(document).on('click', '.edit-auto-reply', function () {
            var autoreply = $(this).data('reply');
            var url = "{{ url('autoreply') }}/" + autoreply.id;
            $('#autoReplyEditModal form').attr('action', url);
            $('#autoreply_type').val(autoreply.type);
            $('#autoreply_keyword').val(autoreply.keyword);
            $('#autoreply_reply').val(autoreply.reply);
            $('#autoreply_sending_time').val(autoreply.sending_time);
            $('#autoreply_repeat').val(autoreply.repeat);
            if (autoreply.is_active == 1) {
                $('#autoreply_is_active').prop("checked", true);
            }
        });

        $('#turn_off_automated').on('click', function () {
            var checked = $(this).prop('checked');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ route('settings.update.automessages') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    value: checked ? 1 : 0
                }
            }).done(function () {
                $(thiss).siblings('.change_status_message').fadeIn(400);

                setTimeout(function () {
                    $(thiss).siblings('.change_status_message').fadeOut(400);
                }, 2000);
            }).fail(function (response) {
                console.log(response);

                alert('Could not saved the changes');
            })
        });

        $('.edit-auto-reply-button').on('click', function () {
            $(this).closest('tr').find('textarea[name="reply"]').toggleClass('hidden');
            $(this).closest('tr').find('textarea[name="reply"]').siblings('.auto-reply-reply').toggleClass('hidden');
        });

        $('.auto-reply-textarea').keypress(function (e) {
            var key = e.which;
            var thiss = $(this);
            var id = $(this).data('id');

            if (key == 13) {
                e.preventDefault();
                var reply = $(thiss).val();

                $.ajax({
                    type: 'POST',
                    url: "{{ url('autoreply') }}/" + id + '/updateReply',
                    data: {
                        _token: "{{ csrf_token() }}",
                        reply: reply,
                    }
                }).done(function () {
                    $(thiss).addClass('hidden');
                    $(thiss).siblings('.auto-reply-reply').text(reply);
                    $(thiss).siblings('.auto-reply-reply').removeClass('hidden');
                }).fail(function (response) {
                    console.log(response);

                    alert('Could not update reply');
                });
            }
        });

    </script>
@endsection
