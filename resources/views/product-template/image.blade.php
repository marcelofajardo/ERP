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
            <h2 class="page-heading">Product Template Processed Image (<span id="count">{{ $templates->total() }} </span>)</h2>
             
             <form method="GET" class="form-inline align-items-start">
                <div class="form-group mr-3 mb-3">

                    <select class="form-control select-multiple2"  placeholder="Please Select Type" name="template">
                    	<option value="0">Select Template Type</option>
                    	@foreach($temps as $temp)
                    	<option value="{{ $temp->id }}">{{ $temp->name }}</option>
                    	@endforeach
                    </select>
                </div>
                <div class="form-group mr-3 mb-3">
                        {!! $category_selection !!}
                    </div>


                    <div class="form-group mr-3">
                        @php $brands = \App\Brand::getAll();
                        @endphp
                        <select data-placeholder="Select brands" class="form-control select-multiple2" name="brand[]" multiple>
                            <optgroup label="Brands">
                                @foreach ($brands as $id => $name)
                                    <option value="{{ $id }}" {{ isset($brand) && $brand == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group mr-3">
                        <div id="date_range" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            <input type="hidden" name="date_range" id="custom_range">
                            <i class="fa fa-calendar"></i>&nbsp;
                                <span></span> <i class="fa fa-caret-down"></i>
                                            </div>
                    </div>

                     <button type="submit" class="btn btn-image"><img src="/images/filter.png"/></button>
            
             <div class="pull-right">
                <button type="button" class="btn btn-image" ><a href="/product-templates/image"><img src="/images/resend2.png" /></a></button>
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
                <th>Image</th>
		        <th>Product Title</th>
		        <th>Brand</th>
                <th>Category</th>
		        <th>Currency</th>
		        <th>Price</th>
		        <th>Discounted price</th>
		        <th>Created at</th>
		        
            </tr>
            </thead>

            <tbody id="content_data">
             @include('product-template.partials.list-image')
            </tbody>

            {!! $templates->render() !!}

        </table>
    </div>

@endsection 

@section('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript">
	$(function() {
	$('.selectpicker').selectpicker();
    $(".select-multiple2").select2();
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

     var start = moment().subtract(29, 'days');
                var end = moment();

                function cs(start, end) {
                    $('#date_range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    $('#custom_range').val(start.format('YYYY/MM/DD') + ' - ' + end.format('YYYY/MM/DD'));
                }

                $('#date_range').daterangepicker({
                    startDate: start,
                    endDate: end,
                    ranges: {
                     'Today': [moment(), moment()],
                     'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                     'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                     'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                     'This Month': [moment().startOf('month'), moment().endOf('month')],
                     'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                 }
             }, cs)
                cs(start, end);

	
</script>
@endsection   