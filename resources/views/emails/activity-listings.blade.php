@component('mail::message')

<table class="table table-bordered" width="100%">
  <tr style="text-align: left;">
    <th>Activities</th>
    @foreach ($data['results'] as $user_id => $item)
      <th>{{ $user_id == 70 ? 'Shrutika' : 'Bhalchandra' }}</th>
    @endforeach
    <th>Out of</th>
  </tr>

  @foreach ($data['total_data'] as $activity => $item)
    <tr>
      <td>{{ ucwords($activity) }}</td>

      @foreach ($data['results'] as $item)
        <td>{{ $item[$activity] }}</td>
      @endforeach

      @if ($activity == 'selection')
        <td>{{ $data['benchmark']['selections'] }}</td>
      @elseif ($activity == 'searcher')
        <td>{{ $data['benchmark']['searches'] }}</td>
      @elseif ($activity == 'attribute')
        <td>{{ $data['benchmark']['attributes'] }}</td>
      @elseif ($activity == 'sales')
        <td></td>
      @else
        <td>{{ $data['benchmark'][$activity] }}</td>
      @endif
    </tr>
  @endforeach

</table>

@endcomponent
