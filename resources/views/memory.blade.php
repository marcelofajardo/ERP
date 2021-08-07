@extends('layouts.app')

@section('content')
{{--    @php--}}
{{--        $shell = shell_exec("free -m");--}}
{{--        echo "<pre>$shell</pre>";--}}




{{--    @endphp--}}
<h3 class="mt-5">Memory Usage</h3>
    <table style="width: 100%;" class="mt-5 table table-bordered">
        <tr>
            <th>Total</th>
            <th>Used</th>
            <th>Free</th>
            <th>Buff & Cache</th>
            <th>Available</th>
            <th>Created</th>
        </tr>

                @php
                    $array = \App\MemoryUsage::get();
                @endphp
            @foreach ($array as $member)
            <tr>
                <td>{{$member->total}}</td>
                <td>{{$member->used}}</td>
                <td>{{$member->free}}</td>
                <td>{{$member->buff_cache}}</td>
                <td>{{$member->available}}</td>
                <td>{{ \Carbon\Carbon::parse($member->created_at)->format(' F j, Y') }}</td>
            </tr>
            @endforeach



    </table>
@endsection