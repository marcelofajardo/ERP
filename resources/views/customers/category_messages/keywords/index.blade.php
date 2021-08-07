@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <h2 class="page-heading">Keyword-Category Management</h2>
    </div>
    <div class="col-md-12">
        @if(Session::has('message'))
            <div class="alert alert-info">
                {{ Session::get('message') }}
            </div>
        @endif
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Assign Keyword To Category/Lead Status/Order Status</strong>
            </div>
            <div class="panel-body">
                <form action="{{ action('KeywordToCategoryController@store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="keyword">Keyword/Message</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select Category</option>
                                    <optgroup label="Order Statuses">
                                        @foreach($orderStatuses as $key=>$orderStatus)
                                            <option value="order_{{$key}}">{{$orderStatus}}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Lead Statuses">
                                        @foreach($leadStatuses as $key=>$leadStatus)
                                            <option value="lead_{{$key}}">{{$leadStatus}}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Categories">
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <div class="form-group">
                                <button class="btn btn-secondary">Add Keyword & Customer Reference</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        @if($keywords->count())
            <table class="table-bordered table table-striped">
                <tr>
                    <th>S.N</th>
                    <th>Keyword</th>
                    <th>Category Type</th>
                    <th>Category Value</th>
                    <th>Action</th>
                </tr>
                @foreach($keywords as $key=>$keyword)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $keyword->keyword_value }}</td>
                        <td>
                            @if($keyword->category_type=='category')
                                <strong>Customer Category</strong>
                            @else
                                <strong>{{ $keyword->category_type }}</strong>
                            @endif
                        </td>
                        <td>
                            @if($keyword->category_type == 'lead')
                                {{ $keyword->leadStatus() }}
                            @elseif($keyword->category_type == 'order')
                                {{ $keyword->model_id }}
                            @else
                                {{ $keyword->category ? $keyword->category->name : 'N/A' }}
                            @endif
                        </td>
                        <td>
                            <form method="post" action="{{ action('KeywordToCategoryController@destroy', $keyword->id) }}">
                                <button class="btn btn-image btn-xs">
                                    @csrf
                                    @method('DELETE')
                                    <img src="{{ asset('images/delete.png') }}" alt="Delete Keyword">
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <div class="alert alert-info">
                <h3>No Keywords!</h3>
                <p>There are no keywords saved yet. Please save it using the form above!</p>
            </div>
        @endif
    </div>

@endsection