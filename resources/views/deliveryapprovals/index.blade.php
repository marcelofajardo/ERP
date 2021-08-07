@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Delivery Approvals</h2>
            <div class="pull-left">

                {{-- <form action="/purchases/" method="GET">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <input name="term" type="text" class="form-control"
                                       value="{{ isset($term) ? $term : '' }}"
                                       placeholder="Search">
                            </div>
                            <div class="col-md-4">
                                <button hidden type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form> --}}
            </div>
            {{-- <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('stock.create') }}">+</a>
            </div> --}}
        </div>
    </div>

    @include('partials.flash_messages')

    @include('customers.partials.modal-voucher')

    {{-- <div class="row">
      <div class="col">
        <form class="form-inline my-3" action="" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="form-group">
            <input type="file" name="images[]" required multiple>
          </div>

          <button type="submit" class="btn btn-xs btn-secondary ml-3">Upload for Approval</button>
        </form>
      </div>
    </div> --}}

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th width="10%">Assigned To</th>
            <th width="5%">Date</th>
            <th width="30%">Details</th>
            <th width="25%">Uploaded Photos</th>
            <th width="10%">Approved</th>
            <th width="10%">Status</th>
            <th width="10%">Voucher</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($delivery_approvals as $delivery_approval)
            <tr class="{{ \Carbon\Carbon::parse($delivery_approval->date)->format('Y-m-d') == date('Y-m-d') ? 'row-highlight' : '' }}">
              <td>{{ $delivery_approval->user ? $delivery_approval->user->name : 'There is no assigned user' }}</td>
              <td>{{ Carbon\Carbon::parse($delivery_approval->date)->format('d-m') }}</td>
              <td>
                @if ($delivery_approval->order && $delivery_approval->order->customer)
                  <strong>{{ $delivery_approval->order->customer->name }}</strong>

                  <br>

                  <span class="text-muted">{{ $delivery_approval->order->customer->phone }}</span>

                  <br>

                  {{ $delivery_approval->order->customer->address }}, {{ $delivery_approval->order->customer->pincode }}, {{ $delivery_approval->order->customer->city }}
                @endif
              </td>
              <td>
                <div class="d-flex">
                  @if ($delivery_approval->hasMedia(config('constants.media_tags')))
                    @foreach ($delivery_approval->getMedia(config('constants.media_tags')) as $image)
                      <div class="ml-1">
                        <a href="{{ $image->getUrl() ?? '#no-image' }}" target="_blank"><img class="img-responsive thumbnail-200" src="{{ $image->getUrl() ?? '#no-image' }}" /></a>
                      </div>
                    @endforeach
                  @endif
                </div>

                <form action="{{ route('order.upload.approval', $delivery_approval->order_id) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group">
                    <input type="file" name="images[]" required multiple>
                  </div>

                  <button type="submit" class="btn btn-xs btn-secondary">Upload</button>
                </form>
              </td>
              <td>
                @if ($delivery_approval->approved == 1)
                  Approved
                @else
                  <form action="{{ route('order.delivery.approve', $delivery_approval->id) }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-xs btn-secondary" {{ !Auth::user()->hasRole('Admin') ? "disabled" : "" }}>Approve</button>
                  </form>
                @endif
              </td>
              <td>
                <select class="form-control status-change" name="status" data-id="{{ $delivery_approval->id }}">
                  <option value="">Select Status</option>
                  <option value="delivered" {{ 'delivered' == $delivery_approval->status ? 'selected' : '' }}>Delivered</option>
                  <option value="returned" {{ 'returned' == $delivery_approval->status ? 'selected' : '' }}>Returned</option>
                </select>

                @if (count($delivery_approval->status_changes) > 0)
                  <button type="button" class="btn btn-xs btn-secondary change-history-toggle">?</button>

                  <div class="change-history-container hidden">
                    <ul>
                      @foreach ($delivery_approval->status_changes as $status_history)
                        <li>
                          {{ array_key_exists($status_history->user_id, $users_array) ? $users_array[$status_history->user_id] : 'Unknown User' }} - <strong>from</strong>: {{ $status_history->from_status }} <strong>to</strong> - {{ $status_history->to_status }} <strong>on</strong> {{ \Carbon\Carbon::parse($status_history->created_at)->format('H:i d-m') }}
                        </li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                <span class="text-success change_status_message" style="display: none;">Successfully updated status</span>
              </td>
              {{-- <td>
                @if ($order->delivery_approval->approved == 2)
                  Approved
                @else
                  <form action="{{ route('order.delivery.approve', $order->delivery_approval->id) }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-xs btn-secondary">Approve</button>
                  </form>
                @endif
              </td> --}}
              <td>
                @if(auth()->user()->checkPermission('voucher'))
                  @if ($delivery_approval->voucher)
                    <button type="button" class="btn btn-xs btn-secondary edit-voucher" data-toggle="modal" data-target="#editVoucherModal" data-id="{{ $delivery_approval->voucher->id }}" data-amount="{{ $delivery_approval->voucher->amount }}" data-travel="{{ $delivery_approval->voucher->travel_type }}">Edit Voucher</button>
                  @else
                    @if ($delivery_approval->order && $delivery_approval->order->customer)
                      <button type="button" class="btn btn-xs btn-secondary create-voucher" data-id="{{ $delivery_approval->id }}" data-customer="{{ $delivery_approval->order->customer }}">Create Voucher</button>
                    @endif
                  @endif
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>



    {{-- {!! $private_views->appends(Request::except('page'))->links() !!} --}}
@endsection

@section('scripts')
  <script type="text/javascript">
    $(document).on('click', '.create-voucher', function() {
      var id = $(this).data('id');
      var customer = $(this).data('customer');
      var thiss = $(this);
      var description = "Delivery to " + customer.name + " at " + (customer.address).replace('/\s+/', ' ') + ", " + customer.city;
      var date = moment().add(5, 'days').format('YYYY-MM-DD');

      $.ajax({
        url: "{{ route('voucher.store') }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          user_id: {{ Auth::id() }},
          delivery_approval_id: id,
          description: description,
          date: date
        },
        beforeSend: function() {
          $(thiss).text('Creating...');
        }
      }).done(function(response) {
        var edit_button = '<button type="button" class="btn btn-xs btn-secondary edit-voucher" data-toggle="modal" data-target="#editVoucherModal" data-id="' + response.id + '" data-amount="" data-travel="">Edit Voucher</button>';
        $(thiss).parent().html($(edit_button));

      }).fail(function(response) {
        $(thiss).text('Create Voucher');

        console.log(response);
        alert('There was an error creating voucher');
      });
    });

    $(document).on('click', '.edit-voucher', function() {
      var id = $(this).data('id');
      var amount = $(this).data('amount');
      var travel = $(this).data('travel');
      var url = "{{ url('voucher') }}/" + id;
      var form = $('#editVoucherForm');
      var travel_select = $('option[value="' + travel + '"]');

      form.attr('action', url);
      travel_select.attr('selected', true);
      $('#voucher_amount_field').val(amount);
    });

    $(document).on('change', '.status-change', function() {
      var status = $(this).val();
      var id = $(this).data('id');
      var thiss = $(this);

      $.ajax({
        type: "POST",
        url: "{{ url('deliveryapproval') }}/" + id + "/updateStatus",
        data: {
          _token: "{{ csrf_token() }}",
          status: status
        }
      }).done(function(response) {
        console.log(response);

        $(thiss).parent().find('.change_status_message').fadeIn(400);

        setTimeout(function () {
          $(thiss).parent().find('.change_status_message').fadeOut(400);
        }, 2000);
      }).fail(function(response) {
        console.log(response);
        alert('Could not update the status');
      });
    });

    $(document).on('click', '.change-history-toggle', function() {
      $(this).siblings('.change-history-container').toggleClass('hidden');
    });
  </script>
@endsection
