<!-- Modal -->
<div id="upload-document-modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Upload Document</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form id="upload-task-documents">
          <div class="modal-body">
              <?php echo csrf_field(); ?>
              <input type="hidden" id="hidden-identifier" name="developer_task_id" value="">
              <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Subject</label>
                                <?php echo Form::text("subject",null, ["class" => "form-control", "placeholder" => "Enter subject"]); ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description</label>
                                <?php echo Form::textarea("description",null, ["class" => "form-control", "placeholder" => "Enter Description"]); ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Documents</label>
                                <input type="file" name="files[]" id="filecount" multiple="multiple">
                            </div>
                        </div>  
                    </div>  
                </div>  
              </div>  
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-default">Save</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
       </form>
    </div>
  </div>
</div>
