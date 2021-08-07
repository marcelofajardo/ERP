@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
@endsection

@section('large_content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Reviews</h2>
        </div>

        <div class="col-md-12">
            <form action="{{ action('QuickReplyController@store') }}" method="post">
                @csrf
                <divr class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" name="text" id="text" placeholder="Quick reply.." class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button class="btn btn-info">Save It!</button>
                        </div>
                    </div>
                </divr>
            </form>
        </div>

        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>S.N</th>
                    <th>Text</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
                @foreach($replies as $key=>$reply)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $reply->text }}</td>
                        <td>{{ $reply->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a class="btn btn-danger btn-sm" href="{{ action('QuickReplyController@show', $reply->id) }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

@endsection

@section('scripts')

@endsection
