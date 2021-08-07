<form action="<?php echo route("leads.erpLeads.store"); ?>">
  <?php echo csrf_field(); ?>
  <?php echo Form::hidden("id",$erpLeads->id,["class"=> "form-control"]);  ?>
  <div class="form-group">
    <label for="customer_id">Customer:</label>
    <?php echo Form::select("customer_id", $customerList, $erpLeads->customer_id,["class"=> "form-control customer-search-box", "style"=>"width:100%;"]);  ?>
  </div>
  <div class="form-group">
    <label for="product_id">Products: (selected :<?php echo @reset($products); ?>)</label>
    <?php echo Form::select("product_id", $products , $erpLeads->product_id,["class"=> "form-control" ,"id" => "select2-product", "style"=>"width:100%;"]);  ?>
  </div>
  <div class="form-group">
    <label for="brand_id">Brand:</label>
    <?php echo Form::select("brand_id", ["" => "-- Select an option --"] + $brands , $erpLeads->brand_id,["class"=> "form-control"]);  ?>
  </div>
  <div class="form-group">
    <label for="category_id">Category:</label>
    <?php echo $category; ?>
  </div>
  <div class="form-group">
    <label for="color">Color:</label>
    <?php echo Form::select("color",["" => "-- Select an option --"] + $colors,$erpLeads->color,["class"=> "form-control"]);  ?>
  </div>
  <div class="form-group">
    <label for="size">Size:</label>
    <?php echo Form::text("size",$erpLeads->size,["class"=> "form-control"]);  ?>
  </div>
  <div class="form-group">
    <label for="min_price">Min price:</label>
    <?php echo Form::text("min_price",$erpLeads->min_price,["class"=> "form-control"]);  ?>
  </div>
  <div class="form-group">
    <label for="max_price">Max price:</label>
    <?php echo Form::text("max_price",$erpLeads->max_price,["class"=> "form-control"]);  ?>
  </div>
  <div class="form-group">
    <label for="lead_status_id">Status:</label>
    <?php echo Form::select("lead_status_id", $status , $erpLeads->lead_status_ids,["class"=> "form-control"]);  ?>
  </div>
  <button type="submit" class="btn btn-default lead-button-submit-form">Submit</button>
</form>