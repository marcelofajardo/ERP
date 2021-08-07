@extends('layouts.app')

@section('favicon' , 'password-manager.png')

@section('title', 'SimplyDuty Category')


@section('content')

<div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">SimplyDuty Country</h2>
            <div class="pull-right">
                <button type="button" class="btn btn-secondary" onclick="getCategoryData()">Load from SimplyDuty</button>
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
                <th style="width:10%">Country Code</th>
                <th style="width:60%">Country</th>
                <th>Default Duty</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
            <tr>
            <th><input type="text" id="code" class="search form-control"></th>    
            <th><input type="text" id="country" class="search form-control"></th>
            <th></th>
            <th></th>
          </tr>
            </thead>
             {!! $countries->appends(Request::except('page'))->links() !!}
            <tbody>
            @include('simplyduty.country.partials.data')
            </tbody>
        </table>
    </div>
    {!! $countries->appends(Request::except('page'))->links() !!}

@endsection
@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
 $(document).ready(function() {
        $(document).on('focusout','.dutyinput',function(){
            let id = $(this).data('id');
            let duty = $(this).val();

            if (duty != '0' && duty != 0 && duty != null && duty != undefined){
                $.ajax({
                    url:'{{route("simplyduty.country.updateduty")}}',
                    dataType:'json',
                    data:{
                        id: id,
                        duty : duty
                    },
                    success:function(result){
                        // console.log(result);
                    },
                    error:function(exx){
                        alert('Something went wrong!')
                        window.location.reload();
                    }
                })
            }
        });
        src = "{{ route('simplyduty.country.index') }}";
        $(".search").autocomplete({
        source: function(request, response) {
            code = $('#code').val();
            country = $('#country').val();
            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    code : code,
                    country : country,
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

       function getCategoryData() {
           src = "{{ route('simplyduty.country.update') }}"
           $.ajax({
                url: src,
                dataType: "json",
                data: {
                    
                },
                beforeSend: function() {
                       
                },
            
            }).done(function (data) {
                alert('Category Updated');
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
           
       }

       function resetSearch() {
           src = "{{ route('simplyduty.country.index') }}";
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
