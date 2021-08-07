<html>
<head>
  <style>
    * {
      color: #6c6c6c;
    }
  </style>
{{--  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">--}}
</head>
<body>

<div class="row">
  <div class="col-12">
    <h2 class="page-heading">Orders List</h2>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-bordered" border="1" style="border-collapse: collapse;">
    <thead>
    <tr>
      <th width="10%"><a href="/order{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=id{{ ($orderby == 'DESC') ? '&orderby=ASC' : '' }}">ID</a></th>
      <th width="10%"><a href="/order{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=date{{ ($orderby == 'DESC') ? '&orderby=ASC' : '' }}">Date</a></th>
      <th width="15%"><a href="/order{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=client_name{{ ($orderby == 'DESC') ? '&orderby=ASC' : '' }}">Client</a></th>
      <th width="10%">Products</th>
      <th width="15%"><a href="/order{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=status{{ ($orderby == 'DESC') ? '&orderby=ASC' : '' }}">Order Status</a></th>
      <th width="10%"><a href="/order{{ isset($term) ? '?term='.$term.'&' : '?' }}sortby=advance{{ ($orderby == 'DESC') ? '&orderby=ASC' : '' }}">Advance</a></th>
      <th width="10%"><a href="/order{{ isset($term) ? '?term='.$term.'&' : '?' }}{{ isset($order_status) ? implode('&', array_map(function($item) {return 'status[]='. $item;}, $order_status)) . '&' : '&' }}sortby=balance{{ ($orderby == 'DESC') ? '&orderby=ASC' : '' }}">Balance</a></th>
    </tr>
    </thead>

    <tbody>
    @foreach ($orders_array as $key => $order)
      <tr class="{{ \App\Helpers::statusClass($order->assign_status ) }}">
        <td class="expand-row table-hover-cell">
          <div class="form-inline">
            @if ($order->is_priority == 1)
              <strong class="text-danger mr-1">!!!</strong><br>
            @endif

              {{ $order->order_id }}

          </div>
        </td>
        <td>{{ Carbon\Carbon::parse($order->order_date)->format('d-m') }}</td>
        <td class="expand-row table-hover-cell">
          @if ($order->customer)
{{--            <span class="td-mini-container">--}}
{{--                    <a href="{{ route('customer.show', $order->customer->id) }}">{{ strlen($order->customer->name) > 15 ? substr($order->customer->name, 0, 13) . '...' : $order->customer->name }}</a>--}}
{{--                  </span>--}}

            <span class="td-full-container hidden">
                    <a href="{{ route('customer.show', $order->customer->id) }}">{{ $order->customer->name }}</a>
                  </span>
          @endif
        </td>
        <td class="expand-row table-hover-cell">
          @php $count = 0; @endphp
              @foreach ($order->order_product as $order_product)
                @if ($order_product->product)
                  @if ($order_product->product->hasMedia(config('constants.media_tags')))
                     <img alt="Image" style="width: 50px; height: 50px;" src="{{ $order_product->product->getMedia(config('constants.media_tags'))->first()->getAbsolutePath() }}">
                        @php $count++; @endphp
                  @endif
                @endif
              @endforeach
        </td>
        <td class="expand-row table-hover-cell">
          <span class="td-full-container hidden">
            {{ $order->order_status }}
          </span>
        </td>
        <td>{{ $order->advance_detail }}</td>
        <td>{{ $order->balance_amount }}</td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
</body>
</html>