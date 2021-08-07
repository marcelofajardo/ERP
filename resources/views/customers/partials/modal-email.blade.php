<div id="emailSendModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Send an Email</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('customer.email.send') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="customer_id" value="{{ $customer->id }}">

        <div class="modal-body">
          <div class="form-group">
            <strong>Select Order</strong>
            <select class="form-control" name="order_id" id="email_order_id">
              <option value="">Select an Order</option>

              @if (count($customer->orders) > 0)
                @foreach ($customer->orders as $order)
                  <option value="{{ $order->order_id }}">{{ $order->order_id }}</option>
                @endforeach
              @endif
            </select>
          </div>

          <div class="form-group">
            <strong>Subject</strong>
            <input type="text" class="form-control" name="subject" value="{{ old('subject') }}" required>
          </div>

          <div class="form-group">
            <strong>Message</strong>
            <textarea name="message" class="form-control" rows="8" cols="80" required>{{ old('message') }}</textarea>
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
