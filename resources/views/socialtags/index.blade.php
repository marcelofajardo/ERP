@extends('layouts.app')

@section('favicon' , 'socailtags.png')

@section('title', 'Social Tags - ERP Sololuxury')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Social Tags</h2>
        </div>
        <div class="col-md-12">
            @if(Session::has('message'))
                <div class="alert alert-info">
                    {{ Session::get('message') }}
                </div>
            @endif
            <form method="post" action="{{ action('SocialTagsController@store') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Tag Name</label>
                    <input type="text" name="name" placeholder="Name" id="name" class="form-control">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
                @foreach($tags as $key=>$tag)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{ $tag->name }}</td>
                        <td>
                            <a target="_new" href="https://www.facebook.com/hashtag/{{$tag->name}}" class="btn btn-primary">Show Facebook</a>&nbsp;
                            <a target="_new" href="{{ action('SocialTagsController@show', $tag->id) }}" class="btn btn-warning">Show Instagram</a>&nbsp;
                            <a href="{{ action('SocialTagsController@edit', $tag->id) }}" class="btn btn-info">Edit</a>&nbsp;
                            <form style="display: inline" action="{{ action('SocialTagsController@destroy', $tag->id) }}" method="post">
                                @method('delete')
                                @csrf
                                <button class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </table>
        </div>
    </div>
@endsection
