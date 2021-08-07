@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endsection
@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> User's Logs</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('users.index') }}"> Back</a>
            </div>
        </div>
    </div>
    <div class="row input-daterange">
                <div class="col-md-3">
                    <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
                </div>
                <div class="col-md-3">
                    <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
                </div>
                <div class="col-md-3">
                    <select class="form-control selectpicker" data-live-search="true" id="username">
                      @php
                      $users = \App\User::select('name')->get();
                      @endphp
                      <option value="">Select User</option>
                      @foreach($users as $user)
                      <option value="{{ $user->name }}">{{ $user->name }}</option>
                      @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                    <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
                </div>
            </div>

    <table class="table table-bordered" id="userlogs-table">
        <thead>
        <tr>
            <th>Id</th>
            <th>URL</th>
            <th>User</th>
            <th>Created At</th>
            <th>Updated At</th>
        </tr>
        </thead>
    </table>




@endsection

@section('scripts')
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    
<script>
$(document).ready(function(){
 $('.input-daterange').datepicker({
  todayBtn:'linked',
  format:'yyyy-mm-dd',
  autoclose:true
 });

 load_data();

 function load_data(from_date = '', to_date = '',id = '')
 {
  $('#userlogs-table').DataTable({
   processing: true,
   serverSide: true,
   ajax: {
    url:'{{ route("userlogs.datatable") }}',
    data:{from_date:from_date, to_date:to_date , id:id}
   },
   columns: [
        {
             data:'id',
             name:'id'
        },
        {
             data:'url',
             name:'url'
        },
        {
             data:'user_name',
             name:'user_name'
        },
        {
             data:'created_at',
             name:'created_at'
        },
        {
             data:'updated_at',
             name:'updated_at'
    }
   ]
  });
 }

 $('#filter').click(function(){
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var username = $('#username').val(); 
  if(from_date != '' &&  to_date != '' || username != '')
      {
       $('#userlogs-table').DataTable().destroy();
       load_data(from_date, to_date, username);
      }
  else
      {
       alert('Please Select To Filter');
      }
  });

  $('#refresh').click(function(){
  $('#from_date').val('');
  $('#to_date').val('');
  $('#userlogs-table').DataTable().destroy();
  load_data();
 });

  $('#userlogs-table').on('draw.dt', function () {
    
});

});
</script>
@endsection