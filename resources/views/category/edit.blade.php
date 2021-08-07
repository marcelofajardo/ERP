
@extends('layouts.app')

@section('content')

    <link href="{{ asset('css/treeview.css') }}" rel="stylesheet">
    <div class="panel panel-primary">
        {{--<div class="panel-heading">Edit Category</div>--}}
        <div class="panel-body">
            <div class="row">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h3>Edit Category</h3>

                    {!! Form::open(['route'=> ['category.edit' , $id]  ]) !!}

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif

                    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                        {!! Form::label('New Title:') !!}
                        {!! Form::text('title', old('title') ? old('title') : $title , ['class'=>'form-control', 'placeholder'=>'Enter New Title']) !!}
                        <span class="text-danger">{{ $errors->first('title') }}</span>
                    </div>

                    <div class="form-group {{ $errors->has('magento_id') ? 'has-error' : '' }}">
                        {!! Form::label('Magento Id:') !!}
                        {!! Form::text('magento_id', old('magento_id') ? old('magento_id') : $magento_id, ['class'=>'form-control', 'placeholder'=>'Enter Magento Id']) !!}
                        <span class="text-danger">{{ $errors->first('magento_id') }}</span>
                    </div>

                    <div class="form-group {{ $errors->has('show_all_id') ? 'has-error' : '' }}">
                        {!! Form::label('Show all Id:') !!}
                        {!! Form::text('show_all_id', old('show_all_id') ? old('show_all_id') : $show_all_id, ['class'=>'form-control', 'placeholder'=>'Enter Show All Id']) !!}
                        <span class="text-danger">{{ $errors->first('show_all_id') }}</span>
                    </div>

                    <div class="form-group">
                        {!! Form::label('Category Segment:') !!}
                        {!! Form::select('category_segment_id', $category_segments, old('category_segment_id')? old('category_segment_id'):$category_segment_id, ['class'=>'form-control', 'placeholder'=>'Select Category Segment']) !!}
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-secondary">Edit</button>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>
    <script src="{{asset('js/treeview.js')}}"></script>
@endsection
