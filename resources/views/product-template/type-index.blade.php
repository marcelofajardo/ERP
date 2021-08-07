@extends('layouts.app')

@section('title', 'Product Template New')

@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
     <style type="text/css">
        #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
        }
        .bootstrap-select{
        	width: 200px !important;
        }
    </style>
@endsection

@section('content')
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Product Template New (<span id="count">{{ $templates->total() }} </span>)</h2>
             
             <div class="pull-left">
                <select class="form-control search"  placeholder="Please Select Type">
                	<option value="0">Select Template Type</option>
                	@foreach($temps as $temp)
                	<option value="{{ $temp->id }}">{{ $temp->name }}</option>
                	@endforeach
                </select>
            </div>
             <div class="pull-right">
                <button type="button" class="btn btn-image" onclick="refreshPage()"><img src="/images/resend2.png" /></button>
            </div>

        </div>
    </div>

       @include('partials.flash_messages')

    <div class="mt-3 col-md-12">
        <table class="table table-bordered table-striped" id="log-table">
            <thead>
            <tr>
                <th>Id</th>
		        <th>Template no</th>
		        <th>Product Title</th>
		        <th>Brand</th>
		        <th>Currency</th>
		        <th>Price</th>
		        <th>Discounted price</th>
		        <th>Product</th>
		        <th>Is Processed</th>
		        <th>Created at</th>
		        <th>Action</th>
            </tr>
            </thead>

            <tbody id="content_data">
             @include('product-template.partials.type-list-template')
            </tbody>

            {!! $templates->render() !!}

        </table>
    </div>

@endsection 

@section('scripts')
<script type="text/javascript">
	$(function() {
	$('.selectpicker').selectpicker();
	});

	$( ".search" ).change(function() {
		 search = $(this).val();
		  $.ajax({
                url: '/templates/type',
                dataType: "json",
                data: {
                    search : search,
                },
                beforeSend: function () {
                    $("#loading-image").show();
                },
            }).done(function (data) {
                $("#loading-image").hide();
                $("#count").text(data.total);
                $("#log-table tbody").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                $("#loading-image").hide();
                alert('No response from server');
            });

  		
	});

	function refreshPage(){
		blank = ''
		$.ajax({
                url: '/templates/type',
                dataType: "json",
                data: {
                    blank : blank,
                },
                beforeSend: function () {
                    $("#loading-image").show();
                },
            }).done(function (data) {
                $("#loading-image").hide();
                $("#count").text(data.total);
                $("#log-table tbody").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                $("#loading-image").hide();
                alert('No response from server');
            });
	}

	
</script>
@endsection   