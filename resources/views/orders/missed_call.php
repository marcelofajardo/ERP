@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Orders List</h2>
        <div class="pull-left">

            <form action="/order/" method="GET">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <input name="term" type="text" class="form-control"
                                   value="{{ isset($term) ? $term : '' }}"
                                   placeholder="Search">
                        </div>
                        <div class="col-md-4">
                            <button hidden type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="pull-right">
            <a class="btn btn-secondary" href="{{ route('order.create') }}">+</a>
        </div>
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
            <th style="width: 8%"><a
                        href="/order{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=id{{ ($orderby == 'desc') ? '&orderby=asc' : '' }}">Lead
                    ID</a></th>
            <th style="width: 8%"><a
                        href="/order{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=date{{ ($orderby == 'desc') ? '&orderby=asc' : '' }}">Message</a>
            </th>
        </tr>
        @foreach ($callBusyMessages as $key => $callBusyMessage)
        <tr class="{{ \App\Helpers::statusClass($order['assign_status'] ) }} {{ ((!empty($order['communication']['body']) && $order['communication']['status'] == 0) || $order['communication']['status'] == 1 || $order['communication']['status'] == 5) ? 'row-highlight' : '' }} {{ ((!empty($order['communication']['message']) && $order['communication']['status'] == 0) || $order['communication']['status'] == 1 || $order['communication']['status'] == 5) ? 'row-highlight' : '' }}">
            <td>{{ $callBusyMessage->lead_id }}</td>
            <td>{{ $callBusyMessage->message }}</td>
        </tr>
        @endforeach
    </table>
</div>


{{ {!! $callBusyMessages->links() !!} }}
@endsection
