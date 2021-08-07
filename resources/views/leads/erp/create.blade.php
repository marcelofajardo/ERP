<form action="<?php echo route("leads.erpLeads.store"); ?>" id="lead_create" enctype="multipart/form-data" >
  <?php echo csrf_field(); ?>
  <div class="form-group">
    <label for="customer_id">Customer:</label>
    <?php echo Form::select("customer_id", $customerList, null,["class"=> "form-control customer-search-box", "style"=>"width:100%;"]);  ?>
  </div>
  <div class="form-group">
    <label for="brand_id">Brand:</label>
    <select class="form-control multi_brand_select" name="brand_id" multiple>
        <?php foreach ($brands as $brand) {
            echo '<option value="'.$brand->id.'" data-brand-segment="'.$brand->brand_segment.'">'.$brand->name.'</option>';
        } ?>
    </select>
  </div>
  <div class="form-group">
    <label for="category_id">Category:</label>
    <?php echo $category; ?>
  </div>
  <div class="form-group">
    <strong>Brand Segment:</strong>
    {{ App\Helpers\ProductHelper::getBrandSegment('brand_segment[]', null, ['class' => "form-control brand_segment_select" , 'multiple' => ''])}}
  </div>
  <div class="form-group">
    <label for="color">Color:</label>
    <?php echo Form::select("color",["" => "-- Select an option --"] + $colors,null,["class"=> "form-control"]);  ?>
  </div>
  <div class="form-group">
    <label for="size">Size:</label>
    <?php echo Form::text("size",null,["class"=> "form-control"]);  ?>
  </div>
  <div class="form-group">
    <label for="lead_status_id">Status:</label>
    <?php echo Form::select("lead_status_id", $status , null,["class"=> "form-control"]);  ?>
  </div>
  <div class="form-group">
    <strong>Gender:</strong>
    <select name="gender" class="form-control">
      <option value="male">Male</option>
      <option value="female">Female</option>
    </select>
  </div>
  <input  type="hidden" name="oldImage[]" value="-1" />
  <div class="form-group new-image" style="">
    <strong>Upload Image:</strong>
    <input  type="file" class="form-control" name="image[]" multiple />
    @if ($errors->has('image'))
      <div class="alert alert-danger">{{$errors->first('image')}}</div>
    @endif
  </div>
  <button type="submit" class="btn btn-default lead-button-submit-form">Submit</button>
</form>