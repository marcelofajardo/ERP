<div class="row">
    <div class="col-md-12">
      <div class="collapse" id="brandFilterCount">
        <div class="card card-body">
          <?php if(!empty($countBrands)) { ?>
            <div class="row col-md-12">
                <?php foreach($countBrands as $listFilter) { ?>
                  <div class="col-md-2">
                    <div class="card">
                        <div class="card-header">
                          <?php echo $listFilter["name"]; ?>
                        </div>
                        <div class="card-body">
                            <?php echo $listFilter["count"]; ?>
                        </div>
                    </div>
                  </div>
              <?php } ?>
            </div>
          <?php } else  {
            echo "Sorry , No data available";
          } ?>
        </div>
      </div>

      <div class="collapse" id="categoryFilterCount">
        <div class="card card-body">
          <?php if(!empty($countCategory)) { ?>
            <div class="row col-md-12">
                <?php foreach($countCategory as $listFilter) { ?>
                  <div class="col-md-2">
                    <div class="card">
                        <div class="card-header">
                          <?php echo $listFilter["name"]; ?>
                        </div>
                        <div class="card-body">
                            <?php echo $listFilter["count"]; ?>
                        </div>
                    </div>
                  </div>
              <?php } ?>
            </div>
          <?php } else  {
            echo "Sorry , No data available";
          } ?>
        </div>
      </div>
       </br>
      <div class="collapse" id="suppliersFilterCount">
        <div class="card card-body">
          <?php if(!empty($countSuppliers)) { ?>
            <div class="row col-md-12">
                <?php foreach($countSuppliers as $listFilter) { ?>
                  <div class="col-md-2">
                    <div class="card">
                        <div class="card-header">
                          <?php echo $listFilter["name"]; ?>
                        </div>
                        <div class="card-body">
                            <?php echo $listFilter["count"]; ?>
                        </div>
                    </div>
                  </div>
              <?php } ?>
            </div>
          <?php } else  {
            echo "Sorry , No data available";
          } ?>
        </div>
      </div>
    </div>
  </div>