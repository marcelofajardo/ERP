@extends('layouts.app')
@section('title', 'Store Website country shipping')
@section('content')
  <div class="row mb-5">
    <div class="col-lg-12 margin-tb">
      <h2 class="page-heading">Store Website country shipping</h2>
      <div class="pull-left">
      </div>
      <div class="pull-right mt-4">
      </div>
    </div>
  </div>
@include('partials.flash_messages')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card">
          <div class="card-header">{{ __('Create') }}</div>
          <div class="card-body">
            <form method="POST" action="{{ route('store-website-country-shipping.create') }}" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="id" value="{{old('id') ?? $data->id}}">
              <input type="hidden" name="ship_id" value="{{old('ship_id') ?? $data->ship_id}}">
              <div class="form-group row">
                    <label for="select" class="col-4 col-form-label">Store Website Id</label>
                    <div class="col-8">
                      <select id="select" name="store_website_id" class="form-control">
                      @foreach($storeWebsites as $website)
                        @php
                          $selected = '';
                        if( $data->store_website_id == $website->id ){
                          $selected = 'selected';
                        }
                        @endphp
                        <option value="{{$website->id}}" {{$selected}}>{{$website->id .' - '. $website->website}}</option>
                      @endforeach
                      </select>
                    </div>
              </div>
              <div class="form-group row">
                    <label for="select" class="col-4 col-form-label">Country name</label>
                    <div class="col-8">
                      <select id="select" name="country_name" class="form-control">
                      @foreach($simplyDutyCountry as $key)
                        @php
                            $selected = '';
                            if($data->country_name == $key->country_name){
                              $selected = 'selected';
                            }
                        @endphp
                        <option value="{{ $key->country_name }}" {{ $selected }}>{{ $key->country_name }}</option>
                      @endforeach
                      </select>
                    </div>
              </div>
              <div class="form-group row">
                <label for="price" class="col-4 col-form-label"> Price </label>
                <div class="col-8">
                  <input id="price" name="price" placeholder="price" type="text" class="form-control {{ $errors->has('price') ? ' is-invalid' : '' }}" value="{{ $data->price ?? null }}" required="required">
                  @if ($errors->has('price'))
                    <span class="invalid-feedback">
                      <strong>{{ $errors->first('website') }}</strong>
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label for="currency" class="col-4 col-form-label"> Currency </label>
                <div class="col-8">
                  <input id="currency" name="currency" placeholder="Currency" type="text" class="form-control {{ $errors->has('currency') ? ' is-invalid' : '' }}" value="{{ $data->currency ?? null}}" required="required" >
                  @if ($errors->has('currency'))
                  <span class="invalid-feedback">
                    <strong>{{ $errors->first('currency') }}</strong>
                  </span>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <div class="offset-4 col-8">
                  <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
