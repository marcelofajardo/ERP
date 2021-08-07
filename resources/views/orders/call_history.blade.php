@extends('layouts.app')

@section('content')

<div class="row">
  <div class="col-lg-12 margin-tb">
    <h2 class="page-heading">Calls History</h2>
  </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <th>Customer Name</th>
      <th>Phone Number</th>
      <th>Status</th>
      <th>Store Website</th>
      <th>Call Time</th>
    </thead>
    <tbody>
      @foreach ($calls as $call)
        <tr>
          <td><a href="{{ $call->customer ? route('customer.show', $call->customer->id) : '#' }}" target="_blank">{{ $call->customer ? $call->customer->name : 'Non Existing Customer' }}</a></td>
          <td>{{ $call->customer ? $call->customer->phone : '' }}</td>
          <td>{{ $call->status }}</td>
          @if ($call->store_website)
            <td>{{ $call->store_website->title }} ({{ $call->store_website->website }})</td>
          @else
            <td> - </td>
          @endif
          <td>{{ \Carbon\Carbon::parse($call->created_at)->format('H:i d-m') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

{!! $calls->appends(Request::except('page'))->links() !!}

@endsection

@section('scripts')
  {{-- <script type="text/javascript">
    jQuery(document).ready(function( $ ) {
      $('audio').on("play", function (me) {
        $('audio').each(function (i,e) {
          if (e !== me.currentTarget) {
            this.pause();
          }
        });
      });
    })
  </script> --}}
@endsection
