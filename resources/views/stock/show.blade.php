@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Stock</h2>

            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('stock.index') }}">Back</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
      <div class="alert alert-success">
        <p>{{ $message }}</p>
      </div>
    @endif

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
      <div class="col-xs-12 col-md-8 col-md-offset-2">
        {!! Form::open(array('route' => ['stock.update', $stock->id],'method'=>'PUT')) !!}
          <div class="form-group">
            <strong>Courier:</strong>
            {!! Form::text('courier', $stock->courier, array('placeholder' => 'Courier','class' => 'form-control', 'required'  => true)) !!}
            @if ($errors->has('courier'))
              <div class="alert alert-danger">{{$errors->first('courier')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>From:</strong>
            {!! Form::text('package_from', $stock->package_from, array('placeholder' => 'Name','class' => 'form-control')) !!}
            @if ($errors->has('package_from'))
              <div class="alert alert-danger">{{$errors->first('package_from')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Date:</strong>
            <div class='input-group date' id='date'>
                <input type='text' class="form-control" name="date" value="{{ $stock->date ? $stock->date : date('Y-m-d') }}" />

                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            @if ($errors->has('date'))
              <div class="alert alert-danger">{{$errors->first('date')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>AWB:</strong>
            <input type="text" name="awb" class="form-control" placeholder="00000000000" value="{{ $stock->awb }}" required>
            @if ($errors->has('awb'))
              <div class="alert alert-danger">{{$errors->first('awb')}}</div>
            @endif
            <button type="button" class="btn btn-xs btn-secondary mt-1" id="trackShipmentButton">Track</button>
          </div>

          <div class="form-group" id="tracking-container">

          </div>

          <strong>Size dimensions</strong>
          <div class="row">
            <div class="col-xs-4">
              <div class="form-group">
                <input type="text" name="l_dimension" class="form-control" placeholder="L" value="{{ $stock->l_dimension }}">
                @if ($errors->has('l_dimension'))
                  <div class="alert alert-danger">{{$errors->first('l_dimension')}}</div>
                @endif
              </div>
            </div>

            <div class="col-xs-4">
              <div class="form-group">
                <input type="text" name="w_dimension" class="form-control" placeholder="W" value="{{ $stock->w_dimension }}">
                @if ($errors->has('w_dimension'))
                  <div class="alert alert-danger">{{$errors->first('w_dimension')}}</div>
                @endif
              </div>
            </div>

            <div class="col-xs-4">
              <div class="form-group">
                <input type="text" name="h_dimension" class="form-control" placeholder="H" value="{{ $stock->h_dimension }}">
                @if ($errors->has('h_dimension'))
                  <div class="alert alert-danger">{{$errors->first('h_dimension')}}</div>
                @endif
              </div>
            </div>
          </div>

          <div class="form-group">
            <strong>Weight:</strong>
            <input type="number" name="weight" class="form-control" placeholder="3.2" step="0.01" value="{{ $stock->weight }}">
            @if ($errors->has('weight'))
              <div class="alert alert-danger">{{$errors->first('weight')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Pcs:</strong>
            {!! Form::number('pcs', $stock->pcs, array('placeholder' => '3','class' => 'form-control')) !!}
            @if ($errors->has('pcs'))
              <div class="alert alert-danger">{{$errors->first('pcs')}}</div>
            @endif
          </div>

          @if ($stock->products()->count() > 0)
            <h3>Products</h3>

            <div class="row mb-3">
              @foreach ($stock->products as $product)
                <div class="col-md-4">
                  <a href="{{ route('products.show', $product->id) }}" target="_blank">
                    <img src="{{ $product->getMedia(config('constants.media_tags'))->first()->getUrl() }}" class="img-responsive" alt="">
                  </a>
                </div>
              @endforeach
            </div>
          @endif

          <div class="text-center">
            <button type="submit" class="btn btn-secondary">Update Stock</button>
          </div>
        {!! Form::close() !!}
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript">
      $('#date').datetimepicker({
        format: 'YYYY-MM-DD'
      });

      $('#trackShipmentButton').on('click', function() {
        var thiss = $(this);
        var awb = $('input[name="awb"]').val();

        $.ajax({
          type: "POST",
          url: "{{ route('stock.track.package') }}",
          data: {
            _token: "{{ csrf_token() }}",
            awb: awb
          },
          beforeSend: function() {
            $(thiss).text('Tracking...');
          }
        }).done(function(response) {
          $(thiss).text('Track');

          $('#tracking-container').html(response);
        }).fail(function(response) {
          $(thiss).text('Tracking...');
          alert('Could not track this package');
          console.log(response);
        });
      });
    </script>
@endsection
