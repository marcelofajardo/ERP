@extends('layouts.app')


@section('title', 'Facebook Posts')

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>

<div class="row" id="common-page-layout">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Facebook Posts ({{$totalPosts}})<span class="count-text"></span></h2>
        <div class="pull-right">
        <a class="btn btn-secondary create-post" style="color:white;">Create</a>
    </div>
    </div>
   
    <br>
    <div class="col-lg-12 margin-tb">
        <div class="row" style="margin:0px;">
            <div class="col">
                <div class="h" style="margin-bottom:10px;">
                    <form class="form-inline" action="{{route('facebook-posts.index')}}" method="get">
                      <div class="row"> 
                      <div class="form-group">
                      <select class="form-control" name="account_id" id="" style="width:100%;">
                        <option value="">Select account</option>
                            @foreach($accounts as $account)
                            <option {{$account_id == $account->id ? 'selected' : ''}} value="{{$account->id}}">{{$account->first_name}}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <input type="text" name="term" placeholder="Search caption / post" class="form-control" value="{{$term}}">
                    </div>
                      <button class="btn btn-image">
                        <img src="{{ asset('images/search.png') }}" alt="Search">
                    </button>
                      </div>    
                    </form> 
                </div>
            </div>
        </div>  
        <div class="col-md-12 margin-tb">
        <div class="table-responsive">
        <table class="table table-bordered" style="table-layout:fixed;">
        <tr>
          <th style="width:5%">Date</th>
          <th style="width:10%">Account</th>
          <th style="width:25%">Caption</th>
          <th style="width:30%">Post</th>
          <th style="width:10%">Image</th>
          <th style="width:10%">Posted on</th>
          <th style="width:5%">Status</th>
          <th style="width:5%">Action</th>
        <tbody class="infinite-scroll-data">
        @include("facebook.data")
        </tbody>
      </table>
    </div>
    {{$posts->appends(request()->except("page"))->links()}}
        </div>
    </div>
</div>
<div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
          50% 50% no-repeat;display:none;">
</div>
<div id="create-modal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content" id="record-content">

      </div>
    </div>  
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>

<script type="text/javascript">

$(document).on('click', '.create-post', function(e) 
{

    var $action_url = "{{ route('facebook-posts.create') }}";                 
        jQuery.ajax({
                
            type: "GET",
            url: $action_url,
            dataType: 'html',
            success: function(data)
            {
                $("#create-modal").modal('show');
                $("#record-content").html(data);
                
            },
            error: function(error)
            {
            },
                
        });
        return false;

});

        $(document).on('submit', '#create-form', function(e) {
            e.preventDefault();

            var form = $(this);
            var postData = new FormData(form[0]);


            $.ajax({
            url: '/facebook-posts/save',
            type: 'POST',
            data: postData,
            processData: false,
                contentType: false,
            dataType: 'json',
            beforeSend: function() {
                $("#loading-image").show();
            }
            }).done( function(response) {
            
            if(response.code == 200) {
                $("#loading-image").hide();
                toastr['success'](response.message, 'Success');
                $('#create-modal').modal('hide');
                location.reload();
            }
            else {
                $("#loading-image").hide();
                toastr['error'](response.message, 'error');
            }
            
            }).fail(function(errObj) {
                toastr['error'](errObj.responseJSON.message, 'error');
            $("#loading-image").hide();
            });
        });



        $(window).scroll(function() {
                if ( ( $(window).scrollTop() + $(window).outerHeight() ) >= ( $(document).height() - 2500 ) ) {
                    loadMore();
                }
            });

var isLoadingProducts;
            function loadMore() {
                if (isLoadingProducts)
                    return;
                isLoadingProducts = true;
                if(!$('.pagination li.active + li a').attr('href'))
                return;

                var $loader = $('.infinite-scroll-products-loader');
                $.ajax({
                    url: $('.pagination li.active + li a').attr('href'),
                    type: 'GET',
                    beforeSend: function() {
                        $loader.show();
                        $('ul.pagination').remove();
                    }
                })
                .done(function(data) {
                    if('' === data.trim())
                        return;

                    $loader.hide();

                    $('.infinite-scroll-data').append(data);

                    isLoadingProducts = false;
                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    console.error('something went wrong');

                    isLoadingProducts = false;
                });
            }
</script>
@endsection