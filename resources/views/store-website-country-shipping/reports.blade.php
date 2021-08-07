<table class="table table-bordered" id="store_website-analytics-report-table">
    <thead>
        <tr>
            <th width="10%">ID</th>
            <th width="40%">Request</th>
            <th width="40%">Response</th>
            <th width="5%">Type</th>
            <th width="10%">Created At</th>
        </tr>
    </thead>
    <tbody class="searchable">
        @foreach($reports as $key => $report)
            <tr>
                <td>{{$report->id}}</td>
                <td>{{$report->request}}</td>
                <td>{{$report->response}}</td>
                <td>{{$report->type}}</td>
                <td>{{$report->created_at}}</td>
            </tr>
        @endforeach
    </tbody>
</table>