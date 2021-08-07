@extends('layouts.app')
@section('content')
	<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Add New Task</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('task.index') }}"> Back</a>
               
            </div>
        </div>
    </div>
       @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
   		 @endif
     {{--   @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
--}}
     <form action="{{ route('task.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
         <div class="row">
             <div class="col-xs-12 col-sm-8 col-sm-offset-4">
                <div class="form-group">
                    <strong>Task Name:</strong>
                    <input type="text" class="form-control" name="name" placeholder="Task Name" value="{{old('name')}}"/>
                    @if ($errors->has('name'))
                        <div class="alert alert-danger">{{$errors->first('name')}}</div>
                    @endif
                </div>
            </div>

             <div class="col-xs-12 col-sm-8 col-sm-offset-4">
                <div class="form-group">
                    <strong>Task Details:</strong>
                    <input type="text" class="form-control" name="details" placeholder="Task Details" value="{{old('details')}}"/>
                    @if ($errors->has('details'))
                        <div class="alert alert-danger">{{$errors->first('details')}}</div>
                    @endif
                </div>
            </div>
            <div class="col-xs-12 col-sm-8 col-sm-offset-4">
                <div class="form-group">
                    <strong>Type:</strong>
                   <Select name="type" class="form-control" id="tasktype"> 
                    @foreach( $data['task'] as $key => $value)                          
                              <option value="{{$value}}">{{$key}}</option>                           
                          @endforeach
                    </Select> 
                    @if ($errors->has('type'))
                        <div class="alert alert-danger">{{$errors->first('type')}}</div>
                    @endif
                </div>
            </div>

              <div class="col-xs-12 col-sm-8 col-sm-offset-4">
                <div class="form-group">
                    <strong>Related (Leads, Contact, Products etc):</strong>
                    <input type="text" class="form-control" name="related" placeholder="Related (Leads, Contact, Products etc)" value="{{old('related')}}"/>
                    @if ($errors->has('related'))
                        <div class="alert alert-danger">{{$errors->first('related')}}</div>
                    @endif
                </div>
            </div>

              <div class="col-xs-12 col-sm-8 col-sm-offset-4">
                <div class="form-group">
                    <strong>Assigned To:</strong>
                    <Select name="assigned_user" class="form-control"> 
                                   
                            @foreach($data['users'] as $user)                          
                              <option value="{{$user['id']}}">{{$user['name']}}</option>                           
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
                    <textarea  class="form-control" name="remark" placeholder="Remarks">{{old('remark')}} </textarea>
                   
                    @if ($errors->has('remark'))
                        <div class="alert alert-danger">{{$errors->first('remark')}}</div>
                    @endif
                </div>
            </div>
           
           <div class="col-xs-12 col-sm-8 col-sm-offset-4 minutes" style="display: none">
                <div class="form-group">
                    <strong>Minutes:</strong>
                    <textarea  class="form-control" name="minutes" placeholder="Remarks">{{old('minutes')}} </textarea>
                   
                    @if ($errors->has('minutes'))
                        <div class="alert alert-danger">{{$errors->first('minutes')}}</div>
                    @endif
                </div>
            </div>

            
              <div class="col-xs-12 col-sm-8 col-sm-offset-4">
                <div class="form-group">
                    <strong>Comments:</strong>
                    <textarea  class="form-control" name="comments" placeholder="comments">{{old('comments')}} </textarea>
                   
                    @if ($errors->has('comments'))
                        <div class="alert alert-danger">{{$errors->first('comments')}}</div>
                    @endif
                </div>
            </div>

             <div class="col-xs-12 col-sm-8 col-sm-offset-4">
                <div class="form-group">
                    <strong>Status:</strong>
                    <Select name="status" class="form-control">
                         @foreach($data['status'] as $key => $value)
                          <option value="{{$value}}">{{$key}}</option>                           
                          @endforeach
                    </Select>      
                    
                    <input type="hidden" class="form-control" name="userid" placeholder="status" value=""/>
                    @if ($errors->has('status'))
                        <div class="alert alert-danger">{{$errors->first('status')}}</div>
                    @endif
                </div>
            </div>
             <div class="col-xs-12 col-sm-8 col-sm-offset-4 text-center">
             
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>    		
    </form>    	
@endsection