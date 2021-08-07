<div id="yesModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('whatsapp.forward') }}" method="POST">
        @csrf

        <div class="modal-header">
          <h4 class="modal-title">Choose your next action</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body text-center">
          <form action="{{ route('order.send.suggestion', $order->id) }}" method="POST">
            @csrf

            <button type="submit" class="btn btn-secondary">Send Images</button>
          </form>
          <button type="button" class="btn btn-secondary" id="create_refund_instruction">Create Instruction</button>

          <input type="hidden" name="order_id" id="refund_instruction_order_id" value="">
        </div>
      </form>
    </div>

  </div>
</div>
