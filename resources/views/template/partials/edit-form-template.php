<div class="modal fade" id="product-template-edit-modal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Template</h4>
        </div>
        <div class="modal-body">
          <form method="post" enctype="multipart/form-data" action="templates/update/bearbanner/template">
             <?php echo csrf_field(); ?>
             <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label">Template Name</label>
                <div class="col-sm-6">
                  <input type="text" name="name" class="form-control" id="name">
                </div>
             </div>

             <div class=>

             </div>
             <!-- <div class="form-group row">
                <label for="no_of_images" class="col-sm-3 col-form-label">No Of Images</label>
                <div class="col-sm-6">
                     <input type="integer" name="number" class="form-control" id="number">
                </div>
             </div> 

              <div class="form-group row">
                <label for="auto_generate_product" class="col-sm-3 col-form-label">Auto generate for products</label>
                <div class="col-sm-6">
                   <input type="checkbox" name="auto" id="auto" class="form-control">
                </div>
             </div> -->
             <input type="hidden" name="id" id="id">

             <div class="form-group row show-product-image"></div>

             <div class="form-group row">
              <div class="col-sm-3 imgUp">
                 <div class="imagePreview" id="imagePreview"></div>
               <!--   <label class="btn btn-primary">
                 Upload<input type="file" name="files[]" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;" id="uploadFile">
                 </label> -->
              </div>
           </div>
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-secondary">Edit Template</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>
    </div>
  </div>

