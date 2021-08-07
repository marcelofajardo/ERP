@extends('layouts.app')

@section('title', 'Old Vendor Info')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@section('large_content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">{{ $title }}</h2>
            <div class="pull-left">
                <form class="form-inline" action="{{ route('old.index') }}" method="GET">
                    <div class="form-group">
                        <input name="term" type="text" class="form-control"
                               value="{{ isset($term) ? $term : '' }}"
                               placeholder="Search">
                    </div>
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div class="form-group">
                        <select class="form-control" name="status">
                            <option value="">Select Status</option>
                            @foreach($status as $stat)
                            <option value="{{ $stat }}">{{ $stat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-image"><img src="/images/filter.png"/></button>
                </form>
            </div>
            <div class="pull-right">
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#emailToAllModal">Bulk Email</button>
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#createOldCategorytModal">Create Category</button>
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#oldCreateModal">+</button>
            </div>
        </div>
    </div>

    @include('partials.flash_messages')
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group">
                <div class="panel mt-5 panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#collapse1">Category Assignments</a>
                        </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse">
                        <div class="panel-body">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>Category</th>
                                    <th>Responsible User</th>
                                </tr>
                                @foreach($old_categories as $cat)
                                    <tr>
                                        <td>{{ $cat->category }}</td>
                                        <td>
                                            <select class="form-control update-category-user" data-categoryId="{{$cat->id}}" name="user_id" id="user_id_{{$cat->id}}">
                                                <option value="">None</option>
                                                @foreach($users as $user)
                                                    <option value="{{$user->id}}" {{$user->id==$cat->user_id ? 'selected': ''}}>{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
   <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th width="2%">ID</th>
                <th width="2%">Category</a></th>
                <th width="10%">Name</th>
                <th width="5%">Phone</th>
                <th width="10%">Email</th>
                <th width="10%">Address</th>
                 <th width="3%">Total Amount</th>
                <th width="3%">Pending Amount</th>
                <th width="20%">Send</th>
                <th width="5%">Communication</th>
                <th width="10%">Status</th>
                @if($type != 2)
                @else
                 <th width="10%">Type</th>
                @endif
                <th width="10%">Action</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($olds as $old)
                <tr>
                    <td>{{ $old->serial_no }}</td>
                    <td class="expand-row table-hover-cell">
                        <span class="td-full-container">
                         @if(isset($old->category->category)) {{ $old->category->category }} @endif
                        </span>
                    </td>
                    <td style="word-break: break-all;">{{ $old->name }}
                    @if($old->phone)
                        <div>
                            <button type="button" class="btn btn-image call-twilio" data-context="old" data-id="{{ $old->id }}" data-phone="{{ $old->phone }}"><img src="/images/call.png"/></button>

                        @if ($old->is_blocked == 1)
                                <button type="button" class="btn btn-image block-twilio" data-id="{{ $old->serial_no }}"><img src="/images/blocked-twilio.png"/></button>
                            @else
                                <button type="button" class="btn btn-image block-twilio" data-id="{{ $old->serial_no }}"><img src="/images/unblocked-twilio.png"/></button>
                            @endif
                        </div>
                    @endif
                    </td>
                    <td>{{ $old->phone }}</td>
                    <td class="expand-row table-hover-cell" style="word-break: break-all;">
                        <span class="td-mini-container">
                         {{ $old->email }}
                        </span>
                    </td>
                    <td style="word-break: break-all;">{{ $old->address }}</td>
                    <td style="word-break: break-all;">{{ $old->amount }}</td>
                    <td style="word-break: break-all;">{{ $old->pending_payment }}</td>

                    <td>
                        <div class="d-flex">
                            <input type="text" class="form-control quick-message-field" name="message" placeholder="Message" value="">
                            <button class="btn btn-sm btn-image send-message" data-id="{{ $old->serial_no }}"><img src="/images/filled-sent.png"/></button>
                        </div>
                    </td>
                    <td class="table-hover-cell {{ $old->message_status == 0 ? 'text-danger' : '' }}" style="word-break: break-all;">
                        <span class="td-full-container">
                            {{ $old->message }}
                            <button data-toggle="tooltip" type="button" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}" class="btn btn-xs btn-image load-communication-modal" data-object='old' data-id="{{ $old->serial_no }}" title="Load More..."><img src="/images/chat.png" alt=""></button>
                        </span>
                    </td>
                    <td>
                        <select class="form-control" id="status-update" data-id="{{ $old->serial_no }}">
                            @foreach($status as $statu)
                            <option @if($statu == $old->status) selected @endif>{{ $statu }}</option>
                            @endforeach
                        </select>
                    </td>
                    @if($type != 2)
                        @else
                        <td> 
                        @if($old->is_payable == 0) 
                            Recieving Payment 
                        @else Out Going Payment 
                        </td>
                        @endif
                        @endif
                    
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('old.show', $old->serial_no) }}" class="btn btn-image" href=""><img src="/images/view.png"/></a>
                            <button type="button" class="btn btn-image edit-old" data-toggle="modal" data-target="#oldEditModal" data-old="{{ json_encode($old) }}"><img src="/images/edit.png"/></button>
                            <button type="button" class="btn btn-image make-remark" data-toggle="modal" data-target="#makeRemarkModal" data-id="{{ $old->serial_no }}"><img src="/images/remark.png"/></a>
                                <!-- <button data-toggle="modal" data-target="#zoomModal" class="btn btn-image set-meetings" data-id="{{ $old->serial_no }}" data-type="old"><i class="fa fa-video-camera" aria-hidden="true"></i></button> -->
                                {!! Form::open(['method' => 'DELETE','route' => ['old.destroy', $old->serial_no ],'style'=>'display:inline']) !!}
                                <button type="submit" class="btn btn-image"><img src="/images/delete.png"/></button>
                            {!! Form::close() !!}
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div id="chat-list-history" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Communication</h4>
                </div>
                <div class="modal-body" style="background-color: #999999;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
  @include('partials.modals.remarks')  
  @include('old.partials.old-category-modals')
  @include('old.partials.modal-emailToAll')
  @include('old.partials.old-modals')
  @include('customers.zoomMeeting');

    <!-- <div id="reminderModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

           

        </div>
    </div> -->

@endsection

@section('scripts')

<script type="text/javascript">
    $(document).on('click', '.edit-old', function () {
            var old = $(this).data('old');
            var url = "{{ url('old') }}/" + old.serial_no;
            //console.log(old.serial_no);    
            $('#oldEditModal form').attr('action', url);
            $('#old_category option[value="' + old.category_id + '"]').attr('selected', true);
            $('#old_name').val(old.name);
            $('#old_address').val(old.address);
            $('#old_phone').val(old.phone);
            $('#old_email').val(old.email);
            $('#old_gst').val(old.gst);
            $('#old_amount').val(old.amount);
            $('#old_account_name').val(old.account_name);
            $('#old_account_number').val(old.account_number);
            $('#old_account_iban').val(old.account_iban);
            $('#old_account_swift').val(old.account_swift);
            $('#pending_payment').val(old.pending_payment);
        });

     $(document).on('click', '.send-message', function () {
            var thiss = $(this);
            var data = new FormData();
            var old_id = $(this).data('id');
            var message = $(this).siblings('input').val();

            data.append("old_id", old_id);
            data.append("message", message);
            data.append("status", 1);

            if (message.length > 0) {
                if (!$(thiss).is(':disabled')) {
                    $.ajax({
                        url: '/whatsapp/sendMessage/old',
                        type: 'POST',
                        "dataType": 'json',           // what to expect back from the PHP script, if anything
                        "cache": false,
                        "contentType": false,
                        "processData": false,
                        "data": data,
                        beforeSend: function () {
                            $(thiss).attr('disabled', true);
                        }
                    }).done(function (response) {
                        $(thiss).siblings('input').val('');

                        $(thiss).attr('disabled', false);
                    }).fail(function (errObj) {
                        $(thiss).attr('disabled', false);

                        alert("Could not send message");
                        console.log(errObj);
                    });
                }
            } else {
                alert('Please enter a message first');
            }
        });

      $(document).on('click', '.make-remark', function (e) {
            e.preventDefault();

            var id = $(this).data('id');
            $('#add-remark input[name="id"]').val(id);

            $.ajax({
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('old.gettaskremark') }}',
                data: {
                    id: id,
                },
            }).done(response => {
                var html = '';

                $.each(response, function (index, value) {
                    html += ' <p> ' + value.remark + ' <br> <small>By ' + value.user_name + ' updated on ' + moment(value.created_at).format('DD-M H:mm') + ' </small></p>';
                    html + "<hr>";
                });
                $("#makeRemarkModal").find('#remark-list').html(html);
            });
        });

        $('#addRemarkButton').on('click', function () {
            var id = $('#add-remark input[name="id"]').val();
            var remark = $('#add-remark').find('textarea[name="remark"]').val();

            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('old.addRemark') }}',
                data: {
                    id: id,
                    remark: remark,
                    
                },
            }).done(response => {
                $('#add-remark').find('textarea[name="remark"]').val('');

                var html = ' <p> ' + remark + ' <br> <small>By You updated on ' + moment().format('DD-M H:mm') + ' </small></p>';

                $("#makeRemarkModal").find('#remark-list').append(html);
            }).fail(function (response) {
                console.log(response);

                alert('Could not fetch remarks');
            });
        });

        $('#status-update').on('change', function() {
            var id = $(this).data('id');
            var value = this.value;
            
             $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('old.update.status') }}',
                data: {
                    id: id,
                    value : value,
                },
            }).done(response => {
                alert('Updated Info');
                location.reload(true);
            }).fail(function (response) {
                console.log(response);
                alert('Could not update old info !');
            });

        });
</script>
@endsection
