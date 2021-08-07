<div class="table-responsive-lg">
    <table class="table table-bordered" style="margin-top: 25px">
    <tr>
      @php $search_query = (isset($term) ? "&term=$term" : '') . (isset($brand) ? "&$brand" : '') . (isset($rating) ? "&$rating" : ''); @endphp
        @if ($type)
          <th></th>
        @endif
        <th style="width: 2%"><a href="/leads?sortby=id{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}{{ $search_query }}">ID</a></th>
        <th style="width: 15%"><a href="/leads?sortby=client_name{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}{{ $search_query }}">Client Name</a></th>
        <th style="width: 8%"><a href="/leads?sortby=city{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}{{ $search_query }}">City</a></th>
        <th style="width: 2%"><a href="/leads?sortby=rating{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}{{ $search_query }}">Rating</a></th>
        <!-- <th style="width: 8%"><a href="/leads?sortby=assigned_user{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}{{ $search_query }}">Assigned to</a></th> -->
        <th style="width: 10%">Products</th>
        <!-- <th style="width: 5%">Message Status</th> -->
        <th style="width: 17%"><a href="/leads?sortby=communication{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}{{ $search_query }}">Communication</a></th>
        <th style="width: 8%"><a href="/leads?sortby=status{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}{{ $search_query }}">Status</a></th>
        <th style="width: 10%"><a href="/leads?sortby=created_at{{ ($orderby == 'asc') ? '&orderby=desc' : '' }}{{ $search_query }}">Created</a></th>
        <th style="width: 15%">Action</th>
    </tr>
    @foreach ($leads_array as $key => $lead)
        <tr class="{{ \App\Helpers::statusClass($lead['assign_status'] ) }} {{ ((!empty($lead['communication']['body']) && $lead['communication']['status'] == 0) || $lead['communication']['status'] == 1 || $lead['communication']['status'] == 5) ? 'row-highlight' : '' }} {{ ((!empty($lead['communication']['message']) && $lead['communication']['status'] == 0) || $lead['communication']['status'] == 1 || $lead['communication']['status'] == 5) ? 'row-highlight' : '' }}">
            @if ($type)
              <td><input type="checkbox" class="check-lead" data-leadid="{{ $lead['id'] }}" /></td>
            @endif
            <td>{{ isset($lead['id'])? $lead['id'] : '' }}</td>
            <td>{{ isset($lead['customer']) ? $lead['customer']['name'] : '' }}</td>
            <td>{{ isset($lead['city']) ?  $lead['city'] : ''}}</td>
            <td>{{ isset($lead['rating']) ? $lead['rating'] : ''}}</td>
            <!-- <td>{{isset(App\User::find($lead['assigned_user'])->name) ? App\User::find($lead['assigned_user'])->name : ''}}</td> -->
            <td>{{App\Helpers::getproductsfromarraysofids($lead['selected_product'])}}</td>
            <!-- <td>
              @if (!empty($lead['communication']['body']))
                @if ($lead['communication']['status'] == 5 || $lead['communication']['status'] == 3)
                  Read
                @elseif ($lead['communication']['status'] == 6)
                  Replied
                @elseif ($lead['communication']['status'] == 1)
                  <span>Awaiting Approval</span>
                  {{-- <a href data-url="/message/updatestatus?status=2&id={{ $lead['communication']['id'] }}&moduleid={{ $lead['communication']['moduleid'] }}&moduletype={{ $lead['communication']['moduletype'] }}" style="font-size: 9px" class="change_message_status">Approve</a> --}}
                @elseif ($lead['communication']['status'] == 2)
                  Approved
                @elseif ($lead['communication']['status'] == 4)
                  Internal Message
                @elseif ($lead['communication']['status'] == 0)
                  Unread
                @endif
              @endif

              @if (!empty($lead['communication']['message']))
                @if ($lead['communication']['status'] == 5)
                  Read
                @elseif ($lead['communication']['status'] == 6)
                  Replied
                @elseif ($lead['communication']['status'] == 1)
                  <span>Awaiting Approval</span>
                  {{-- <a href data-url="/whatsapp/approve/leads?messageId={{ $lead['communication']['id'] }}" style="font-size: 9px" class="change_message_status approve-whatsapp" data-messageid="{{ $lead['communication']['id'] }}">Approve</a> --}}
                @elseif ($lead['communication']['status'] == 2)
                  Approved
                @elseif ($lead['communication']['status'] == 0)
                  Unread
                @endif
              @endif
            </td> -->
            <td>
              @if (isset($lead['communication']['body']))
                @if (strpos($lead['communication']['body'], '<br>') !== false)
                  {{ substr($lead['communication']['body'], 0, strpos($lead['communication']['body'], '<br>')) }}
                @else
                  {{ $lead['communication']['body'] }}
                @endif
              @else
                {{ $lead['communication']['message'] }}
              @endif
            </td>
            <td>{{App\Helpers::getleadstatus($lead['status'])}}</td>
            <td>{{ Carbon\Carbon::parse($lead['created_at'])->format('d-m H:i') }}</td>
            <td>
                <a class="btn btn-image" href="{{ route('leads.show',$lead['id']) }}"><img src="/images/view.png" /></a>
                {{-- <a class="btn btn-image" href="{{ route('leads.edit',$lead['id']) }}"><img src="/images/edit.png" /></a> --}}

                {!! Form::open(['method' => 'DELETE','route' => ['leads.destroy', $lead['id']],'style'=>'display:inline']) !!}
                <button type="submit" class="btn btn-image"><img src="/images/archive.png" /></button>
                {!! Form::close() !!}

                 @if(auth()->user()->isAdmin())
                    {!! Form::open(['method' => 'DELETE','route' => ['leads.permanentDelete', $lead['id']],'style'=>'display:inline']) !!}
                    <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>
                    {!! Form::close() !!}
                @endif
            </td>
        </tr>
    @endforeach
</table>
</div>
{!! $leads->appends(Request::except('page'))->links() !!}
