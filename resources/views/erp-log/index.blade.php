@extends('layouts.app')
@section('content')

<div class="row">
  <div class="col-lg-12 margin-tb">
    <h2 class="page-heading">ERP logs</h2>

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
      <th style="width: 10%">#</th>
      <th style="width: 40%">Url</th>
      <th style="width: 20%">Model</th>>
<!--       <th style="width: 20%">Request</th>
      <th style="width: 20%">Response</th> -->
      <th style="width: 10%">Type</th>
      <th style="width: 20%">Created On</th>
    </tr>
    @foreach ($erpLogData as $key => $row)
    
    <tr>
      <td>{{$key+1}}</td>
      <td>{{ $row['url'] }}</td>
      <td>{{ $row['model'] }}</td>
      <td>{{ $row['type'] }}</td>
<!--       <td>{{ $row['request'] }}</td>
      <td>{{ $row['response'] }}</td> -->
      <td>{{ date("d-m-y H:i:s", strtotime($row['created_at'])) }}</td>
    </tr>
    @endforeach
  </table>
</div>
@endsection
