@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Create New User</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-secondary" href="{{ route('users.index') }}"> Back</a>
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



{!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Name:</strong>
            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Email:</strong>
            {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
        </div>
    </div>

    <div class="col-xs-12">
        <div class="form-group">
            <strong>Phone:</strong>
            <input type="number" class="form-control" name="phone" placeholder="900000000" value="{{old('phone')}}" />
            @if ($errors->has('phone'))
            <div class="alert alert-danger">{{$errors->first('phone')}}</div>
            @endif
        </div>
    </div>

    <div class="col-xs-12">
        <div class="form-group">
            <strong>Solo phone:</strong>
            <?php
                $allWhatsappNo         = config("apiwha.instances");
            ?>

            <Select name="whatsapp_number" class="form-control">
                <option value>None</option>
                <?php foreach($allWhatsappNo as $awp) { ?>
                    <option value="<?php echo $awp['number'] ?>" {{ old('whatsapp_number') == $awp['number'] ? 'selected' : '' }}>{{$awp['number']}}</option>
                <?php } ?>
            </Select>
            @if ($errors->has('whatsapp_number'))
            <div class="alert alert-danger">{{$errors->first('whatsapp_number')}}</div>
            @endif
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Password:</strong>
            {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Confirm Password:</strong>
            {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Responsible User:</strong>
            <select name="responsible_user" class="form-control">
                <option value="">Select User</option>
                @foreach($users as $user)
                <option value="{{$user->id}}">{{$user->name}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Hourly Rate:</strong>
            {!! Form::text('hourly_rate', null, array('placeholder' => '0.00','class' => 'form-control')) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Currency:</strong>
            {!! Form::text('currency', null, array('placeholder' => 'USD','class' => 'form-control')) !!}
        </div>
    </div>
    
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-secondary">+</button>
    </div>
</div>
{!! Form::close() !!}


@endsection