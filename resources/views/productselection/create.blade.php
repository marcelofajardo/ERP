@extends('layouts.app')

@section('favicon' , 'selectionnew.png')
@section('title', 'Add New Selection Grid - ERP Sololuxury')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Add New Selection</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('productselection.index') }}"> Back</a>
            </div>
        </div>
    </div>

    <form action="{{ route('productselection.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Sku:</strong>
                    <input type="text" class="form-control" name="sku" placeholder="Sku" value="{{old('sku')}}"/>
                    @if ($errors->has('sku'))
                        <div class="alert alert-danger">{{$errors->first('sku')}}</div>
                    @endif
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Size:</strong>
                    <input type="text" class="form-control" name="size" placeholder="Size" value="{{old('size')}}"/>
                    @if ($errors->has('size'))
                        <div class="alert alert-danger">{{$errors->first('size')}}</div>
                    @endif
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Size (in eu):</strong>
                    <input type="text" class="form-control" name="size_eu" placeholder="Size (in eu)" value="{{old('size_eu')}}"/>
                    @if ($errors->has('size_eu'))
                        <div class="alert alert-danger">{{$errors->first('size_eu')}}</div>
                    @endif
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Price (in Euro):</strong>
                    <input type="number" class="form-control" name="price" placeholder="Price" value="{{old('price')}}"/>
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
                echo Form::select('brand',$brands,  old('brand'), ['placeholder' => 'Select a brand','class' => 'form-control']);?>
                  {{--<input type="text" class="form-control" name="brand" placeholder="Brand" value="{{ old('brand') ? old('brand') : $brand }}"/>--}}
                  @if ($errors->has('brand'))
                      <div class="alert alert-danger">{{$errors->first('brand')}}</div>
                  @endif
              </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
              <div class="form-group">
                <strong>Special Price (INR):</strong>

                <input type="number" class="form-control" name="price_inr_special" value="{{ old('price_inr_special') }}">

                @if ($errors->has('price_inr_special'))
                    <div class="alert alert-danger">{{$errors->first('price_inr_special')}}</div>
                @endif
              </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Upload Image:</strong>
                    <input enctype="multipart/form-data" type="file" class="form-control" name="image"/>
                    @if ($errors->has('image'))
                        <div class="alert alert-danger">{{$errors->first('image')}}</div>
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
                    <option value="{{ $index }}" {{ $index == old('supplier') ? 'selected' : '' }}>{{ $value }}</option>
                  @endforeach
                </select> --}}

                <select class="form-control" name="supplier[]" multiple>
                  <option value="">Select Supplier</option>
                  @foreach ($suppliers as $index => $supplier)
                    <option value="{{ $supplier->id }}" {{ $supplier->id == old('supplier') ? 'selected' : '' }}>{{ $supplier->supplier }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong> Supplier Link :</strong>
                    <input type="text" class="form-control" name="supplier_link" placeholder="Supplier Link" value="{{ old('supplier_link')}}"/>
                    @if ($errors->has('supplier_link'))
                        <div class="alert alert-danger">{{$errors->first('supplier_link')}}</div>
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong> Description Link :</strong>
                    <input type="text" class="form-control" name="description_link" placeholder="Description Link" value="{{ old('description_link') }}"/>
                    @if ($errors->has('description_link'))
                        <div class="alert alert-danger">{{$errors->first('description_link')}}</div>
                    @endif
                </div>
            </div>

            @if (Auth::user()->hasRole('Admin'))
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Location :</strong>
                      <select class="form-control" name="location">
                        <option value="">Select a Location</option>
                        @foreach ($locations as $location)
                          <option value="{{ $location }}" {{ $location == old('location') ? 'selected' : '' }}>{{ $location }}</option>
                        @endforeach
                      </select>
                      @if ($errors->has('location'))
                          <div class="alert alert-danger">{{$errors->first('location')}}</div>
                      @endif
                  </div>
              </div>
            @endif

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <input type="text" hidden name="stage" value="1">
                <button type="submit" class="btn btn-secondary">Submit</button>
            </div>
        </div>
    </form>


@endsection
