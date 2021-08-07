@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Create Translation</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-secondary" href="{{ route('translation.list') }}"> Back</a>
        </div>
    </div>
</div>

@if(Session::has('success'))
<div class="alert alert-success">
      <p>{{ Session::get('success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
  </div> <!-- end .flash-message -->
@endif

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



{!! Form::open(array('route' => 'translation.store','method'=>'POST')) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Translation From :</strong>
            <select name="from" class="form-control">
                <option value="">Select from</option>
                @if($from)
                @foreach($from as $frm)
                <option value="{{$frm->from}}">{{$frm->from}}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Translation To:</strong>
             <select name="to" class="form-control">
                <option value="">Select to</option>
                @if($from)
                @foreach($to as $t)
                <option value="{{$t->to}}">{{$t->to}}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Original Text:</strong>
            {!! Form::text('text_original', null, array('placeholder' => 'Original Text','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Translated Text:</strong>
            {!! Form::text('text', null, array('placeholder' => 'Translated Text','class' => 'form-control')) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-secondary">+</button>
    </div>
    
</div>
{!! Form::close() !!}


@endsection