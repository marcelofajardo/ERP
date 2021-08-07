<form action="{{ route('plesk.domains.submit-mail', $id) }}" method="POST">
          @csrf
            <div class="modal-header">
            <h4 class="modal-title">Create new email account</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
          <input type="hidden" name="site_name" value={{$sitename}}>
          <div class="row">
              <div class="col">
                <div class="form-group">
                  <strong>Name</strong>
                  <input type="text" name="name" class="form-control" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <strong>Mailbox</strong>
                  <input type="text" name="mailbox"  class="form-control">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <strong>Password</strong>
                    <input type="text" autocomplete="new-password" title="Should have at least 1 lowercase AND 1 uppercase AND 1 number AND 1 special character and minimum 6 character long" name="password" class="form-control" required>
                </div>
              </div>
            </div>
            </div>
          <div class="modal-footer">
            <div class="row" style="margin:0px;">
              <button type="submit" style="margin-top: 5px;" class="btn btn-secondary">Submit</button>
            </div>
          </div>
</form>
