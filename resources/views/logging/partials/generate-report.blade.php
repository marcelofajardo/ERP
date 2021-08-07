<table class="table table-striped table-bordered">
   <tbody>
      <tr>
         <th>ID</th>
         <th>URL</th>
         <th>Count</th>
         <th>Time</th>
      </tr>
         @foreach($logsGroupWise as $i => $lgw)
              <tr>
                 <td>{{ $i + 1 }}</td>
                 <td>{{ $lgw->url }}</td>
                 <td>{{ $lgw->total_request }}</td>
                 <td>{{ $lgw->time_taken }}</td>
              </tr>
         @endforeach
   </tbody>
</table>