<table class="table table-bordered table-striped sort-priority-scrapper">
    
    <!-- STRAT - Purpose : ADD Download  Position History Button - DEVTASK-4086 -->
    <button type="submit" data-id="{{$histories[0]['scraper_id']}}" class="btn btn-default downloadPositionHistory">Download</button><br/><br/>
    <thead>
        <tr>
            <th width="20%">Scraper name</th>
            <th width="60%">Comment</th>
            <th width="20%">Created at</th>
        </tr>
    </thead>
    <tbody class="conent">
        @foreach ($histories as $history)
            <tr>
                <td>{{ $history->scraper_name }}</td>
                <td>{{ $history->comment }}</td>
                <td>{{ $history->created_at }}</td>
            </tr>
        @endforeach
   </tbody>
   {{$histories->appends(request()->except('page'))->links()}} 
</table> 
