@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <h2 class="page-heading">Categories With Messages</h2>
    </div>
    <div class="col-md-12">
        @if(Session::has('message'))
            <div class="alert alert-info">
                {{ Session::get('message') }}
            </div>
        @endif
    </div>
    <div class= "col-md-12">
        <form action="{{ action('CustomerCategoryController@update', $customerCategory->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-secondary" href="{{ action('CustomerCategoryController@index') }}">Go Back</a> &nbsp; <strong>Edit: {{ $customerCategory->name }}</strong>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Category Name <span class="text-danger">*</span></label>
                                <input value="{{ $customerCategory->name }}" class="form-control" type="text" name="name" id="name" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="message">Default Message for this category</label>
                                <textarea rows="1" class="form-control" name="message" id="message" placeholder="" required>{{ $customerCategory->message }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <button class="btn btn-default mt-2">Update Category</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://rawgit.com/jackmoore/autosize/master/dist/autosize.min.js"></script>
    <script>
        autosize(document.getElementById("message"));
    </script>
@endsection