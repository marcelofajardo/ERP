<table class="table table-bordered table-striped sort-priority-scrapper">
    <thead>
        <tr>
            <th>Scraper name</th>
            <th>Log</th>
            <th>Created at</th>
        </tr>
    </thead>
    <tbody class="conent">
        @foreach ($remarks as $remark)
            <tr>
                <td>{{ $remark->scraper_name }}</td>
                <td>{{ $remark->remark }}</td>
                <td>{{ $remark->created_at }}</td>
            </tr>
        @endforeach
   </tbody>
</table> 
