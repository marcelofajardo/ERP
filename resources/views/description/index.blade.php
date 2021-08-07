@extends('layouts.app')
@section('title')
    descriptions 
@endsection
@section('content')
<style type="text/css">
    .form-inline label {
        display: inline-block;
    }
    .form-control {
        height: 25px !important;
    }
    .small-field { 
        margin-bottom: 0px;
     }
     .small-field-btn {
        padding: 0px 13px;
     }   
</style>
<div class="row">
    <div class="col-md-12">
        <h2 class="page-heading">Descriptions ({{$descriptions->total()}})</h2>
    </div>
    <div class="col-md-6 mt-5">
        {!! Form::open(["class" => "form-inline" , "route" => 'descriptions.store',"method" => "POST"]) !!}    
          <div class="form-group">
            <label for="name">Description:</label>
            <input type="text" name="keyword" class="form-control" id="keyword" placeholder="Enter Name" value="{{ old('keyword') ? old('keyword') : request('keyword') }}"/>
          </div>
          <div class="form-group ml-2">
            <label for="replace_with">Change Description:</label>
            <input type="text" name="replace_with" class="form-control" placeholder="Enter Erp Name" value="{{ old('replace_with') ? old('replace_with') : request('replace_with') }}" id="replace_with">
          </div>
          <button type="submit" class="btn btn-default ml-2 small-field-btn">Add</button>
        </form>
    </div>

    <div class="col-md-12 mt-5">
        <table class="table table-bordered">
            <tr>
                <th width="10%">ID</th>
                <th width="30%">Description</th>
                <th width="35%">Change Description</th>
                <th width="20%">Action</th>
            </tr>
            @foreach($descriptions as $key=>$description)
                <tr>
                    <td>{{ $description->id }} </td>

                    <td>{{ $description->keyword }}</td>
                    <td>{{ $description->replace_with }}</td>
                    <td>
                        <form action="descriptions/delete" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="description_id" value="{{ $description->id }}">
                            <button class="btn btn-secondary small-field-btn" onclick="return confirm('Are you sure you want to delete ?')">
                                <i class="fa fa-trash" type="submit"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        {{ $descriptions->appends(request()->except('page'))->links() }}
    </div>
</div>
<div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
          50% 50% no-repeat;display:none;">
</div>
<div class="common-modal modal show-listing-exe-records" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
    </div>  
</div>

@endsection