 <?php
            $query = http_build_query(Request::except('page'));
            $query = url()->current() . (($query == '') ? $query . '?page=' : '?' . $query . '&page=');
            ?>
            <div class="form-group position-fixed hidden-xs hidden-sm" style="top: 50px; left: 20px;">
            Goto :
            <select onchange="location.href = this.value;" class="form-control" id="page-goto">
                @for($i = 1 ; $i <= $customers->lastPage() ; $i++ )
                    <option value="{{ $query.$i }}" {{ ($i == $customers->currentPage() ? 'selected' : '') }}>{{ $i }}</option>
                @endfor
            </select>
    </div> 
    
@if($customers->isEmpty())

 <tr>
  <td>
    No Result Found
  </td>
</tr>
@else

@foreach ($customers as $customer)

<tr id="row{{ $customer->id }}">
  <td class="show_select"><input type="checkbox" name="select" class="form-control checkbox_select" value="{{ $customer->id }}"></td>
  <td><a href="/customers/{{ $customer->id }}/post-show" target="_blank">{{ $customer->id }}</a></td>
  <td>{{ $customer->name }}</td>
  <td><input type="text" name="phone" value="{{ $customer->phone }}" class="form-control" onfocusout="updateCustomer({{ $customer->id }})" id="phone{{ $customer->id }}"></td>
  @php
   $count = 0; 
   $duplicate_customers = \App\Customer::where('phone',$customer->phone)->where('id', '!=', $customer->id)->get();
   if(count($duplicate_customers) != 0){
      $count++;
   }
  @endphp
  <td>
  	 <label class="switch" style="margin: 0px">
      @if($customer->do_not_disturb == 1)
        <input type="hidden" value="0" id="checkbox_value_dnd">
       <input type="checkbox" class="checkbox" checked value="{{ $customer->id }}" onclick="disableDND({{ $customer->id }})">
       @else
        <input type="hidden" value="1" id="checkbox_value_dnd">
        <input type="checkbox" class="checkbox" value="{{ $customer->id }}" onclick="enableDND({{ $customer->id }})">
       @endif
       <span class="slider round"></span>
  	 </label>
  </td>
 <!--  <td></td> -->
  <td>
  	 <label class="switch" style="margin: 0px">
      @if(isset($customer->customerMarketingPlatformActive) && $customer->customerMarketingPlatformActive->active == 1)
        <input type="hidden" value="0" id="checkbox_value">
      <input type="checkbox" class="checkboxs" checked value="{{ $customer->id }}" onclick="removeFromEnable({{ $customer->id }})">
      @else
        <input type="hidden" value="1" id="checkbox_value">
        <input type="checkbox" class="checkboxs" value="{{ $customer->id }}" id="marketing{{ $customer->id }}" onclick="addToEnable( {{ $customer->id }})">
       @endif
       <span class="slider round"></span>
  	 </label>
     @if(isset($customer->customerMarketingPlatformActive) && $customer->customerMarketingPlatformActive->active == 1)
     <br>
     {{ \Carbon\Carbon::parse($customer->customerMarketingPlatformActive->created_at)->format('d-m-Y H:i:s') }}
       @endif
  </td>
  <td>@if(isset($customer->lastBroadcastSend)) @if($customer->lastBroadcastSend->sent_at != '2002-02-02 02:02:02') {{ \Carbon\Carbon::parse($customer->lastBroadcastSend->send_after)->format('d-m-Y H:i:s')  }} @endif @endif</td>
  <td>@if(isset($customer->lastBroadcastSend)) @if($customer->lastBroadcastSend->sent_at == '2002-02-02 02:02:02') {{ \Carbon\Carbon::parse($customer->lastBroadcastSend->send_after)->format('d-m-Y H:i:s')  }} @endif @endif</td>
   
  <td>@if(isset($customer->broadcastLatest)) 

    <p onclick="showBroadcast({{$customer->id}})" id="broadcast{{$customer->id}}">{{ $customer->broadcastLatest->group_id }} {{ $customer->broadcastLatest->created_at->format('d-m-Y') }}  
    <p style="display: none;" onclick="hideBroadcastList({{$customer->id}})" id="broadcastList{{$customer->id}}"></p> @if( $customer->broadcastLatest->is_delivered == 1)  <span class="dot"></span> </p>  

    @endif @endif </td>
  <td>

    @if($customer->do_not_disturb == 1)
      DND 
    @elseif($customer->is_blocked == 1)
      Blocked
    @elseif(substr($customer->phone, 0, 1) === '-')
      Not WhatsApp Number
    @else
      <select class="form-control" onchange="updateNumber('{{ $customer->id }}','{{ $customer->phone }}')">
      <option>Select Number</option>
      @foreach($numbers as $number)
      <option value="{{ $number->number }}" @if($number->number == $customer->broadcast_number) selected @endif>{{ $number->number }}</option>
      @endforeach
    </select>
    @endif
    
  </td>
   <td><button type="button" class="btn btn-image make-remarks d-inline" data-toggle="modal" data-target="#makeRemarksModal" data-id="{{ $customer->id }}"><img src="/images/remark.png" /></button></td>
</tr>



@endforeach

@endif

