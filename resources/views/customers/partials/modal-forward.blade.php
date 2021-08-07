<div id="forwardModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('whatsapp.forward') }}" method="POST">
        @csrf
        <input type="hidden" name="message_id" id="forward_message_id" value="">

        <div class="modal-header">
          <h4 class="modal-title">Forward Message</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
              <strong>Client:</strong>
              <select class="selectpicker form-control" data-live-search="true" data-size="15" name="customer_id[]" title="Choose a Customer" required multiple>
                @foreach ($customers as $client)
                 <option data-tokens="{{ $client->name }} {{ $client->email }}  {{ $client->phone }} {{ $client->instahandler }}" value="{{ $client->id }}">{{ $client->name }} - {{ $client->phone }}</option>
               @endforeach
             </select>

              @if ($errors->has('customer_id'))
                  <div class="alert alert-danger">{{$errors->first('customer_id')}}</div>
              @endif
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Forward Message</button>
        </div>
      </form>
    </div>

  </div>
</div>
