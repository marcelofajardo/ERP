@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Edit: {{ $tag->name }}</h2>
        </div>
        <div class="col-md-12">
            @if(Session::has('message'))
                <div class="alert alert-info">
                    {{ Session::get('message') }}
                </div>
            @endif
            <form method="post" action="{{ action('SocialTagsController@update', $tag->id) }}">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="name">Tag Name</label>
                    <input value="{{ $tag->name }}" type="text" name="name" placeholder="Name" id="name" class="form-control">
                </div>
                <div class="form-group">
                    <a href="{{ action('SocialTagsController@index') }}" class="btn btn-info">Back to All Tags</a>
                    <button class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
