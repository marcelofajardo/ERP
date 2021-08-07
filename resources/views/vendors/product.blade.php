@extends('layouts.app')

@section('title', 'Vendor Product Info')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Product Info</h2>
            <div class="pull-left">
              {{-- <form action="/order/" method="GET">
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
            <div class="pull-right">
              <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#productCreateModal">+</a>
            </div>
        </div>
    </div>

    @include('partials.flash_messages')

    <div class="table-responsive mt-3">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Date of Order</th>
            <th>Name</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total Price</th>
            <th>Payments Terms</th>
            <th>Recurring Type</th>
            <th>Delivery Date</th>
            <th>Received By</th>
            <th>Approved By</th>
            <th>Payment Details</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
          @foreach ($products as $product)
            <tr>
              <td>{{ $product->id }}</td>
              <td>{{ \Carbon\Carbon::parse($product->date_of_order)->format('d-m') }}</td>
              <td>
                @if ($product->hasMedia(config('constants.media_tags')))
                  @foreach ($product->getMedia(config('constants.media_tags')) as $image)
                    <img src="{{ $image->getUrl() }}" class="img-responsive m-1" width="50px" alt="">
                  @endforeach

                  <br>
                @endif

                {{ $product->name }}
                <br>

                {{-- <span class="text-muted">
                  <strong>Vendor: </strong>{{ $product->vendor->name ?? 'No Vendor' }}
                </span> --}}
              </td>
              <td>{{ $product->qty }}</td>
              <td>{{ $product->price }}</td>
              <td>{{ $product->qty * $product->price }}</td>
              <td>{{ $product->payment_terms }}</td>
              <td>{{ $product->recurring_type }}</td>
              <td>{{ $product->delivery_date ? \Carbon\Carbon::parse($product->delivery_date)->format('d-m') : '' }}</td>
              <td>{{ $product->received_by }}</td>
              <td>{{ $product->approved_by }}</td>
              <td>{{ $product->payment_details }}</td>
              <td>
                <button type="button" class="btn btn-image edit-product" data-toggle="modal" data-target="#productEditModal" data-product="{{ $product }}"><img src="/images/edit.png" /></button>

                {!! Form::open(['method' => 'DELETE','route' => ['vendors.product.destroy', $product->id],'style'=>'display:inline']) !!}
                  <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>
                {!! Form::close() !!}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {!! $products->appends(Request::except('page'))->links() !!}

    @include('vendors.partials.product-modals')

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script type="text/javascript">
    $(document).on('click', '.edit-product', function() {
      var product = $(this).data('product');
      var url = "{{ url('vendors/product') }}/" + product.id;

      $('#productEditModal form').attr('action', url);
      $('#vendor_vendor_id').val(product.vendor_id);
      $('#vendor_date_of_order').val(product.date_of_order);
      $('#vendor_name').val(product.name);
      $('#vendor_qty').val(product.qty);
      $('#vendor_price').val(product.price);
      $('#vendor_payment_terms').val(product.payment_terms);
      $('#vendor_recurring_type option[value="' + product.recurring_type + '"]').prop('selected', true);
      $('#vendor_delivery_date').val(product.delivery_date);
      $('#vendor_received_by').val(product.received_by);
      $('#vendor_approved_by').val(product.approved_by);
      $('#vendor_payment_details').val(product.payment_details);
    });

    $('#date-of-order, #vendor-date-of-order, #delivery-date, #vendor-delivery-date').datetimepicker({
      format: 'YYYY-MM-DD HH:mm'
    });
  </script>
@endsection
