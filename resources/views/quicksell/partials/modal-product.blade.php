<div id="productModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create Product</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('quicksell.store') }}" method="POST" enctype="multipart/form-data" id="editquicksell">


      <div class="modal-body">
        @csrf
        <div class="form-group">
            <strong>Image:</strong>
            <input type="file" class="form-control" name="images[]"
                   value="{{ old('image') }}" id="product-image" required multiple/>
            @if ($errors->has('image'))
                <div class="alert alert-danger">{{$errors->first('image')}}</div>
            @endif
        </div>

        <div class="form-group">
          <select class="form-control" name="supplier">
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
                   value="{{ old('name') }}"  id="product-name" required />
            @if ($errors->has('name'))
                <div class="alert alert-danger">{{$errors->first('name')}}</div>
            @endif
        </div>

        <div class="form-group">
            <strong>SKU:</strong>
            <input type="text" class="form-control" name="sku" placeholder="SKU"
                   value="{{ old('sku') }}"  id="product-sku" required/>
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
          <strong>Category:</strong>
          {!! $new_category_selection !!}
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
          <strong>Special Price (INR):</strong>

          <input type="number" class="form-control" name="price_special" value="">

          @if ($errors->has('price_inr_special'))
              <div class="alert alert-danger">{{$errors->first('price_inr_special')}}</div>
          @endif
        </div>

        <div class="form-group">
          <strong>Size:</strong>
          <select class="form-control" name="size[]" id="size-selection" style="width: 100%" multiple>
            <option value="">Select Category</option>
          </select>

          <input type="text" name="other_size" class="form-control mt-3 hidden" id="size-manual-input" placeholder="Manual Size" value="{{ !empty($size) ? $size[0] : '' }}">

          @if ($errors->has('size'))
              <div class="alert alert-danger">{{$errors->first('size')}}</div>
          @endif
        </div>

        <div class="form-group">
          <strong>Location:</strong>

          <select class="form-control" name="location">
            <option value="">Select Location</option>

            @foreach ($locations as $location)
              <option value="{{ $location }}">{{ $location }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-secondary">Create</button>
      </div>
      </form>
    </div>

  </div>
</div>



<div id="editModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Quick Product</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="modal-body text-left">
          <div class="form-group">
            <input type="file" name="images[]" multiple />
            @if ($errors->has('images'))
            <div class="alert alert-danger">{{$errors->first('images')}}</div>
            @endif
          </div>

           <div class="form-group">
                        <strong>Existing Group:</strong>

                        @php
                            $groups = \App\QuickSellGroup::orderBy('group','asc')->get();
                        @endphp
                        <select class="form-control selectpicker" data-live-search="true" name="group_old" id="group_old">
                          <option value="">Select Group</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->group }}">@if($group->name != null) {{ $group->name }} @else {{ $group->group }} @endif </option>
                            @endforeach
                        </select>
                    </div>

          <div class="form-group">
            <strong>Suppliers:</strong>
            @php $supplier_list = (new \App\ReadOnly\SupplierList)->all();
            @endphp
            <select class="form-control selectpicker" name="supplier" data-live-search="true" id="supplier_select">
              <option value="">Select Supplier</option>
              @foreach ($supplier_list as $index => $value)
              <option value="{{ $index }}" {{ $index == old('supplier') ? 'selected' : '' }}>{{ $value }}</option>
              @endforeach
            </select>
            @if ($errors->has('supplier'))
            <div class="alert alert-danger">{{$errors->first('supplier')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Price:</strong>
            <input type="number" name="price" class="form-control" id="price_field" />
            @if ($errors->has('price'))
            <div class="alert alert-danger">{{$errors->first('price')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Special Price (INR):</strong>

            <input type="number" class="form-control" name="price_special" value="" id="price_special_field">

            @if ($errors->has('price_inr_special'))
                <div class="alert alert-danger">{{$errors->first('price_inr_special')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Size:</strong>
            <input type="text" name="size" class="form-control" id="size_field" />
            @if ($errors->has('size'))
            <div class="alert alert-danger">{{$errors->first('size')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Brand:</strong>
            <select name="brand" class="form-control" id="brand_field">
              <option value="">Select Brand</option>
              @foreach ($brands as $id => $brand)
              <option value="{{ $id }}">{{ $brand }}</option>
              @endforeach
            </select>
            @if ($errors->has('brand'))
            <div class="alert alert-danger">{{$errors->first('brand')}}</div>
            @endif
          </div>

          @if (Auth::user()->hasRole('Admin'))
            <div class="form-group">
              <strong>Location:</strong>
              <select name="location" class="form-control" id="location_field">
                <option value="">Select a Location</option>
                @foreach ($locations as $name)
                <option value="{{ $name }}">{{ $name }}</option>
                @endforeach
              </select>
              @if ($errors->has('location'))
              <div class="alert alert-danger">{{$errors->first('location')}}</div>
              @endif
            </div>
          @endif

          <div class="form-group">
            <strong>Category:</strong>
            {!! $category_selection !!}
            @if ($errors->has('category'))
            <div class="alert alert-danger">{{$errors->first('category')}}</div>
            @endif
          </div>

          <div>
            <strong>New Group:</strong>
            <input type="text" name="group_new" placeholder="Please Enter New Group Name" class="form-control" id="group_name_updated">
          </div>

        </div>
        <input type="hidden" name="product_id" id="product_id">
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-secondary" id="updateEditForm">Update</button>
        </form>

          @if(auth()->user()->isAdmin())
          @if(isset($product) && $product->is_pending == 1)
            {!! Form::open(['method' => 'POST','route' => ['quicksell.activate'],'style'=>'display:inline']) !!}
          <input type="hidden" id="productId" name="id">
          <button type="submit" class="btn btn-secondary">Activate</button>
            {!! Form::close() !!}
          @endif
          @endif

        </div>

    </div>

  </div>
</div>
