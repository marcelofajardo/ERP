@extends('layouts.app')

@section('content')

@if(Session::has('message'))
  <div class="alert alert-success alert-block" >
    <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong>{{ Session::get('message') }}</strong>
  </div>
@endif

<h2 class="text-center">Members list from Hubstaff API </h2>

<div class="container">
  <div class="row">
    @if($results->users)
      @if(count($results->users) >= 1)
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Id</th>
              <th>User Name</th>
              <th>Email</th>
              <th>Last Activity</th>
              <th>Pay Rate</th>
              <th>Bill Rate</th>
              <th>Membership Status</th>
            </tr>
          </thead>
          @foreach($results->users as $user)
            <tbody>
              <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->last_activity }}</td>
                <td>
                  @if($user->pay_rate != null)
                    {{ $user->pay_rate }}
                  @else
                    N/A
                  @endif
                </td>
                <td>
                  @if($user->bill_rate != null)
                    {{ $user->bill_rate }}
                  @else
                    N/A
                  @endif 
                </td>
                <td>
                  @if($user->membership_status == "active")
                    <span class="badge badge-success">Active</span>
                  @else
                    <span class="badge badge-danger">In active</span>
                  @endif
                </td>
              </tr>
            </tbody>
          @endforeach
        </table>
      @endif
    @endif
  </div>
</div>
@endsection