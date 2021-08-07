@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $modify ? 'Edit Quick Reply' : 'Create Quick Reply' }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('reply.index') }}">Back</a>
            </div>
        </div>
    </div>


    <form action="{{ $modify ? route('reply.update',$id) : route('reply.store')  }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($modify)
            @method('PUT')
        @endif
        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Quick Reply</strong>
                    <textarea class="form-control" name="reply" placeholder="Quick Reply" required>{{old('reply') ? old('reply') : $reply}}</textarea>
                    @if ($errors->has('reply'))
                        <div class="alert alert-danger">{{$errors->first('reply')}}</div>
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Model</strong>
                    <select class="form-control" name="model" required>
                      <option value="">Select Model</option>
                      <option value="Approval Lead" {{ $model == 'Approval Lead' ? 'selected' : '' }}>Approval Lead</option>
                      <option value="Internal Lead" {{ $model == 'Internal Lead' ? 'selected' : '' }}>Internal Lead</option>
                      <option value="Approval Order" {{ $model == 'Approval Order' ? 'selected' : '' }}>Approval Order</option>
                      <option value="Internal Order" {{ $model == 'Internal Order' ? 'selected' : '' }}>Internal Order</option>
                      <option value="Approval Purchase" {{ $model == 'Approval Purchase' ? 'selected' : '' }}>Approval Purchase</option>
                      <option value="Internal Purchase" {{ $model == 'Internal Purchase' ? 'selected' : '' }}>Internal Purchase</option>
                    </select>
                    @if ($errors->has('model'))
                        <div class="alert alert-danger">{{$errors->first('model')}}</div>
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Category</strong>
                    <select class="form-control" name="category_id" required>
                      @foreach ($reply_categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == $category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('model'))
                        <div class="alert alert-danger">{{$errors->first('model')}}</div>
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-secondary">+</button>
            </div>

        </div>
    </form>


@endsection
