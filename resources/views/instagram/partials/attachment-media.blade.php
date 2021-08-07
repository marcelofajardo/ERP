<div id="instagramAttachmentMedia" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div id="myDiv">
			   	<img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
			</div>

            <div class="modal-header">
                <h2>Attach Content</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">    
            @if($records)
            <div class="col-md-12">
                <div class="row">
            @foreach($records as $key => $record)
                @if($record['extension'] == 'png' || $record['extension'] == 'jpeg' || $record['extension'] == 'jpg' || $record['extension'] == 'gif')
                <div class="col-md-3">
                    <img style="width:100px;height:auto;" src="@if(isset($record['url'])){{$record['url']}}@endif" alt="">
                    <input type="radio" class="selectInstaAttachMedia" name="selectInstaAttachMedia" 
                    data-id="{{$record['id']}}"
                    data-extension="{{$record['extension']}}"
                    data-file_name="{{$record['file_name']}}"
                    data-mime_type="{{$record['mime_type']}}"
                    data-size="{{$record['size']}}"
                    data-thumb="@if(isset($record['url'])) {{$record['url']}} @endif"
                    data-original="{{$record['original']}}"
                    >
                </div>
                @endif
            @endforeach
            <button class="btn btn-small attachInstaMediaBtn">Attach</button>
            </div>
            </div>
            @endif
			</div>
        </div>
	</div>
</div>
   