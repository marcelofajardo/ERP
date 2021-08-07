<div id="createLawyerSpecialityModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create Lawyer Speciality</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('lawyer.speciality.store') }}" method="POST">
        @csrf

        <div class="modal-body">
          <div class="form-group">
            <input type="text" name="title" value="{{ old('title') }}" class="form-control input-sm" placeholder="Enter Speciality">
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
