@php 
$countRow = 0; 
@endphp
@foreach($numbers as $number)
                                   @if($number->is_connected == 0 && $number->is_customer_support == 0 && $number->status == 1)
                                    <tr style="background: red !important;">
                                   @else
                                    <tr>    
                                   @endif     
                                        <td>{{ $number->username }}</td>
                                        <td>{{ $number->device_name }}</td>
                                        <td>@if($number->is_customer_support == 1)<span style="color: red;">{{ $number->number }}</span>@else {{ $number->number }} @endif</td>
                                        <td>{{ $number->customer()->count() }}</td>
                                        <td>{{ $number->customerAttachToday()->count() }}</td>
                                        @php
                                         
                                        if(!isset($pendingTotal)){
                                             $pendingTotal = 0; 
                                        }
                                        $pendingTotal = ($number->imQueueLastMessagePending->count() + $pendingTotal);

                                        if(!isset($customerTotal)){
                                             $customerTotal = 0; 
                                        }
                                        $customerTotal = ($number->customer()->count() + $customerTotal);

                                        if(!isset($customerAttachToday)){
                                             $customerAttachToday = 0; 
                                        }
                                        $customerAttachToday = ($number->customerAttachToday()->count() + $customerAttachToday);

                                                 
                                        @endphp
                                        @if($date != '' || $startDate != '') 
                                        @php
                                        
                                        if($date != ''){
										$count = \App\ImQueue::where('number_from',$number->number)->whereDate('created_at', $date)->count();
                                        }
                                        elseif($startDate != '' && $endDate != ''){
                                        $count = \App\ImQueue::where('number_from',$number->number)->whereBetween('created_at', [$startDate,$endDate])->count();
                                        }
                                        else{
                                        $count = 0;
                                        }
                                       @endphp
                                        <td><button type="button" onclick="showMessage({{ $number->id }} ,{{ $number->number }} )" value="{{ $date }}" id="date{{ $number->id }}">{{ $count }}</button></td>
										@else
										<td>{{ $number->imQueueCurrentDateMessageSend->count() }}</td>
                                        @endif
                                        @php
                                        if(!isset($sendTotal)){
                                             $sendTotal = 0; 
                                        }
                                        $sendTotal = ($number->imQueueCurrentDateMessageSend->count() + $sendTotal);
                                        @endphp
                                        <td>@if(isset($number->imQueueLastMessagePending)){{ $number->imQueueLastMessagePending->count() }}@else 0 @endif</td>
                                        <td>{{ $number->last_online }}</td>
                                        <td> @if(isset($number->imQueueLastMessageSend)) @if($number->imQueueLastMessageSend->send_after == '2002-02-02 02:02:02') Message Failed @else Send SucessFully @endif @endif</td>
                                        <td>{{ $number->created_at->format('d-m-Y') }}</td>
                                        <td>@if($number->status == 1) Active @elseif($number->status == 2) Blocked 
                                        @elseif($number->status == 3) Scan Pending 
                                        @else Inactive @endif</td>
                                        <td>{{ $number->frequency }}</td>
                                        <td>{{ $number->send_start }}</td>
                                        <td>{{ $number->send_end }}</td>
                                        @if($number->is_customer_support == 0)
                                        <td><button class="btn btn-link btn-sm" onclick="switchNumber({{ $number->id }})">Switch</button></td>
                                        @endif

                                    </tr>
                                    
                                        @php 
                                        $countRow++; 
                                        @endphp
                                  

@endforeach
@if($countRow != 0)
                                   <tr>
                                        <td>Total</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $customerTotal }}</td>
                                        <td>{{ $customerAttachToday }}</td>
                                        <td>{{ $sendTotal }}</td>
                                        <td>{{ $pendingTotal }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
@endif
