@foreach($broadcasts as $broadcast)
<tr>
  <td>{{ $broadcast['group_id'] }}</td>
  <td>{{ $broadcast['broadcast_number'] }}</td>
  <td>{{ $broadcast['frequency'] }}</td>
  <td>{{ $broadcast['message'] }}</td>
  <td>@if($broadcast['image'] != '') <img src="{{ $broadcast['image'] }}" height="150" width="150">@endif</td>
  <td>@if($broadcast['start_time'] != null) {{ Carbon\Carbon::parse($broadcast['start_time'])->format('d-m-Y H:i:s') }} @endif</td>
  <td>@if($broadcast['end_time'] != null) {{ Carbon\Carbon::parse($broadcast['end_time'])->format('d-m-Y H:i:s') }}  @endif</td>
  <td>{{ $broadcast['pending'] }}</td>
  <td>{{ $broadcast['received'] }}</td>
  <td>{{ $broadcast['failed'] }}</td>
  <td>{{ $broadcast['total'] }}</td>
</tr>

@endforeach
