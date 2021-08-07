@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Priority</h2>
        </div>
        <div class="col-md-12">
            @if(Session::has('message'))
                <script>
                    alert("{{Session::get('message')}}")
                </script>
            @endif
            <form method="post" action="{{ route('priorty.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="name">Priorty Keyword</label>
                            <input type="text" name="name" id="name" placeholder="Enter Priorty Keyword" class="form-control" required>
                        </div>
                    </div>
                     <div class="col-md-2">
                        <div class="form-group">
                            <label>Enter Level</label>
                            <select name="level" class="form-control" required>
                                <option value="">Select Level</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-group">
                            <label>Enter Description</label>
                          <input type="text" name="description" placeholder="Enter description" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Add?</label>
                            <button class="btn-block btn btn-default">Add Priorty</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <table class="table-striped table-bordered table table-sm">
                <tr>
                    <th>S.N</th>
                    <th>Keyword</th>
                    <th>Description</th>
                    <th>Level</th>
                    <th>Actions</th>
                </tr>
               @foreach($priorities as $key=>$priority)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $priority->keyword }}</td>
                        <td>{{ $priority->description }}</td>
                        <td>{{ $priority->level }}</td>
                        <td>
                            <button onclick="edit({{ $priority->id  }})" class="btn btn-secondary btn-sm">Edit</button>
                          <!--   <button onclick="deleteID({{ $priority->id  }})" class="btn btn-secondary btn-sm">Delete</button> -->
                            @if(auth()->user()->isAdmin())
                            {!! Form::open(['method' => 'POST','route' => ['priorty.destroy', $priority->id],'style'=>'display:inline']) !!}
                            <input type="hidden" name="id" value="{{ $priority->id  }}">
                            <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>
                            {!! Form::close() !!}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
              {!! $priorities->appends(Request::except('page'))->links() !!}
        </div>
    </div>

    @foreach($priorities as $priority)
    <div id="editModal{{$priority->id}}" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form action="{{ route('priorty.update') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h4 class="modal-title">Edit Priority</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <strong>Keyword:</strong>
                            <input type="text" name="name" class="form-control" value="{{ $priority->keyword }}">

                            @if ($errors->has('keyword'))
                                <div class="alert alert-danger">{{$errors->first('keyword')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Description:</strong>
                            <input type="text" name="description" class="form-control" value="{{ $priority->description }}" required>

                            @if ($errors->has('description'))
                                <div class="alert alert-danger">{{$errors->first('description')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Level:</strong>
                            <input type="text" name="level" class="form-control" value="{{ $priority->level }}" required>

                            @if ($errors->has('level'))
                                <div class="alert alert-danger">{{$errors->first('level')}}</div>
                            @endif
                        </div>
                            <input type="hidden" name="id" value="{{ $priority->id }}"/>
                       
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
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/media-card.css') }}">
@endsection

@section('scripts')
   <script type="text/javascript">
        function edit(password_id) {
        $("#editModal"+ password_id +"" ).modal('show');
    }
   </script>
   <!--  <script type="text/javascript">
        function deleteID(password_id) {
            if (confirm("Are you sure?")) {
                
            }
            return false;
    }
   </script> -->
@endsection