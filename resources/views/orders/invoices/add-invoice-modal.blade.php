<div id="addInvoice" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content ">
      <div class="row">
        <div class="form-group">
                    <select name="term" type="text" class="form-control" placeholder="Search" id="customer-search" data-allow-clear="true">
                        <?php
                            if (request()->get('term')) {
                                echo '<option value="'.request()->get('term').'" selected>'.request()->get('term').'</option>';
                            }
                        ?>
                    </select>
          </div>
        </div>
        <div class="add-invoice-content">

        </div>
      </div>
    </div>
</div>
