@extends('layouts.app')

@section('large_content')
    <div class="row" id="cold_leads_vue">
        <div class="col-md-12">
            <h1 class="text-center" style="background: #CCCCCC;padding: 20px">Cold Leads Broadcast</h1>
        </div>
        <div class="col-md-12">
            <cold-lead-broadcast-component></cold-lead-broadcast-component>
        </div>
    </div>
@endsection

@section('styles')
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
@endsection

@section('scripts')
    <script src="{{ asset('js/main.js') }}"></script>
@endsection