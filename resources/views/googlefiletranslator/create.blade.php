@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Create File Translation </h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-secondary" href="{{ route('googlefiletranslator.list') }}"> Back</a>
        </div>
    </div>
</div>


@if (count($errors) > 0)
<div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif



{!! Form::open(array('route' => 'googlefiletranslator.store','method'=>'POST','enctype' => 'multipart/form-data')) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Language:</strong>
            <select name="tolanguage" class="form-control">
                <option value="">Select Language</option>
                @foreach($Language as $lang)
                <option value="{{$lang->id}}" {{ ($lang->id == old('tolanguage'))?'selected':''}}>{{$lang->locale}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Upload File:</strong>
            <input type="file" name="file" class="form-control"> 
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-secondary">+</button>
    </div>
    
</div>
{!! Form::close() !!}


@endsection