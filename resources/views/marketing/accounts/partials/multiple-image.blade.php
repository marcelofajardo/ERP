<div class="modal fade" id="largeImageModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="width: 200% !important; right: 220px !important">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <div class="modal-header">
                    <div class="row container">
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="image_text" placeholder="Enter text" >
                                <button class="btn btn-outline-secondary" type="button" id="get-images">Search</button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="type" value="photos" class="selectgroup-input post-type" checked="">
                                        <span class="selectgroup-button">Photos</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="type" value="collection" class="selectgroup-input post-type">
                                        <span class="selectgroup-button">Collection</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="type" value="user" class="selectgroup-input post-type">
                                        <span class="selectgroup-button">User</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body" >
                    <img id="loading-image-model" src="/images/pre-loader.gif" style="display:none;"/>
                    <div id="images">
                    </div>
                </div>
                <input type="hidden" id="account_id">
                <div class="modal-footer">
                	<button type="button" class="btn btn-default" onclick="getCaptions()" id="next_button">Next</button>
                	<button type="button" class="btn btn-default" onclick="submitPost()" style="display: none;" id="submit_button">Submit Post</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
        </div>
    </div>
</div>