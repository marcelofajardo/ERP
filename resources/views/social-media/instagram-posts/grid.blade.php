@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Instagram Posts (Grid)</h2>
        </div>
        <div class="col-md-12 mt-4">
            {{ $posts->appends($request->all())->render() }}
            <table id="table" class="table table-striped">
                <thead>
                <tr>
                    <th width="16%">Date</th>
                    <th width="16%">Hashtag</th>
                    <th width="16%">Author</th>
                    <th width="52%">Post</th>
                </tr>
                <tr>
                    <th><input type="text" id="date" class="form-control" value="{{ isset($_GET['date']) ? $_GET['date'] : '' }}"></th>
                    <th><input type="text" id="hashtag" class="form-control" value="{{ isset($_GET['hashtag']) ? $_GET['hashtag'] : '' }}"></th>
                    <th><input type="text" id="author" class="form-control" value="{{ isset($_GET['author']) ? $_GET['author'] : '' }}"></th>
                    <th><input type="text" id="post" class="form-control" value="{{ isset($_GET['post']) ? $_GET['post'] : '' }}"></th>
                </tr>
                </thead>
                <tbody>
                @include('social-media.instagram-posts.json_grid', ['posts' => $posts])
                </tbody>
            </table>
            {{ $posts->appends($request->all())->render() }}
        </div>
    </div>

    <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>

    <style type="text/css">
        #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
        }
    </style>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#date,#hashtag,#author,#post').on('blur', function () {
                $.ajax({
                    url: '/social-media/instagram-posts/grid',
                    dataType: "json",
                    data: {
                        date: $('#date').val(),
                        hashtag: $('#hashtag').val(),
                        author: $('#author').val(),
                        post: $('#post').val()
                    },
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                }).done(function (data) {
                    $("#loading-image").hide();
                    console.log(data);
                    $("#table tbody").empty().html(data.tbody);
                    if (data.links.length > 10) {
                        $('ul.pagination').replaceWith(data.links);
                    } else {
                        $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                    }
                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    $("#loading-image").hide();
                    alert('No response from server');
                });
            });
        });
    </script>
@endsection