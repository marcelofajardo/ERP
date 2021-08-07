<div id="makeRemarkModal" class="modal fade" role="dialog" style="z-index: 1051;">
  <div class="modal-dialog <?php echo (!empty($type) && ($type == 'scrap' || $type == 'email')) ? 'modal-lg' : ''  ?>">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Remarks</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <?php if((!empty($type) && ($type == 'scrap' || $type == 'email'))) {  ?>
          <form id="filter-module-remark">
            <div class="form-group">
              <label for="filter_auto">Remove auto</label>
              <input type="checkbox" name="filter_auto" class="filter-auto-remark">
            </div>
          </form>
        <?php } ?>
        <?php if((!empty($type) && ($type == 'scrap' || $type == 'email'))) {  ?>
          <table class="table fixed_header table-bordered">
              <thead class="thead-dark">
                <tr>
                  <th width="50%">Comment</th>
                  <th width="10%">Created By</th>
                  <th width="10%">Created At</th>
                </tr>
              </thead>
              <tbody id="remark-list"></tbody>
            </table>
        <?php } else{ ?>
        <div class="list-unstyled" id="remark-list">

        </div>
        <?php } ?>
        <form id="add-remark">
          <input type="hidden" name="id" value="">
          <div class="form-group">
            <textarea rows="2" name="remark" class="form-control" placeholder="Start the Remark"></textarea>
          </div>
          {{-- We dont need following settings for email page --}}
          @if (empty($type) || $type != 'email')
            <div class="form-group">
              <label><input type="checkbox" class="need_to_send" value="1">&nbsp;Need to Send Message ?</label>
            </div>
            <div class="form-group">
              <label><input type="checkbox" class="inlcude_made_by" value="1">&nbsp;Want to include Made By ?</label>
            </div>
          @endif
          <button type="button" class="btn btn-secondary btn-block mt-2" id="{{ (!empty($type) && $type == 'scrap') ? 'scrapAddRemarkbutton' : 'addRemarkButton' }}">Add</button>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
