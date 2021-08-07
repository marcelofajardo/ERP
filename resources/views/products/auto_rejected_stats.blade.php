@extends('layouts.app')


@section('favicon' , 'autorejectstats.png')


@section('title', 'Auto Reject Stats')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Auto Rejected Stats</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped">
                <tr>
                    <td>Remaining To Be Pulled</td>
                    <td>Total Pulled</td>
                    <td>Total Rejected</td>
                </tr>
                <tr>
                    <td>{{ $totalRemaining }}</td>
                    <td>{{ $totalDone }}</td>
                    <td>{{ $totalRemaining+$totalDone }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection