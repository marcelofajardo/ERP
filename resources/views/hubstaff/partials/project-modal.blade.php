<div id="project-modal-view" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Create Project</span></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body">
            <div class="col-md-12">
               <form>
                  <?php echo csrf_field(); ?>
                  <div class="form-group">
                     <label for="hubstaff_project_name">Project Name</label>
                     <input type="text" class="form-control" name="hubstaff_project_name">
                  </div>
                  <div class="form-group">
                     <label for="hubstaff_project_description">Project Description</label>
                     <textarea name="hubstaff_project_description" class="form-control"></textarea>
                  </div>
                  <div class="form-group">
                     <button class="btn btn-secondary store-project">ADD</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>