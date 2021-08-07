 @if($platforms->isEmpty())

 <tr>
  <td>
    No Result Found
  </td>
</tr>
@else

@foreach ($platforms as $platform)

<tr>
  <td>{{ $platform->id }}</td>
  <td>{{ $platform->name }}</td>
  <td><button onclick="changePlatform({{ $platform->id }})" class="btn btn-secondary btn-sm">Edit</button>
    @if(Auth::user()->hasRole('Admin'))
    <button onclick="deleteConfig({{ $platform->id }})" class="btn btn-sm">Delete</button>
    @endif
  </td>
</tr>

@include('marketing.platforms.partials.edit-modal')
@endforeach

@endif