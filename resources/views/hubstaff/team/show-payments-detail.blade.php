@extends('layouts.app')

@section('content')

@if(Session::has('message'))
  <div class="alert alert-success alert-block" >
    <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong>{{ Session::get('message') }}</strong>
  </div>
@endif

<h2 class="text-center">Payments list from Hubstaff API</h2>

<div class="container">

<div class="row">
  <div class="token">

      @if($results->team_payments)
        @if(count($results->team_payments) >= 1)
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Id</th>
                <th>Next payment at</th>
                <th>Created at</th>
                <th>Start Date</th>
                <th>Stop Date</th>
                <th>Tracked</th>
                <th>Blame User Id</th>
                <th>Payment Type</th>
                <th>Payment Complete</th>
                <th>Paid via payroll</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>Details</th>
            </thead>
            @foreach($results->team_payments as $payment)
              <tbody>
                <tr>
                  <td>{{ $payment->id }}</td>
                  <td>{{ $payment->next_payment_at }}</td>
                  <td>{{ $payment->created_at }}</td>
                  <td>{{ $payment->start_date }}</td>
                  <td>{{ $payment->stop_date }}</td>
                  <td>{{ $payment->tracked }}</td>
                  <td>{{ $payment->blame_user_id }}</td>
                  <td>{{ $payment->payment_type }}</td>
                  <td>{{ $payment->payment_complete }}</td>
                  <td>{{ $payment->paid_via_payroll }}</td>
                  <td>{{ $payment->amount }}</td>
                  <td>{{ $payment->currency }}</td>
                  <td>
                    @if($payment->details)
                      <ul>
                        <li>Id: {{ $payment->id }}</li>
                        <li>Id: {{ $payment->user_id }}</li>
                        <li>Id: {{ $payment->project_id }}</li>
                        <li>Id: {{ $payment->date }}</li>
                        <li>Id: {{ $payment->tracked }}</li>
                        <li>Id: {{ $payment->pay_rate }}</li>
                        <li>Id: {{ $payment->amount }}</li>
                        <li>Id: {{ $payment->currency }}</li>
                        <li>Id: {{ $payment->pay_rate }}</li>
                        <li>Id: {{ $payment->payment_type }}</li>
                        <li>Id: {{ $payment->payment_complete }}</li>
                      </ul>
                    @endif
                    {{ $payment->details }}
                  </td>
                </tr>
              </tbody>
            @endforeach
          </table>
        @endif

      @else

        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Id</th>
              <th>Next payment at</th>
              <th>Created at</th>
              <th>Start Date</th>
              <th>Stop Date</th>
              <th>Tracked</th>
              <th>Blame User Id</th>
              <th>Payment Type</th>
              <th>Payment Complete</th>
              <th>Paid via payroll</th>
              <th>Amount</th>
              <th>Currency</th>
              <th>Details</th>  
            </tr>
          </thead>
          <tbody>
            <tr>
              No Results Found
            </tr>
          </tbody>
        </table>
      @endif
  </div>      
</div>
@endsection