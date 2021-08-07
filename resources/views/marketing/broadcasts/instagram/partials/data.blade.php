 @if($leads->isEmpty())

 <tr>
  <td>
    No Result Found
  </td>
</tr>
@else

@foreach ($leads as $lead)

<tr>
  <td>{{ $lead->id }}</td>
  <td>{{ $lead->name }}</td>
  <td>{{ $lead->number_of_users }}</td>
  <td>{{ $lead->message }}</td>
  <td>{{ $lead->frequency }}</td>
  <td>{{ $lead->started_at }}</td>
  <td>{{ $lead->imQueueBroadcastSend->count() }}</td>
  <td>{{ $lead->imQueueBroadcastPending->count() }}</td>
  <td>{{ $lead->imQueueBroadcast->count() }}</td>
</tr>

@endforeach

@endif