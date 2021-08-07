@extends('layouts.app')

@section('title', 'lawyer Info')

@section('styles')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css">
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Lawyer Info</h2>
            <div class="pull-left">
                <form class="form-inline" action="{{ route('lawyer.index') }}" method="GET">
                    <div class="form-group">
                        <input name="term" type="text" class="form-control"
                               value="{{ isset($term) ? $term : '' }}"
                               placeholder="Search">
                    </div>

                    <div class="form-group">
                        <input type="checkbox" name="with_archived"
                               id="with_archived" {{ Request::get('with_archived')=='on'? 'checked' : '' }}>
                        <label for="with_archived">Archived</label>
                    </div>

                    <button type="submit" class="btn btn-info"><i class="fa fa-filter"></i> Filter</button>
                </form>
            </div>
            <div class="pull-right">
                <button type="button" class="btn btn-secondary" data-toggle="modal"
                        data-target="#createLawyerSpecialityModal">Create Speciality
                </button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#lawyerFormModal"
                        title="Add new Lawyer"><i class="fa fa-plus"></i> Create New
                </button>
            </div>
        </div>
    </div>

    @include('partials.flash_messages')

    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="10%">Name</th>
                <th width="10%">Phone</th>
                <th width="10%">Email</th>
                <th width="10%">Address</th>
                <th width="10%">Reference By</th>
                <th width="5%">
                    <a href="{{route('lawyer.index')}}{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=speciality{{ ($orderby == 'ASC') ? '&orderby=DESC' : '' }}">Speciality</a>
                </th>
                <th width="10%">Rating</th>
                <th width="20%">Send</th>
                <th width="20%">Communication</th>
                <th width="10%">Action</th>
            </tr>
            </thead>

            <tbody>
            @forelse ($lawyers as $lawyer)
                <tr>
                    <td>{{ $lawyer->id }}</td>
                    <td style="word-break: break-all;">{{ $lawyer->name }}</td>
                    <td>{{ $lawyer->phone }}</td>
                    <td class="expand-row table-hover-cell" style="word-break: break-all;">
                <span class="td-mini-container">
                  {{ strlen($lawyer->email) > 10 ? substr($lawyer->email, 0, 10) : $lawyer->email }}
                </span>

                        <span class="td-full-container hidden">
                  {{ $lawyer->email }}
                </span>
                    </td>
                    <td style="word-break: break-all;">{{ $lawyer->address }}</td>
                    <td>{{ $lawyer->referenced_by }}</td>
                    <td class="expand-row table-hover-cell">
                        @php
                        $speciality = $lawyer->lawyerSpeciality;
                        @endphp
                        <span class="td-mini-container">
                            {{ strlen(optional($speciality)->title) > 7 ? substr(optional($speciality)->title, 0, 7) : optional($speciality)->title }}
                        </span>
                        <span class="td-full-container hidden">
                            {{ optional($speciality)->title }}
                        </span>
                    </td>
                    <td>{{$lawyer->rating}}</td>
                    <td>
                        <div class="d-flex">
                            <input type="text" class="form-control quick-message-field" name="message"
                                   placeholder="Message" value="">
                            <button class="btn btn-sm btn-image send-message" data-lawyerid="{{ $lawyer->id }}"><img
                                        src="/images/filled-sent.png"/></button>
                        </div>
                    </td>
                    <td class="expand-row table-hover-cell {{ $lawyer->chat_message->count() && $lawyer->chat_message->first()->status == 0 ? 'text-danger' : '' }}"
                        style="word-break: break-all;">
                        @if($lawyer->chat_message->first())
                            <span class="td-mini-container">
                          {{ strlen($lawyer->chat_message->first()->message) > 32 ? substr($lawyer->chat_message->first()->message, 0, 29) . '...' : $lawyer->chat_message->first()->message }}
                        </span>
                            <span class="td-full-container hidden">
                            {{ $lawyer->chat_message->first()->message }}
                        </span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('lawyer.show', $lawyer->id) }}" class="btn btn-image" href=""><img
                                        src="/images/view.png"/></a>

                            <button type="button" class="btn btn-image edit-lawyer" data-toggle="modal"
                                    data-target="#lawyerFormModal" data-lawyer="{{ json_encode($lawyer) }}"><img
                                        src="/images/edit.png"/></button>
                            <button type="button" class="btn btn-image make-remark" data-toggle="modal"
                                    data-target="#makeRemarkModal" data-id="{{ $lawyer->id }}"><img
                                        src="/images/remark.png"/></button>

                            {!! Form::open(['method' => 'DELETE','route' => ['lawyer.destroy', $lawyer->id],'style'=>'display:inline']) !!}
                            <button type="submit" class="btn btn-image"><img src="/images/delete.png"/></button>
                            {!! Form::close() !!}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <th colspan="11" class="text-center text-danger">No Lawyer/s Found.</th>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {!! $lawyers->appends(Request::except('page'))->links() !!}
    @include('lawyer.partials.lawyer-form-modals')
    @include('partials.modals.remarks')
    @include('lawyer.partials.lawyer-speciality-modals')
@endsection

@section('scripts')
    <script>
        @if(request()->has('create'))
        $(document).ready(function(){
            $('#lawyerFormModal').modal();
        })
        @endif
        $('#lawyerFormModal').on('show.bs.modal', function (event) {
            var modal = $(this)
            var button = $(event.relatedTarget)
            var lawyer = button.data('lawyer')
            if (lawyer != undefined) {
                var url = "{{ url('lawyer') }}/" + lawyer.id;
                modal.find('form').attr('action', url);
                var method = '<input type="hidden" name="_method" value="PUT">'
                modal.find('form').append(method)
                modal.find('input[name="_method"]').val('PUT');
                modal.find('#name').val(lawyer.name)
                modal.find('#address').val(lawyer.address)
                modal.find('#phone').val(lawyer.phone)
                modal.find('#email').val(lawyer.email)
                modal.find('#referenced_by').val(lawyer.referenced_by)
                modal.find('#rating').val(lawyer.rating)
                modal.find('#speciality_id option[value="' + lawyer.speciality_id + '"]').attr('selected', 'true')
                modal.find('button[type="submit"]').html('Update')
                modal.find('.modal-title').html('Update a Lawyer')
            } else {
                var url = "{{ route('lawyer.store') }}";
                modal.find('form').attr('action', url);
                modal.find('form').trigger('reset');
                modal.find('button[type="submit"]').html('Add')
                modal.find('.modal-title').html('Store a Lawyer')
                modal.find('input[name="_method"]').remove()
            }
        })
        $(document).on('click', '.expand-row', function () {
            var selection = window.getSelection();
            if (selection.toString().length === 0) {
                $(this).find('.td-mini-container').toggleClass('hidden');
                $(this).find('.td-full-container').toggleClass('hidden');
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
                url: '{{ route('task.gettaskremark') }}',
                data: {
                    id: id,
                    module_type: "lawyer"
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
                url: '{{ route('task.addRemark') }}',
                data: {
                    id: id,
                    remark: remark,
                    module_type: 'lawyer'
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

        $(document).on('click', '.send-message', function () {
            var thiss = $(this);
            var data = new FormData();
            var lawyer_id = $(this).data('lawyerid');
            var message = $(this).siblings('input').val();

            data.append("lawyer_id", lawyer_id);
            data.append("message", message);
            data.append("status", 1);

            if (message.length > 0) {
                if (!$(thiss).is(':disabled')) {
                    $.ajax({
                        url: '/whatsapp/sendMessage/lawyer',
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
    </script>
@endsection
