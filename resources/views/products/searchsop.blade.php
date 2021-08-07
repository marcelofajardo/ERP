

    {{-- @if($usersop->total() >= 1) --}}
    {{-- @if(count($usersop)) --}}
    @foreach ($usersop as $key => $value)
        <tr id="sid{{ $value->id }}" class="parent_tr"  data-id="{{$value->id }}">
            <td>{{ $value->id }}</td>
            <td>{{ $value->name }}</td>
            <td>{!! $value->content !!}</td>

            <td>{{ date('m-d  H:i', strtotime($value->created_at)) }}</td>
            <td>{{ date('m-d  H:i', strtotime($value->updated_at)) }}</td>
            <td>
                {{-- <button type="submit" class="btn btn-image" id = "{{$value->id}}" class="editor_edit btn-xs btn btn-image p-2"><img src="/images/edit.png"/></button> --}}
                {{-- <a href="javascript:;" data-note-id="{{ $value->id }}"
                    class="editor_edit btn-xs btn btn-image p-2">
                    <img src="/images/edit.png"></a> --}}
                    <a href="javascript:;" data-id = "{{$value->id}}" class="editor_edit btn-xs btn btn-image p-2">
                        <img src="/images/edit.png"></a>
                        {{-- <a onclick="editname({{$value->id}})" class="btn btn-image"> <img src="/images/edit.png"></a> --}}

                    <a class="btn btn-image deleteRecord" data-id="{{ $value->id }}" ><img src="/images/delete.png" /></a>
                   
                {{-- <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button> --}}
            </td>
    @endforeach
    {{-- @endif --}}
   {{-- @endif --}}