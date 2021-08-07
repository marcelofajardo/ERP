@extends('layouts.app')

@section('styles')
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
    <style src="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.css"></style>
@endsection
@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Passwords Manager</h2>
            <div class="pull-left">

            </div>
            <div class="pull-right">
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#passwordCreateModal">+</a>
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
       <div class="col-xs-5">
            <h3>Select Users To Change Password</h3>
            <select name="from[]" id="keepRenderingSort" class="form-control" size="8" multiple="multiple">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-xs-2">
            <h3>Action</h3>
            <button type="button" id="keepRenderingSort_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
            <button type="button" id="keepRenderingSort_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
            <button type="button" id="keepRenderingSort_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
            <button type="button" id="keepRenderingSort_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
        </div>

        <div class="col-xs-5">
            <h3>Selected Users</h3>
            {{ Form::open(array('url' => route('password.change'), 'method' => 'post')) }}
            @csrf
            <select name="users[]" id="keepRenderingSort_to" class="form-control" size="8" multiple="multiple"></select>
            <br>
            <div class="pull-right">
                <button type="submit" class="btn btn-primary btn-md">Proceed</button>
            </div>
            {{ Form::close() }}
        </div>

    </div>





@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/multiselect/2.2.9/js/multiselect.min.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#keepRenderingSort').multiselect({
                keepRenderingSort: true
            });
        });
    </script>
@endsection