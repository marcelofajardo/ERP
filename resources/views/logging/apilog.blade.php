@extends('layouts.app')
@section('title', 'Laravel API Log List')
@section("styles")
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')
<div class="row">
   <div class="col-lg-12 margin-tb">
      <h2 class="page-heading">Laravel API Logs (<span class="page-total">{{$count}}</span>)</h2>
      <!-- <div class="pull-right">
         <a href="/logging/live-laravel-logs" type="button" class="btn btn-secondary">Live Logs</a>
         <button type="button" class="btn btn-image" onclick="refreshPage()"><img src="/images/resend2.png" /></button>
         </div> -->
   </div>
</div>
@include('partials.flash_messages')
<div class="mt-3 col-md-12">
   <div class="row">
      <div class="col">
         <div class="h" style="margin-bottom:10px;">
            <form class="form-inline message-search-handler" method="get">
               <div class="row">
                  <div class="form-group ml-2 ">
                     <label for="keyword">Keyword:</label>
                     <input class="form-control" placeholder="Enter keyword" name="keyword" type="text">
                  </div>
                  <div class="form-group ml-2">
                     <label for="for_date">Created at:</label>
                     <input class="form-control datepicker-block" placeholder="Enter date" name="for_date" type="text">
                  </div>
                  <div class="form-group ml-2">
                     <label for="for_date">Report Type:</label>
                     <select name="report_type" class="form-control">
                        <option value="error_wise">Error Wise</option>
                        <option value="time_wise">Time Wise</option>
                     </select>
                  </div>
                  <div class="form-group ml-2">
                     <label for="button">&nbsp;</label>
                     <button style="display: inline-block;" class="btn btn-sm btn-secondary btn-generate-report">Generate Report</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<div class="mt-3 col-md-12">
   <table class="table table-bordered table-striped" id="log-table">
      <thead>
         <tr>
            <th width="10%">ID</th>
            <th width="10%">IP</th>
            <th width="10%">Method</th>
            <th width="25%">URL</th>
            <th width="20%">Request</th>
            <th width="5%">Status Code</th>
            <th width="5%">Time Taken</th>
            <th width="10%">Created At</th>
            <th width="10%">Action</th>
         </tr>
         <tr>
            <th width="10%"><input type="text" class="search form-control tbInput" name="id" id="filename"></th>
            <th width="10%"><input type="text" class="search form-control tbInput" id="log" name="ip"></th>
            <th width="10%"><input type="text" name="method" class="search form-control tbInput" id="website"></th>
            <th width="10%"><input type="text" name="url" class="search form-control tbInput" id="moduleName"></th>
            <th></th>
            <!--  <th width="10%"><input type="text" class="search form-control" id="controllerName"></th> -->
            <th width="10%">
              <select name="status" class="search form-control tbInput" id="action">
                <option value="">Select Status Code</option>
                @foreach($status_codes as $status_code)
                  <option value="{{$status_code->status_code}}">{{$status_code->status_code}}</option>
                @endforeach
              </select>
              <!-- <input type="text" name="status" class="search form-control tbInput" id="action"> -->
            </th>
            <th></th>
            <th>
               <div class='input-group' id='log-created-date'>
                  <input type='text' class="form-control" name="created_at" value="" placeholder="Date" autocomplete="off" />
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
               </div>
            </th>
            <th></th>
            <!-- <th> <div class='input-group' id='created-date'>
               <input type='text' class="form-control " name="phone_date" value="" placeholder="Date" id="created_date" />
                   <span class="input-group-addon">
                   <span class="glyphicon glyphicon-calendar"></span>
                   </span>
               </div>
               </th>
               <th> <div class='input-group' id='updated-date'>
               <input type='text' class="form-control " name="phone_date" value="" placeholder="Date" id="updated_date" />
                   <span class="input-group-addon">
                   <span class="glyphicon glyphicon-calendar"></span>
                   </span>
               </div>
               </th> -->
         </tr>
      </thead>
      <tbody id="content_data" class="tableLazy">
         @include('logging.partials.apilogdata')
      </tbody>
   </table>
</div>
<div class="modal fade" id="api_response_modal" role="dialog" style="display: none;">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">View Response</h4>
         </div>
         <div class="modal-body">
            <label>Reponse</label>
            <pre style="overflow:scroll;max-height:350px;" id="json"></pre>
            <label>Request</label>
            <pre style="overflow:scroll;max-height:100px;" id="json_request"></pre>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
<div id="generate-report-modal" class="modal fade in" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Report</h4>
            <button type="button" class="close" data-dismiss="modal">×</button>
         </div>
         <div class="modal-body">
            
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
<div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
          50% 50% no-repeat;display:none;">
</div>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
   //Ajax Request For Search
   $(document).ready(function () {
   
   
       $(document).on('keyup','.tbInput',function()
       {
   
           filterResults();
          // console.log(data);
       })
        $(document).on('change','select[name="status"]',function(){
          filterResults();
        });
   
       //Expand Row
   
       $('#log-created-date').datetimepicker(
           { format: 'YYYY/MM/DD' }).on('dp.change',
           function (e)
           {
   
            var formatedValue = e.date.format(e.date._f);
               created = $('#created_date').val();
   
   
           filterResults();
   
   
   
   
           })
   
   
   
   
   
   
   
   
        $(window).on('scroll',function()
        {
   
   if($(window).scrollTop() == $(document).height() - $(window).height()) {
          var page_no=$('.currentPage').last().attr('data-page');
   
          page_no=parseInt(page_no)+1;
   //console.log(page_no);
   
   var row= getFilterValues();
   
   
   
   $.ajax({
                   url: '{{route("api-log-list")}}'+'?page='+page_no,
                   dataType: "json",
                   data: row,
                   method:'post',
                   beforeSend: function () {
   
                   },
   
               }).done(function (res) {
   
                     $('#noresult_tr').remove();
             //var res=JSON.parse(res);
   
             if(res.status){
             $('.tableLazy').append(res.html);
             $(".page-total").html(res.count);
             $.each(res.logs.data,function(k,v)
             {
               $logsRecords.push(v);
             })
   
   
          }
             else
               $('.tableLazy').append(res.html)
          })
   
   
   
   }
   
        })
   
        function filterResults()
        {
                     $('#noresult_tr').remove();
   
            var row= getFilterValues();
   
   
           $.ajax({
                   url: '{{route("api-log-list")}}',
                   dataType: "json",
                   data: row,
                   method:'post',
                   beforeSend: function () {
                      $("#loading-image").show();
                   },
   
               }).done(function (res) {
                      $("#loading-image").hide();
                     $('#noresult_tr').remove();
   
   
             if(res.status){
               $('.tableLazy').html(res.html);
   
   
               $logsRecords=res.logs.data;
               $(".page-total").html(res.count);
   
   
          }
             else
               $('.tableLazy').html(res.html)
          })
        }
   
        function getFilterValues()
        {
           var row={};
           $('.tbInput').each(function()
           {
               var name=$(this).attr('name');
   
                row[name]=$(this).val();
   
               //data.push(row);
           })
   
           row['created_at']=$('[name="created_at"]').val();
           row['_token']='{{csrf_token()}}';
   
           return row;
   
        }
   
        $(document).on('click','.showModalResponse',function()
        {
           var selector=$(this);
           $.each($logsRecords,function(k,v)
           {
   
               if(v.id==selector.attr('data-id'))
               {
                  console.log(v.id);
                   $('#api_response_modal').find('.modal-body').find('#json').html( JSON.stringify(JSON.parse(v.response), undefined, 2));
   
   
   
                    $('#api_response_modal').find('.modal-body').find('#json_request').html( JSON.stringify(JSON.parse(v.request), undefined, 2));
               }
           })
           $('#api_response_modal').modal('show');
        })
   
        var logsRecords=@json($logs);
        $logsRecords=logsRecords.data;

        $(document).on("click",".btn-generate-report",function(e) {
            e.preventDefault();
            var form = $(this).closest("form");
            $.ajax({
              method:'get',
              url:"/logging/list/api/logs/generate-report",
              data: { 
                  keyword : form.find("input[name='keyword']").val(),
                  for_date : form.find("input[name='for_date']").val(),
                  report_type : form.find("select[name='report_type']").val()
                },
              beforeSend: function () {
                $("#loading-image").show();
              }
            }).done(function (res) {
                $("#loading-image").hide();
                $("#generate-report-modal").find(".modal-body").html(res);
                $("#generate-report-modal").modal("show");
            }).fail(function (res) {
                $("#loading-image").hide();
            });
        });

        $(".datepicker-block").datetimepicker({
           format: 'YYYY-MM-DD'
        });

       // console.log($logsRecords);
   
    })
</script>
@endsection