@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Competitor Pages</h2>
        </div>
        <div class="col-md-12">
            @if(Session::has('message'))
                <script>
                    alert("{{Session::get('message')}}")
                </script>
            @endif
            <form method="post" action="{{ action('CompetitorPageController@store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" placeholder="sololuxuryindia (without @)" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" placeholder="Username" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="platform_id">Platform ID (optional)</label>
                            <input type="text" name="platform_id" id="platform_id" placeholder="Platform ID" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="platform_id">Platform</label>
                            <select name="platform" id="platform" class="form-control">
                                <option value="instagram">Instagram</option>
                                <option value="facebook">Facebook</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Add?</label>
                            <button class="btn-block btn btn-success">Add Competitor</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <table class="table-striped table-bordered table table-sm">
                <tr>
                    <th>S.N</th>
                    <th>Platform</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>All Followers</th>
                    <th>Action</th>
                </tr>
                @foreach($pages as $key=>$page)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{$page->name}}</td>
                        <td>{{$page->platform}}</td>
                        <td><a href="https://instagram.com/{{$page->username}}">{{$page->username}}</a></td>
                        <td>
                            <a href="{{ action('CompetitorPageController@edit', $page->id) }}">Show All</a>
                        </td>
                        <td>
                            <a href="{{ action('CompetitorPageController@show',$page->id) }}">
                                Show Data
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/media-card.css') }}">
@endsection

@section('scripts')
    <script>
        var cid = null;
        $(function(){
            $('.show-details').on('click',function() {
                let id = $(this).attr('data-pid');
                $('.reveal-'+id).slideToggle('slow');
            });

            $('.card-reveal .close').on('click',function(){
                $(this).parent().slideToggle('slow');
            });

        });
    </script>
@endsection