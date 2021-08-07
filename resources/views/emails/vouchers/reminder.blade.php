@component('mail::message')
# Voucher Reminder

This is unpaid voucher reminder.
<br><br>
{{ $voucher->description }}
<br>
{{ $voucher->amount - $voucher->paid }} due {{ \Carbon\Carbon::parse($voucher->date)->format('d-m') }} for {{ $voucher->user->name }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
