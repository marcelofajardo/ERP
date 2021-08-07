@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Google Search Results (<span>{{ $posts->total() }}</span>)</h2>
        </div>
        <div class="col-md-12 mt-4">
            {{ $posts->appends($request->all())->render()}}
            <table id="table" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th width="16%"><a href="#">#</a></th>
                    <th width="16%"><a href="/google/search/results{{ ($queryString) ? '?'.$queryString : '?' }}sortby=posted_at&orderby={{ ($orderBy == 'ASC') ? 'DESC' : 'ASC' }}">Date</a></th>
                    <th width="16%"><a href="/google/search/results{{ ($queryString) ? '?'.$queryString : '?' }}sortby=hashtag&orderby={{ ($orderBy == 'ASC') ? 'DESC' : 'ASC' }}">Keyword</a></th>
                    <th width="26%"><a href="/google/search/results{{ ($queryString) ? '?'.$queryString : '?' }}sortby=location&orderby={{ ($orderBy == 'ASC') ? 'DESC' : 'ASC' }}">Location</a></th>
                    <th width="42%">Post</th>
                </tr>
                <tr>
                    <th width="10%"><a href=""></a></th>
                    <th><input type="text" id="date" class="form-control" value="{{ isset($_GET['date']) ? $_GET['date'] : '' }}"></th>
                    <th><input type="text" id="hashtag" class="form-control" value="{{ isset($_GET['hashtag']) ? $_GET['hashtag'] : '' }}"></th>
                    <th><input type="text" id="location" class="form-control" value="{{ isset($_GET['location']) ? $_GET['location'] : '' }}"></th>
                    <th><input type="text" id="post" class="form-control" value="{{ isset($_GET['post']) ? $_GET['post'] : '' }}"></th>
                </tr>
                </thead>
                <tbody>
                @include('google.search.row_results', ['posts' => $posts])
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
            $('#date,#hashtag,#location,#post').on('blur', function () {
                var queryString = '';
                if($('#date').val() != ''){
                    queryString += 'date=' + $('#date').val() + '&';
                }
                if($('#hashtag').val() != ''){
                    queryString += 'hashtag=' + $('#hashtag').val() + '&';
                }
                if($('#location').val() != ''){
                    queryString += 'location=' + $('#location').val() + '&';
                }
                if($('#post').val() != ''){
                    queryString += 'post=' + $('#post').val() + '&';
                }

                if(queryString != ''){
                    queryString = '?' + queryString;
                }

                window.location.href = '/google/search/results' + queryString;
            });
        });
    </script>
@endsection