@extends('layouts.app')

@section('title', 'SKU log')

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
            z-index: 48;
        }

        input {
            width: 100px;
        }
    </style>
@endsection

@section('content')
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">SKU warnings/errors (<span id="count">{{ $failed }}</span>)</h2>
            <div class="pull-right">
                <button type="button" class="btn btn-secondary" onclick="sendMulti()" style="display: none;" id="nulti">Send Selected</button>
                <button type="button" class="btn btn-secondary">Number of open task {{ $pendingIssuesCount }}</button>
                <button type="button" class="btn btn-secondary">Last Created task @if($lastCreatedIssue) {{ $lastCreatedIssue->created_at->format('d-m-Y H:i:s') }} @endif</button>
                <button type="button" class="btn btn-image" onclick="refreshPage()"><img src="/images/resend2.png"/></button>
            </div>

        </div>
    </div>

    @include('partials.flash_messages')

    <div class="row">
        <div class="col-md-12">
            <div class="panel-group">
                <div class="panel mt-5 panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#collapse1">Pending Issues</a>
                        </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="pull-right">
                            <form action="{{ route('broadcasts.index') }}" method="GET">
                                <div class="form-group">
                                    <div class="row">
                                    
                                 </div>
                             </div>
                         </form>
                     </div>
                            <table class="table table-bordered table-striped" id="phone-table">
                                <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>User</th>
                                    <th>Count</th>
                                    
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingIssues as $issue)
                                    <tr>
                                        <td>{{ $issue->status }}</td>
                                        <td>{{ \App\User::find($issue->user_id)->name }}</td>
                                        <td>{{ $issue->countUserTaskFromReference($issue->user_id) }}</td>
                                    </tr>
                                    @endforeach    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
   <div class="mt-3 col-md-12">
     <table class="table table-bordered table-striped" id="log-table">
            <thead>
            <tr>
                <th style="width: 20% !important;">Brand</th>
                <th style="width: 20% !important;">Category</th>
                <th style="width: 20% !important;">Supplier</th>
                <th><button onclick="changeOrder()" value="1" id="order_count" class="btn btn-link">Count</button></th>
                <th>Action</th>
            </tr>
            <tr>
                <th style="width: 20% !important;">@php $brands = \App\Brand::getAll();
                        @endphp
                        <select data-placeholder="Select brands" class="form-control select-multiple2" id="brand" multiple>
                            <optgroup label="Brands">
                                @foreach ($brands as $id => $name)
                                    <option value="{{ $name }}" {{ isset($brand) && $brand == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </optgroup>
                        </select></th>
                <th style="width: 20% !important;"><select data-placeholder="Select Category" class="form-control select-multiple2" id="category">
                            <optgroup label="Category">
                                    <option value="">Select Category</option>
                                @foreach ($category_selection as $category)
                                    <option value="{{ $category }}" >{{ $category }}</option>
                                @endforeach
                            </optgroup>
                        </select></th>
                
                 <th style="width: 20% !important;">@php $suppliers = new \App\Supplier();
                        @endphp
                        <select data-placeholder="Select Supplier" class="form-control select-multiple2" id="supplier" multiple>
                            <optgroup label="Suppliers">
                                @foreach ($suppliers->select('id','supplier')->where('supplier_status_id',1)->get() as $id => $suppliers)
                                    @if($suppliers->scraper)
                                    <option value="{{ $suppliers->scraper->scraper_name }}" {{ isset($supplier) && $supplier == $suppliers->scraper->scraper_name ? 'selected' : '' }}>{{ $suppliers->supplier }}</option>
                                    @endif
                                }
                                }
                                @endforeach
                            </optgroup>
                        </select></th>
                <th>
                    <select data-placeholder="Select Type" class="form-control" id="custom">
                            <option value="0">Select For SKU String Count</option>
                            <option value="1">SKU is not proper</option>
                        </select></th>
                </th>
            </tr>
            </thead>
            <tbody id="content_data">
            @include('logging.partials.listsku_errors_data')
            </tbody>

            {{ $logScrappers->render() }}

        </table>
    </div>
<div id="chat-list-history" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Communication</h4>
                </div>
                <div class="modal-body" style="background-color: #999999;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@include('partials.modals.task-module')
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
    
    $(document).ready(function () {
        $('#sku,#category').on('blur', function () {
            $.ajax({
                url: '/logging/sku-logs-errors',
                dataType: "json",
                data: {
                    sku: $('#sku').val(),
                    brand: $('#brand').val(),
                    category: $('#category').val()
                },
                beforeSend: function () {
                    $("#loading-image").show();
                },
            }).done(function (data) {
                $("#loading-image").hide();
                console.log(data);
                $("#count").text(data.totalFailed);
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

         $('#brand,#category,#supplier,#custom').on('change', function () {
            $.ajax({
                url: '/logging/sku-logs-errors',
                dataType: "json",
                data: {
                    sku: $('#sku').val(),
                    brand: $('#brand').val(),
                    category: $('#category').val(),
                    supplier : $('#supplier').val(),
                    custom : $('#custom').val(),
                },
                beforeSend: function () {
                    $("#loading-image").show();
                },
            }).done(function (data) {
                $("#loading-image").hide();
                 $("#nulti").show();
                console.log(data);
                $("#count").text(data.totalFailed);
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
    


    });
    function refreshPage(){
        blank = '';
        $.ajax({
                url: '/logging/sku-logs-errors',
                dataType: "json",
                data: {
                    blank : blank
                },
                beforeSend: function () {
                    $("#loading-image").show();
                },
            }).done(function (data) {
                $("#loading-image").hide();
                $("#nulti").show();
                console.log(data);
                $("#count").text(data.totalFailed);
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

    function addTask(supplier , category , sku , brand) {
        $('#taskModal').modal('show');
        $('#task_subject').val('Supplier :'+supplier +' Category '+category+' Brand : '+brand);
        $('#references').val(supplier+''+category+''+brand);
    }

    $(".checkbox").change(function() {
    if(this.checked) {
        validate = 1;
        $.ajax({
                url: '/logging/sku-logs-errors',
                dataType: "json",
                data: {
                    sku: $('#sku').val(),
                    brand: $('#brand').val(),
                    category: $('#category').val(),
                    supplier : $('#supplier').val(),
                    validate : validate,
                },
                beforeSend: function () {
                    $("#loading-image").show();
                },
            }).done(function (data) {
                $("#loading-image").hide();
                $("#nulti").show();
                console.log(data);
                $("#count").text(data.totalFailed);
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
        
    }else{
        validate = 2;
        $.ajax({
                url: '/logging/sku-logs-errors',
                dataType: "json",
                data: {
                    sku: $('#sku').val(),
                    brand: $('#brand').val(),
                    category: $('#category').val(),
                    supplier : $('#supplier').val(),
                    validate : validate,
                },
                beforeSend: function () {
                    $("#loading-image").show();
                },
            }).done(function (data) {
                $("#loading-image").hide();
                $("#nulti").show();
                console.log(data);
                $("#count").text(data.totalFailed);
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
    });

    function sendMulti(){
        brand =  $('#brand').val();
        category = $('#category').val();
        supplier = $('#supplier').val();
        if(brand == ''){
            alert('Please Select Brand');
        }
        if(category == ''){
            alert('Please Select Category');
        }
        if(supplier == ''){
            alert('Please Select Supplier');
        }
        if(brand != '' && category != '' && supplier != ''){
            $('#taskModal').modal('show');
            $('#task_subject').val(supplier +' '+category+' multi');
            $('#references').val(supplier+''+category+''+brand);
        }
        
    }

    function changeOrder(){
        order = $('#order_count').val();
         $.ajax({
                url: '/logging/sku-logs-errors',
                dataType: "json",
                data: {
                    sku: $('#sku').val(),
                    brand: $('#brand').val(),
                    category: $('#category').val(),
                    supplier : $('#supplier').val(),
                    order : order,
                },
                beforeSend: function () {
                    $("#loading-image").show();
                },
            }).done(function (data) {
                $("#loading-image").hide();
                $("#nulti").show();
                if(order == 1){
                    $('#order_count').val('0');
                }else{
                   $('#order_count').val(1); 
                }
                $("#count").text(data.totalFailed);
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