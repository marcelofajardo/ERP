<table class="table table-bordered">
    <thead>
      <tr>
        <th width="10%">Id</th>
        <th width="10%">Type</th>
        <th width="50%">Message</th>
        <th width="10%">User</th>
        <th width="20%">Date</th>
      </tr>
    </thead>
    <tbody>
      @forelse($StoreWebsiteBrandHistories as $history)
        <tr>
          <td>{!! $history->id !!}</td>
          <td>{!! $history->type !!}</td>
          <td>{!! $history->message !!}</td>
          <td>{!! $history->user_name !!}</td>
          <td>{!! $history->created_at->format('d-m-Y') !!}</td>
        </tr>
      @empty
        <tr><td colspan="5"><center>No history found</center></td></tr>
      @endforelse
    </tbody>
</table>