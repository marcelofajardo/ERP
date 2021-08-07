@extends('layouts.app')
@section('title', 'Store Website Analytics')
@section('content')
  <div class="row mb-5">
    <div class="col-lg-12 margin-tb">
      <h2 class="page-heading">Store Website Analytics</h2>
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
          <div class="card-header">{{ __('Edit') }}</div>
          <div class="card-body">
            <form method="POST" action="{{ url('/store-website-analytics/create') }}" enctype="multipart/form-data">
              @csrf
              <div class="form-group row">
                <label for="website" class="col-4 col-form-label">{{ __('Website') }}</label>
                <div class="col-8">
                  <input type="hidden" name="id" value="{{old('id') ?? $storeWebsiteAnalyticData->id}}">
                  <input id="website" name="website" placeholder="Website" type="text" class="form-control {{ $errors->has('website') ? ' is-invalid' : '' }}" value="{{old('website') ?? $storeWebsiteAnalyticData->website}}" required="required" autofocus>
                  @if ($errors->has('website'))
                    <span class="invalid-feedback">
                      <strong>{{ $errors->first('website') }}</strong>
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label for="email" class="col-4 col-form-label">{{ __('Email') }}</label>
                <div class="col-8">
                  <input id="email" name="email" placeholder="Email" type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{old('email') ?? $storeWebsiteAnalyticData->email}}" required="required" autofocus>
                  @if ($errors->has('email'))
                    <span class="invalid-feedback">
                      <strong>{{ $errors->first('email') }}</strong>
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label for="account_id" class="col-4 col-form-label">{{ __('Account Id') }}</label>
                <div class="col-8">
                  <input id="account_id" name="account_id" placeholder="Account Id" type="text" class="form-control {{ $errors->has('account_id') ? ' is-invalid' : '' }}" value="{{old('account_id') ?? $storeWebsiteAnalyticData->account_id}}" required="required" autofocus>
                  @if ($errors->has('account_id'))
                    <span class="invalid-feedback">
                      <strong>{{ $errors->first('account_id') }}</strong>
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label for="view_id" class="col-4 col-form-label">{{ __('View Id') }}</label>
                <div class="col-8">
                  <input id="view_id" name="view_id" placeholder="View Id" type="text" class="form-control {{ $errors->has('view_id') ? ' is-invalid' : '' }}" value="{{old('view_id') ?? $storeWebsiteAnalyticData->view_id}}" required="required" autofocus>
                  @if ($errors->has('view_id'))
                    <span class="invalid-feedback">
                      <strong>{{ $errors->first('view_id') }}</strong>
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label for="select" class="col-4 col-form-label">Store Website Id</label>
                <div class="col-8">
                  <select id="select" name="store_website_id" class="form-control">
                      @foreach($storeWebsites as $website)
                      @php
                      $selected = '';
                      if(old('store_website_id') == $website->id){
                          $selected = 'selected';
                      }elseif($storeWebsiteAnalyticData->store_website_id == $website->id){
                          $selected = 'selected';
                      }
                      @endphp
                      <option value="{{$website->id}}" {{$selected}}>{{$website->id .' - '. $website->website}}</option>
                      @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="view_id" class="col-4 col-form-label">{{ __('google service account json') }}</label>
                <div class="col-8">
                  <input id="google_service_account_json" name="google_service_account_json" placeholder="Google Service Account Json" type="file" class="{{ $errors->has('google_service_account_json') ? ' is-invalid' : '' }}" value="{{old('google_service_account_json') ?? $storeWebsiteAnalyticData->google_service_account_json}}" autofocus>
                  @if ($errors->has('google_service_account_json'))
                      <span class="invalid-feedback">
                          <strong>{{ $errors->first('google_service_account_json') }}</strong>
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
