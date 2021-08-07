@extends('layouts.app')

@section('title', 'Orders List')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <h2 class="page-heading">Add Magneto Status</h2>
          </div>

          <div class="col-12 mb-3">
            <div class="pull-left">

                <form class="form-inline" action="{{ route('order.index') }}" method="GET">
                  <div class="form-group">
                    <input name="term" type="text" class="form-control"
                           value="{{ isset($term) ? $term : '' }}"
                           placeholder="Search">
                  </div>

                  <button type="submit" class="btn btn-image ml-3"><img src="/images/filter.png" /></button>
                </form>
            </div>
            <div class="pull-right">
                
            </div>
        </div>
    </div>
@endsection
