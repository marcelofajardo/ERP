@extends('layouts.app')

@section('content')
    <table class="table table-striped table-bordered">
        <tr>
            <th>Image</th>
            <th>Color</th>
            <th>Picked Color</th>
            <th>ERP Color</th>
        </tr>
        @foreach($pictures as $picture)
            <tr>
                <td><img src="{{ $picture->image_url }}" alt="" style="width: 250px;"></td>
                <td style="background-color: {{$picture->picked_code}}"></td>
                <td>
                    {{ $picture->picked_color }}
                </td>
                <td>{{ $picture->color }}</td>
            </tr>
        @endforeach
    </table>
@endsection