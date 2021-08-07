@foreach ($data as $key => $file)
<tr>
    <td>{{ ++$i }}</td>
    <td>{{ $file->name }}</td>
    <td>{{ $file->updated_at }}</td>
    <td>{{ $file->created_at }}</td>
    <td>
        <a class="btn btn-image" href="{{ route('googlefiletranslator.download',$file->name) }}">Download File</a>
        {!! Form::open(['method' => 'DELETE','route' => ['googlefiletranslator.destroy', $file->id],'style'=>'display:inline']) !!}
        <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>
        {!! Form::close() !!}

    </td>
</tr>
@endforeach