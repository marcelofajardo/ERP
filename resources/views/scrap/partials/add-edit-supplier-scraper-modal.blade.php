<div id="scrapAddModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('generic.save.scraper') }}" method="POST">
        <div class="modal-header">
          <h4 class="modal-title">Add Generic Scraper</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Scraper Name:</strong>
            <input type="integer" name="name" class="form-control">
          </div>
          @csrf  
          <div class="form-group">
            <strong>Supplier:</strong>
            <select class="form-control selectpicker"  name="supplier_id" data-live-search="true">>
              @foreach($suppliers as $supplier)
              <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <strong>Run Gap:</strong>
            <input type="integer" name="run_gap" class="form-control">
          </div>
          
          <div class="form-group">
            <strong>Time Out:</strong>
            <input type="text" name="time_out" class="form-control">
            @if ($errors->has('name'))
              <div class="alert alert-danger">{{$errors->first('name')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Starting URL:</strong>
            <textarea type="text" name="starting_url" class="form-control"></textarea>
          </div>
          <div class="form-group">
            <strong>Designer URL Selector:</strong>
            <input type="text" name="designer_url"  class="form-control">
          </div>
          <div class="form-group">
            <strong>Product URL Selector:</strong>
            <input type="text" name="product_url_selector" class="form-control">
          </div>
          </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div id="scrapEditModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h4 class="modal-title">Edit Generic Scraper</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Run Gap:</strong>
            <input type="integer" name="run_gap" id="run_gap" class="form-control">
          </div>
          
          <div class="form-group">
            <strong>Time Out:</strong>
            <input type="text" name="time_out" id="time_out" class="form-control">
            @if ($errors->has('name'))
              <div class="alert alert-danger">{{$errors->first('name')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Starting URL:</strong>
            <textarea type="text" name="starting_url" id="starting_url" class="form-control"></textarea>
          </div>
          <div class="form-group">
            <strong>Designer URL Selector:</strong>
            <input type="text" name="designer_url" id="designer_url" class="form-control">
          </div>
          <div class="form-group">
            <strong>Product URL Selector:</strong>
            <input type="text" name="designer_url" id="product_url_selector" class="form-control">
          </div>
          <div class="form-group">
            <strong>Full Scrape:</strong>
            <select class="form-control" id="full_scrape" name="full_scrape">
              <option value="1">Yes</option>
              <option value="0">No</option>
            </select>
          </div>
          <input type="hidden" id="scraper_id">
          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-secondary" onclick="updateSupplier()">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

