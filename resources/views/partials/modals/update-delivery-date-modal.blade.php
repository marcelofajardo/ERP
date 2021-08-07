<!-- Modal -->
<div id="update-del-date-modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
    <form action="{{ route('order.updateDelDate') }}" method="GET" id="updateDelDateForm">
    @csrf
      <div class="modal-header">
        <h4 class="modal-title">Update Estimated Delivery Date</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
       
        <div class="form-group">
          <input type="hidden" id="orderid" name="orderid" value="">
          <input type="text" class="form-control date-picker" id="newdeldate" name="newdeldate" placeholder="select a date to update estimate delivery date">
          <input type="hidden" id="fieldname" name="fieldname" value="estimated_delivery_date">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default update-del-date" >Update</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </form>
    </div>
  </div>
</div>