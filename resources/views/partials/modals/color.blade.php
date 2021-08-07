<div id="colorUpdate" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      
        <div class="modal-header">
          <h4 class="modal-title">Change Color</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Original Color:</strong>
            <p id="old_color"></p>
            <strong>Changed Color:</strong>
            <p id="changed_color"></p>
            <strong>No of product will affect:</strong>
            <p id="no_of_product_will_affect_color"></p>
            
            @if ($errors->has('message'))
              <div class="alert alert-danger">{{$errors->first('message')}}</div>
            @endif
          </div>
          <input type="hidden" id="product_id">
          <input type="hidden" id="color_id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button class="btn btn-secondary" onclick="changeSelectedColor()">Change This</button>
          <button class="btn btn-secondary" onclick="changeAllColors()">Change All</button>
        </div>
      
    </div>

  </div>
</div>
