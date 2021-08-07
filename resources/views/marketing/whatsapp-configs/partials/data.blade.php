 @if($whatsAppConfigs->isEmpty())

 <tr>
  <td>
    No Result Found
  </td>
</tr>
@else

@foreach ($whatsAppConfigs as $whatsAppConfig)

<tr>
 
  <td>{{ $whatsAppConfig->username }}</td>
  <td></td>
  <td>{{ $whatsAppConfig->number }}</td>
  <td>{{ $whatsAppConfig->provider }}</td>
  <td>{{ $whatsAppConfig->frequency }}</td>
  <td>@if($whatsAppConfig->is_customer_support == 1) Yes @else No @endif</td>
  <td>{{ $whatsAppConfig->send_start }}</td>
  <td>{{ $whatsAppConfig->send_end }}</td>
  <td>{{ $whatsAppConfig->device_name }}</td>
  <!-- <td>{{ $whatsAppConfig->simcard_number }}</td>
  <td>{{ $whatsAppConfig->simcard_owner }}</td>
  <td>{{ $whatsAppConfig->payment }}</td>
  <td>{{ $whatsAppConfig->recharge_date }}</td> -->
  <td>@if($whatsAppConfig->status == 1) Active @elseif($whatsAppConfig->status == 2) Blocked @elseif($whatsAppConfig->status == 3)  Scan Barcode @else Inactive @endif</td>
  <td>{{ $whatsAppConfig->created_at->format('d-m-Y') }}</td>
  <td>{{ $whatsAppConfig->instance_id }}</td>
  <td>
    <button onclick="changewhatsAppConfig({{ $whatsAppConfig->id }})" class="btn btn-secondary btn-sm">Edit</button>
    @if(Auth::user()->hasRole('Admin'))
    <button onclick="deleteConfig({{ $whatsAppConfig->id }})" class="btn btn-sm">Delete</button>
    @endif
    <a href="{{route('whatsapp.config.history', $whatsAppConfig->id)}}" title="History"><i class="fa fa-history" aria-hidden="true"></i></a>
    <a href="{{route('whatsapp.config.queue', $whatsAppConfig->id)}}" title="Queue"><i class="fa fa-list" aria-hidden="true"></i></a>
    <button class="btn btn-link" onclick="getBarcode({{ $whatsAppConfig->id }})" data-toggle="tooltip" data-placement="top" title="Barcode For Whatsapp"><i class="fa fa-barcode"></i></button>
    <button class="btn btn-link" onclick="getScreen({{ $whatsAppConfig->id }})" data-toggle="tooltip" data-placement="top" title="WhatsApp Screen"><i class="fa fa-desktop"></i></button>
    <button class="btn btn-link" onclick="deleteChrome({{ $whatsAppConfig->id }})" data-toggle="tooltip" data-placement="top" title="Delete Chrome Config"><i class="fa fa-trash"></i></button>
    <button class="btn btn-link" onclick="restartScript({{ $whatsAppConfig->id }})" data-toggle="tooltip" data-placement="top" title="Restart"><i class="fa fa-refresh"></i></button>
    @if($whatsAppConfig->is_use_own == 1)
      <button class="btn btn-link" onclick="logoutScript({{ $whatsAppConfig->id }})" data-toggle="tooltip" data-placement="top" title="Logout script">
        <i class="fa fa-sign-out"></i>
      </button>
      <button class="btn btn-link" onclick="getInfo({{ $whatsAppConfig->id }})" data-toggle="tooltip" data-placement="top" title="Get status">
        <i class="fa fa-info"></i>
      </button>
    @endif
  </td>
</tr>

@include('marketing.whatsapp-configs.partials.edit-modal')
@endforeach

@endif