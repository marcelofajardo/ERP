@extends('layouts.app')

@section('favicon' , 'croprejectedgrid.png')
@section('title', 'Crop Issue Summary - ERP Sololuxury')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Crop Issue Summary</h2>
        </div>
        <div class="col-md-12">
            <table class="table table-bordered">
                <tr>
                    <th>Crop Erros</th>
                    <th>Count</th>
                </tr>
                @foreach($issues as $issue)
                    <tr>
                        <td>{{ $issue->remark }}</td>
                        <td>{{ $issue->issue_count }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection