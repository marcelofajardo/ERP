@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@section('content')

<div class="row">
  <div class="col-xs-12">
    <h2 class="page-heading">Purchase Product</h2>
  </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <div class="pull-right">
      <a class="btn btn-secondary" href="{{ route('purchase.index') }}">Back</a>
    </div>
  </div>
</div>


@include('partials.flash_messages')

<div class="row">
  <div class="col-md-6 col-12">
    <div class="form-group">
      <strong>ID:</strong> {{ $product->id }}
    </div>

    <div class="form-group">
      <strong>Name:</strong> {{ $product->name }}
    </div>

    <div class="form-group">
      <strong>Brand:</strong> {{ \App\Http\Controllers\BrandController::getBrandName($product->brand) }}
    </div>

    <div class="form-group">
      <strong>Color:</strong> {{ $product->color }}
    </div>

    <div class="form-group">
      <strong>Price (in Euro):</strong> {{ $product->price }}
    </div>

    <div class="form-group">
      <strong>Purchase price:</strong> <span id="purchase-price">{{ isset($product->percentage) || isset($product->factor) ? (($product->price - ($product->price * $product->percentage / 100) - $product->factor) * 80) : ($product->price) }}</span>
    </div>

    <div class="form-group">
      <strong>Percentage %:</strong>
      <input type="number" name="percentage" class="form-control" placeholder="10%" value="{{ $product->percentage }}" min="0" max="100">
    </div>

    <div class="form-group">
      <strong>Amount:</strong>
      <input type="number" name="factor" class="form-control" placeholder="1.22" value="{{ $product->factor }}" min="0" step="0.01">
      <a href="#" class="btn-link save-purchase-price">Save</a>
    </div>

    <div class="form-group">
      <strong>Order price:</strong> {{ $product->price_special }}
    </div>

    <div class="form-group">
      <strong>Supplier Link:</strong> {{ $product->supplier_link }}
    </div>

    <div class="form-group">
      <strong>Size Details:</strong>
      @if (count($order_details) > 0)
        <ul>
          @foreach ($order_details as $value)
            <li>{{ $value->size }}</li>
          @endforeach
        </ul>
      @endif
    </div>

    <div class="form-group">
      <strong>Order Details:</strong>
      @if (count($order_details) > 0)
        <ul>
          @foreach ($order_details as $value)
            <li><a href="{{ route('order.show', $value->order_id) }}">{{ $value->order_id }}</a></li>
          @endforeach
        </ul>
      @endif
    </div>

    {{-- <div class="form-group">
      <strong>Status:</strong>
      <Select name="status" class="form-control" id="change_status">
           @foreach($purchase_status as $key => $value)
            <option value="{{$value}}" {{$value == $order->status ? 'Selected=Selected':''}}>{{$key}}</option>
            @endforeach
      </Select>
      <span id="change_status_message" class="text-success" style="display: none;">Successfully changed status</span>
    </div> --}}

  </div>
  <div class="col-md-6 col-12">
    <div class="row">
      @foreach ($product->getMedia(config('constants.media_tags')) as $image)
        <div class="col-md-4">
          <img src="{{ $image->getUrl() }}" class="img-responsive" alt="">
        </div>
      @endforeach
    </div>
  </div>
</div>

<div class="row">
  <h4>Remarks</h4>

  <div class="col-xs-4">
    <div class="form-group">
      <textarea class="form-control" name="remark" rows="8" cols="20" placeholder="Remark"></textarea>
    </div>

    <button type="button" class="btn btn-xs btn-secondary" id="sendRemarkButton">Send</button>
  </div>

  <div class="col-xs-4">
    <div id="remarks-container">
      <ul>

      </ul>
    </div>
  </div>
</div>

@endsection

@section('scripts')
  <script type="text/javascript">
    $('#change_status').on('change', function() {
      var token = "{{ csrf_token() }}";
      var status = $(this).val();
      var id = {{ $product->id }};

      $.ajax({
        url: '/product/' + id + '/changestatus',
        type: 'POST',
        data: {
          _token: token,
          status: status
        }
      }).done(function(response) {
        $('#change_status_message').fadeIn(400);
        setTimeout(function() {
          $('#change_status_message').fadeOut(400);
        }, 2000);
      }).fail(function(errObj) {
        alert("Could not change status");
      });
    });

    $(document).on('click', '.change_message_status', function(e) {
      e.preventDefault();
      var url = $(this).data('url');
      var thiss = $(this);

      $.ajax({
        url: url,
        type: 'GET',
        beforeSend: function() {
          $(thiss).text('Loading');
        }
      }).done(function(response) {
        $(thiss).remove();
      }).fail(function(errObj) {
        alert("Could not change status");
      });
    });

    $('input[name="percentage"], input[name="factor"]').on('keyup', function() {
      if ($('input[name="percentage"]').val() < 0) {
        $('input[name="percentage"]').val(0);
      } else if ($('input[name="percentage"]').val() > 100) {
        $('input[name="percentage"]').val(100);
      }
      var price = {{ $product->price }};
      var percentage = $('input[name="percentage"]').val();
      var factor = $('input[name="factor"]').val();

      $('#purchase-price').text((price - (price * percentage / 100) - factor) * 80);
    });

    $('.save-purchase-price').on('click', function(e) {
      e.preventDefault();

      var thiss = $(this);
      var url = "{{ route('purchase.product.percentage', $product->id) }}";
      var token = "{{ csrf_token() }}";
      var percentage = $('input[name="percentage"]').val();
      var factor = $('input[name="factor"]').val();

      $.ajax({
        type: 'POST',
        url: url,
        data: {
          _token: token,
          percentage: percentage,
          factor: factor
        },
        beforeSend: function() {
          $(thiss).text('Saving');
        },
        success: function() {
          $(thiss).text('Save');
        }
      });
    });

    $('#sendRemarkButton').on('click', function() {
      var id = {{ $product->id }};
      var remark = $(this).parent('div').find('textarea').val();
      var thiss = $(this);

      $.ajax({
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
          },
          url: '{{ route('task.addRemark') }}',
          data: {
            id: id,
            remark: remark,
            module_type: 'purchase-product'
          },
      }).done(response => {
          $(thiss).parent('div').find('textarea').val('');
          var comment = '<li> '+ remark +' <br> <small>By updated on '+ moment().format('DD-M H:mm') +' </small></li>';

          $('#remarks-container').find('ul').prepend(comment);
      }).fail(function(response) {
        console.log(response);
        alert('Could not add remark');
      });
    });

    var id = {{ $product->id }};

    $.ajax({
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        url: '{{ route('task.gettaskremark') }}',
        data: {
          id: id,
          module_type: "purchase-product"
        },
    }).done(response => {
        var html='';

        $.each(response, function( index, value ) {
          html+=' <li> '+value.remark+' <br> <small>By ' + value.user_name + ' updated on '+ moment(value.created_at).format('DD-M H:mm') +' </small></li>';
        });
        $("#remarks-container").find('ul').html(html);
    });
  </script>
@endsection
