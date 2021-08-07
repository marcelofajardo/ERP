@foreach ($missingBrands as $missingBrand)
    <tr>
        <td><input type="checkbox" name="m_brands[]" class="multi-brand-ref" value="{{ $missingBrand->id }}">&nbsp;{{ $missingBrand->id }}</td>
        <td>{{ $missingBrand->name }}</td>
        <td>{{ $missingBrand->supplier }}</td>
        <td>{{ $missingBrand->created_at }}</td>
        <td><a href="javascript:;" data-name="{{$missingBrand->name}}" data-id="{{$missingBrand->id}}" class="create-brand">Brand</a> | 
            <a href="javascript:;" data-name="{{$missingBrand->name}}" data-id="{{$missingBrand->id}}" class="create-reference">Reference</a></td>
    </tr>
@endforeach