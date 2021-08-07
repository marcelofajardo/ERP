<tr>
    <td>{{$item->id}}</td>
    <td>{{$item->subject}}</td>
    <td>{{$item->audience->name}}</td>
    <td>{{$item->template->name}}</td>
    <td>{{$item->scheduled_date}}</td>
    <td>
        <i data-id="{{$item->id}}" title="Preview" id="preview" class="fa fa-eye preview" aria-hidden="true"></i>
        <i data-id="{{$item->id}}" title="Duplicate" id="duplicate"  class="fa fa-clone duplicate" aria-hidden="true"></i>
    </td>
</tr>
