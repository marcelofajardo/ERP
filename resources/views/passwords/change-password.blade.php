@extends('layouts.app')

@section('styles')
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
    <style src="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.css"></style>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
@endsection
@section('content')
    
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Passwords Manager</h2>
            <div class="pull-left">

            </div>
            <div class="pull-right">
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#passwordCreateModal">+</button>
            </div>
            <div>
                {{ Form::open(array('url' => route('password.change'), 'method' => 'post')) }}
                    <input type="hidden" name="users" id="userIds">
                    <button type="submit" class="btn btn-secondary"> Generate password </button>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    @include('partials.flash_messages')
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
              <table class="table table-bordered" id="passwords-table">
                <thead>
                  <tr>
                    <th>#ID</th>
                    <th>Username</th>
                    <th>Email</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                      <tr>
                            <td > <input type="checkbox" class="checkbox_ch" id="u{{ $user->id }}" name="userIds[]" value="{{ $user->id }}"></td>
                            <td><label for="u{{ $user->id }}"> {{ $user->name }} </label></td>
                            <td><label for="u{{ $user->id }}" > {{ $user->email }}</label></td>
                      </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
        </div>

       {{-- <div class="col-xs-5">
            <h3>Select Users To Change Password</h3>
            <select name="from[]" id="keepRenderingSort" class="form-control" size="8" multiple="multiple">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
 --}}
        {{-- <div class="col-xs-2">
            <h3>Action</h3>
            <button type="button" id="keepRenderingSort_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
            <button type="button" id="keepRenderingSort_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
            <button type="button" id="keepRenderingSort_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
            <button type="button" id="keepRenderingSort_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
        </div> --}}

       {{--  <div class="col-xs-5">
            <h3>Selected Users</h3>
            {{ Form::open(array('url' => route('password.change'), 'method' => 'post')) }}
            @csrf
            <select name="users[]" id="keepRenderingSort_to" class="form-control" size="8" multiple="multiple"></select>
            <br>
            <div class="pull-right">
                <button type="submit" class="btn btn-primary btn-md">Proceed</button>
            </div>
            {{ Form::close() }}
        </div> --}}

    </div>





@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/multiselect/2.2.9/js/multiselect.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">

        $(document).ready( function () {
            $('#passwords-table').DataTable();
        });

         $('.checkbox_ch').change(function(){
             var values = $('input[name="userIds[]"]:checked').map(function(){return $(this).val();}).get();
             $('#userIds').val(values);
         });

        jQuery(document).ready(function($) {
            $('#keepRenderingSort').multiselect({
                keepRenderingSort: true
            });
        });
    </script>
@endsection