@extends('layouts.app')

@section('favicon' , 'scrapperdubbizle.png')

@section('title', 'Dubbizle - ERP Sololuxury')

@section('large_content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Dubbizle Posts</h2>
        </div>

        <div class="col-12">
          <div class="pull-left"></div>

          <div class="pull-right">
            <div class="form-group">
              <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#bulkWhatsappModal">Bulk Whatsapp</button>
            </div>
          </div>
        </div>
    </div>

    @include('partials.modals.bulk-whatsapp')

    <div class="row">
        <div class="col-md-12">
            <table id="table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>S.N</th>
                        <th>URL</th>
                        <th>Keywords</th>
                        <th width="10%">Requirement</th>
                        <th width="20%">Body</th>
                        <th>Phone #</th>
                        <th>Post</th>
                        <th width="10%">Communication</th>
                        <th width="30%">Send Message</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $key=>$post)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td style="word-break: break-word !important; word-wrap: break-word !important;">
                                <a href="{{ $post->url }}">Visit</a>
                            </td>
                            <td style="word-break: break-all !important; word-wrap: break-word !important;">{{ $post->keywords }}</td>
                            <td style="word-break: break-all !important; word-wrap: break-word !important;">{{ $post->requirements }}</td>
                            <td style="word-break: break-all !important; word-wrap: break-word !important;">{{ $post->body }}</td>
                            <td>{{ $post->phone_number }}</td>
                            <td>{{ $post->post_date }}</td>
                            <td>
                              @if (isset($post->message))
                                {{ strlen($post->message) > 100 ? substr($post->message, 0, 97) . '...' : $post->message }}
                              @endif

                              {{-- <button type="button" class="btn btn-xs btn-secondary load-more-communication" data-id="{{ $customer->id }}">Load More</button>

                              <ul class="more-communication-container">

                              </ul> --}}
                            </td>
                            <td>
                              <div class="d-inline">
                                <input type="text" class="form-control quick-message-field" name="message" placeholder="Message" value="">
                                <button class="btn btn-sm btn-image send-message" data-dubbizleid="{{ $post->id }}"><img src="/images/filled-sent.png" /></button>
                              </div>
                            </td>
                            <td>
                              <a href="{{ route('dubbizle.show', $post->id) }}" class="btn btn-image"><img src="/images/view.png" /></a>
                                <a class="btn btn-sm btn-info" href="{{ action('DubbizleController@edit', $post->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <button data-toggle="modal" data-target="#reminderModal" class="btn btn-image set-reminder" data-id="{{ $post->id }}" data-frequency="{{ $post->frequency ?? '0' }}" data-reminder_message="{{ $post->reminder_message }}">
                                    <img src="{{ asset('images/alarm.png') }}" alt=""  style="width: 18px;">
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="reminderModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Set/Edit Reminder</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="frequency">Frequency (in Minutes)</label>
                        <select class="form-control" name="frequency" id="frequency">
                            <option value="0">Disabled</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="25">25</option>
                            <option value="30">30</option>
                            <option value="35">35</option>
                            <option value="40">40</option>
                            <option value="45">45</option>
                            <option value="50">50</option>
                            <option value="55">55</option>
                            <option value="60">60</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="reminder_message">Reminder Message</label>
                        <textarea name="reminder_message" id="reminder_message" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-secondary save-reminder">Save</button>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <style>
        thead input {
            width: 100%;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table thead tr').clone(true).appendTo( '#table thead' );
            $('#table thead tr:eq(1) th').each( function (i) {
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control input-sm" placeholder="Search '+title+'" />' );

                $( 'input', this ).on( 'keyup change', function () {
                    if ( table.column(i).search() !== this.value ) {
                        table
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
            var table = $('#table').dataTable({
                orderCellsTop: true,
                fixedHeader: true
            });
        });

        var dubbzileToRemind;

        $(document).on('click', '.set-reminder', function() {
            let dubbizleId = $(this).data('id');
            let frequency = $(this).data('frequency');
            let message = $(this).data('reminder_message');

            $('#frequency').val(frequency);
            $('#reminder_message').val(message);
            dubbzileToRemind = dubbizleId;

        });

        $(document).on('click', '.save-reminder', function() {
            let frequency = $('#frequency').val();
            let message = $('#reminder_message').val();

            $.ajax({
                url: "{{action('DubbizleController@updateReminder')}}",
                type: 'POST',
                success: function() {
                    toastr['success']('Reminder updated successfully!');
                },
                data: {
                    dubbizle_id: dubbzileToRemind,
                    frequency: frequency,
                    message: message,
                    _token: "{{ csrf_token() }}"
                }
            });
        });

        var cached_suggestions = localStorage['message_suggestions'];
        var suggestions = [];

        $(document).on('click', '.send-message', function() {
          var thiss = $(this);
          var data = new FormData();
          var dubbizle_id = $(this).data('dubbizleid');
          var message = $(this).siblings('input').val();

          data.append("dubbizle_id", dubbizle_id);
          data.append("message", message);
          data.append("status", 1);

          if (message.length > 0) {
            if (!$(thiss).is(':disabled')) {
              $.ajax({
                url: '/whatsapp/sendMessage/dubbizle',
                type: 'POST',
               "dataType"    : 'json',           // what to expect back from the PHP script, if anything
               "cache"       : false,
               "contentType" : false,
               "processData" : false,
               "data": data,
               beforeSend: function() {
                 $(thiss).attr('disabled', true);
               }
             }).done( function(response) {
                $(thiss).siblings('input').val('');

                if (cached_suggestions) {
                  suggestions = JSON.parse(cached_suggestions);

                  if (suggestions.length == 10) {
                    suggestions.push(message);
                    suggestions.splice(0, 1);
                  } else {
                    suggestions.push(message);
                  }
                  localStorage['message_suggestions'] = JSON.stringify(suggestions);
                  cached_suggestions = localStorage['message_suggestions'];

                  console.log('EXISTING');
                  console.log(suggestions);
                } else {
                  suggestions.push(message);
                  localStorage['message_suggestions'] = JSON.stringify(suggestions);
                  cached_suggestions = localStorage['message_suggestions'];

                  console.log('NOT');
                  console.log(suggestions);
                }

                // $.post( "/whatsapp/approve/customer", { messageId: response.message.id })
                //   .done(function( data ) {
                //
                //   }).fail(function(response) {
                //     console.log(response);
                //     alert(response.responseJSON.message);
                //   });

                $(thiss).attr('disabled', false);
              }).fail(function(errObj) {
                $(thiss).attr('disabled', false);

                alert("Could not send message");
                console.log(errObj);
              });
            }
          } else {
            alert('Please enter a message first');
          }
        });
    </script>
    @if (Session::has('message'))
        <script>
            toastr["success"]("{{ Session::get('message') }}", "Message")
        </script>
    @endif
@endsection
