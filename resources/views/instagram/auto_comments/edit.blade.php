@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
@endsection

@section('large_content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Edit Comment</h2>
        </div>

        <div class="col-md-12">
            <form action="{{ action('InstagramAutoCommentsController@update', $comment->id) }}" method="post">
                @csrf
                @method('PUT')
                <divr class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input value="{{$comment->comment}}" type="text" name="text" id="text" placeholder="Quick reply.." class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input value="{{$comment->source}}" type="text" name="source" id="source" placeholder="Source.." class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="options[]" multiple id="options" style="height: 200px;">
                            <option {{ in_array('BAGS', is_array($comment->options) ? $comment->options : []) ? 'selected' : '' }} value="BAGS">BAGS</option>
                            <option {{ in_array('SHOES', is_array($comment->options) ? $comment->options : []) ? 'selected' : '' }} value="SHOES">SHOES</option>
                            <option {{ in_array('COMMON', is_array($comment->options) ? $comment->options : []) ? 'selected' : '' }} value="COMMOM">COMMON</option>
                            @foreach(\App\Brand::all() as $brand)
                                <option {{ in_array(strtoupper($brand->name), is_array($comment->options) ? $comment->options : []) ? 'selected' : '' }} value="{{ strtoupper($brand->name) }}">{{ strtoupper($brand->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button class="btn btn-info">Update It!</button>
                        </div>
                    </div>
                </divr>
            </form>
        </div>

    </div>

@endsection

@section('scripts')

@endsection
