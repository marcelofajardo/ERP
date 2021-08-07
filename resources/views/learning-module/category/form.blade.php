@extends('layouts.app')

@section('favicon' , 'task.png')

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $modify ? 'Edit' : 'Create' }} Task Category</h2>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-6">
            <form action="{{ $modify ?  route('task_category.update',$id ) :  route('task_category.store') }}" method="POST">
                @csrf
                @if($modify)
                    @method('PUT')
                @endif
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input id="name" type="text" name="name" value="{{ old('name') ? old('name') : $name }}" class="form-control" placeholder="Name"></div>

                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-secondary">{{ $modify ? 'Update' : 'Create' }}</button>
                </div>
            </form>
        </div>
    </div>

@endsection
