<div id="chooseRecipientModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Resend Email</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('purchase.email.resend') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="purchase_id" value="{{ $order->id }}">
        <input type="hidden" name="email_id" value="" id="resend_email_id">
        <input type="hidden" name="email_type" value="" id="resend_email_type">
        <input type="hidden" name="type" value="" id="resend_type">

        <div class="modal-body">
          <div class="form-group">
            <strong>Choose Recipient</strong>
            <select class="form-control" name="recipient" required>
              <option value="">Select Recipient</option>

              @if ($order->purchase_supplier)
                @foreach ($order->purchase_supplier->agents as $agent)
                  <option value="{{ $agent->email }}">{{ $agent->name }} - {{ $agent->email }}</option>
                @endforeach
              @endif
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Send</button>
        </div>
      </form>
    </div>

  </div>
</div>
