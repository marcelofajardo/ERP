@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
@endsection

@section('large_content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Quick Reply Comments</h2>
        </div>

        <div class="p-5 col-md-12" style="background: #dddddd">
            <form action="{{ action('InstagramAutoCommentsController@store') }}" method="post">
                <input type="hidden" name="gender" value="all">
                <input type="hidden" name="source" value="default">
                @csrf
                <divr class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" name="text" id="text" placeholder="Quick reply.." class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <select name="country" id="country" class="form-control">
                                <option value="">All</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->region }}">{{$country->region}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <button class="btn btn-info">Save It!</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="options[]" multiple id="options" style="height: 200px;">
                            <option value="BAGS">BAGS</option>
                            <option value="SHOES">SHOES</option>
                            <option value="COMMOM">COMMON</option>
                            @foreach(\App\Brand::all() as $brand)
                                <option value="{{ strtoupper($brand->name) }}">{{ strtoupper($brand->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </divr>
            </form>
        </div>

        <div class="col-md-12">
            <form action="{{ action('InstagramAutoCommentsController@show', 'delete') }}">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>S.N</th>
                        <th style="width: 500px;">Text</th>
                        <th>Country</th>
                        <th>Options</th>
                        <th>Use Count</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    @foreach($comments as $key=>$reply)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $reply->comment }}</td>
                            <td>{{ $reply->country ?? 'All' }}</td>
                            <td>
                                @if (is_array($reply->options))
                                    @foreach($reply->options as $opt)
                                        <li>{{$opt}}</li>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $reply->use_count ?? 0 }}</td>
                            <td>{{ $reply->created_at->format('Y-m-d') }}</td>
                            <td>
                                <input value="{{$reply->id}}" type="checkbox" name="comments[]" id="comments">
                                <a href="{{ action('InstagramAutoCommentsController@edit', $reply->id) }}" class="btn btn-info">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <button class="btn btn-sm btn-danger">
                                Delete
                            </button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

@endsection

@section('scripts')

@endsection
