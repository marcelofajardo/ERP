<ul>
    @foreach($childs as $child)
        <li>
            {{ $child->title }} ({{ $child->id }}) <a href="javascript:;" data-id="{{ $child->id }}" data-name="{{ $child->title }}" data-simply-duty-code="{{ $child->simplyduty_code }}" class="edit-modal-window"><i class="fa fa-edit"></i></a>
            @if(count($child->childs))
                @include('category.manageChild',['childs' => $child->childs])
            @endif
        </li>
    @endforeach
</ul>