@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $modify ? 'Edit Category Segment' : 'Create Category Segment' }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('category-segment.index') }}"> Back</a>
            </div>
        </div>
    </div>


    <form action="{{ $modify ? route('category-segment.update',$id) : route('category-segment.store')  }}" method="POST">
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
                    <strong>Status</strong>
                    <select name="status" class="form-control">
                        <option value="1" {{ $status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="2" {{ $status == 2 ? 'selected' : '' }}>Blocked</option>
                        <option value="3" {{ $status == 3 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @if ($errors->has('status'))
                        <div class="alert alert-danger">{{$errors->first('status')}}</div>
                    @endif
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-secondary">+</button>
                </div>

            </div>

        </div>
    </form>


@endsection
