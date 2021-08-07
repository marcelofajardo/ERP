<div class="modal-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th width="5%">No</th>
          <th width="15%">Amount</th>
          <th width="35%">Note</th>
          <th width="25%">Created At</th>
          <th width="25%">Updated At</th>
        </tr>
      </thead>
      <tbody>
        @foreach($payments as $i => $payment)
          <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $payment->amount }} {{ $payment->currency }}</td>
              <td>{{ $payment->note }}</td>
              <td>{{ $payment->created_at }}</td>
              <td>{{ $payment->updated_at }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
</div>