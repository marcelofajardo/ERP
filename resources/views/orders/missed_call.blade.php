@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Missed Call</h2>

    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th style="width: 30%">Mobile Number</th>
            <th style="width: 50%">Message</th>
            <th style="width: 50%">Call Recording</th>
            <th style="width: 50%">Call Time</th>
            <th class="text-right" style="width: 10%">Action</th>
        </tr>
        @foreach ($callBusyMessages['data'] as $key => $callBusyMessage)
        <tr class="">
            <td>
              @if(isset($callBusyMessage['customer_name']))
                {{ $callBusyMessage['customer_name'] }}
              @else
                {{ $callBusyMessage['twilio_call_sid'] }}
              @endif
            </td>
            <td>{{ $callBusyMessage['message'] }}</td>
             <td><audio src="{{$callBusyMessage['recording_url']}}" controls preload="metadata">
  <p>Alas, your browser doesn't support html5 audio.</p>
</audio> </td>
            <td>{{ $callBusyMessage['created_at'] }}</td>

            <td>
                @if(isset($callBusyMessage['customerid']))
                <a class="btn btn-image" href="{{ route('customer.show',$callBusyMessage['customerid']) }}"><img src="/images/view.png" /></a>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>

 <script type="text/javascript">

jQuery(document).ready(function( $ ) {
  $('audio').on("play", function (me) {
    $('audio').each(function (i,e) {
      if (e !== me.currentTarget) {
        this.pause();
      }
    });
  });
})

   </script>

@endsection
