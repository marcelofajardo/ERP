<table class="table table-bordered table-striped sort-priority-scrapper">
    <thead>
        <tr>
            <th>Scraper Name</th>
            <th>Server Id</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Created at</th>
        </tr>
    </thead>
    <tbody class="conent">
        @foreach ($statusHistory as $statusHist)
            <tr>
                <td>{{ $statusHist->scraper_name }}</td>
                <td>{{ $statusHist->server_id }}</td>
                <td>{{ $statusHist->started_at }}</td>
                <td>{{ $statusHist->ended_at }}</td>
                <td>{{ $statusHist->created_at }}</td>
            </tr>
        @endforeach
   </tbody>
</table> 
