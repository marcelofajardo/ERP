@extends('layouts.app')

@section('large_content')
<div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Instagram Users ({{ $users->total() }})</h2>
            <form method="post" action="/instagram/users/save">
                @csrf
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-2">
                            <label>Link</label>
                            <input type="text" name="userlink" class="form-control" placeholder="Enter Link" style="padding-right: 10px">
                        </div>
                        <div class="col-md-2">
                            <label>Account</label>    
                            <?php echo Form::select("account",\App\Marketing\InstagramConfig::where('provider','instagram')->pluck('username','id'),null, ["class" => "form-control"]); ?>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-secondary mt-5">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

     

        <div class="col-md-12">
            <table class="table table-striped" id="table" style="width: 100%">
                <thead>
                    <tr>
                        <th>User Id</th>
                        <th>Username</th>
                        <th>Image</th>
                        <th>Bio</th>
                        <th>Following</th>
                        <th>Followers</th>
                        <th>Post</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $user)
                         <tr>
                                <td>
                                    <a href="/instagram/users/grid/{{ $user->user_id }}" target="_blank">{{ $user->user_id }}</a>
                                </td>
                                <td>
                                    {{ $user->username }}
                                </td>
                                <td>
                                    <img src="{{ $user->image_url }}" width="100" height="100">
                                </td>
                                <td>
                                    {{ $user->bio }}
                                </td>
                                <td>
                                    {{ $user->following }}
                                </td>
                                <td>
                                    {{ $user->followers }}
                                </td>
                                <td>
                                    {{ $user->posts }}
                                </td>
                                <td>
                                    <a href="/instagram/users/{{ $user->id }}">Get Posts</a>
                                    
                                </td>
                            </tr>
                    @endforeach
                </tbody>
                {!! $users->links() !!}
            </table>
        </div>
    </div>
@endsection



@section('scripts')
    
@endsection