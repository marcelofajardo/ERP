<div id="makeRemarksModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Remarks</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <div id="remarks-list">

        </div>

        <form class="add-remarks">
          <input type="hidden" name="id" value="" class="id">
          <div class="form-group">
            <textarea rows="2" name="remark" class="form-control remark" placeholder="Start the Remark"></textarea>
          </div>

          <button type="button" class="btn btn-secondary btn-block mt-2" id="addRemarksButton">Add</button>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
