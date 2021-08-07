@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Results For : {{ $tag->name }}</h2>
        </div>
        <iframe class="col-md-12" style="height: 800px;" src="https://www.instagram.com/explore/tags/{{$tag->name}}/"></iframe>
    </div>
@endsection
