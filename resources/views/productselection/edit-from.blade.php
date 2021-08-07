<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Selection</h2>
        </div>
        <div class="pull-right js-back">
            <a class="btn btn-secondary" href="{{ route('productselection.index') }}"> Back</a>
        </div>
    </div>
</div>

@if (  $productselection->isApproved == -1 )
    <div class="alert alert-danger alert-block mt-2">
        <button type="button" class="close" data-d ismiss="alert">×</button>
        <p><strong>Product has been rejected</strong></p>
        <p><strong>Reason : </strong> {{ $productselection->rejected_note }}</p>
    </div>
@endif

<form action="{{ route('productselection.update',$productselection->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Sku:</strong>
                <input type="text" name="sku" value="{{ old('sku') ? old('sku') : $productselection->sku }}" class="form-control" placeholder="Sku">
                @if ($errors->has('sku'))
                    <div class="alert alert-danger">{{$errors->first('sku')}}</div>
                @endif
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Size:</strong>
                <input type="text" class="form-control" name="size" placeholder="Size" value="{{old('size') ? old('size') : $productselection->size }}"/>
                @if ($errors->has('size'))
                    <div class="alert alert-danger">{{$errors->first('size')}}</div>
                @endif
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Size (in eu):</strong>
                <input type="text" class="form-control" name="size_eu" placeholder="Size (in eu)" value="{{old('size_eu') ? old('size_eu') : $productselection->size_eu }}"/>
                @if ($errors->has('size_eu'))
                    <div class="alert alert-danger">{{$errors->first('size_eu')}}</div>
                @endif
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Price (in Euro):</strong>
                <input type="number" class="form-control" name="price" placeholder="Price" value="{{old('price') ? old('price') : $productselection->price }}"/>
                @if ($errors->has('price'))
                    <div class="alert alert-danger">{{$errors->first('price')}}</div>
                @endif
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
              <strong> Brand :</strong>

            <?php
            $brands = \App\Brand::getAll();
            echo Form::select('brand',$brands,  (old('brand') ? old('brand') : $productselection->brand), ['placeholder' => 'Select a brand','class' => 'form-control']);?>
              {{--<input type="text" class="form-control" name="brand" placeholder="Brand" value="{{ old('brand') ? old('brand') : $brand }}"/>--}}
              @if ($errors->has('brand'))
                  <div class="alert alert-danger">{{$errors->first('brand')}}</div>
              @endif
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
            <strong>Special Price (INR):</strong>

            <input type="number" class="form-control" name="price_special" value="{{ old('price_inr_special') ? old('price_inr_special') : $productselection->price_inr_special }}">

            @if ($errors->has('price_inr_special'))
                <div class="alert alert-danger">{{$errors->first('price_inr_special')}}</div>
            @endif
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
            <strong>Supplier</strong>

            {{-- @php $supplier_list = (new \App\ReadOnly\SupplierList)->all(); @endphp
            <select class="form-control" name="supplier">
              <option value="">Select Supplier</option>
              @foreach ($supplier_list as $index => $value)
                <option value="{{ $index }}" {{ $index == $productselection->supplier ? 'selected' : '' }}>{{ $value }}</option>
              @endforeach
            </select> --}}

            <select class="form-control" name="supplier[]" multiple>
              <option value="">Select Supplier</option>
              @foreach ($suppliers as $index => $supplier)
                <option value="{{ $supplier->id }}" {{ $productselection->suppliers->contains($supplier->id) ? 'selected' : '' }}>{{ $supplier->supplier }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong> Supplier Link :</strong>
                <input type="text" class="form-control" name="supplier_link" placeholder="Supplier Link" value="{{ old('supplier_link') ? old('supplier_link') : $productselection->supplier_link }}"/>
                @if ($errors->has('supplier_link'))
                    <div class="alert alert-danger">{{$errors->first('supplier_link')}}</div>
                @endif
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
            <strong>Status</strong>
            <select class="form-control" name="status_id">
              <option value="">Select Status</option>
              @foreach (\App\Helpers\StatusHelper::getStatus() as $index => $status)
                <option value="{{ $index }}" {{ $index == ( old('status_id') ? old('status_id') : $productselection->status_id ) ? 'selected' : (request()->get('status_id') == $index) ? 'selected' : '' }}>{{ $status }}</option>
              @endforeach
            </select>
          </div>
        </div>

        @if (Auth::user()->hasRole('Admin'))
          <div class="col-xs-12 col-sm-12 col-md-12">
              <div class="form-group">
                  <strong>Location :</strong>
                  <select class="form-control" name="location">
                    <option value="">Select a Location</option>
                    @foreach ($locations as $location)
                      <option value="{{ $location }}" {{ $location == $productselection->location ? 'selected' : '' }}>{{ $location }}</option>
                    @endforeach
                  </select>
                  @if ($errors->has('location'))
                      <div class="alert alert-danger">{{$errors->first('location')}}</div>
                  @endif
              </div>
          </div>
        @endif

  {{--      <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong> Description Link :</strong>
                <input type="text" class="form-control" name="description_link" placeholder="Description Link" value="{{ old('description_link') ? old('description_link') : $productselection->description_link }}"/>
                @if ($errors->has('description_link'))
                    <div class="alert alert-danger">{{$errors->first('description_link')}}</div>
                @endif
            </div>
        </div>--}}


        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="old-image" style="
                 @if ($errors->has('image'))
                    display: none;
                 @endif
            ">
                <p>
                 <?php $image = $productselection->getMedia(config('constants.media_tags'))->first() ?>
                <img src="{{ $image ? $image->getUrl() : '' }}"
                     class="img-responsive" style="max-width: 200px;"  alt="">

                     <input type="text" hidden name="oldImage" value="{{ $image ? '0' : '-1' }}">
                </p>
                @if ($image)
                  <button class="btn btn-image removeOldImage" data-id="" media-id="{{ $image->id }}"><img src="/images/delete.png" /></button>
                @endif
            </div>
            <div class="form-group new-image" style="
            @if ($image) display: none; @endif
            ">
                <strong>Upload Image:</strong>
                <input  type="file" enctype="multipart/form-data" class="form-control" name="image" />
                @if ($errors->has('image'))
                    <div class="alert alert-danger">{{$errors->first('image')}}</div>
                @endif
            </div>

            {{-- @if (!$image)
              <div class="form-group new-image">
                  <strong>Upload Image:</strong>
                  <input  type="file" enctype="multipart/form-data" class="form-control" name="image" />
                  @if ($errors->has('image'))
                      <div class="alert alert-danger">{{$errors->first('image')}}</div>
                  @endif
              </div>
            @endif --}}
        </div>


        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <input type="text" hidden name="stage" value="1">
            <button type="submit" class="btn btn-secondary">+</button>
        </div>
    </div>
</form>