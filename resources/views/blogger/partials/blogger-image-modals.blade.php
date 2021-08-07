<div id="bloggerImageModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="width: 800px;left: -25%;">
            <div class="modal-header">
                <h4 class="modal-title">Blogger Images</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="blogger_images row">

                </div>
                <br>
                <hr>
                {!! Form::open(['files'=>'true','method'=>'POST']) !!}
                <input type="file" class="form-control-file" name="images[]" accept="image/*" multiple id="images" required>
                <br>
                <button type="submit" class="btn btn-sm btn-secondary pull-right">Add</button>
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>