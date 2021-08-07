<tr>
    <td>{{ $category->category }}</td>
    <td class="communication-td">
        <input type="text" class="form-control send-message-textbox" data-id="{{$category->user_id}}" id="send_message_{{$category->user_id}}" name="send_message_{{$category->user_id}}" placeholder="Enter Message...." style="margin-bottom:5px;width:calc(100% - 25px);display:inline;" @if (!Auth::user()->isAdmin()) {{ "readonly" }} @endif/>
        <button style="display: inline-block;padding:0px;width:20px" class="btn btn-sm btn-image send-message-open" data-feedback_cat_id="{{$category->id}}" type="submit" id="submit_message"  data-id="{{$category->user_id}}" ><img src="/images/filled-sent.png"/></button></button>
    </td>
    <td class="communication-td ">
        <input type="text" class="form-control send-message-textbox" data-id="{{$category->user_id}}" id="send_message_{{$category->user_id}}" name="send_message_{{$category->user_id}}" placeholder="Enter Message...." style="margin-bottom:5px;width:calc(100% - 25px);display:inline;" @if (Auth::user()->isAdmin()) {{ "readonly" }} @endif/>
        <button style="display: inline-block;padding:0px;width: 20px" class="btn btn-sm btn-image send-message-open" data-feedback_cat_id="{{$category->id}}" type="submit" id="submit_message"  data-id="{{$category->user_id}}" ><img src="/images/filled-sent.png"/></button></button>
    </td>
    <td>
        <select class="form-control" class="user_feedback_status">
            <option>Select</option>
            @foreach ($status as $st)
                <option value="{{$st->id}}">{{ $st->status }}</option>
            @endforeach
        </select>
    </td>
    <td><button type="button" class="btn btn-xs btn-image load-communication-modal" data-feedback_cat_id="{{$category->id}}" data-object='user-feedback' data-id="{{$category->user_id}}"  style="margin-top: -0%;margin-left: -2%;" title="Load messages"><img src="/images/chat.png" alt=""></button></td>
</tr>