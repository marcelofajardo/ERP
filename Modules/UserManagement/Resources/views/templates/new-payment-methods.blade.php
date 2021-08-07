@foreach($paymentMethods as $paymentMethod)
  <option value="{{ $paymentMethod->id }}"> {{ $paymentMethod->name }} </option>
@endforeach