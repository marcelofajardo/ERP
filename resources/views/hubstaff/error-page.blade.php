@extends('layouts.app')

@section('content')

<div class="container" style="padding:30px;">
  <div class="token">
      <p>{{ $curl->http_error_message }}</p>
      <p>{{ $curl->response }}</p>
  </div>
</div>
@endsection