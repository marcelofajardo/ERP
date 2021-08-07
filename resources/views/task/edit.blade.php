@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Task</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('task.index') }}"> Back</a>
            </div>
        </div>
    </div>

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


    <form action="{{ route('task.update',$task->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
             <div class="col-xs-12 col-sm-8 col-sm-offset-4">
                <div class="form-group">
                    <strong>Task Name:</strong>
                    <input type="text" class="form-control" name="name" placeholder="Task Name" value="{{$task->name}}"/>
                    
                </div>
            </div>

             <div class="col-xs-12 col-sm-8 col-sm-offset-4">
                <div class="form-group">
                    <strong>Task Details:</strong>
                    <input type="text" class="form-control" name="details" placeholder="Task Details" value="{{$task->details}}"/>
                    
                </div>
            </div>
            <div class="col-xs-12 col-sm-8 col-sm-offset-4">
                <div class="form-group">
                    <strong>Type:</strong>
                   <Select name="type" class="form-control" id="tasktype"> 
                    @foreach( $task['task'] as $key => $value)                          
                              <option value="{{$value}}" {{$value == $task->type ? 'Selected=Selected':''}}>{{$key}}</option>                           
                          @endforeach
                    </Select>                   
                </div>
            </div>

             <div class="col-xs-12 col-sm-8 col-sm-offset-4">
                <div class="form-group">
                    <strong>Related (Leads, Contact, Products etc):</strong>
                    <input type="text" class="form-control" name="related" placeholder="Related (Leads, Contact, Products etc)" value="{{$task->related}}"/>
                    @if ($errors->has('related'))
                        <div class="alert alert-danger">{{$errors->first('related')}}</div>
                    @endif
                </div>
            </div>
            <div class="col-xs-12 col-sm-8 col-sm-offset-4">
                <div class="form-group">
                    <strong>Assigned To:</strong>
                    <Select name="assigned_user" class="form-control"> 
                                   
                          @foreach($task['user'] as $user)                          
                              <option value="{{$user['id']}}" {{$user['id'] == $task->assigned_user ? 'Selected=Selected':''}}>{{$user['name']}}</option>                           
                          @endforeach
                    </Select>    
                    
                    @if ($errors->has('assigned_user'))
                        <div class="alert alert-danger">{{$errors->first('assigned_user')}}</div>
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-sm-8 col-sm-offset-4">
                <div class="form-group">
                    <strong>Remarks:</strong>
                    <textarea  class="form-control" name="remark" placeholder="Remarks">{{$task->remark}} </textarea>
                </div>
            </div>

             <div class="col-xs-12 col-sm-8 col-sm-offset-4 minutes" style="display: none">
                <div class="form-group">
                    <strong>Minutes:</strong>
                    <textarea  class="form-control" name="minutes" placeholder="Remarks">{{$task->minutes}} </textarea>
                </div>
            </div>
              <div class="col-xs-12 col-sm-8 col-sm-offset-4">
                <div class="form-group">
                    <strong>Comments:</strong>
                          <textarea  class="form-control" name="comments" placeholder="comments">{{$task->comments}} </textarea>
                   
                   
                </div>
            </div>

             <div class="col-xs-12 col-sm-8 col-sm-offset-4">
                <div class="form-group">
                    <strong>status:</strong>
                    <Select name="status" class="form-control">
                         @foreach($task['status'] as $key => $value)
                          <option value="{{$value}}" {{$value == $task->status ? 'Selected=Selected':''}}>{{$key}}</option>                           
                          @endforeach
                    </Select>      
                    
                    <input type="hidden" class="form-control" name="userid" placeholder="status" value="{{$task->userid}}"/>
                    
                </div>
            </div>
             <div class="col-xs-12 col-sm-8 col-sm-offset-4 text-center">
             
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>          
 </form>       


@endsection    