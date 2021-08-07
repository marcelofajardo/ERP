<div class="col-md-12">
    <?php echo Form::hidden("product_id",$productId,["class" => "instruction-pr-id"]); ?>
    <div class="form-group">
        <label>Mode of Shipment:</label>
        <?php echo Form::text("modeof_shipment",null,["class" => "form-control instruction-type-select"]); ?>
    </div>
     <div class="form-group">
        <label>Delivery Person:</label>
        <?php echo Form::text("delivery_person",null,["class" => "form-control"]); ?>
    </div>
     <div class="form-group">
        <label>AWB:</label>
        <?php echo Form::text("awb",null,["class" => "form-control"]); ?>
    </div>
    <div class="form-group">
      <label>ETA:</label>
      <?php echo Form::text("eta",null,["class" => "form-control"]); ?>
    </div>
    <!-- <div class="form-group">
      <label>Date</label>
      <?php echo Form::text("date_time",null,["class" => "form-control date-time-picker"]); ?>
    </div> -->
    <?php for ($i=0; $i < 5; $i++) { ?> 
      <div class="form-group">
        <label><?php echo "Image : ". ($i+1); ?></label>
        <?php echo Form::file("file[]",null,["class" => "form-control"]); ?>
      </div>
    <?php } ?>
</div>