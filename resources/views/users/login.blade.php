@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">User Logins</h2>

            <form class="form-inline mb-3" action="{{ route('users.login.index') }}" method="GET">
              <div class='input-group date' id='login_date'>
                  <input type='text' class="form-control" name="date" value="{{ $date }}" />

                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>

              <button type="submit" class="btn btn-secondary ml-3">Submit</button>
            </form>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Logged In At</th>
            <th>Logged Out At</th>
        </tr>
        @foreach ($logins as $login)
            <tr>
              <td>{{ $login->user->name }}</td>
              <td><span class="user-status {{ $login->user->isOnline() ? 'is-online' : '' }}"></span> {{ $login->user->email }}</td>
              <td>{{ $login->login_at }}</td>
              <td>{{ $login->logout_at }}</td>
            </tr>
        @endforeach
    </table>
    </div>

    {!! $logins->appends(Request::except('page'))->links() !!}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript">
      $('#login_date').datetimepicker({
        format: 'YYYY-MM-DD'
      });
    </script>
@endsection
