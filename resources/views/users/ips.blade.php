@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">User Login IPs</h2>
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
            <th>Date</th>
            <th>User Email</th>
            <th>IP</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        @foreach ($user_ips as $user_ip)
            <tr>
              <td>{{ $user_ip->created_at }}</td>
              <td> {{ $user_ip->email }}</td>
              <td>{{ $user_ip->ip }}</td>
              <td>@if($user_ip->is_active) Active  @else Inactive @endif</td>
              <td>@if($user_ip->is_active) <button type="button" class="btn btn-warning ml-3 statusChange" data-status="Inactive" data-id="{{$user_ip->id}}">Inactive</button>  @else <button type="button" class="btn btn-success ml-3 statusChange" data-status="Active" data-id="{{$user_ip->id}}">Active</button> @endif</td>
            </tr>
        @endforeach
    </table>
    </div>

    {{-- {!! $logins->appends(Request::except('page'))->links() !!} --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript">
      $('#login_date').datetimepicker({
        format: 'YYYY-MM-DD'
      });
      $(document).on('click','.statusChange',function(event){
        event.preventDefault();
        $.ajax({
           type: "post",
           url: '{{ action("UserController@statusChange") }}',
           data: {
             _token: "{{ csrf_token() }}",
             status: $(this).attr('data-status'),
             id: $(this).attr('data-id')
           },
           beforeSend: function() {
             $(this).attr('disabled', true);
             // $(element).text('Approving...');
           }
        }).done(function( data ) {
          toastr["success"]("Status updated!", "Message")
          window.location.reload();
        }).fail(function(response) {
           alert(response.responseJSON.message);
           toastr["error"](error.responseJSON.message);
        });
      });
    </script>
@endsection
