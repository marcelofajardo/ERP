@extends('layouts.app')

@section('favicon' , 'supplierstats.png')

@section('title', 'Server Statistics')

@section('styles')
<style type="text/css">

</style>
@endsection

@section('large_content')

    <div class="row mb-5">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Server Scrapping Info <span class="total-info"></span></h2>
        </div>
    </div>

    @include('partials.flash_messages')
    <form class="" action="{!! route('scrap.servers.statistics') !!}">
        <div class="row">
            <div class="form-group mb-3 col-md-2">
                <input name="q" type="text" class="form-control" id="server-search" value="{{ request()->get('q','') }}" placeholder="Type to search
                ">
            </div>
            <div class="form-group mb-3 col-md-2">
                <button type="submit" class="btn btn-image"><img src="/images/search.png"></button>
            </div>
        </div>
    </form>
    <div class="row no-gutters mt-3">
        <div class="col-md-12" id="plannerColumn">
            <div class="">
                <table class="table table-bordered table-striped sort-priority-scrapper">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>0-3</th>
                        <th>3-6</th>
                        <th>6-9</th>
                        <th>9-12</th>
                        <th>12-15</th>
                        <th>15-18</th>
                        <th>18-21</th>
                        <th>21-24</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $id => $d)
                        <tr>
                            <td width="4%">{!! $id !!}</td>
                            <td width="12%">{!! isset($d[3]) ? implode("<br>",$d[3]) : "-" !!}</td>
                            <td width="12%">{!! isset($d[6]) ? implode("<br>",$d[6]) : "-" !!}</td>
                            <td width="12%">{!! isset($d[9]) ? implode("<br>",$d[9]) : "-" !!}</td>
                            <td width="12%">{!! isset($d[12]) ? implode("<br>",$d[12]) : "-" !!}</td>
                            <td width="12%">{!! isset($d[15]) ? implode("<br>",$d[15]) : "-" !!}</td>
                            <td width="12%">{!! isset($d[18]) ? implode("<br>",$d[18]) : "-" !!}</td>
                            <td width="12%">{!! isset($d[21]) ? implode("<br>",$d[21]) : "-" !!}</td>
                            <td width="12%">{!! isset($d[24]) ? implode("<br>",$d[24]) : "-" !!}</td>
                        </tr>
                        @endforeach
                </table>

            </div>
        </div>
    </div>
@endsection