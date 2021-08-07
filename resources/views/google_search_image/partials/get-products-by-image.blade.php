<div class="modal fade" id="product-image" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
           
                <div class="modal-body">
                     <div class="form-group">
                        
                        <strong>Image:</strong>

                        <input type="file" name="image" accept="image/*" id="imgupload">

                        @if ($errors->has('first_customer'))
                            <div class="alert alert-danger">{{$errors->first('first_customer')}}</div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-secondary" onclick="getProductsFromImage()">Get Product</button>
                </div>
        </div>
    </div>
</div>