@extends('layouts.app')

@section('favicon' , 'supplierstats.png')

@section('title', 'Scrape Brand')

@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <style type="text/css">
        .dis-none {
            display: none;
        }
        .modal-lg{
            max-width: 1500px !important; 
        } 
    </style>
@endsection

@section('large_content')

    <div class="row mb-5">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Scrapped Brand ({{$brands->total()}})</h2>
        </div>
    </div>

    @include('partials.flash_messages')
    <?php $status = request()->get('status', ''); ?>
    <?php $excelOnly = request()->get('excelOnly', ''); ?>
    <form class="" action="/scrap/scrap-brand">
        <div class="row">
            <div class="form-group mb-3 col-md-2">
                <input name="term" type="text" class="form-control" id="brand-search" value="{{ request()->get('term','') }}" placeholder="Enter Brand name">
            </div>
            
            <div class="form-group mb-3 col-md-2">
                <button type="submit" class="btn btn-image"><img src="/images/filter.png"></button>
            </div>
        </div>
    </form>
    
    <div class="row no-gutters mt-3">
        <div class="col-md-12" id="plannerColumn">
            <div class="">
                <table class="table table-bordered table-striped sort-priority-scrapper">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Brand</th>
                        <th>Brand Qty</th>
                        <th>Functions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($brands as $brand)
                            <td width="2%">
                            {{$brand->id}}
                            </td>
                            <td width="14%">
                            {{$brand->name}}
                            </td>
                            <td width="5%">
                            {{$brand->total_products }}
                            </td>
                            <td width="14%">
                                <button style="padding: 3px" data-id="{{ $brand->id }}" type="button" class="btn btn-image d-inline get-tasks-remote" title="Task list">
                                     <i class="fa fa-tasks"></i>
                                </button> 
                            </td>
                            </tr>
                               
                            </tr>
                            
                    @endforeach
                </table>

                <div class="text-center">
                    {!! $brands->appends($filters)->links() !!}
                </div>

                @include('partials.modals.remarks',['type' => 'scrap'])
                @include('partials.modals.latest-remarks',[])
            </div>
        </div>
    </div>
    
      <div id="show-content-model-table" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                       
                    </div>
                </div>
            </div>
      </div>
      <div id="chat-list-history" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Communication</h4>
                    <input type="text" name="search_chat_pop"  class="form-control search_chat_pop" placeholder="Search Message" style="width: 200px;">
                    <input type="hidden" id="chat_obj_type" name="chat_obj_type">
                    <input type="hidden" id="chat_obj_id" name="chat_obj_id">
                    <button type="submit" class="btn btn-default downloadChatMessages">Download</button>
                </div>
                <div class="modal-body" style="background-color: #999999;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
               50% 50% no-repeat;display:none;">
    </div>
@endsection

@section('scripts')
    <script src="/js/jquery-ui.js"></script>
    <script type="text/javascript">

         $(document).on("change", ".quickComments", function (e) {
            var message = $(this).val();
            var select = $(this);

            if ($.isNumeric(message) == false) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/scrap/statistics/reply/add",
                    dataType: "json",
                    method: "POST",
                    data: {reply: message}
                }).done(function (data) {
                    var vendors_id =$(select).find("option[value='']").data("vendorid");
                    var message_re = data.data.reply;
                    $("textarea#messageid_"+vendors_id).val(message_re);

                    console.log(data)
                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    alert('No response from server');
                });
            }
            
            var vendors_id =$(select).find("option[value='']").data("vendorid");
            var message_re = $(this).find("option:selected").html();

            $("textarea#messageid_"+vendors_id).val($.trim(message_re));

        }); 

        $(document).on("click", ".delete_quick_comment-scrapp", function (e) {
            var deleteAuto = $(this).closest(".d-flex").find(".quickComments").find("option:selected").val();
            if (typeof deleteAuto != "undefined") {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    url: BASE_URL+"/scrap/statistics/reply/delete",
                    dataType: "json",
                    method: "POST",
                    data: {id: deleteAuto}
                }).done(function (data) {
                    if (data.code == 200) {
                        
                        toastr["success"]("Quick Comments Deleted successfully!", "Message");

                        $(".quickComments").each(function(){
                        var selecto=  $(this)
                            $(this).children("option").not(':first').each(function(){
                                $(this).remove();

                            });
                            $.each(data.data, function (k, v) {
                                $(selecto).append("<option  value='" + k + "'>" + v + "</option>");
                            });
                            $(selecto).select2({tags: true});
                        });


                    }

                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    alert('No response from server');
                });
            }
        }); 


        $(".scrapers_status").select2();
        $(document).on("change", ".scraper_type", function () {
            var tr = $(this).closest("tr");
            var id = tr.data("eleid");
            $.ajax({
                type: 'GET',
                url: '/scrap/statistics/update-field',
                data: {
                    search: id,
                    field: "scraper_type",
                    field_value: tr.find(".scraper_type").val()
                },
            }).done(function (response) {
                toastr['success']('Data updated Successfully', 'success');
            }).fail(function (response) {

            });
        }); 

        $(document).on("change", ".scraper_made_by", function () {
            var tr = $(this).closest("tr");
            var id = tr.data("eleid");
            $.ajax({
                type: 'GET',
                url: '/scrap/statistics/update-field',
                data: {
                    search: id,
                    field: "scraper_made_by",
                    field_value: tr.find(".scraper_made_by").val()
                },
            }).done(function (response) {
                toastr['success']('Data updated Successfully', 'success');
            }).fail(function (response) {

            });
        }); 

        $(document).on("change", ".scrapers_status", function (e) {
            e.preventDefault();
            var tr = $(this).closest("tr");
            var id = tr.data("eleid");
            $("#remark-confirmation-box").modal("show").on("click",".btn-confirm-remark",function(e) {
                e.preventDefault();
                 var remark =  $("#confirmation-remark-note").val();
                 if($.trim(remark) == "") {
                    alert("Please Enter remark");
                    return false;
                 }
                 $.ajax({
                    type: 'GET',
                    url: '/scrap/statistics/update-field',
                    data: {
                        search: id,
                        field: "status",
                        field_value: tr.find(".scrapers_status").val(),
                        remark : remark    
                    },
                }).done(function (response) {
                    toastr['success']('Data updated Successfully', 'success');
                    $("#remark-confirmation-box").modal("hide");
                }).fail(function (response) {
                });
            });

            return false;
        }); 

        $(".select2").select2();       

        $(document).on("click",".get-tasks-remote",function (e){
            e.preventDefault();
            var id = $(this).data("id");
            $.ajax({
                url: '/scrap/task-list',
                type: 'GET',
                data: {id: id,type: 'brand'},
                beforeSend: function () {
                    $("#loading-image").show();
                }
            }).done(function(response) {
                $("#loading-image").hide();
                var model  = $("#show-content-model-table");
                model.find(".modal-title").html("Task List");
                model.find(".modal-body").html(response);
                model.modal("show");
            }).fail(function() {
                $("#loading-image").hide();
                alert('Please check laravel log for more information')
            });
        }); 

        $(document).on("click",".btn-create-task",function (e){
            e.preventDefault();
            var $this = $(this).closest("form");
            $.ajax({
                url: $this.attr("action"),
                type: $this.attr("method"),
                data: $this.serialize() + "&type=brand",
                beforeSend: function () {
                    $("#loading-image").show();
                }
            }).done(function(response) {
                $("#loading-image").hide();
                var model  = $("#show-content-model-table");
                model.find(".modal-title").html("Task List");
                model.find(".modal-body").html(response);
            }).fail(function() {
                $("#loading-image").hide();
                alert('Please check laravel log for more information')
            });
        }); 


        $(document).on("click","#show-content-model-table li",function (e){
            e.preventDefault();
            var a = $(this).find("a");
            if(typeof a != "undefined") {
                $.ajax({
                    url: a.attr("href"),
                    type: 'GET',
                    beforeSend: function () {
                        $("#loading-image").show();
                    }
                }).done(function(response) {
                     $("#loading-image").hide();
                    var model  = $("#show-content-model-table");
                    model.find(".modal-body").html(response);
                }).fail(function() {
                    $("#loading-image").hide();
                    alert('Please check laravel log for more information')
                });
            }
        }); 

        $(document).on('click', '.send-message1', function () {
            var thiss = $(this);
            var data = new FormData();
            var task = $(this).data('task-id');
            var message = $("#messageid_"+task).val();
            data.append("issue_id", task);
            data.append("message", message);
            data.append("status", 1);
            data.append("sendTo", $(".send-message-number-"+task).val());

            if (message.length > 0) {
                if (!$(this).is(':disabled')) {
                    $.ajax({
                        url: BASE_URL+'/whatsapp/sendMessage/issue',
                        type: 'POST',
                        "dataType": 'json',           // what to expect back from the PHP script, if anything
                        "cache": false,
                        "contentType": false,
                        "processData": false,
                        "data": data,
                        beforeSend: function () {
                            $(thiss).attr('disabled', true);
                            $("#loading-image").show();
                        }
                    }).done(function (response) {
                        
                        $("#message-chat-txt-"+task).html(response.message.message);
                        $("#messageid_"+task).val('');
                        $("#loading-image").hide();
                        $(this).attr('disabled', false);
                    }).fail(function (errObj) {
                        $(this).attr('disabled', false);

                        alert("Could not send message");
                        console.log(errObj);
                        $("#loading-image").hide();
                    });
                }
            } else {
                alert('Please enter a message first');
            }
        }); 

    </script>
@endsection
