@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>Targeted Country/Region (<a href="{{ action('TargetLocationController@edit', 1) }}">Show Statistics</a>)</h1>
        </div>
        <div class="col-md-12">
            <form action="{{ action('TargetLocationController@store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" name="country" id="country" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="region">Region</label>
                            <input type="text" name="region" id="region" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lng">Longitude (lng, X)</label>
                            <textarea type="text" name="lng" id="lng" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lat">Latitude (lat, Y)</label>
                            <textarea type="text" name="lat" id="lat" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-offset-10 col-md-2">
                        <div class="form-group">
                            <button class="btn btn-info btn-block">Sign Up</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <table class="table-striped table table-sm">
                <tr>
                    <th>S.N</th>
                    <th>Country</th>
                    <th>Region</th>
                    <th>Action</th>
                </tr>
                @foreach($locations as $key=>$location)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{$location->country}}</td>
                        <td>{{$location->region}}</td>
                        <td>
                            <a href="{{ action('TargetLocationController@show',$location->id) }}">
                                Show Users From This Location
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/media-card.css') }}">
@endsection

@section('scripts')
@endsection