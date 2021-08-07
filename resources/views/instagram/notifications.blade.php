@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Recent Comments (Notification)</h2>
        </div>
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>S.N</th>
                    <th>Post Text</th>
                    <th>Post URL</th>
                    <th>Recent Comments</th>
                </tr>
                <?php $c = 1; ?>
                @foreach($commentsFinal as $post)
                    <tr>
                        <td>{{ $c }} <?php $c++; ?></td>
                        <td><div style="width: 400px;word-wrap: break-word;word-break: break-word">
                                {{ $post['text'] }}
                            </div></td>
                        <td><a href="https://instagram.com/{{$post['code']}}">Visit Post</a></td>
                        <td>
                            @foreach($post['comments'] as $comment)
                                <i style="display: block; border-bottom: 1px solid #CCC;"><strong>{{$comment['username']}}</strong> : {{$comment['text']}} <br> <strong>{{ $comment['created_at'] }}</strong></i>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/media-card.css') }}">
@endsection

@section('scripts')

@endsection