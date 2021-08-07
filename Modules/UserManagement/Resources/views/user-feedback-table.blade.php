@foreach ($category as $cat)    
    <tr>
        <td>{{ $cat->category }}</td>
        <td class="communication-td">
            <input type="text" class="form-control send-message-textbox" data-id="{{$cat->user_id}}" id="send_message_{{$cat->user_id}}" name="send_message_{{$cat->user_id}}" placeholder="Enter Message...." style="margin-bottom:5px;width:77%;display:inline;" @if (!Auth::user()->isAdmin()) {{ "readonly" }} @endif/>
            <button style="display: inline-block;padding:0px;" class="btn btn-sm btn-image send-message-open" data-feedback_cat_id="{{$cat->id}}" type="submit" id="submit_message"  data-id="{{$cat->user_id}}" ><img src="/images/filled-sent.png"/></button></button>
        </td>
        <td class="communication-td">
            <input type="text" class="form-control send-message-textbox" data-id="{{$cat->user_id}}" id="send_message_{{$cat->user_id}}" name="send_message_{{$cat->user_id}}" placeholder="Enter Message...." style="margin-bottom:5px;width:77%;display:inline;" @if (Auth::user()->isAdmin()) {{ "readonly" }} @endif/>
            <button style="display: inline-block;padding:0px;" class="btn btn-sm btn-image send-message-open" data-feedback_cat_id="{{$cat->id}}" type="submit" id="submit_message"  data-id="{{$cat->user_id}}" ><img src="/images/filled-sent.png"/></button></button>
        </td>
        <td>
            <select class="form-control" class="user_feedback_status1">
                <option>Select</option>
                @foreach ($status as $st)
                    <option value="{{$st->id}}">{{ $st->status }}</option>
                @endforeach
            </select>
        </td>
        <td><button type="button" class="btn btn-xs btn-image load-communication-modal" data-feedback_cat_id="{{$cat->id}}" data-object='user-feedback' data-id="{{$cat->user_id}}" style="mmargin-top: -0%;margin-left: -2%;" title="Load messages"><img src="/images/chat.png" alt=""></button></td>
    </tr>
@endforeach