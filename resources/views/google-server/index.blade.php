@extends('layouts.app')

@section('title', 'Google Server List')

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Google Server List</h2>
            <div class="pull-right">
                <button type="button" class="btn btn-secondary ml-3" data-toggle="modal" data-target="#googleServerCreateModal">+</button>
            </div>
        </div>
    </div>

    @include('partials.flash_messages')

    <div class="mt-3 col-md-12">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Key</th>
            <th>Description</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
          @foreach ($googleServer as $server)
            <tr>
              <td>{{ $server->id }}</td>
              <td>
                {{ $server->name }}
              </td>
              <td>
                {{ $server->key }}
              </td>
              <td>
                {{ $server->description }}
              </td>
              <td>
                  <button type="button" class="btn btn-image edit-google-server d-inline" data-toggle="modal" data-target="#googleServerEditModal" data-google-server="{{ json_encode($server) }}"><img src="/images/edit.png" /></button>
                  {!! Form::open(['method' => 'DELETE','route' => ['google-server.destroy', $server->id],'style'=>'display:inline']) !!}
                    <button type="submit" class="btn btn-image d-inline"><img src="/images/delete.png" /></button>
                  {!! Form::close() !!}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {!! $googleServer->appends(Request::except('page'))->links() !!}

<div id="googleServerCreateModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('google-server.store') }}" method="POST">
        @csrf

        <div class="modal-header">
          <h4 class="modal-title">Store a Google Server</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Name:</strong>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>

            @if ($errors->has('name'))
              <div class="alert alert-danger">{{$errors->first('name')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Key:</strong>
            <input type="text" name="key" class="form-control" value="{{ old('key') }}">

            @if ($errors->has('key'))
              <div class="alert alert-danger">{{$errors->first('key')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Description:</strong>
            <textarea class="form-control" name="description">{{ old('description') }} </textarea>
            @if ($errors->has('description'))
              <div class="alert alert-danger">{{$errors->first('description')}}</div>
            @endif
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Add</button>
        </div>
      </form>
    </div>

  </div>
</div>

<div id="googleServerEditModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h4 class="modal-title">Update a Google Server</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Name:</strong>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>

            @if ($errors->has('name'))
              <div class="alert alert-danger">{{$errors->first('name')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Key:</strong>
            <input type="text" name="key" class="form-control" value="{{ old('key') }}">

            @if ($errors->has('key'))
              <div class="alert alert-danger">{{$errors->first('key')}}</div>
            @endif
          </div>

          <div class="form-group">
            <strong>Description:</strong>
            <textarea class="form-control" name="description">{{ old('description') }} </textarea>
            @if ($errors->has('description'))
              <div class="alert alert-danger">{{$errors->first('description')}}</div>
            @endif
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

@endsection

@section('scripts')
  
  <script type="text/javascript">
    $(document).on('click', '.edit-google-server', function() {
      var googleServer = $(this).data('google-server');
      var url = "{{ route('google-server.index') }}/" + googleServer.id;

      $('#googleServerEditModal form').attr('action', url);
      $('#googleServerEditModal').find('input[name="name"]').val(googleServer.name);
      $('#googleServerEditModal').find('input[name="key"]').val(googleServer.key);
      $('#googleServerEditModal').find('textarea[name="description"]').val(googleServer.description);
    });
  </script>
@endsection
