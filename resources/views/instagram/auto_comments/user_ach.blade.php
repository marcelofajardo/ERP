@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
@endsection

@section('large_content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-heading">Bulk Comments <a class="btn btn-info float-right" href="{{ action('UsersAutoCommentHistoriesController@assignPosts') }}">Assign Posts</a><a
                            href="https://docs.google.com/document/d/1GmZ5JIEQfy-EZykHHy6Yp6lPyJcmhBYk30ZBOyHQcOI/edit?usp=sharing" class="pull-right">SOP &nbsp;</a></h2>
            </div>

            <div class="col-md-12">
                <br>
                <br>
                @foreach($comments->chunk(5) as $key=>$commentx)
                    <table id="table" class="table-striped table table-bordered">
                        <tr>
                            <th width="10%">
                                <button class="btn btn-sm send-whatsapp" data-id="{{ $key+1 }}">
                                    <img src="{{ asset('images/whatsapp.png') }}" alt="" title="Send To WhatsApp" style="width: 25px !important;">
                                </button>
                            </th>
                            <th width="10%">Post</th>
                            <th width="50%">Comment</th>
                            <th width="10%">Status</th>
                            <th width="10%">
                                <select class="form-control form-control-sm" name="account_{{$key}}" id="account_{{$key}}">
                                    <option value="">Account select...</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->last_name }}</option>
                                    @endforeach
                                </select>
                            </th>
                        </tr>
                        @foreach($commentx as $k=>$comment)
                            <tr>
                                <td>
                                    {{ $k+1 }}
                                    <input type="hidden" name="whatsapp_{{$key+1}}[]" value="{{ $comment->id }}">
                                </td>
                                <td>
                                    <a target="_new" href="https://instagram.com/p/{{$comment->post_code}}">Visit post</a>
                                </td>
                                <td>{{$comment->comment ?? 'N/A'}}</td>
                                <td>
                                    <span class="label label-{{ $comment->status ? 'success' : 'danger' }}">{{ $comment->status ? 'Posted' : 'Not Posted' }}</span>
                                </td>
                                <td>
                                    @if ($comment->is_verified)
                                        <span class="label label-success">Verified</span>
                                    @else
                                        <button class="btn btn-default btn-xs verify-comment" data-key="{{ $key }}" id="verify_{{$comment->id}}" data-commentId="{{$comment->id}}"><img src="{{ asset('images/1.png') }}" alt=""> <span>Verify</span></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endforeach
            </div>

            <div class="text-center col-md-12">
                {!! $comments->links() !!}
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).on('click', '.send-whatsapp', function() {
            let keyId = $(this).attr('data-id');
            postIds = [];

            $('input[name^="whatsapp_'+keyId+'"]').each(function() {
                postIds.push($(this).val());
            });

            $.ajax({
                url: '{{ action('UsersAutoCommentHistoriesController@sendMessagesToWhatsappToScrap') }}',
                data: {
                    posts: postIds
                },
                success: function(response) {
                    toastr['success']('Posts sent successfully', 'success');
                }
            });
        });

        $(document).on('click', '.verify-comment', function() {
            let key = $(this).attr('data-key');
            let accountid = $('#account_'+key).val();
            if (!accountid) {
                alert('You must choose account first!');
                return;
            }
            let commentId = $(this).attr('data-commentId');
            let button = this;
            $.ajax({
                url: "{{ action('UsersAutoCommentHistoriesController@verifyComment') }}",
                data: {
                    id: commentId,
                    account_id: accountid
                },
                success: function(response) {
                    if (response.status == 'verified') {
                        $(button).find('span').html('Verified');
                    } else {
                        $(button).find('span').html('Updated');
                    }
                }
            });
        });
    </script>
@endsection
