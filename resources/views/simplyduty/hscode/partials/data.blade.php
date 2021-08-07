 @if($categories->isEmpty())

            <tr>
                <td>
                    No Result Found
                </td>
            </tr>
@else

@foreach ($categories as $category)
    <tr>
        <td>{{ $category->code }}</td>
        <td>{{ $category->description }}</td>
        <td>{{ $category->created_at->format('d-m-Y') }}</td>
        <td>{{ $category->updated_at->format('d-m-Y H:i:s') }}</td>
    </tr>   
@endforeach
@endif