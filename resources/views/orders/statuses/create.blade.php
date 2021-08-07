<form action="{{ route('store-website.submit.status') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h4 class="modal-title">Map Status</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
          <div class="row">
              <div class="col">
                <div class="form-group">
                  <strong>ERP status</strong>
                    <select class="form-control select2" name="order_status_id">
                        <option  value="">Select</option>
                        @foreach($order_statuses as $status)
                        <option  value="{{$status->id}}">{{$status->status}}</option>
                        @endforeach
                    </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <strong>Store</strong>
                    <select class="form-control store_website_id select2" name="store_website_id">
                        <option  value="">Select</option>
                        @foreach($store_website as $website)
                        <option  value="{{$website->id}}">{{$website->title}} ({{$website->website_source}})</option>
                        @endforeach
                    </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <strong>Store Status</strong>
                    <select class="form-control select2" name="store_master_status_id" id="store_master_status_id">
                        
                    </select>
                </div>
              </div>
            </div>
          <div class="modal-footer">
            <div class="row" style="margin:0px;">
              <button type="submit" style="margin-top: 5px;" class="btn btn-secondary">Add</button>
            </div>
          </div>
</form>

<!-- <option  value="">Select</option>
                        @foreach($store_master_statuses as $status)
                        <option  value="{{$status->id}}">{{$status->label}}</option>
                        @endforeach -->

