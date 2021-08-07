@extends('bookstack::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('bookstack.name') !!}
    </p>
@endsection
