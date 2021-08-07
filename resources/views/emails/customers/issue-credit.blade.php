@component('mail::message')

  Dear {{ $customer->name }},
  <br><br>

  This is to confirm that an amount of Rs. {{ $customer->credit }} - is credited with us against your previous order. You can use this credit note for reference on your next purchase.
  <br><br>

  Thanks & Regards,
  <br>

  Solo Luxury Team

@endcomponent
