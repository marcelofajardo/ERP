 @if($instagramConfigs->isEmpty())

 <tr>
  <td>
    No Result Found
  </td>
</tr>
@else

@foreach ($instagramConfigs as $instagramConfig)

<tr>
 
  <td>{{ $instagramConfig->username }}</td>
  <td>{{ Crypt::decrypt($instagramConfig->password) }}</td>
  <td>{{ $instagramConfig->number }}</td>
  <td>{{ $instagramConfig->provider }}</td>
  <td>{{ $instagramConfig->frequency }}</td>
  <td>@if($instagramConfig->is_customer_support == 1) Yes @else No @endif</td>
  <td>{{ $instagramConfig->send_start }}</td>
  <td>{{ $instagramConfig->send_end }}</td>
  <td>{{ $instagramConfig->device_name }}</td>
  <td>@if($instagramConfig->status == 1) Active @elseif($instagramConfig->status == 2) Blocked @elseif($instagramConfig->status == 3)  Scan Barcode @else Inactive @endif</td>
  <td>{{ $instagramConfig->created_at->format('d-m-Y') }}</td>
  <td>
    <button onclick="addBroadcast({{ $instagramConfig->id }})" class="btn btn-sm">Add Broadcast</button>
    <button onclick="changeinstagramConfig({{ $instagramConfig->id }})" class="btn btn-secondary btn-sm">Edit</button>
    @if(Auth::user()->hasRole('Admin'))
    <button onclick="deleteConfig({{ $instagramConfig->id }})" class="btn btn-sm">Delete</button>
    @endif
  </td>
</tr>

@include('marketing.instagram-configs.partials.edit-modal')
@endforeach

@endif