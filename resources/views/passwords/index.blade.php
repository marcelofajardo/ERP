@extends('layouts.app')

@section('favicon' , 'password-manager.png')

@section('title', 'Passwords Manager Info')

@section('styles')
    <style>
        .users {
            display: none;
        }

    </style>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <style type="text/css">
         #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
        }
    </style>
@endsection


@section('content')
    <div id="myDiv">
       <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
   </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Passwords Manager</h2>
            <div class="pull-left">
                <form action="{{ route('password.index') }}" method="GET" class="form-inline align-items-start">
                    <div class="form-group mr-3 mb-3">
                        <input name="term" type="text" class="form-control global" id="term"
                               value="{{ isset($term) ? $term : '' }}"
                               placeholder="website , username, password">
                    </div>
                    <div class="form-group ml-3">
                        <div class='input-group date' id='filter-date'>
                            <input type='text' class="form-control global" name="date" value="{{ isset($date) ? $date : '' }}" placeholder="Date" id="date" />

                            <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                        </div>
                    </div>


                    <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
                </form>
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

    <div class="table-responsive mt-3">
      <table class="table table-bordered" id="passwords-table">
        <thead>
          <tr>
            <th>Website</th>
            <th>Username</th>
            <th>Password</th>
            <th>Registered With</th>
            <th>Actions</th>
          </tr>

          <tr>
            <th><input type="text" id="website" class="search form-control"></th>
            <th><input type="text" id="username" class="search form-control"></th>
            <th></th>
            <th><input type="text" id="registered_with" class="search form-control"></th>
            <th></th>
          </tr>
        </thead>

        <tbody>

       @include('passwords.data') 

          {!! $passwords->render() !!}
          
        </tbody>
      </table>
    </div>



    <div id="passwordCreateModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <form action="{{ route('password.store') }}" method="POST">
            @csrf

            <div class="modal-header">
              <h4 class="modal-title">Store a Password</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <strong>Website:</strong>
                <input type="text" name="website" class="form-control" value="{{ old('website') }}">

                @if ($errors->has('website'))
                  <div class="alert alert-danger">{{$errors->first('website')}}</div>
                @endif
              </div>

              <div class="form-group">
                <strong>URL:</strong>
                <input type="text" name="url" class="form-control" value="{{ old('url') }}" required>

                @if ($errors->has('url'))
                  <div class="alert alert-danger">{{$errors->first('url')}}</div>
                @endif
              </div>

              <div class="form-group">
                <strong>Username:</strong>
                <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>

                @if ($errors->has('username'))
                  <div class="alert alert-danger">{{$errors->first('username')}}</div>
                @endif
              </div>

              <div class="form-group">
                <strong>Password:</strong>
                <input type="text" name="password" class="form-control" value="{{ old('password') }}" required>

                @if ($errors->has('password'))
                  <div class="alert alert-danger">{{$errors->first('password')}}</div>
                @endif
              </div>
              <div class="form-group">
                    <strong>Registered With:</strong>
                    <input type="text" name="registered_with" class="form-control"  required>


              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-secondary">Store</button>
            </div>
          </form>
        </div>

      </div>
    </div>
    @if($passwords->isEmpty())


    @else
    @foreach($passwords as $password)
    <div id="passwordEditModal{{$password->id}}" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h4 class="modal-title">Store a Password</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <strong>Website:</strong>
                            <input type="text" name="website" class="form-control" value="{{ $password->website }}">

                            @if ($errors->has('website'))
                                <div class="alert alert-danger">{{$errors->first('website')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>URL:</strong>
                            <input type="text" name="url" class="form-control" value="{{ $password->url }}" required>

                            @if ($errors->has('url'))
                                <div class="alert alert-danger">{{$errors->first('url')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Username:</strong>
                            <input type="text" name="username" class="form-control" value="{{ $password->username }}" required>

                            @if ($errors->has('username'))
                                <div class="alert alert-danger">{{$errors->first('username')}}</div>
                            @endif
                        </div>
                            <input type="hidden" name="id" value="{{ $password->id }}"/>
                        <div class="form-group">
                            <strong>Password:</strong>
                            <input type="text" name="password" class="form-control" value="{{ Crypt::decrypt($password->password) }}" required>

                            @if ($errors->has('password'))
                                <div class="alert alert-danger" >{{$errors->first('password')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Registered With:</strong>
                            <input type="text" name="registered_with" class="form-control" value="{{ $password->registered_with }}">

                            @if ($errors->has('password'))
                                <div class="alert alert-danger" >{{$errors->first('password')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                          <input type="checkbox" class="check" value="1" name="send_message"> Send Via WhatsApp
                        </div>
                        <div class="form-group users">
                            <select class="form-control" name="user_id">
                                @foreach($users as $user)
                                <option class="form-control" value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-secondary">Update</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    @endforeach
    @endif
    <div id="getHistory" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">


                    <div class="modal-header">
                        <h4 class="modal-title">Password History</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <table>
                            <thead>
                            <tr>
                                <th>Website</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Registered With</th>
                            </tr>
                            </thead>
                            <tbody class="table" id="data">


                            </tbody>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>

        $(document).ready(function() {
            $(".select-multiple").multiselect();
            $(".select-multiple2").select2();
        });

        $('#filter-date').datetimepicker({
            format: 'YYYY-MM-DD',
        });


        // $('.date').change(function(){
        //     alert('date selected');
        // });


    function changePassword(password_id) {
        $("#passwordEditModal"+ password_id +"" ).modal('show');
    }
    $(".check").change(function() {
        if(this.checked) {
            $(".users").show();
        }else{
            $(".users").hide();
        }
    });

    function getData(password_id) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{ route('password.history') }}",
            data: {"_token": "{{ csrf_token() }}", "password_id": password_id},
            dataType: "json",
            success: function (message) {
               $c =  message.length;
                if($c == 0){
                   alert('No History Exist');
                } else{
                    var detials="";
                    $.each( message, function( key, value ) {
                        detials += "<tr><th>" + value.website + "</th><th>" + value.username +"</th><th>" + value.password_decrypt + "</th><th>" + value.registered_with +"</th><tr>";
                    });
                    console.log(detials);
                    $('#data').html(detials);
                    $("#getHistory").modal('show');
                }
            }, error: function () {

            }

        });
    }

        $(document).ready(function() {
        src = "{{ route('password.index') }}";
        $(".search").autocomplete({
        source: function(request, response) {
            website = $('#website').val();
            username = $('#username').val();
            password = $('#password').val();
            registered_with = $('#registered_with').val();
          

            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    website : website,
                    username : username,
                    password : password,
                    registered_with : registered_with,
                
                },
                beforeSend: function() {
                       $("#loading-image").show();
                },
            
            }).done(function (data) {
                 $("#loading-image").hide();
                console.log(data);
                $("#passwords-table tbody").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
                
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
        },
        minLength: 1,
       
        });
    });


         $(document).ready(function() {
        src = "{{ route('password.index') }}";
        $(".global").autocomplete({
        source: function(request, response) {
            term = $('#term').val();
            date = $('#date').val();
            
          

            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    term : term,
                    date : date,
                
                },
                beforeSend: function() {
                       $("#loading-image").show();
                },
            
            }).done(function (data) {
                 $("#loading-image").hide();
                console.log(data);
                $("#passwords-table tbody").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
                
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
        },
        minLength: 1,
       
        });
    });

        
</script>
@endsection
