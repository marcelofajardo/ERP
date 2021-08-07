<div id="productModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create Product</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="order_id" value="">
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
            <strong>Price: (Euro)</strong>
            <input type="number" class="form-control" name="price" placeholder="Price (Euro)"
                   value="{{ old('price') }}" step=".01"  id="product-price"/>
            @if ($errors->has('price'))
                <div class="alert alert-danger">{{$errors->first('price')}}</div>
            @endif
        </div>

        <div class="form-group">
            <strong>Price:</strong>
            <input type="number" class="form-control" name="price_inr_special" placeholder="Price"
                   value="{{ old('price_inr_special') }}" step=".01"  id="product-price-special"/>
            @if ($errors->has('price_inr_special'))
                <div class="alert alert-danger">{{$errors->first('price_inr_special')}}</div>
            @endif
        </div>

        <div class="form-group">
            <strong>Size:</strong>
            <input type="text" class="form-control" name="size[]" placeholder="Size"
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
        <button type="button" class="btn btn-secondary createProduct">Create</button>
      </div>
    </div>

  </div>
</div>
