<div style="width: 100%;padding: 0px 30px;">
    <table cellpadding="0" cellspacing="0" style="border: 1px solid #f4e7e1;">
      <tbody>
        <tr><td style="border-bottom:1px solid #f4e7e1;text-align: center;font-size: 16px;font-weight: bold;padding: 10px;color: #898989;">Confirmed Items</td></tr>
        <tr>
          <td>
            <table border="0" cellpadding="0" cellspacing="0">
              <tbody>
                @php $total = $product_total = 0; @endphp
                @foreach ($order->order_product as $order_product)
                  @php $product = $order_product->product @endphp
                  @if($product)
                    <tr>
                      <td style="padding:5px 10px; width: 80px;"><div><img width="71px" height="83px" src="{{ ($order_product->product && $order_product->product->getMedia(config('constants.attach_image_tag'))->first()) ? $order_product->product->getMedia(config('constants.attach_image_tag'))->first()->getUrl() : asset('images/no-image.jpg') }}"></div></td>
                      <td style="padding: 5px 10px;">
                        <h4 style="margin: 0;padding:0;font-weight: bold;font-size: 14px;color: #898989;">{{ ($product->brands) ? ucwords($product->brands->name) : "" }}</h4>
                        <p style="margin: 0;padding: 0;width: 70%;margin: 5px 0;">{{ $product->name }}</p>
                        <div style="font-size: 12px;color: #898989;">Quantity : {{ $order_product->qty }}</div>
                        @if(!empty($order_product->size))
                          <div style="font-size: 12px;color: #898989;">Size : {{ $order_product->size }}</div>
                        @endif
                        @if(!empty($order_product->color))
                          <div style="font-size: 12px;color: #898989;">Color : {{ $order_product->color }}</div>
                        @endif
                        <div style="font-size: 12px;font-weight: 700;color: #000000;margin-top: 5px;margin-bottom: 10px;">Receive it by {{ date("M d, Y",strtotime($order->estimated_delivery_date)) }}</div>
                      </td>
                      <td style="font-weight: bold;padding: 5px 10px;">{{ $order->currency }} {{$order_product->order_price}}</td>
                    </tr>
                    @php $product_total += $order_product->product_price; @endphp
                    @php $total += $order_product->order_price; @endphp
                  @endif
                @endforeach
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
    <table cellpadding="0" cellspacing="0">
      <tbody>
        <tr>
          <td align="right">
           <table align="right" style="width: 230px;">
              <tbody align="right">
                <tr>
                  <td align="left"><div style="color: #898989;font-size: 14px;padding-top: 10px;">Subtotal</div></td>
                  <td align="right" style="padding-right: 10px;">
                    <div style="color: #898989;font-size: 14px;font-weight: bold;padding-top: 10px;padding-left: 20px;">
                    {{ $order->currency }}{{$product_total}}
                    </div>
                  </td>
                </tr>
                <tr>
                  <td align="left">
                    <div style="color: #000000;font-size: 14px;font-weight: bold;padding-top: 10px;">Advance Amount</div>
                  </td>
                  <td align="right" style="padding-right: 10px;">
                    <div style="color: #000000;font-size: 14px;font-weight: bold;padding-top: 10px;padding-left: 20px;">
                      {{ $order->currency }}{{$order->advance_detail}}
                    </div>
                  </td>
                </tr>
                <tr>
                  <td align="left">
                    <div style="color: #000000;font-size: 14px;font-weight: bold;padding-top: 10px;">Balance Amount</div>
                  </td>
                  <td align="right" style="padding-right: 10px;">
                    <div style="color: #000000;font-size: 14px;font-weight: bold;padding-top: 10px;padding-left: 20px;">
                      {{ $order->currency }}{{$order->balance_amount}}
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </div>