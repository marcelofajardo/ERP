<div id="set-description-site-wise" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Set Description For</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
       <form method="post">
          <div class="modal-body">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="product_id" id="store-product-id" value="">
            <input type="hidden" name="description" id="store-product-description" value="">
            <div class="form-group">
                <strong>Description:</strong>
                <p id="show-description-summery"></p>
            </div>
            <div class="form-group">
               <?php echo Form::select("store_wesites[]",\App\StoreWebsite::pluck("website","id")->toArray(),null, ["class" => "form-control select2"]); ?>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button class="btn btn-secondary btn-save-store">Save store</button>
          </div>
        </form>
    </div>
  </div>
</div>
