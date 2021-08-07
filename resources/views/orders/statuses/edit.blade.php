<form action="{{ route('store-website.status.submitEdit', $store_order_status->id) }}" method="POST">
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
                        @foreach($order_statuses as $erp)
                        <option value="{{ $erp->id }}" {{ $erp->id == $store_order_status->order_status_id ? 'selected' : '' }}>{{ $erp->status }}</option>
                        @endforeach
                    </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <strong>Store</strong>
                    <select class="form-control store_website_id select2" name="store_website_id" id="store_website_id">
                        <option  value="">Select</option>
                        @foreach($store_website as $website)
                        <option value="{{ $website->id }}" {{ $website->id == $store_order_status->store_website_id ? 'selected' : '' }}>{{$website->title}} ({{$website->website_source}})</option>
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
                      <option  value="">Select</option>
                        @foreach($store_master_statuses as $status)
                        <option  value="{{$status->id}}" {{$status->id == $store_order_status->store_master_status_id ? 'selected' : ''}} >{{$status->label}}</option>
                        @endforeach
                    </select>
                </div>
              </div>
            </div>
          <div class="modal-footer">
            <div class="row" style="margin:0px;">
              <button type="submit" style="margin-top: 5px;" class="btn btn-secondary">Edit</button>
            </div>
          </div>
</form>

