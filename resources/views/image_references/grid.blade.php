@extends('layouts.app')

@section('styles')
<style type="text/css">
    .select2-search__field{
        padding-left: 5px;
    }
        #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
            z-index: 60;
        }
        #reason-select{
            display: none;
        }
    </style>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />  
@endsection
@section('content')
<div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
 <div class="row">
        <div class="col-md-12">
            <h1 class="text-center">Crop Reference Grid (<span id="total">{{ $products->total() }}</span>) ({{ $pendingProduct }}) ({{ $pendingCategoryProduct }})</h1>
            <div class="pull-right">
                 <button onclick="addTask()" class="btn btn-secondary">Add Issue</button>
                 <button onclick="rejectImage()" class="btn btn-secondary">Reject Image</button>
                 <select class="form-control-sm form-control bg-secondary text-light" name="reject_cropping" id="reason-select">
                    <option value="0">Select...</option>
                    <option value="Images Not Cropped Correctly">Images Not Cropped Correctly</option>
                    <option value="No Images Shown">No Images Shown</option>
                    <option value="Grid Not Shown">Grid Not Shown</option>
                    <option value="Blurry Image">Blurry Image</option>
                    <option value="First Image Not Available">First Image Not Available</option>
                    <option value="Dimension Not Available">Dimension Not Available</option>
                    <option value="Wrong Grid Showing For Category">Wrong Grid Showing For Category</option>
                    <option value="Incorrect Category">Incorrect Category</option>
                    <option value="Only One Image Available">Only One Image Available</option>
                    <option value="Image incorrect">Image incorrect</option>
                    <option value="Auto rejected">Image incorrect</option>
                </select>
            </div>
                <br>
            <!--Product Search Input -->
                <form method="GET" action="crop-references-grid" class="form-inline align-items-start">
                   
                   <div class="form-group mr-3">
                       <select data-placeholder="Product id" style="width: 200px" class="ajax-get-product-ids form-control " id="filter-id" name="id">
                       </select>
                    </div>
                   <div class="form-group mr-3">
                        <select data-placeholder="Status Type" class="form-control select-multiple2" name="status" id="status">
                            <optgroup label="Status Type">
                               <option value="0">Select Status</option>
                               <option value="4">AutoCrop</option>
                               <option value="18">Crop Rejected</option>
                               <option value="12">Manual Image Upload</option>
                            </optgroup>
                        </select>
                    </div>

                   <div class="form-group mr-3 mb-3">
                       <select data-placeholder="Select Category" style="width: 300px" class="ajax-get-categories form-control " id="category" name="category[]">
                       </select>
                   </div>
    
                    <div class="form-group mr-3">
                        <select data-placeholder="Select Brands" style="width: 300px" class="ajax-get-brands form-control " id="brand" name="brand[]">
                        </select>
                    </div>

                    <div class="form-group mr-3">
                        <select data-placeholder="Select Supplier" style="width: 300px" class="ajax-get-supplier form-control " id="supplier" name="supplier[]">
                        </select>
                    </div>

                     
                    <button type="submit" class="btn btn-image"><img src="/images/filter.png"/></button>
                    <button type="button" class="btn btn-image" onclick="refreshPage()"><img src="/images/resend2.png" /></button>
                </form>
        </div>
         
      
        <div class="col-md-12">
            <div class="panel-group">
                <div class="panel mt-5 panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#collapse1">Crop Stats</a>
                        </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="pull-right">
                            <form action="crop-references-grid" method="GET">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-8">
                                           
                                                <div id="reportrange_phone" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                        <input type="hidden" name="customer_range" id="customer_range">
                                                        <i class="fa fa-calendar"></i>&nbsp;
                                                        <span></span> <i class="fa fa-caret-down"></i>
                                                </div>
                   
                                        </div>

                                        
                                        <div class="col-md-1">
                                         <button class="btn btn-image" type="button"><img src="/images/filter.png"/ onclick="getCount()"></button>
                                     </div>
                                 </div>
                             </div>
                         </form>
                     </div>
                            <table class="table table-bordered table-striped" id="phone-table">
                                <thead>
                                <tr>
                                    <th>Count</th>
                                    <th>Date Time Range</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <th><span id="count_images"></span></th>
                                    <th><span id="date_time"></span></th>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
   

        {!! $products->links() !!}
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table-striped table-bordered table" id="log-table">
                    <thead>
                    <tr>
                        <th>ID <input type="checkbox" name="" id="globalCheckbox"></th>
                        <th>Pro. Id</th>
                        <th>Category</th>
                        <th>Supplier</th>
                        <th>Brand</th>
                        <th>Original Image</th>
                        <th>Cropped Image</th>
                        <th>Time</th>
                        <th>Date</th>
                        <th>Action</th>
                        <th>Issue</th>
                    </tr>
                     </thead>
                    <tbody id="content_data">
                    @include('image_references.partials.griddata')
                    </tbody>
                </table>
            </div>
        </div>

        
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
 @include('partials.modals.large-image-modal')
   
@endsection

@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script type="text/javascript">
      $(document).ready(function () {
             $(".select-multiple").multiselect();
             $(".select-multiple2").select2();
        });     

    function bigImg(img){
        $('#image_crop').attr('src',img);
        $('#largeImageModal').modal('show');
    }

    function normalImg(){
        $('#largeImageModal').modal('hide');
    }

    function addTask() {
       var id = [];
            $.each($("input[name='issue']:checked"), function(){
                id.push($(this).val());
            });
        if(id.length == 0){
            alert('Please Select Image');
        }else{
            $('#taskModal').modal('show');
            $('#task_subject').val('Image ID '+id);
            $('#module').val('41');
            $('#response').val(0);
        }   
        
    }

    function refreshPage() {
         blank = ''
         $.ajax({
            url: '/crop-references-grid',
            dataType: "json",
            data: {
                blank : blank
            },
            beforeSend: function() {
                   $("#loading-image").show();
            },
            
        }).done(function (data) {
             $("#loading-image").hide();
            console.log(data);
            $("#total").text(data.total);
            $("#log-table tbody").empty().html(data.tbody);
            if (data.links.length > 10) {
                $('ul.pagination').replaceWith(data.links);
            } else {
                $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
            }
            
        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });
    }    

    $('#globalCheckbox').click(function(){
            if($(this).prop("checked")) {
                $(".checkBox").prop("checked", true);
            } else {
                $(".checkBox").prop("checked", false);
            }                
        });

        var start = moment().subtract(29, 'days');
        var end = moment();

        function cs(start, end) {
            if(start.format('YYYY-MM-DD') == '1995-12-25'){
                $('#reportrange_phone span').html(end.format('MMMM D, YYYY'));
            }else{
                $('#reportrange_phone span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
            $('#customer_range').val(start.format('YYYY/MM/DD')+' - '+end.format('YYYY/MM/DD'));
        }

        $('#reportrange_phone').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
             'Past Hour' : [moment('1995-12-25'), moment()],  
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

 <script type="text/javascript">
        $(document).ready(function () {
            $('#brand,#category,#crop,#supplier,#status,#filter-id').on('change', function () {
                $.ajax({
                    url: '/crop-references-grid',
                    dataType: "json",
                    data: {
                        brand: $('#brand').val(),
                        category: $('#category').val(),
                        crop : $('#crop').val(),
                        supplier : $('#supplier').val(),
                        status : $('#status').val(),
                        filter_id : $('#filter-id').val(),
                    },
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                }).done(function (data) {
                    $("#loading-image").hide();
                    console.log(data);
                    $("#total").text(data.total);
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

        function getCount(){
            $.ajax({
                    url: '/crop-references-grid',
                    dataType: "json",
                    data: {
                        customer_range: $('#customer_range').val(),
                    },
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                }).done(function (data) {
                    $("#loading-image").hide();
                    console.log(data);
                    $("#count_images").text(data.count);
                    string = $('#customer_range').val();
                    $("#date_time").text(string.replace('1995/12/25',''));
                   
                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    $("#loading-image").hide();
                    alert('No response from server');
                });
        }

        $(document).on('change', '.reject-cropping', function (event) {
            let pid = $(this).data('id');
            let remark = $(this).val();

            if (remark == 0 || remark == '0') {
                return;
            }

            let self = this;

            $.ajax({
                url: '/products/auto-cropped/' + pid + '/reject',
                data: {
                    remark: remark,
                    _token: "{{csrf_token()}}",
                    senior: 1
                },
                type: 'GET',
                success: function () {
                    toastr['success']('Crop rejected successfully!', 'Success');
                    removeIdFromArray(pid);
                    $(self).removeAttr('disabled');
                },
                error: function () {
                    $(self).removeAttr('disabled');
                },
                beforeSend: function () {
                    $(self).attr('disabled');
                }
            });

        });

        function rejectImage(){
            var id = [];
            $.each($("input[name='issue']:checked"), function(){
                id.push($(this).val());
            });
            if(id.length == 0){
                alert('Please Select Image');
            }else{
                $('#reason-select').show();
            }
        }

         $(document).on('change', '#reason-select', function (event) {
            let remark = $(this).val();
            $.each($("input[name='issue']:checked"), function(){
            if (remark == 0 || remark == '0') {
                return;
            }

            pid = $(this).attr('data-id');

            $.ajax({
                url: '/products/auto-cropped/' + pid + '/reject',
                data: {
                    remark: remark,
                    _token: "{{csrf_token()}}",
                    senior: 1
                },
                type: 'GET',
                success: function () {
                    toastr['success']('Crop rejected successfully!', 'Success');
                    $(self).removeAttr('disabled');
                },
                error: function () {
                    $(self).removeAttr('disabled');
                },
                beforeSend: function () {
                    $(self).attr('disabled');
                }
            });

            });
            $('#reason-select').hide();

        });

        $('.ajax-get-product-ids').select2({
            tags: true,
            multiple: true,
            language: {
                "noResults": function(){
                    return "Please enter Product id";
                }
            }
        });

        const $brandsSelect = $('.ajax-get-brands'),
            $supplierSelect = $('.ajax-get-supplier'),
            $categoriesSelect = $('.ajax-get-categories');

        $brandsSelect.select2();
        $supplierSelect.select2();
        $categoriesSelect.select2();

        const ajaxGetSupplier = () => {
            return $.ajax({
                url: '/crop-references-grid/getSupplier',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: params.term,
                    };
                },
                processResults: function (data) {
                    $supplierSelect.addClass('select-multiple2');
                    $supplierSelect.attr('multiple','multiple');
                    return {
                        results: data.result
                    };
                }
            })
        }

        const ajaxGetCategories = () => {
            return $.ajax({
                url: '/crop-references-grid/getCategories',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: params.term,
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.result
                    };
                },
            })
        }

        const ajaxGetBrands = () => {
            return $.ajax({
                url: '/crop-references-grid/getBrands',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: params.term,
                    };
                },
                processResults: function (data) {
                    $brandsSelect.addClass('select-multiple2');
                    $brandsSelect.attr('multiple','multiple');
                    return {
                        results: data.result
                    };
                },
            })
        }

        ajaxGetBrands().done((result)=>{
            $brandsSelect.select2({
                multiple:true,
                data:result.result
            })
        })

        ajaxGetSupplier().done((result)=>{
            $supplierSelect.select2({
                multiple:true,
                data:result.result
            })
        })

        ajaxGetCategories().done((result)=>{
            $categoriesSelect.select2({
                data:result.result,
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    return data.text;
                },
                templateSelection: function(data) {
                    return data.text;
                }
            })
        })
    </script>

@endsection