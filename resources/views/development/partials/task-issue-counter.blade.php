<div class="row">
    <div class="col-md-12">
        <div class="collapse" id="plannedFilterCount">
            <div class="card card-body">
              <?php if(!empty($countPlanned)) { ?>
                <div class="row col-md-12">
                    <?php foreach($countPlanned as $listFilter) { ?>
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
        <div class="collapse" id="inProgressFilterCount">
            <div class="card card-body">
              <?php if(!empty($countInProgress)) { ?>
                <div class="row col-md-12">
                    <?php foreach($countInProgress as $listFilter) { ?>
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