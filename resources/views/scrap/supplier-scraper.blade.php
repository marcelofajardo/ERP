@extends('layouts.app')

@section('title', 'Generic Supplier Scraper')

@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <style type="text/css">
        #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
        }
    </style>
@endsection

@section('content')
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Generic Supplier Scraper (<span id="count">{{ $scrapers->total() }}</span>)</h2>
            <div class="pull-right">
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#scrapAddModal">+</a>
                <button type="button" class="btn btn-image" onclick="refreshPage()"><img src="/images/resend2.png"/></button>
            </div>
            <div class="pull-left">
                <input type="text" class="form-control" id="global" placeholder="Global Search">
            </div>

        </div>
    </div>

    @include('partials.flash_messages')
   <div class="mt-3 col-md-12">
     <table class="table table-bordered table-striped" id="scraper-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Scraper name</th>
                <th>Supplier</th>
                <th>Full Scrape</th>
                <th style="width: 5%">Start Time</th>
                <th style="width: 5%">End Time</th>
                <th>Run Gap</th>
                <th>Time Out</th>
                <th>Starting URL</th>
                <th>Designer URL Selector</th>
                <th>Product URL Selector</th>
                <th style="width: 15%">Action</th>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <th><input type="text" id="scraper_name" class="form-control"></th>
                <th><input type="text" id="supplier_name" class="form-control"></th>
                <th style="width: 5%">&nbsp;</th>
                <th style="width: 5%">&nbsp;</th>
                <th style="width: 5%">&nbsp;</th>
                <th><input type="text" id="run_gap_search" class="form-control"></th>
                <th><input type="text" id="time_out_search" class="form-control"></th>
                <th><input type="text" id="starting_url_search" class="form-control"></th>
                <th><input type="text" id="designer_url_search" class="form-control"></th>
                <th><input type="text" id="product_url_search" class="form-control"></th>
                <th style="width: 15%">&nbsp;</th>
                
            </tr>

            </thead>
            <tbody id="content_data">
            @include('scrap.partials.supplier-scraper-data')
            </tbody>

            {{ $scrapers->render() }}

        </table>
        {{ $scrapers->render() }}
    </div>

@include('scrap.partials.add-edit-supplier-scraper-modal')
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    <script type="text/javascript">


        $(document).ready(function() {
         $(".select-multiple").multiselect();
         $(".select-multiple2").select2();
     });


        function refreshPage(){
            blank = '';
            $.ajax({
                url: '/scrap/generic-scraper',
                dataType: "json",
                data: {
                    blank : blank
                },
                beforeSend: function () {
                    $("#loading-image").show();
                },
            }).done(function (data) {

                //Removing Text From Search
                $('#scraper_name').val('');
                $('#supplier_name').val('');
                $('#run_gap_search').val('');
                $('#time_out_search').val('');
                $('#starting_url_search').val('');
                $('#designer_url_search').val('');
                $('#product_url_search').val('');

                //Loading Data
                $("#loading-image").hide();
                $("#count").text(data.count);
                $("#scraper-table tbody").empty().html(data.tbody);
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

        function editSupplier(scraper){
            console.log(JSON.stringify(scraper));
            $("#scraper_id").val(scraper.id);

            $("#run_gap").val(scraper.run_gap);
            $("#time_out").val(scraper.time_out);
            $("#starting_url").val(scraper.starting_urls);
            $("#designer_url").val(scraper.designer_url_selector);
            $("#product_url_selector").val(scraper.product_url_selector);
            $("#full_scrape").val(scraper.full_scrape);
            $("#scrapEditModal").modal('show');
        }

        function updateSupplier(){
            id = $("#scraper_id").val();
            $.ajax({
                url: "{{ route('generic.save.scraper') }}",
                dataType: "json",
                type : "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id : id,
                    starting_url : $("#starting_url").val(),
                    designer_url : $("#designer_url").val(),
                    product_url_selector : $("#product_url_selector").val(),
                    run_gap : $("#run_gap").val(),
                    time_out: $("#time_out").val(),
                    full_scrape : $("#full_scrape").val(),
                },
                beforeSend: function () {
                    $("#loading-image").show();
                },
            }).done(function (data) {
                $("#loading-image").hide();
                window.location = "/scrap/generic-scraper/mapping/"+id;
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                $("#loading-image").hide();
                alert('No response from server');
            });

        }
        
        $(document).ready(function () {
            $('#global,#scraper_name,#supplier_name,#run_gap_search,#time_out_search,#starting_url_search,#designer_url_search,#product_url_search').on('blur', function () {
                $.ajax({
                    url: '/scrap/generic-scraper',
                    dataType: "json",
                    data: {
                        global: $('#global').val(),
                        scraper_name: $('#scraper_name').val(),
                        supplier_name: $('#supplier_name').val(),
                        run_gap_search: $('#run_gap_search').val(),
                        time_out_search: $('#time_out_search').val(),
                        starting_url_search: $('#starting_url_search').val(),
                        designer_url_search: $('#designer_url_search').val(),
                        product_url_search: $('#product_url_search').val(),
                    },
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                }).done(function (data) {
                    $("#loading-image").hide();
                    console.log(data);
                    $("#count").val(data.count);
                    $("#scraper-table tbody").empty().html(data.tbody);
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
        });

        function changeFullScrape(id) {
            value = $('#full_scrape'+id).val();
            if(value == 1){
                var result = confirm("Are you sure ? you want to run Full Scrape");
                if (result) {
                    $.ajax({
                    url: '/scrap/generic-scraper/full-scrape',
                    dataType: "json",
                    type : "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        value : value,
                        id : id,
                    },
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    }).done(function (data) {
                        $("#loading-image").hide();
                    }).fail(function (jqXHR, ajaxOptions, thrownError) {
                        $("#loading-image").hide();
                        alert('No response from server');
                    });    
                }
            }else{
                $.ajax({
                    url: '/scrap/generic-scraper/full-scrape',
                    dataType: "json",
                    type : "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        value : value,
                        id : id,
                    },
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    }).done(function (data) {
                        $("#loading-image").hide();
                    }).fail(function (jqXHR, ajaxOptions, thrownError) {
                        $("#loading-image").hide();
                        alert('No response from server');
                    }); 
            }
            
        }
    </script>
@endsection