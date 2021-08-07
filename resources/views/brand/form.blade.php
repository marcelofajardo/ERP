@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $modify ? 'Edit Brand' : 'Create Brand' }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('brand.index') }}"> Back</a>
            </div>
        </div>
    </div>


    <form action="{{ $modify ? route('brand.update',$id) : route('brand.store')  }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($modify)
            @method('PUT')
        @endif
        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <strong>Name</strong>
                    <input type="text" class="form-control" name="name" placeholder="name" value="{{old('name') ? old('name') : $name}}"/>
                    @if ($errors->has('name'))
                        <div class="alert alert-danger">{{$errors->first('name')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong>Euro To Inr</strong>
                    <input type="text" class="form-control" name="euro_to_inr" placeholder="euro_to_inr" value="{{old('euro_to_inr') ? old('euro_to_inr') : $euro_to_inr}}"/>
                    @if ($errors->has('euro_to_inr'))
                        <div class="alert alert-danger">{{$errors->first('euro_to_inr')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong>Deduction %</strong>
                    <input type="number" class="form-control" name="deduction_percentage" placeholder="deduction_percentage" value="{{old('deduction_percentage') ? old('deduction_percentage') : $deduction_percentage}}"/>
                    @if ($errors->has('deduction_percentage'))
                        <div class="alert alert-danger">{{$errors->first('deduction_percentage')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong>Sales Discount %</strong>
                    <input type="number" class="form-control" name="sales_discount" placeholder="sales discount" value="{{old('sales_discount') ? old('sales_discount') : (isset($sales_discount) ? $sales_discount : '')}}"/>
                    <small class="form-text text-muted">
                        If the product is discounted at the supplier, regardless of the percentage, this discount will be applied to the special price (original price - brand discount)
                    </small>
                    @if ($errors->has('sales_discount'))
                        <div class="alert alert-danger">{{$errors->first('sales_discount')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong>Apply B2B discount above</strong>
                    <input type="number" class="form-control" name="apply_b2b_discount_above" placeholder="e.g. 40" value="{{old('apply_b2b_discount_above') ? old('apply_b2b_discount_above') :(isset($apply_b2b_discount_above) ? $apply_b2b_discount_above : '')}}"/>
                    <small class="form-text text-muted">
                        Above this percentage of discount at the supplier, the below discount will be applied
                    </small>
                    @if ($errors->has('apply_b2b_discount_above'))
                        <div class="alert alert-danger">{{$errors->first('apply_b2b_discount_above')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong>B2B Sales Discount %</strong>
                    <input type="number" class="form-control" name="b2b_sales_discount" placeholder="B2B sales discount" value="{{old('b2b_sales_discount') ? old('b2b_sales_discount') : (isset($b2b_sales_discount) ? $b2b_sales_discount : '')}}"/>
                    <small class="form-text text-muted">
                        If a B2B discount is higher than the above percentage, the sales_discount will be applied to the special price (original price - brand discount)
                    </small>
                    @if ($errors->has('b2b_sales_discount'))
                        <div class="alert alert-danger">{{$errors->first('b2b_sales_discount')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong>Magento Id</strong>
                    <input type="text" class="form-control" name="magento_id" placeholder="Magento ID" value="{{old('magento_id') ? old('magento_id') : $magento_id}}"/>
                    @if ($errors->has('magento_id'))
                        <div class="alert alert-danger">{{$errors->first('magento_id')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong>Segment</strong>
                    <select name="brand_segment" class="form-control">
                        <option value=""></option>
                        <option value="A" {{$brand_segment == 'A' ? ' SELECTED' : ''}}>A</option>
                        <option value="B" {{$brand_segment == 'B' ? ' SELECTED' : ''}}>B</option>
                        <option value="C" {{$brand_segment == 'C' ? ' SELECTED' : ''}}>C</option>
                    </select>
                    @if ($errors->has('brand_segment'))
                        <div class="alert alert-danger">{{$errors->first('brand_segment')}}</div>
                    @endif
                </div>

                @foreach($category_segments as $category_segment)
                    <div class="form-group">
                        <strong>{{ $category_segment->name }}</strong>
                        @if($modify)
                            @php
                                $category_segment = \DB::table('category_segment_discounts')->where('brand_id', $id)->where('category_segment_id', $category_segment->id)->first();
                            @endphp
                            @if($category_segment)
                                <input type="text" class="form-control" name="amount" placeholder="Amount" value="{{ old('amount') ? old(amount) : $category_segment->amount }}"/>
                            @else
                                <input type="text" class="form-control" name="amount" placeholder="Amount" value="{{ old('amount') }}"/>
                            @endif
                        @else
                            <input type="text" class="form-control" name="amount" placeholder="Amount" value="{{ old('amount') }}"/>
                        @endif
                    </div>
                @endforeach

                <div class="form-group">
                    <strong>Strip last # characters from SKU</strong>
                    <input type="text" class="form-control" name="sku_strip_last" placeholder="Strip last # characters from SKU" value="{{old('sku_strip_last') ? old('sku_strip_last') : (isset($sku_strip_last) ? $sku_strip_last : '')}}"/>
                    @if ($errors->has('sku_strip_last'))
                        <div class="alert alert-danger">{{$errors->first('sku_strip_last')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong>Add to SKU for brand site</strong>
                    <input type="text" class="form-control" name="sku_add" placeholder="Add to SKU for brand site" value="{{old('sku_add') ? old('sku_add') : (isset($sku_add) ? $sku_add : '') }}"/>
                    @if ($errors->has('sku_add'))
                        <div class="alert alert-danger">{{$errors->first('sku_add')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong>References</strong>
                    <input type="text" class="form-control" name="references" placeholder="Add/update references in comma seperate values" value="{{old('references') ? old('references') : (isset($references) ? $references : '') }}"/>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-secondary">+</button>
                </div>

            </div>

        </div>
    </form>

@endsection
