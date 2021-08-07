{!! $msg !!}
<br>
<div>
    <div>---------- Forwarded message ---------</div>
    <div>From: &lt;{{ $forwardEmail->from }}&gt;</div>
    <div>Date: {{ $dateCreated }} at {{ $timeCreated }}</div>
    <div>Subject: {{ $forwardEmail->subject }}</div>
    <div>To: &lt;{{ $forwardEmail->to }}&gt;</div>
    <br>
    <div>{!! $forwardEmail->message !!}</div>
</div>
