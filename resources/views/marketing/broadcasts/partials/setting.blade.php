<div id="settingModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Global Setting</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <form action="{{ route('broadcast.global.save') }}" method="POST">
          <div class="form-group">
            @csrf
            <strong>Frequency:</strong>
            <input type="integer" name="frequency" class="form-control" name="frequency">
          </div>

          <div class="form-group">
            <strong>Start Time:</strong>
            <select class="form-control" name="send_start">
              <option value="">Select Send Time</option>
              <option value="0">0</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
              <option value="13">13</option>
              <option value="14">14</option>
              <option value="15">15</option>
              <option value="16">16</option>
              <option value="17">17</option>
              <option value="18">18</option>
              <option value="19">19</option>
              <option value="20">20</option>
              <option value="21">21</option>
              <option value="22">22</option>
              <option value="23">23</option>
            </select>

            @if ($errors->has('send_at'))
            <div class="alert alert-danger">{{$errors->first('send_at')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>End Time:</strong>
            <select class="form-control" name="send_end">
              <option value="">Select End Time</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
              <option value="13">13</option>
              <option value="14">14</option>
              <option value="15">15</option>
              <option value="16">16</option>
              <option value="17">17</option>
              <option value="18">18</option>
              <option value="19">19</option>
              <option value="20">20</option>
              <option value="21">21</option>
              <option value="22">22</option>
              <option value="23">23</option>
            </select>

            @if ($errors->has('send_end'))
            <div class="alert alert-danger">{{$errors->first('send_end')}}</div>
            @endif
          </div>         

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Save</button>
        </div>
      </form>
    </div>

  </div>
</div>
