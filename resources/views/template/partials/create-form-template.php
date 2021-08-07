<script type="text/x-jsrender" id="product-templates-create-block">
<div class="modal fade" id="product-template-create-modal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Create Template</h4>
        </div>
        <div class="modal-body">
          <form method="post" enctype="multipart/form-data" id="product-template-from">
             <?php echo csrf_field(); ?>
             <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label">Template Name</label>
                <div class="col-sm-6">
                   <?php echo Form::text("name",null,["class" => "form-control name"]); ?>
                </div>
             </div>
             <div class="form-group row">
                <label for="no_of_images" class="col-sm-3 col-form-label">No Of Images</label>
                <div class="col-sm-6">
                   <?php echo Form::text("no_of_images",0,["class" => "form-control no_of_images"]); ?>
                </div>
             </div>
             <div class="form-group row">
                <label for="no_of_images" class="col-sm-3 col-form-label">Auto generate for products</label>
                <div class="col-sm-6">
                   <?php echo Form::checkbox("auto_generate_product",null,null, array("class" => "form-control auto_generate_product")); ?>
                </div>
             </div>
             <div class="form-group row show-product-image"> </div>
             <div class="form-group row">
              <div class="col-sm-3 imgUp">
                 <div class="imagePreview"></div>
                 <label class="btn btn-primary">
                 Upload<input type="file" name="files[]" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                 </label>
              </div>
           </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary create-product-template">Create Template</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</script>
