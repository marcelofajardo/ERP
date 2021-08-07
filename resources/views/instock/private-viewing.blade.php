@extends('layouts.app')

@section('title', 'Private Viewing')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Private Viewing</h2>
            <div class="pull-left">
                <form class="form-inline" action="{{ route('stock.private.viewing') }}" method="GET">
                  <div class="form-group" style="width: 200px;">
                    <select class="selectpicker form-control" data-live-search="true" data-size="15" name="customer_id" title="Choose a Customer">
                      @foreach ($customers_all as $customer)
                        <option data-tokens="{{ $customer['name'] }} {{ $customer['email'] }}  {{ $customer['phone'] }} {{ $customer['instahandler'] }}" value="{{ $customer['id'] }}" {{ $customer['id'] == $selected_customer ? 'selected' : '' }}>{{ $customer['id'] }} - {{ $customer['name'] }} - {{ $customer['phone'] }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group ml-3">
                    <select class="form-control" name="type">
                      <option value="">Select Type</option>
                      <option value="delivered" {{ isset($type) && 'delivered' == $type ? 'selected' : '' }}>Delivered</option>
                      <option value="returned" {{ isset($type) && 'returned' == $type ? 'selected' : '' }}>Returned</option>
                      <option value="no_boy" {{ isset($type) && 'no_boy' == $type ? 'selected' : '' }}>No Office Boy</option>
                    </select>
                  </div>

                  <button type="submit" class="btn btn-image ml-3"><img src="/images/filter.png" /></button>

                  <a href="{{ route('stock.private.viewing') }}" class="btn btn-xs btn-secondary ml-3">Reset</a>
                </form>

            </div>

            {{-- <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('stock.create') }}">+</a>
            </div> --}}
        </div>
    </div>

    @include('partials.flash_messages')

    <div class="table-responsive mt-3">
        <table class="table table-bordered">
        <tr>
          <th width="20%">Customer</th>
          <th width="10%">Date</th>
          <th width="10%">Products</th>
          <th width="20%">Delivery Images</th>
          <th width="15%">Status</th>
          <th width="20%">Office Boy</th>
          <th width="5%">Action</th>
        </tr>
        @foreach ($private_views as $key => $view)
            <tr class="{{ \Carbon\Carbon::parse($view->date)->format('Y-m-d') == date('Y-m-d') ? 'row-highlight' : '' }}">
                <td>
                  <a href="{{ route('customer.show', $view->customer->id) }}" target="_blank">{{ $view->customer->name }}</a>

                  @if ($view->order_product && $view->order_product->order->is_priority == 1)
                    <span style="color: red;">!!!</span>
                  @endif

                  <br>

                  <span class="text-muted">{{ $view->customer->phone }}</span>

                  <br>

                  {{ $view->customer->address }}, {{ $view->customer->pincode }}, {{ $view->customer->city }}
                </td>
                <td>
                  @if ($view->order_product)
                    {{ Carbon\Carbon::parse($view->order_product->shipment_date)->format('d-m') }}
                  @else
                    {{ Carbon\Carbon::parse($view->date)->format('d-m') }}
                  @endif
                </td>
                <td>
                  @foreach ($view->products as $product)
                    <img src="{{ $product->getMedia(config('constants.media_tags'))->first() ? $product->getMedia(config('constants.media_tags'))->first()->getUrl() : '' }}" class="img-responsive" style="width: 50px;" alt="">
                  @endforeach
                </td>
                <td>
                  @if ($view->hasMedia(config('constants.media_tags')))
                    @foreach ($view->getMedia(config('constants.media_tags')) as $image)
                      <a href="{{ $image->getUrl() }}" target="_blank" class="d-inline-block">
                        <img src="{{ $image->getUrl() }}" class="img-responsive" style="width: 50px;" alt="">
                      </a>
                    @endforeach
                  @endif

                  {{-- @if (\Carbon\Carbon::parse($view->date)->format('Y-m-d') <= date('Y-m-d')) --}}
                    <form action="{{ route('stock.private.viewing.upload') }}" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="form-group">
                        <input type="hidden" name="view_id" value="{{ $view->id }}">
                        <input type="file" name="images[]" required multiple>
                      </div>

                      <button type="submit" class="btn btn-xs btn-secondary">Upload</button>
                    </form>
                  {{-- @endif --}}
                </td>
                <td>
                  <div class="d-flex2">
                    <div class="">
                      <select class="form-control status-change" name="status" data-id="{{ $view->id }}">
                        <option value="">Select Status</option>
                        <option value="delivered" {{ 'delivered' == $view->status ? 'selected' : '' }}>Delivered</option>
                        <option value="returned" {{ 'returned' == $view->status ? 'selected' : '' }}>Returned</option>
                      </select>

                      <span class="text-success change_status_message" style="display: none;">Successfully updated status</span>
                    </div>

                    @if (count($view->status_changes) > 0)
                      <button type="button" class="btn btn-xs btn-secondary change-history-toggle">?</button>

                      <div class="change-history-container hidden">
                        <ul>
                          @foreach ($view->status_changes as $status_history)
                            <li>
                              {{ array_key_exists($status_history->user_id, $users_array) ? $users_array[$status_history->user_id] : 'Unknown User' }} - <strong>from</strong>: {{ $status_history->from_status }} <strong>to</strong> - {{ $status_history->to_status }} <strong>on</strong> {{ \Carbon\Carbon::parse($status_history->created_at)->format('H:i d-m') }}
                            </li>
                          @endforeach
                        </ul>
                      </div>
                    @endif
                  </div>
                </td>
                <td>
                  <select class="form-control change-office-boy" name="office_boy_id" data-id="{{ $view->id }}">
                    <option value="">Select Office Boy</option>
                    @foreach ($office_boys as $office_boy)
                      <option value="{{ $office_boy->id }}" {{ $office_boy->id == $view->assigned_user_id ? 'selected' : '' }}>{{ $office_boy->name }}</option>
                    @endforeach
                  </select>

                  <span class="text-success change_status_message" style="display: none;">Successfully updated status</span>
                </td>
                <td>
                  {{-- <a class="btn btn-image" href="{{ route('stock.show', $stock->id) }}"><img src="/images/view.png" /></a> --}}

                  {{-- {!! Form::open(['method' => 'DELETE','route' => ['stock.destroy', $stock->id],'style'=>'display:inline']) !!}
                  <button type="submit" class="btn btn-image"><img src="/images/archive.png" /></button>
                  {!! Form::close() !!} --}}

                  {!! Form::open(['method' => 'DELETE','route' => ['stock.private.viewing.destroy', $view->id],'style'=>'display:inline']) !!}
                    <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>
                  {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    </table>
    </div>

    {!! $private_views->appends(Request::except('page'))->links() !!}
@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
  <script type="text/javascript">
    $(document).on('change', '.status-change', function() {
      var status = $(this).val();
      var id = $(this).data('id');
      var thiss = $(this);

      $.ajax({
        type: "POST",
        url: "{{ url('stock/private/viewing') }}/" + id + "/updateStatus",
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

    $(document).on('change', '.change-office-boy', function() {
      var assigned_user_id = $(this).val();
      var id = $(this).data('id');
      var thiss = $(this);

      $.ajax({
        type: "POST",
        url: "{{ url('stock/private/viewing') }}/" + id + "/updateOfficeBoy",
        data: {
          _token: "{{ csrf_token() }}",
          assigned_user_id: assigned_user_id
        }
      }).done(function(response) {
        console.log(response);

        $(thiss).parent().find('.change_status_message').fadeIn(400);

        setTimeout(function () {
          $(thiss).parent().find('.change_status_message').fadeOut(400);
        }, 2000);
      }).fail(function(response) {
        console.log(response);
        alert('Could not update the office boy');
      });
    });

    $(document).on('click', '.change-history-toggle', function() {
      $(this).siblings('.change-history-container').toggleClass('hidden');
    });
  </script>
@endsection
