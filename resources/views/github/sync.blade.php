@extends('layouts.app')

@section('content')
<div class="container text-center">
    <a href="{{ url('github/sync/start') }}" class="btn btn-primary">Sync Github data</a>
</div>
@endsection