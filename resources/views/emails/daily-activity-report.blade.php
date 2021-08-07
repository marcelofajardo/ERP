@component('mail::message')
# {{ $user->name }} Daily Planner Report

<table class="table table-bordered" width="100%" border="1">
  <tr style="text-align: left;">
    <th>Time</th>
    <th>Planned</th>
    <th>Actual</th>
  </tr>

  @foreach ($time_slots as $time_slot => $items)
    @foreach ($items as $task)
      <tr style="border: 1px solid black;">
        <td>{{ $time_slot }}</td>
        <td>
          @if ($task['activity'] == '')
            {{ $task['task_subject'] ?? substr($task['task_details'], 0, 20) }}
          @else
            {{ $task['activity'] }}
          @endif

          @if ($task['pending_for'] != 0)
            - pending for {{ $task['pending_for'] }} days
          @endif
        </td>
        <td>{{ $task['is_completed'] != '' ? \Carbon\Carbon::parse($task['is_completed'])->format('d-m H:i') : '' }}</td>
      </tr>
    @endforeach
  @endforeach

</table>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
