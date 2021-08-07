@foreach($records as $key => $record)
<tr>
    <td>{{$key + 1}}</td>
    <td>
    @if($record['isImage'])
    <img class="zoom-img" style="max-height:150px;" src="{{$record['url']}}" alt="">
    @else 
        <p>{{$record['url']}}</p>
     @endif   
    </td>
    <td>
    <select name="" id="" class="form-control send-message-to-id">
        <option value="" > Select </option>
        @foreach($record['userList'] as $key => $u)
        <option value="{{$key}}" > {{$u}} </option>
        @endforeach
    </select>
    </td>
    <td>{{$record['created_at']}}</td>
    <td>
    <a class="btn-secondary" href="{{$record['url']}}" target="__blank"><i class="fa fa-download" aria-hidden="true"></i></a>&nbsp;
    &nbsp;<a class="btn-secondary link-send-document" title="forward to" data-id="{{$record['id']}}" href="_blank"><i class="fa fa-forward" aria-hidden="true"></i></a>
    </td>
</tr>
@endforeach