          <div class="modal-header">
            <h4 class="modal-title">Domain information</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
          <div class="row">
              <div class="col">
                <div class="form-group">
                  <strong>Name</strong>
                  <input type="text" value="{{$domain->name}}" class="form-control" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <strong>ASCII name</strong>
                  <input type="text" value="{{$domain->ascii_name}}" class="form-control" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <strong>GUID</strong>
                    <input type="text" value="{{$domain->guid}}" class="form-control" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <strong>Hosting type</strong>
                    <input type="text" value="{{$domain->hosting_type}}" class="form-control" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <strong>Created at</strong>
                    <input type="text" value="{{$domain->created}}" class="form-control" readonly>
                </div>
              </div>
            </div>
            </div>
          <div class="modal-footer">
            <div class="row" style="margin:0px;">
              <button data-dismiss="modal" type="button" style="margin-top: 5px;" class="btn btn-secondary">Close</button>
            </div>
          </div>

