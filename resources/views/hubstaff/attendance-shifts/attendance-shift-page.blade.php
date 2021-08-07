@extends('layouts.app')

@section('content')
<h2 class="text-center">Get Users from Hubstaff</h2>
<div class="container">
@if(Session::has('message'))
  <div class="alert alert-success alert-block" >
    <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong>{{ Session::get('message') }}</strong>
  </div>
@endif

  <div class="row">
    <div class="col-md-5">
      <div class="well">
         {!! Form::open(['route' => 'attendance.shifts-post']) !!}
          <div>
            <h3 class="text-center">Get Attendance Shifts of Organization</h3>
             
             <div class="form-group">
                <input class="form-control" name="page_start_id" id="page_start_id" type="text" placeholder="Page Start Id" required>
             </div>
            
             <div class="form-group">
               <input class="form-control" name="page_limit" id="id" type="text" placeholder="Page limit" required>
             </div>

             <div class="form-group">
               <input type="date" name="start_time" id="start_time" class="form-control">
             </div>

             <div class="form-group">
               <input type="date" name="stop_time" id="stop_time" class="form-control">
             </div>

              <div class="form-group">
               <input class="form-control" name="organization_id" id="organization_id" type="text" placeholder="Organization Id" required>
              </div>
            
             <br/>
             <div class="text-center">
              <button class="btn btn-info btn-lg" type="submit">Get Attendance Shifts</button>
             </div>
          </div>
         {!! Form::close() !!}
       </div>
    </div>
   
</div>
@endsection