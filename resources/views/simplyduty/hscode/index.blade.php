@extends('layouts.app')

@section('favicon' , 'password-manager.png')

@section('title', 'Hs Code')


@section('content')

<div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Hs Code</h2>
            <div class="pull-right">
                <button type="button" class="btn btn-image" onclick="resetSearch()"><img src="/images/resend2.png"/></button>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="table-responsive mt-3">
        <table class="table table-bordered" id="category-table">
            <thead>
            <tr>
                <th style="width:10%">Code</th>
                <th style="width:60%">Description</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
            <tr>
            <th><input type="text" id="code" class="search form-control"></th>    
            <th><input type="text" id="description" class="search form-control"></th>
            <th></th>
            <th></th>
          </tr>
            </thead>
             {!! $categories->appends(Request::except('page'))->links() !!}
            <tbody>
            @include('simplyduty.category.partials.data')
            </tbody>
        </table>
    </div>
    {!! $categories->appends(Request::except('page'))->links() !!}

@endsection
@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
 $(document).ready(function() {
        src = "{{ route('simplyduty.hscode.index') }}";
        $(".search").autocomplete({
        source: function(request, response) {
            code = $('#code').val();
            description = $('#description').val();
            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    code : code,
                    description : description,
                },
                beforeSend: function() {
                       $("#loading-image").show();
                },
            
            }).done(function (data) {
                 $("#loading-image").hide();
                console.log(data);
                $("#category-table tbody").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
                
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
        },
        minLength: 1,
       
        });
       });  

       function resetSearch() {
           src = "{{ route('simplyduty.hscode.index') }}";
            reset = '';
            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    reset : reset,
                },
                beforeSend: function() {
                        $("#loading-image").show();
                },
            
            }).done(function (data) {
                    $("#loading-image").hide();
                console.log(data);
                $("#category-table tbody").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
                
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
           
       }
        </script>
@endsection
