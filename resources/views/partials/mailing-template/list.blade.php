@foreach($mailings as $item)
    <tr>
        <td>{{$item["name"]}}</td>
        <td>{{$item["mail_tpl"]}}</td>
        <!-- <td>{{$item["image_count"]}}</td> -->
        <!-- <td>{{$item["text_count"]}}</td> -->
        <td>
            @if($item['example_image'])
                <img style="width: 100px" src="{{ asset($item['example_image']) }}">
            @endif
        </td>
        <td>
            <a data-id="{{ $item['id'] }}" class="delete-template-act" href="javascript:;">
                <i class="fa fa-trash"></i>
            </a>
            | <a data-id="{{ $item['id'] }}" data-storage="{{ $item }}" class="edit-template-act" href="javascript:;">
                <i class="fa fa-edit"></i>
            </a>
        </td>
    </tr>
@endforeach

