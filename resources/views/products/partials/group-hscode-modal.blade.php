<div id="groupModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('dubbizle.bulk.whatsapp') }}" method="POST">
        @csrf

        <div class="modal-header">
          <h4 class="modal-title">Get Hs Code</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <!-- <div class="form-group">
            <strong>HsCode:</strong>
            <select class="form-control selectpicker" name="group" data-live-search="true" id="hscode" required>
              <option>Select HsCode</option>
              @foreach ($hscodes as $hscode)
                <option value="{{ $hscode->id }}">{{ $hscode->code }}</option>
              @endforeach
            </select>

          </div> -->

          <div class="form-group">
            <strong>Compositon:</strong>
            <input type="text" name="composition" class="form-control" id="composition" required>
          </div>
          
          <div class="form-group">
            <strong>Existing Group:</strong>
          <select class="form-control selectpicker" name="existing_group" data-live-search="true" id="existing_group" required>
              <option value="">Select Group</option>
              @foreach ($groups as $group)
                <option value="{{ $group->id }}">{{ $group->name }}</option>
              @endforeach
            </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-secondary" onclick="submitGroup()">Send</button>
        </div>
      </form>
    </div>

  </div>
</div>