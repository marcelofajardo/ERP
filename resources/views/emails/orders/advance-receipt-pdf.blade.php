@component('mail::content-message')
<br>
# Advance Receipt

{{ $order->customer->name }} <br>
{{ $order->customer->city }}, <br>
<br>

We here by confirm receipt of Rs {{ $order->advance_detail }}/ - advance towards your order of {{ $product_names }}.

<br><br>

Thanking You,<br>
<br>
<br>
Yours sincerely,<br>
{{ config('app.name') }}
@endcomponent
