@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Sitejabber Accounts, Reviews & Q/A</h2>
        </div>
        <div class="col-md-12">
            <form method="get" action="{{ action('SitejabberQAController@accounts') }}">
                <div class="row">
                    <div class="col-md-2">
                        <input class="form-control form-control-sm" type="date" value="{{$request->get('date')}}" name="date" id="date">
                    </div>
                    <div class="col-md-2">
                        <select name="filter" id="filter" class="form-control form-control-sm">
                            <option value="">Select Filter..</option>
                            <option {{ $request->get('filter')==='live' ? 'selected' : '' }} value="live">Live Reviews</option>
                            <option {{ $request->get('filter')==='approved' ? 'selected' : '' }} value="approved">Approved Reviews</option>
                            <option {{ $request->get('filter')==='unapproved' ? 'selected' : '' }} value="unapproved">Unapproved Reviews</option>
                            <option {{ $request->get('filter')==='not_live' ? 'selected' : '' }} value="not_live">Not Live Reviews</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-sm btn-secondary">Ok</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12 mt-5">
            <ul class="nav nav-tabs" role="tablist">
                <li class="active">
                    <a id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Accounts & Reviews</a>
                </li>
{{--                <li>--}}
{{--                    <a id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Q&A</a>--}}
{{--                </li>--}}
                <li>
                    <a id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Reports</a>
                </li>
                <li>
                    <a id="templates-tab" data-toggle="tab" href="#templates" role="tab" aria-controls="templates" aria-selected="false">Review Templates</a>
                </li>
                <li>
                    <a id="comments-tab" data-toggle="tab" href="#comments" role="tab" aria-controls="comments" aria-selected="false">Negative Comments</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <table id="table" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>S.N</th>
                            <th>Name</th>
                            <th>E-Mail Address</th>
                            <th>Password</th>
                            <th>Created On</th>
                            <th>Reviews Posted</th>
                            <th>Approval Status</th>
                            <th>Post Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($accounts as $key=>$sj)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $sj->first_name ?? 'N/A' }} {{ $sj->last_name ?? 'N/A' }}</td>
                                <td>{{ $sj->email }}</td>
                                <td>{{ $sj->password }}</td>
                                <td>{{ $sj->reviews()->first() ? $sj->reviews()->first()->updated_at->format('Y-m-d') : 'N/A' }}</td>

                                <td>
                                    @if ($sj->reviews()->count())
                                        @foreach($sj->reviews as $answer)
                                            <div class="@if($answer->status=='posted_one') text-danger @elseif($answer->status=='posted') text-success @elseif($answer->is_approved) text-dark @else text-info @endif">
                                                <strong>{{ $answer->title }}</strong><br>{{ $answer->review }}
                                            </div>
                                            @if($answer->status!= 'posted' && $answer->status!= 'posted_one')
                                                <form method="post" action="{{ action('ReviewController@destroy', $answer->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="{{ action('ReviewController@edit', $answer->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    @if(!$answer->is_approved)
                                                        <a title="Approve" href="{{ action('ReviewController@updateStatus', $answer->id) }}?id_approved=1" class="btn btn-sm btn-info">
                                                            <i class="fa fa-check"></i>
                                                        </a>
                                                    @endif
                                                    <button class="btn btn-sm btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="accordion" id="accordionExample">
                                            <div class="card mt-0" style="width:400px;">
                                                <div class="card-header">
                                                    <div style="cursor: pointer;font-size: 20px;font-weight: bolder;" data-toggle="collapse" data-target="#form_am" aria-expanded="true" aria-controls="form_am">
                                                        Attach A New Review
                                                    </div>
                                                </div>
                                                <div id="form_am" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <form action="{{ action('ReviewController@store') }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="account_id" value="{{ $sj->id }}">
                                                            <div class="form-group">
                                                                <input name="title" type="text" class="form-control" placeholder="Enter Title...">
                                                            </div>
                                                            <div class="form-group">
                                                                <textarea class="form-control review-editor-box" data-id="{{$key+1}}" name="review" id="review_{{$key+1}}" rows="3" placeholder="Enter Body..."></textarea>
                                                                <span class="letter_count_review_{{$key+1}}"></span>
                                                            </div>
                                                            <div class="text-right">
                                                                <button class="btn btn-success">Attach A Review</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">{!! (isset($answer) && $answer->is_approved) ? '<img src="/images/active.png" style="width:20px;">' : '<img src="/images/inactive.png" style="width:20px;">'!!}</td>
                                <td class="text-center"><a href="{{ action('SitejabberQAController@confirmReviewAsPosted', isset($answer) ? $answer->id : '') }}">{!! (isset($answer) && $answer->status =='posted') ? '<img src="/images/active.png" style="width:20px;">' : '<img src="/images/inactive.png" style="width:20px;">'!!}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
{{--                <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">--}}
{{--                    <div class="accordion" id="accordionExample">--}}
{{--                        <div class="card mt-0">--}}
{{--                            <div class="card-header">--}}
{{--                                <div style="cursor: pointer;font-size: 20px;font-weight: bolder;" data-toggle="collapse" data-target="#form_amx" aria-expanded="true" aria-controls="form_amx">--}}
{{--                                    Attach A New Question--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div id="form_amx" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">--}}
{{--                                <div class="card-body">--}}
{{--                                    <form action="{{ action('SitejabberQAController@store') }}" method="post">--}}
{{--                                        @csrf--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="question">Question</label>--}}
{{--                                            <input name="question" type="text" id="question" class="form-control" placeholder="Type your question..">--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="account_id">Poster</label>--}}
{{--                                            <select name="account_id" type="text" id="account_id" class="form-control">--}}
{{--                                                @foreach($accounts as $account)--}}
{{--                                                    <option value="{{ $account->id }}">{{ $account->first_name }} {{ $account->last_name }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                        <div class="text-right">--}}
{{--                                            <button class="btn btn-success">Add Question</button>--}}
{{--                                        </div>--}}
{{--                                    </form>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <br>--}}
{{--                    <table id="table2" class="table table-striped table-bordered">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th>I.D</th>--}}
{{--                            <th>Question</th>--}}
{{--                            <th>Answers</th>--}}
{{--                            <th>Status</th>--}}
{{--                            <th>Reply</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        @foreach($sjs as $kkk=>$sj)--}}
{{--                            <tr>--}}
{{--                                <th>{{$kkk+1}}</th>--}}
{{--                                <th>{{$sj->text}}</th>--}}
{{--                                <td>--}}
{{--                                    <table class="table table-striped">--}}
{{--                                        @foreach($sj->answers as $answer)--}}
{{--                                            <tr>--}}
{{--                                                <td>{{ $answer->author }} <span class="badge badge-success">{{$answer->type}}</span> @if ($answer->status == 1) <span class="badge badge-primary">Posted</span> @endif</td>--}}
{{--                                                <td>{{ $answer->text }}</td>--}}
{{--                                            </tr>--}}
{{--                                        @endforeach--}}
{{--                                    </table>--}}
{{--                                </td>--}}
{{--                                <td class="text-center">{!! $sj->status==1 ? '<img src="/images/active.png" style="width:20px;">' : '<img src="/images/inactive.png" style="width:20px;">'!!}</td>--}}
{{--                                <td>--}}
{{--                                    <div class="form-group" style="width: 400px;">--}}
{{--                                        <form action="{{ action('SitejabberQAController@update', $sj->id) }}" method="post">--}}
{{--                                            @csrf--}}
{{--                                            @method('put')--}}
{{--                                            <textarea type="text" name="reply" class="form-control" placeholder="Type reply..."></textarea>--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label for="account_id">Poster</label>--}}
{{--                                                <select name="account_id" type="text" id="account_id" class="form-control">--}}
{{--                                                    @foreach($accounts as $account)--}}
{{--                                                        <option value="{{ $account->id }}">--}}
{{--                                                            {{ $account->first_name }} {{ $account->last_name }}--}}
{{--                                                            @if ($account->reviews()->first())--}}
{{--                                                                (Review: {{ $account->reviews()->first()->title }})--}}
{{--                                                            @endif--}}
{{--                                                        </option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
{{--                                            </div>--}}
{{--                                            <div class="text-right">--}}
{{--                                                <button class="btn btn-success mt-1">Reply To Thread</button>--}}
{{--                                            </div>--}}
{{--                                        </form>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
                <div class="tab-pane" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <br>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th colspan="2" class="text-center">Reviews Posted Today</th>
                        </tr>
                            @foreach($reviewsPostedToday as $rev)
                                <tr>
                                    <td><strong>{{$rev->title}}</strong><br>{{ $rev->review }}</td>
                                    <td>
                                        @if($rev->status=='posted_one')
                                            <strong>Not Live Yet</strong>
                                        @else
                                            <strong>Live</strong>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                    </table>
                    <table class="table table-bordered">
                        <tr>
                            <td>Account Remaining</td>
                            <td>{{$accountsRemaining}}</td>
                        </tr>
                        <tr>
                            <td>Reviews Remaining</td>
                            <td>{{ $remainingReviews }}</td>
                        </tr>
                    </table>
{{--                    <form method="get" action="{{action('SitejabberQAController@edit', 'routines')}}">--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="range">Post this number of reviews in a day</label>--}}
{{--                                <input name="range" id="range" type="number" class="form-control" placeholder="Eg: 6" value="{{ $setting->times_a_day }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="range2">Create this number of SJ account in a day</label>--}}
{{--                                <input name="range2" id="range2" type="number" class="form-control" placeholder="Eg: 6" value="{{ $setting2->times_a_day }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="range3">Post this number of question every week</label>--}}
{{--                                <input name="range3" id="range3" type="number" class="form-control" placeholder="Eg: 6" value="{{ $setting3->times_a_week }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-4">--}}
{{--                            <button class="mt-4 btn btn-primary">Ok</button>--}}
{{--                        </div>--}}
{{--                    </form>--}}
                </div>
                <div class="tab-pane" id="templates" role="tabpanel" aria-labelledby="templates-tab">
                    <form method="post" action="{{ action('SitejabberQAController@attachOrDetachReviews') }}">
                        <span class="form-group" style="display:inline-block;width: 85% !important;;">
                            <select class="form-control" name="action" id="action">
                                <option value="attach">Attach</option>
                                <option value="delete">Delete</option>
                            </select>
                        </span>
                        <span style="display:inline-block; width: 12% !important;" class="form-group">
                            <button class="btn btn-sm btn-info form-control">Go</button>
                        </span>
                        @csrf
                        <table id="table3" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Platform</th>
                                <th>brand</th>
                                <th>Title</th>
                                <th>Body</th>
                                <th>Created At</th>
                                <th>Attach For Approval</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($brandReviews as $key=>$brandReview)
                                <tr>
                                    <th>{{ $key+1 }}</th>
                                    <td>{{ $brandReview->website }}</td>
                                    <td>{{ $brandReview->brand }}</td>
                                    <td>{{ $brandReview->title }}</td>
                                    <td>{{ $brandReview->body }}</td>
                                    <td>{{ $brandReview->created_at->format('Y-m-d') }}</td>
                                    <td width="100px;">
                                        <input type="checkbox" name="reviewTemplate[]" id="reviewTemplate" value="{{$brandReview->id}}">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="tab-pane" id="comments" role="tabpanel" aria-labelledby="comments-tab">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>S.N</th>
                            <th>Username</th>
                            <th>title</th>
                            <th>Body</th>
                            <th>Reply</th>
                        </tr>
                        @foreach($negativeReviews as $key=>$negativeReview)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $negativeReview->username }}</td>
                                <td class="expand-row">
                                    <span class="td-mini-container">
                                        {{ strlen($negativeReview->title) > 20 ? substr($negativeReview->title, 0, 20).'...' : $negativeReview->title }}
                                    </span>

                                    <span class="td-full-container hidden">
                                        {{ $negativeReview->title }}
                                    </span>
                                </td>
                                <td class="expand-row">
                                    <span class="td-mini-container">
                                        {{ strlen($negativeReview->body) > 20 ? substr($negativeReview->body, 0, 20).'...' : $negativeReview->body }}
                                    </span>
                                    <span class="td-full-container hidden">
                                        {{ $negativeReview->body }}
                                    </span>
                                </td>
                                <td class="expand-row">
                                    @if($negativeReview->reply != '')
                                        <span class="td-mini-container">
                                            {{ strlen($negativeReview->reply) > 20 ? substr($negativeReview->reply, 0, 20).'...' : $negativeReview->reply }}
                                          </span>

                                        <span class="td-full-container hidden">
                                            {{ $negativeReview->reply }}
                                        </span>
                                    @else
                                        <div class="form-group">
                                            <input data-rid="{{$negativeReview->id}}" data-title="{{$negativeReview->title}}" style="width: 300px;" type="text" class="form-control reply-review" id="reply_{{$negativeReview->id}}" name="reply_{{ $negativeReview->id }}">
                                            <select class="form-control quick-reply" data-id="{{$negativeReview->id}}" name="quick-reply-{{$negativeReview->id}}" id="quick-reply-{{$negativeReview->id}}">
                                                <option value="">None</option>
                                                @foreach($quickReplies as $rep)
                                                    <option value="{{$rep->text}}">{{$rep->text}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/media-card.css') }}">
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
            $(document).on('click', '.expand-row', function() {
                var selection = window.getSelection();
                if (selection.toString().length === 0) {
                    // if ($(this).data('switch') == 0) {
                    //   $(this).text($(this).data('details'));
                    //   $(this).data('switch', 1);
                    // } else {
                    //   $(this).text($(this).data('subject'));
                    //   $(this).data('switch', 0);
                    // }
                    $(this).find('.td-mini-container').toggleClass('hidden');
                    $(this).find('.td-full-container').toggleClass('hidden');
                }
            });
            $(document).on('change', '.quick-reply', function(event) {
                let id = $(this).attr('data-id');
                let r = $(this).val();
                $("#reply_"+id).val(r);
            });

            $('.review-editor-box').keyup(function() {
                let data = $(this).val();
                let length = data.length;
                let id = $(this).attr('data-id');
                $('.letter_count_review_'+id).html(length);
            });
            $('.reply-review').keyup(function(event) {
                let title = $(this).attr('data-title');
                let message = $(this).val();
                let rid = $(this).attr('data-rid');
                let self = this;
                if (event.keyCode==13) {

                    $(this).attr('disabled', true);
                    $.ajax({
                        url: '{{ action('SitejabberQAController@sendSitejabberQAReply') }}',
                        type: 'post',
                        data: {
                            comment: title,
                            reply: message,
                            rid: rid,
                            _token: "{{csrf_token()}}"
                        },
                        success: function(response) {
                            // $(self).removeAttr('disabled');
                            alert('Posted successfully!');
                            location.reload();
                        },
                        error: function() {
                            $(this).removeAttr('disabled');
                            location.reload();
                            alert('Couldnt post the reply!');
                        }

                    });
                }
            });
        });
    </script>
    @if (Session::has('message'))
        <script>
            toastr["success"]("{{ Session::get('message') }}", "Message")
        </script>
    @endif
@endsection