@component('mail::message')

  Dear Sir/Ma'am, <br><br>
  Thank you for your order. <br>
  Your order will be assigned to a customer care executive who will be available at all times to answer any queries. Our customer care executives will contact you shortly. <br>
  Your order confirmation is below. <br>

  <br>

  Order Number: {{ $order->order_id }}

  @component('mail::table')
    | Billing Information | Payment Method |
    | ------------- |:-------------:|
    | {{ $order->customer->name }} <br> {{ $order->customer->address }} <br>  {{ $order->customer->city }} <br>    {{ $order->customer->pincode }} <br>    {{ $order->customer->country }} <br>    T: {{ $order->customer->phone }}    | {{ $order->payment_mode }} |
  @endcomponent

  <table class="table table-bordered" width="100%">
    <tr>
      <th colspan="2">Item</th>
      <th>Sku</th>
      <th>Qty</th>
      <th>Total</th>
    </tr>

    @php $total_price = 0 @endphp
    @foreach ($order->order_product as $order_product)
      <tr>
        <td>
          @if ($order_product->product->hasMedia(config('constants.media_tags')))
            <img src="{{ $order_product->product->getMedia(config('constants.media_tags'))->first()->getUrl() }}" class="img-responsive" style="width: 50px;" alt="">
          @endif
        </td>
        <td>{{ $order_product->product->name ?? 'No Product' }}</td>
        <td>{{ $order_product->sku }}</td>
        <td>{{ $order_product->qty }}</td>
        <td>{{ $order_product->qty * $order_product->product_price }}</td>
      </tr>

      @php $total_price += $order_product->qty * $order_product->product_price  @endphp
    @endforeach

    <tr>
      <td colspan="3" style="text-align: right;">Subtotal</td>
      <td>Rs. {{ $total_price }}</td>
    </tr>

    <tr>
      <th colspan="3" style="text-align: right;">Grand Total</th>
      <th>Rs. {{ $total_price }}</th>
    </tr>
  </table>

<br>
Thank you,<br>
{{ config('app.name') }}
@endcomponent
