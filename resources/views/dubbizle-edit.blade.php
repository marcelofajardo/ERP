@extends('layouts.app')

@section('styles')

@endsection

@section('content')


<div class="row">
  <div class="col-lg-12 margin-tb">
    <div class="pull-left">
      <h3><a href="{{ action('DubbizleController@index') }}">Go BACK</a> | Dubbizle Edit Page </h3>
    </div>
  </div>
</div>

@include('partials.flash_messages')


<form method="post" action="{{ action('DubbizleController@update', $d->id) }}">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone_number">Phone number</label>
                <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="phone_number" value="{{$d->phone_number}}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="url">url</label>
                <input type="text" class="form-control" name="url" id="url" placeholder="Url" value="{{$d->url}}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="keywords">keywords</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="keywords" value="{{$d->keywords}}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="post_date">post_date</label>
                <input type="text" class="form-control" name="post_date" id="post_date" placeholder="post_date" value="{{$d->post_date}}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="requirements">url</label>
                <textarea class="form-control"  name="requirements" id="requirements" placeholder="requirements">{{$d->requirements}}</textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="body">body</label>
                <textarea class="form-control"  name="body" id="body" placeholder="body">{{$d->body}}</textarea>
            </div>
        </div>
        <div class="col-md-6">
            <button class="btn btn-info">Save</button>
        </div>
    </div>
    @csrf
    @method('PUT')
</form>

@endsection
