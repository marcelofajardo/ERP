<table class="table table-bordered table-striped sort-priority-scrapper">
    <thead>
        <tr>
            <th>#</th>
            <th>Scraper name</th>
            <th>Screenshot</th>
            <th>Created at</th>
        </tr>
    </thead>
    <tbody class="conent">
        @foreach ($screenshots as $screenshot)
            <tr>
                <td>{{ $screenshot->scraper_id }}</td>
                <td>{{ $screenshot->scraper_name }}</td>
                <td>
                    @if($screenshot->hasMedia(config('constants.media_screenshot_tag')))
                        @foreach($screenshot->getMedia(config('constants.media_screenshot_tag')) as $image)
                            <a href="{{ $image->getUrl() }}" target="_blank" > <img width="150" height="150" src="{{ $image->getUrl() }}"></a>
                        @endforeach
                    @endif
                </td>
                <td>{{ $screenshot->created_at }}</td>
            </tr>
        @endforeach
   </tbody>
   {{$screenshots->appends(request()->except('page'))->links()}} 
</table> 
