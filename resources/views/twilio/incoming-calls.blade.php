@extends('layouts.app')
@section('content')
    <div class="row" xmlns="http://www.w3.org/1999/html">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Incoming Calls On {{ $phone_number }}</h2>
        </div>
    </div>
    @include('partials.flash_messages')
    <div class="row mb-3">
        <div class="col-md-10 col-sm-12">
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th scope="col" class="text-center">S. No.</th>
                            <th scope="col" class="text-center">Date</th>
                            <th scope="col" class="text-center">Time</th>
                            <th scope="col" class="text-center">From Number</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Answered By</th>
                            <th scope="col" class="text-center">Call Recording</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody class="text-center">
                            @if($incoming_calls)
                                <?php $c= 1; ?>
                                @foreach($incoming_calls->calls as $call)
                                    <tr>
                                        <td>{{ $c++ }}</td>
                                        <td>{{ date('d-m-Y', strtotime($call->date_created)) }}</td>
                                        <td>{{ date('H:i:s', strtotime($call->date_created)) }}</td>
                                        <td>{{ $call->from }}</td>
                                        <td>{{ $call->status }}</td>
                                        <td>{{ $call->answered_by }}</td>
                                        <td>
                                            <a href="{{ route('twilio-incoming-call-recording', $call->sid).'?id='.request()->get('id') }}">Download Recording</a>
                                        </td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
            </div>
        </div>
    </div>

@endsection
