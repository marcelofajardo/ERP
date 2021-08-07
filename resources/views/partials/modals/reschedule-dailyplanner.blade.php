<div id="reschedule-daily-planner" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Reschedule</span></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form action="<?php echo route('dailyplanner.reschedule'); ?>" method="post">
            {{ csrf_field() }}
            <div class="row">
                <div class="col">
                    <div class="form-group">
                      <input type="hidden" name="type" id="reschedule-type">
                      <input type="hidden" name="id" id="reschedule-id">
                      <div class='input-group date' id='reschedule-planned-datetime'>
                        <input type='text' class="form-control input-sm planned-at-input" name="planned_at" value="" />
                        <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                      </div>
                  </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <button class="btn btn-secondary save-reschedule-planner">Save</button>
                    </div>
               </div>   
            </div>
          </form>  
        </div>
      </div>
    </div>
</div>