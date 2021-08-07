<form action="{{ route('order.submitEdit.invoice') }}" method="POST">
          @csrf
          <input type="hidden" name="id" value="{{ $invoice['id'] }}">

          <div class="modal-header">
            <h4 class="modal-title">Add orders to invoice</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <strong>Invoice number:</strong>
              <input type="text" name="invoice_number" class="form-control" value="{{ $invoice['invoice_number']}}" readonly>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <strong>Invoice date</strong>
                  <div class='input-group date' id='invoice_date'>
                    <input type='text' class="form-control input_invoice_date" name="invoice_date" value="{{ $invoice['invoice_date'] }}" required />

                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <br>
            @if(count($more_orders) > 0)
            <p>You can add more orders to this invoice</p>
            <div>
            <div class="table-responsive" style="margin-top:20px;">
      <table class="table table-bordered" style="border: 1px solid #ddd;">
        <thead>
          <tr>
            <th>Date</th>
            <th>Client Name</th>
            <th>Country</th>
            <th>Currency</th>
            <th>Amount</th>
            <th>Site Name</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
        @foreach($more_orders as $more_order)
            <tr>
              <td>{{ Carbon\Carbon::parse($more_order->order_date)->format('d-m-Y') }}</td>
              <td>{{ $more_order->client_name }}</td>

              <td>
                @if ($more_order->customer)
                {{ $more_order->customer->country}}
                @endif
              </td>
              <td>
                {{$more_order->currency}}
              </td>
              <td> {{ count($more_order->order_product) > 0 ? $more_order->order_product->sum('product_price') : 0 }} </td>
              <td>
                @if ($more_order->customer)
                  @if ($more_order->customer->store_website_id)
                  @if(isset($more_order->customer->store_website)) {{ $more_order->customer->store_website->website}} @endif
                  @endif
                @endif
              </td>
              <td>
              <div class="form-group">
              <input type="checkbox" name="order_ids[]" {{ $more_order->invoice_id ? 'checked' : '' }} class="" value="{{ $more_order->id }}">
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
</div>
</div>
@endif
          </div>
          <div class="modal-footer">
            <div class="row" style="margin:0px;">
              <button type="submit" style="margin-top: 5px;" class="btn btn-secondary">Edit</button>
            </div>
          </div>
</form>

<script>
    $(document).ready(function() {
      $('#invoice_date').datetimepicker({
        format: 'YYYY-MM-DD'
      });
    });
</script>
