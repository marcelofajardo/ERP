@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>{{ $targetLocation->country }} ({{$targetLocation->region}})</h1>
        </div>
        <div class="col-md-12">
            <table class="table-striped table table-sm">
                <tr>
                    <th>S.N</th>
                    <th>Username</th>
                    <th>Image</th>
                    <th>Bio</th>
                    <th>Rating</th>
                </tr>
                @foreach($targetLocation->people as $key=>$person)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{$person->username}}</td>
                        <td>
                            <img src="{{ $person->image_url }}" alt="{{ $person->username }}">
                        </td>
                        <td>
                            {{ $person->bio }}
                        </td>
                        <td>
                            {{ $person->rating }}
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