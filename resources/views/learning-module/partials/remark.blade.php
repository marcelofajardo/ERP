<textarea id="remark-text-{{$task['id']}}" rows="1" name="remark" class="form-control"></textarea>
<button class="mt-2 update-remark" data-id="{{$task['id']}}">update</button>
<img id="remark-load-{{$task['id']}}" style="display: none" src="{{ asset('images/loading.gif') }}"/>
<span id="remarks-{{$task['id']}}" >
    @foreach(\App\Task::getremarks($task['id']) as $remark)
        <p> {{$remark['remark']}} <br> <small>updated on {{ Carbon\Carbon::parse($remark['created_at'])->format('d-m H:i') }}</small></p>
        <hr>
    @endforeach
</span>
