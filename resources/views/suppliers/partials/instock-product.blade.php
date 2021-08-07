<div id="productModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create Product</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

     <div class="modal-body">
        @csrf
        
        <input type="hidden" name="images" id="images">

        <div class="form-group">
            <strong>No Of Images: <span id="count_images"></span></strong>
        </div>
 
        <div class="form-group">
          <select class="form-control" name="supplier" id="supplier" required>
            <option value="">Select Supplier</option>
            @foreach ($suppliers as $supplier)
              <option value="{{ $supplier->supplier }}" {{ $supplier->supplier == old('supplier') ? 'selected' : '' }}>{{ $supplier->supplier }}</option>
            @endforeach
          </select>

          @if ($errors->has('supplier'))
          <div class="alert alert-danger">{{$errors->first('supplier')}}</div>
          @endif
        </div>

        <div class="form-group">
            <strong>Name:</strong>
            <input type="text" class="form-control" name="name" placeholder="Name"
                   value="{{ old('name') }}"  id="name" required />
            @if ($errors->has('name'))
                <div class="alert alert-danger">{{$errors->first('name')}}</div>
            @endif
        </div>

        <div class="form-group">
            <strong>SKU:</strong>
            <input type="text" class="form-control" name="sku" placeholder="SKU"
                   value="{{ old('sku') }}"  id="sku" required/>
            @if ($errors->has('sku'))
                <div class="alert alert-danger">{{$errors->first('sku')}}</div>
            @endif
        </div>

        <div class="form-group">
            <strong>Color:</strong>
            <input type="text" class="form-control" name="color" placeholder="Color"
                   value="{{ old('color') }}"  id="color"/>
            @if ($errors->has('color'))
                <div class="alert alert-danger">{{$errors->first('color')}}</div>
            @endif
        </div>

        <div class="form-group">
            <strong>Brand:</strong>
            <?php
            $brands = \App\Brand::getAll();
            echo Form::select('brand',$brands, ( old('brand') ? old('brand') : '' ), ['placeholder' => 'Select a brand','class' => 'form-control', 'id'  => 'brand']);?>
              {{--<input type="text" class="form-control" name="brand" placeholder="Brand" value="{{ old('brand') ? old('brand') : $brand }}"/>--}}
              @if ($errors->has('brand'))
                  <div class="alert alert-danger">{{$errors->first('brand')}}</div>
              @endif
        </div>

        <div class="form-group">
          <strong>Category:</strong>
          {!! $new_category_selection !!}
        </div>

        <div class="form-group">
            <strong>Price:</strong>
            <input type="number" class="form-control" name="price" placeholder="Price"
                   value="{{ old('price') }}" step=".01"  id="price"/>
            @if ($errors->has('price'))
                <div class="alert alert-danger">{{$errors->first('price')}}</div>
            @endif
        </div>

        <div class="form-group">
          <strong>Special Price (INR):</strong>

          <input type="number" class="form-control" name="price_special" id="price_inr_special_stock">

          @if ($errors->has('price_inr_special'))
              <div class="alert alert-danger">{{$errors->first('price_inr_special')}}</div>
          @endif
        </div>

        <div class="form-group">
          <strong>Size:</strong>
          <select class="form-control" name="size[]" id="size-selection" style="width: 100%" multiple>
            <option value="">Select Size</option>
          </select>

          <input type="text" name="other_size" class="form-control mt-3 hidden" id="size-manual-input" placeholder="Manual Size" value="{{ !empty($size) ? $size[0] : '' }}">

          @if ($errors->has('size'))
              <div class="alert alert-danger">{{$errors->first('size')}}</div>
          @endif
        </div>

        <div class="form-group">
          <strong>Location:</strong>

          <select class="form-control" name="location" id="location_data">
            <option value="">Select Location</option>

            @foreach ($locations as $location)
              <option value="{{ $location }}">{{ $location }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-secondary" onclick="createProduct()">Create</button>
      </div>
      </form>
    </div>

  </div>
</div>