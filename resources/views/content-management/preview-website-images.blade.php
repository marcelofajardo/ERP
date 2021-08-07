@foreach($records as $key => $record)
<tr>
    <td>{{$key + 1}}</td>
    <td>
        <img style="max-height:100px;" src="{{$record['url']}}" alt="">
    </td>
</tr>
@endforeach