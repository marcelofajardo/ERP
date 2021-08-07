<div class="modal-content">
   <div class="modal-header">
      <h5 class="modal-title">Affected Product Count </h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
   </div>
   <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
              <p><b>Total Product: </b>{{ $total }} affected please choose yes to apply or no for update only color</p>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="row">
            <div class="col-md-12">
                <button type="button" data-from="{{$from}}" data-to="{{$to}}" class="btn btn-default btn-change-color" data-with-product="yes" data-dismiss="modal">Yes</button>
                <button type="button" data-from="{{$from}}" data-to="{{$to}}" class="btn btn-default btn-change-color" data-with-product="no" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>