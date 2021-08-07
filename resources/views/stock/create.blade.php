@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Create Stock</h2>

            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('stock.index') }}">Back</a>
            </div>
        </div>
    </div>

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
        {!! Form::open(array('route' => 'stock.store','method'=>'POST', 'id' => 'stockForm')) !!}
          <div class="form-group">
            <strong>Courier:</strong>
            {!! Form::text('courier', old('courier'), array('placeholder' => 'Courier','class' => 'form-control', 'required'  => true)) !!}
            @if ($errors->has('courier'))
              <div class="alert alert-danger">{{$errors->first('courier')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>From:</strong>
            {!! Form::text('package_from', old('package_from'), array('placeholder' => 'Name','class' => 'form-control')) !!}
            @if ($errors->has('package_from'))
              <div class="alert alert-danger">{{$errors->first('package_from')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Date:</strong>
            <div class='input-group date' id='date'>
                <input type='text' class="form-control" name="date" value="{{ date('Y-m-d') }}" />

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
            <input type="text" name="awb" class="form-control" placeholder="00000000000" value="{{ old('awb') }}" required>
            @if ($errors->has('awb'))
              <div class="alert alert-danger">{{$errors->first('awb')}}</div>
            @endif
          </div>

          <strong>Size dimensions</strong>
          <div class="row">
            <div class="col-xs-4">
              <div class="form-group">
                <input type="text" name="l_dimension" class="form-control" placeholder="L" value="{{ old('l_dimension') }}">
                @if ($errors->has('l_dimension'))
                  <div class="alert alert-danger">{{$errors->first('l_dimension')}}</div>
                @endif
              </div>
            </div>

            <div class="col-xs-4">
              <div class="form-group">
                <input type="text" name="w_dimension" class="form-control" placeholder="W" value="{{ old('w_dimension') }}">
                @if ($errors->has('w_dimension'))
                  <div class="alert alert-danger">{{$errors->first('w_dimension')}}</div>
                @endif
              </div>
            </div>

            <div class="col-xs-4">
              <div class="form-group">
                <input type="text" name="h_dimension" class="form-control" placeholder="H" value="{{ old('h_dimension') }}">
                @if ($errors->has('h_dimension'))
                  <div class="alert alert-danger">{{$errors->first('h_dimension')}}</div>
                @endif
              </div>
            </div>
          </div>

          <div class="form-group">
            <strong>Weight:</strong>
            <input type="number" name="weight" class="form-control" placeholder="3.2" step="0.01" value="{{ old('weight') }}">
            @if ($errors->has('weight'))
              <div class="alert alert-danger">{{$errors->first('weight')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Pcs:</strong>
            {!! Form::number('pcs', old('pcs'), array('placeholder' => '3','class' => 'form-control')) !!}
            @if ($errors->has('pcs'))
              <div class="alert alert-danger">{{$errors->first('pcs')}}</div>
            @endif
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-secondary" id="createStockButton">Create Stock</button>
          </div>
        {!! Form::close() !!}
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
              <strong> Products Attached:</strong>
              <table class="table table-bordered" id="products-table">
                <tr>
                  <th>Image</th>
                  <th>Name</th>
                  <th>Sku</th>
                  <th>Color</th>
                  <th>Brand</th>
                  <th style="width: 160px">Action</th>
                </tr>
              </table>
          </div>
      </div>

      <div class="col-xs-12">
          <div class="form-group btn-group">
              <a href="#" id="attachProduct" class="btn btn-image"><img src="/images/attach.png" /></a>
              <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#productModal">+</button>
          </div>
      </div>
    </div>



    <div id="productModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Create Product</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <strong>Image:</strong>
                <input type="file" class="form-control" name="image"
                       value="{{ old('image') }}" id="product-image"/>
                @if ($errors->has('image'))
                    <div class="alert alert-danger">{{$errors->first('image')}}</div>
                @endif
            </div>

            <div class="form-group">
                <strong>Name:</strong>
                <input type="text" class="form-control" name="name" placeholder="Name"
                       value="{{ old('name') }}"  id="product-name"/>
                @if ($errors->has('name'))
                    <div class="alert alert-danger">{{$errors->first('name')}}</div>
                @endif
            </div>

            <div class="form-group">
                <strong>SKU:</strong>
                <input type="text" class="form-control" name="sku" placeholder="SKU"
                       value="{{ old('sku') }}"  id="product-sku"/>
                @if ($errors->has('sku'))
                    <div class="alert alert-danger">{{$errors->first('sku')}}</div>
                @endif
            </div>

            <div class="form-group">
                <strong>Color:</strong>
                <input type="text" class="form-control" name="color" placeholder="Color"
                       value="{{ old('color') }}"  id="product-color"/>
                @if ($errors->has('color'))
                    <div class="alert alert-danger">{{$errors->first('color')}}</div>
                @endif
            </div>

            <div class="form-group">
                <strong>Brand:</strong>
                <?php
                $brands = \App\Brand::getAll();
                echo Form::select('brand',$brands, ( old('brand') ? old('brand') : '' ), ['placeholder' => 'Select a brand','class' => 'form-control', 'id'  => 'product-brand']);?>
                  {{--<input type="text" class="form-control" name="brand" placeholder="Brand" value="{{ old('brand') ? old('brand') : $brand }}"/>--}}
                  @if ($errors->has('brand'))
                      <div class="alert alert-danger">{{$errors->first('brand')}}</div>
                  @endif
            </div>

            <div class="form-group">
                <strong>Price:</strong>
                <input type="number" class="form-control" name="price" placeholder="Price"
                       value="{{ old('price') }}" step=".01"  id="product-price"/>
                @if ($errors->has('price'))
                    <div class="alert alert-danger">{{$errors->first('price')}}</div>
                @endif
            </div>

            <div class="form-group">
                <strong>Size:</strong>
                <input type="text" class="form-control" name="size" placeholder="Size"
                       value="{{ old('size') }}"  id="product-size"/>
                @if ($errors->has('size'))
                    <div class="alert alert-danger">{{$errors->first('size')}}</div>
                @endif
            </div>

            <div class="form-group">
                <strong>Quantity:</strong>
                <input type="number" class="form-control" name="quantity" placeholder="Quantity"
                       value="{{ old('quantity') }}"  id="product-quantity"/>
                @if ($errors->has('quantity'))
                    <div class="alert alert-danger">{{$errors->first('quantity')}}</div>
                @endif
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" id="createProduct">Create</button>
          </div>
        </div>

      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript">
      $('#date').datetimepicker({
        format: 'YYYY-MM-DD'
      });

      var stock_id = '';

      $('#createStockButton').on('click', function(e) {
        e.preventDefault();
        var form = $('#stockForm');
        var thiss = $(this);

        if (form[0].checkValidity()) {
          var formData = $('#stockForm').serialize();

          $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: formData,
            beforeSend: function() {
              $(thiss).text('Creating...');
            }
          }).done(function(response) {
            $(thiss).text('Create Stock');
            stock_id = response;
          }).fail(function(response) {
            $(thiss).text('Create Stock');
            console.log(response);
            alert('Could not create stock!');
          });
        } else {
          form[0].reportValidity();
        }
      });

      $('#createProduct').on('click', function() {
        if (stock_id == '') {
          alert('Please create first stock');

          return false;
        }

        var token = "{{ csrf_token() }}";
        var url = "{{ route('products.store') }}";

        var image = $('#product-image').prop('files')[0];
        var name = $('#product-name').val();
        var sku = $('#product-sku').val();
        var color = $('#product-color').val();
        var brand = $('#product-brand').val();
        var price = $('#product-price').val();
        var size = $('#product-size').val();
        var quantity = $('#product-quantity').val();

        var form_data = new FormData();
        form_data.append('_token', token);
        form_data.append('stock_id', stock_id);
        form_data.append('image', image);
        form_data.append('name', name);
        form_data.append('sku', sku);
        form_data.append('color', color);
        form_data.append('brand', brand);
        form_data.append('price', price);
        form_data.append('size', size);
        form_data.append('quantity', quantity);

        $.ajax({
          type: 'POST',
          url: url,
          processData: false,
          contentType: false,
          enctype: 'multipart/form-data',
          data: form_data,
          success: function(response) {
            var brands_array = {!! json_encode(\App\Helpers::getUserArray(\App\Brand::all())) !!};
            var show_url = "{{ url('products') }}/" + response.product.id;
            var product_row = '<tr><th><img width="200" src="' + response.product_image + '" /></th>';
                product_row += '<th>' + response.product.name + '</th>';
                product_row += '<th>' + response.product.sku + '</th>';
                product_row += '<th>' + response.product.color + '</th>';
                product_row += '<th>' + brands_array[response.product.brand] + '</th>';
                product_row += '<th><a class="btn btn-image" href="' + show_url + '"><img src="/images/view.png" /></a>';
                product_row += '</tr>';

            $('#products-table').append(product_row);
          }
        });
      });

      $('#attachProduct').on('click', function(e) {
        e.preventDefault();

        if (stock_id == '') {
          alert('Please create first stock');

          return false;
        }
        var url = "{{ url('attachProducts') }}/stock/" + stock_id;

        window.location.href = url;
      });
    </script>
@endsection
