<table class="table table-bordered">
    <thead>
      <tr>
        <th width="10%">Id</th>
        <th width="10%">User</th>
        <th width="50%">Message</th>
        <th width="20%">Date</th>
      </tr>
    </thead>
    <tbody>
      @forelse($activites as $activity)
        <tr>
          <td>{!! $activity->id !!}</td>
          <td>{!! $activity->causer->name !!}</td>
          <td>{!! $activity->description !!}</td>
          <td>{!! $activity->created_at->format('d-m-Y') !!}</td>
        </tr>
      @empty
        <tr><td colspan="4"><center>No activity found</center></td></tr>
      @endforelse
    </tbody>
</table>