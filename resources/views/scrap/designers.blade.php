@extends('layouts.app')

@section('favicon' , 'scraperdesign.png')

@section('title', 'Designers List Info')


@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Designer's List</h2>
        </div>
        <div class="col-md-12 text-center">
            {{ $designers->links() }}
        </div>
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>S.N</th>
                    <th>Website</th>
                    <th>Title</th>
                    <th>Address</th>
                    <th>Designers</th>
                    <th>Image</th>
                </tr>
                @foreach($designers as $key=>$designer)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{ $designer->website }}</td>
                        <td>{{ $designer->title }}</td>
                        <td>{{ $designer->address }}</td>
                        <td>
                            <?php
                            $dns = $designer->designers;
                            $dns = str_replace('"[', '', $dns);
                            $dns = str_replace(']"', '', $dns);
                            $dns = explode(',', $dns);
                            ?>

                            @foreach($dns as $dn)
                                <li>{{ $dn }}</li>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ $designer->image }}">
                                <img src="{!! $designer->image !!}" alt="" style="width: 150px">
                            </a>
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>
        <div class="col-md-12 text-center">
            {{ $designers->links() }}
        </div>
    </div>
@endsection

@section('styles')
@endsection

@section('scripts')
@endsection
