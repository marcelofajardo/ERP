<div class="row margin-tb" style="margin-top:10px;">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <form>
                <div class="form-group">
                    <input data-id="{{ $id }}" type="text" class="form-control" value="{{ $keyword }}" id="search-by-phrases" aria-describedby="search-by-phrases" placeholder="Enter Keyword">
                </div>
            </form>
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-secondary ml-3" onclick="addGroupPhrase()">Phrase Group</a>
        </div>
    </div>
</div>
<div class="col-md-12 margin-tb" style="margin-top:10px;">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Words</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
            @if($phrases)
                @foreach($phrases as $raw)
                    <tr>
                        <td><input type="checkbox" name="phrase" value="{{ $raw->id }}" data-keyword="{{ $raw->id }}">  {{ $raw->phrase }}</td>
                        <td>
                            <button data-id="{{ $raw->chat_id }}" class="btn btn-image get-chat-details"><img src="/images/chat.png"></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
<div class="col-md-12 phrases-pagination">
    {{ $phrases->appends(request()->except("page"))->links() }}
</div>    