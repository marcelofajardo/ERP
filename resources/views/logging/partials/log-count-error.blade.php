<table class="table table-bordered">
    <thead>
        <tr>
            <th># Website</th>
            <th>Count</th>
            <th>Url</th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($log))
            @foreach($log as $l)
                <tr>
                    <td>{{$l->website}}</td>
                    <td>{{$l->total_error}}</td>
                    <td>{{$l->url}}</td>
                </tr>
            @endforeach
        @endif
    </tbody> 
</table>    