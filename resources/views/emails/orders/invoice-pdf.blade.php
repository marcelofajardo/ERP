<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
      body {
        background-color: white;
      }
    </style>
  </head>

  <body>
    <table class="table table-bordered">
      <tr>
        <th colspan="5" class="text-center">INVOICE</th>
      </tr>
      <tr>
        <th width="50%">Consignor:</th>
        <th class="text-center">Bill No.</th>
        <th class="text-center">Date</th>
        <th colspan="2" rowspan="3"></th>
      </tr>
      <tr>
        <td rowspan="2" class="p-3">
          <strong>{{ $consignor['name'] }}</strong> <br>
          {{ $consignor['address'] }} <br>
          {{ $consignor['city'] }} <br>
          {{ $consignor['country'] }} <br>
          Tel. {{ $consignor['phone'] }}
        </td>
        <td class="text-center">{{ $order->id }}</td>
        <td class="text-center">{{ $order->created_at }}</td>
      </tr>
      <tr>
        <td colspan="2"></td>
      </tr>

      <tr>
        <th>Consignee:</th>
        <th colspan="4">Other Reference(s)</th>
      </tr>

      <tr>
        <td class="p-3">
          <strong>{{ $order->customer->name }}</strong> <br>
          {{ $order->customer->address }} <br>
          {{ $order->customer->city }}, {{ $order->customer->pincode }} <br>
          {{ $order->customer->country }} <br>
          Tel. {{ $order->customer->phone }}
        </td>
        <td colspan="4"></td>
      </tr>

      <tr>
        <th class="text-center">Particulars</th>
        <th></th>
        <th class="text-center">Qty.</th>
        <th class="text-center">Rate</th>
        <th class="text-center">Amount</th>
      </tr>

      @php
        $total_qty = 0;
        $total_rate = 0;
        $total_amount = 0;
      @endphp
      @foreach ($order->order_product as $order_product)
        <tr>
          <td class="p-3">{{ $order_product->product->name ?? '' }}</td>
          <td></td>
          <td class="text-center">{{ $order_product->qty }}</td>
          <td class="text-center">{{ $order_product->product_price }}</td>
          <td class="text-center">{{ $order_product->qty * $order_product->product_price }}</td>
        </tr>

        @php
          $total_qty += $order_product->qty;
          $total_rate += $order_product->product_price;
          $total_amount += $order_product->qty * $order_product->product_price;
        @endphp
      @endforeach

      <tr>
        <th></th>
        <th class="text-center">TOTAL</th>
        <th class="text-center">{{ $total_qty }}</th>
        <th class="text-center">{{ $total_rate }}</th>
        <th class="text-center">{{ $total_amount }}</th>
      </tr>

      <tr>
        <td colspan="5" class="p-3">
          Amount chargeable: <br>
          @php
            $format = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
          @endphp
          <strong>
            {{ ucwords($format->format($total_amount)) }}
          </strong>
        </td>
      </tr>

      <tr>
        <td colspan="2"></td>
        <td colspan="3" class="p-3">
          For Solo Luxury
          <br><br><br><br><br>
          Authorized Signatory
        </td>
      </tr>
    </table>
  </body>
</html>
