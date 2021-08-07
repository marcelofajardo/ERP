<div id="categoryUpdate" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      
        <div class="modal-header">
          <h4 class="modal-title">Change Category</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Original Category:</strong>
            <p id="old_category"></p>
            <strong>Changed Category:</strong>
            <p id="changed_category"></p>
            <strong>No of product will affect:</strong>
            <p id="no_of_product_will_affect"></p>
            
            @if ($errors->has('message'))
              <div class="alert alert-danger">{{$errors->first('message')}}</div>
            @endif
          </div>
          <input type="hidden" id="product_id">
          <input type="hidden" id="category_id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button class="btn btn-secondary" onclick="changeSelected()">Change This</button>
          <button class="btn btn-secondary" onclick="changeAll()">Change All</button>
        </div>
      
    </div>

  </div>
</div>
