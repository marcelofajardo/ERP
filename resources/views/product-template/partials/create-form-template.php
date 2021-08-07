<script type="text/x-jsrender" id="product-templates-create-block">
<div class="modal fade" id="product-template-create-modal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Create Product Template</h4>
        </div>
        <div class="modal-body">
          <form method="post" enctype="multipart/form-data" id="product-template-from">
             <?php echo csrf_field(); ?>
             <div class="form-group row">
                <label  class="col-sm-3 col-form-label">Generate image From</label>
                <div class="col-sm-6">
                   <select class="form-control" name="generate_image_from" id="generate_image_from_input" aria-invalid="false">
                        <option value="python-script">Python Script</option>
                        <option value="banner-bear">Banner bear</option>
                    </select>
                </div>
             </div>
             <div class="form-group row">
                <label for="template_no" class="col-sm-3 col-form-label">Template No</label>
                <div class="col-sm-6">
                    <select class="form-control template-dropdown-function template_no valid" name="template_no" aria-invalid="false">
                        <?php 
                            foreach ($templateArr as $template) {
                               $media = $template->lastMedia(config('constants.media_tags'));
                                echo '<option value="'.$template->id.'" data-image="'.(($media) ? $media->getUrl():"").'" data-no-of-images="'.$template->no_of_images.'">'.$template->name.'</option>';
                            }
                       ?>
                    </select>
                </div>


                <div class="col-sm-3">
                  <div class="image_template_no" style="position: absolute; width: 85%;">
                  </div>
                </div>
             </div>
             <div class="form-group row">
                <label for="product_id" class="col-sm-3 col-form-label">Product</label>
                <div class="col-sm-6">
                    <div style="width: 94%; float: left;" class="div-select-product">
                        
                        <select class="orm-control ddl-select-product" name="product_id[]" aria-invalid="false" multiple>
                        <?php 
                            if ($productArr) {
                              foreach ($productArr as $product) {
                                  echo '<option value="'.$product->id.'" data-brand="'.$product->brand.'" data-product-title="'.$product->name.'" selected>'.$product->name.'</option>';
                              }
                            }
                       ?>
                    </select>
                    </div>
                    <div style="width: 6%; float: right;">
                        <a href="<?php echo route('attachImages', 'product-templates');?>" class="btn btn-image px-1 images-attach"><img src="/images/attach.png"></a>
                    </div>
                </div>
             </div>
           
           <div class="special"> </div>
           <div class="default">
                <div class="form-group row">
                    <label for="currency" class="col-sm-3 col-form-label">Text</label>
                    <div class="col-sm-6">
                       <?php echo Form::text("modifications_array[0][text]",null,["class" => "form-control"]); ?>
                    </div>
                 </div>
                 <div class="form-group row">
                    <label for="currency" class="col-sm-3 col-form-label">Color</label>
                    <div class="col-sm-6">
                       <?php echo Form::color("modifications_array[0][color]",null,["class" => "form-control"]); ?>
                    </div>
                 </div>
                 <div class="form-group row">
                    <label for="currency" class="col-sm-3 col-form-label">Background</label>
                    <div class="col-sm-6">
                       <?php echo Form::color("modifications_array[0][background]",null,["class" => "form-control"]); ?>
                    </div>
                 </div>
           </div>

             <div class="form-group row">
                <label for="brand_id" class="col-sm-3 col-form-label">Brand</label>
                <div class="col-sm-6">
                   <?php echo Form::select("brand_id",["" => "-Select-"] + \App\Brand::all()->pluck("name","id")->toArray(),null,["class" => "form-control"]); ?>
                </div>
             </div>
             <div class="form-group row">
                <label for="currency" class="col-sm-3 col-form-label">Currency</label>
                <div class="col-sm-6">
                   <?php echo Form::text("currency",null,["class" => "form-control"]); ?>
                </div>
             </div>
             <div class="form-group row">
                <label for="price" class="col-sm-3 col-form-label">Price</label>
                <div class="col-sm-6">
                   <?php echo Form::text("price",(float)0.00,["class" => "form-control"]); ?>
                </div>
             </div>
             <div class="form-group row">
                <label for="discounted_price" class="col-sm-3 col-form-label">Discounted Price</label>
                <div class="col-sm-6">
                   <?php echo Form::text("discounted_price",(float)0.00,["class" => "form-control"]); ?>
                </div>
             </div>
             <div class="form-group row">
                <label for="discounted_price" class="col-sm-3 col-form-label">Discounted Price</label>
                <div class="col-sm-6">
                   <?php echo Form::text("discounted_price",(float)0.00,["class" => "form-control"]); ?>
                </div>
             </div>
             <div class="form-group row">
                <label for="store_website_id" class="col-sm-3 col-form-label">Store Website</label>
                <div class="col-sm-6">
                   <?php echo Form::select("store_website_id",\App\StoreWebsite::pluck('title','id')->toArraY(),null,["class" => "form-control"]); ?>
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
              <i class="fa fa-plus imgAdd"></i>
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
