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
        <form action="{{ action('CustomerCategoryController@store') }}" method="post">
            @csrf
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Add New Category</strong>
                </div>
                <div class="panel-body">
                    <p>Please enter the details asked below to add a new category.</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Category Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" id="name" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="message">Default Message for this category</label>
                                <textarea rows="1" class="form-control" name="message" id="message" placeholder="" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <button class="btn btn-default mt-2">Add Category</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12">
        @if($categories->count())
            <table class="table table-striped table-bordered">
                <tr>
                    <th>S.N</th>
                    <th>Category</th>
                    <th>Message</th>
                    <th>Action</th>
                </tr>
                @foreach($categories as $key=>$category)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{!! str_replace("\n", '<br>', $category->message ?? 'Not Set') !!}</td>
                        <td>
                            <form method="post" action="{{ action('CustomerCategoryController@destroy', $category->id) }}">
                                @csrf
                                @method('DELETE')
                                <a href="{{ action('CustomerCategoryController@edit', $category->id) }}" class="btn btn-image">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit Category">
                                </a>
                                <button class="btn btn-image">
                                    <img src="{{ asset('images/delete.png') }}" alt="Delete Category">
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <div class="alert alert-info">
                <h3>No Categories</h3>
                <p>There are no categories created yet! Please create one from the form above!</p>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script src="https://rawgit.com/jackmoore/autosize/master/dist/autosize.min.js"></script>
    <script>
        autosize(document.getElementById("message"));
    </script>
@endsection