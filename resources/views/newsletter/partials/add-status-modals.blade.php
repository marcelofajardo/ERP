<div id="createStatusModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create Status</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="" method="POST" id="formCreateLandingPageStatus">
        @csrf
        {!! csrf_field() !!}
        <div class="modal-body">
          <div class="form-group">
            <input type="text" name="landing_page_status" value="{{ old('landing_page_status') }}" class="form-control input-sm" placeholder="Status Name">
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
<script>

</script>