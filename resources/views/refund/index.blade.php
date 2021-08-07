@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Refunds</h2>

            <div class="pull-right">
              <a class="btn btn-secondary" href="{{ route('refund.create') }}">+</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
      <div class="alert alert-success">
        <p>{{ $message }}</p>
      </div>
    @endif

    <div class="table-responsive mt-3">
      <table class="table table-bordered">
        <tr>
          <th>Customer Name</th>
          <th>Order ID</th>
          <th>Date of Refund Request</th>
          <th>Date of Issue</th>
          <th>Dispatched</th>
          <th width="280px">Action</th>
        </tr>
        @foreach ($refunds as $refund)
          <tr>
            <td>{{ $refund->customer->name ?? 'No Customer' }}</td>
            <td>
              @if($refund->order)
                <a href="{{ route('order.show', $refund->order->id) }}">{{ $refund->order->order_id }}</a></td>
              @else 
                -
              @endif
            <td>{{ $refund->date_of_request ? \Carbon\Carbon::parse($refund->date_of_request)->format('d-m') : '' }}</td>
            <td>{{ $refund->date_of_issue ? \Carbon\Carbon::parse($refund->date_of_issue)->format('d-m') : '' }}</td>
            <td>{{ $refund->dispatch_date ? \Carbon\Carbon::parse($refund->dispatch_date)->format('d-m') : '' }}</td>
            <td>
              <a class="btn btn-image" href="{{ route('refund.show', $refund->id) }}"><img src="/images/view.png" /></a>

              {!! Form::open(['method' => 'DELETE','route' => ['refund.destroy', $refund->id],'style'=>'display:inline']) !!}
                <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>
              {!! Form::close() !!}
            </td>
          </tr>
        @endforeach
      </table>
    </div>

    {!! $refunds->render() !!}

@endsection
