@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Live Chat Setting</h2>
            <div class="pull-left">
                <h3>Add User</h3>
                <form class="form-inline" action="{{ route('livechat.save') }}" method="POST">
                    
                    <div class="form-group mr-3">
                              
                              <select class="form-control select-multiple2" name="users[]" data-placeholder="Select Users.." multiple>
                                <optgroup label="Users">
                                  @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                  @endforeach
                              </optgroup>
                              </select>
                            </div>
                    <div class="form-group mr-3">
                              <input name="username" type="text" class="form-control" placeholder="User Name For Live Chat" value="@if(isset($setting->username) && $setting->username != '') {{ $setting->username  }}  @endif">
                            </div>
                 <div class="form-group mr-3">
                              <input name="key" type="text" class="form-control"  placeholder="Key For Live Chat" value="@if(isset($setting->key) && $setting->key != '') {{ $setting->key  }}  @endif">
                            </div>           
                    <button type="submit" class="btn btn-info"><i class="fa fa-filter"></i> Submit</button>
                </form>
            </div>
            <div class="pull-right">
             </div>
        </div>
    </div>

     <div class="table-responsive mt-3">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Users</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
            @foreach ($liveChatUsers as $liveChatUser)
                <tr>
                <th>{{ $liveChatUser->id }}</th>    
                <th>{{  $liveChatUser->user->name }}</th>
                <th><button onclick="removeFromList({{ $liveChatUser->id }})">Remove</button></th>
          </tr>
            @endforeach
        </tbody>
      </table>
     </div>

    

    

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script>

    $(document).ready(function() {
       $(".select-multiple").multiselect();
       $(".select-multiple2").select2();
    });

    function removeFromList(id){
         $.ajax({
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('livechat.remove') }}',
                data: {
                    id: id,
                },
            }).done(response => {
                location.reload();
            });

    }
    </script>
 @endsection   