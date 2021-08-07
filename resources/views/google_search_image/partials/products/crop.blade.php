<div class="modal fade" id="cropModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <form  method="POST" action="{{route('google.search.crop.post')}}" id="formSubmit">
                <div class="modal-body">
                    <img id="image_crop" width="100%">
                </div>
                {{ csrf_field() }}
                <div class="modal-footer">
                    <div class="col text-center">
                <select name="type" id="crop-type" class="form-control">
                    <option value="0">Select Crop Type</option>
                    <option value="8">8</option>
                </select>
                <input type="hidden" name="product_id" id="product-id">
                <input type="hidden" name="media_id" id="media_id">
                <button type="button" class="btn btn-image my-3" onclick="sendImageMessage()"><img src="/images/filled-sent.png" /></button>
                    </div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </form>    
        </div>
    </div>
</div>