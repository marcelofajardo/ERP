<div id="editGroupModal{{ $group->id }}" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Group</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Name:</strong>
            <input type="text" name="composition" class="form-control" id="name{{ $group->id }}" value="{{ $group->name }}">
          </div>

          <div class="form-group">
            <strong>Composition:</strong>
            <input type="text" name="composition" class="form-control" id="composition{{ $group->id }}" value="{{ $group->composition }}">
          </div>

          <strong>HsCode:</strong>
            <select class="form-control selectpicker" name="group" data-live-search="true" id="hscode{{ $group->id }}" required>
              <option>Select HsCode</option>
              @foreach ($hscodes as $hscode)
                <option value="{{ $hscode->id }}" @if($hscode->id == $group->hs_code_id) selected @endif>{{ $hscode->code }}</option>
              @endforeach
            </select>

          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-secondary" onclick="submitGroupChange({{ $group->id }})">Edit</button>
        </div>
      </form>
    </div>

  </div>
</div>