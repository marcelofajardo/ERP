@extends('layouts.app')

@section('favicon' , 'supplierstats.png')

@section('title', 'Scrape Statistics')
@section('large_content')
    <style type="text/css">
        #loading-image {
                position: fixed;
                top: 50%;
                left: 50%;
                margin: -50px 0px 0px -50px;
                z-index: 60;
            }
    </style>
    
    <div class="row mb-5">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Scrapper Server Status (<span id="total-count">{{ $scrappers->total() }}</span>)</h2>
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control" onchange="filterByServer('server_id_filter')" id="server_change">
                        <option value="">Select All</option>
                        @foreach($servers as $server)
                            <option value="{{ $server->server_id }}">{{ $server->server_id }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input class="form-control" id="server_text">
                    <button type="button" class="form-control" onclick="filterByText('filter_by_text')">Search</button>
                </div>
            </div>
        </div>
        
    </div>

    @include('partials.flash_messages')
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div class="row no-gutters mt-3">
        <div class="col-md-12" id="plannerColumn">
            <div class="table-responsive">
                <table class="table table-bordered table-striped sort-priority-scrapper" id="detail-table">
                    <thead>
                    <tr>
                        <th><a onclick="filter('server_id')">Server<input type="text" style="display: none;" id="server_id" value="asc"><i class="fa fa-chevron-up" id="server_id_class"></i></th>
                        <th width="5%">Time</th>
                        <th><a onclick="filter('scraper_name')">Scrapper<input type="text" style="display: none;" id="scraper_name" value="asc"><i class="fa fa-chevron-up" id="scraper_name_class"></i></th>
                        <th><a onclick="filter('last_started_at')">Start Time<input type="text" style="display: none;" id="last_started_at" value="asc"><i class="fa fa-chevron-up" id="last_started_at_class"></i></a></th>
                        <th><a onclick="filter('last_completed_at')">End Time<input type="text" style="display: none;" id="last_completed_at" value="asc"><i class="fa fa-chevron-up" id="last_completed_at_class"></i></a></th>
                        <th><a onclick="filter('updated_at')">Last Updated<input type="text" style="display: none;" id="updated_at" value="asc"><i class="fa fa-chevron-up" id="updated_at_class"></i></a></th>
                        <th>Dur</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                     <tbody>  
                        @include('scrap.partials.scrap-server-status-data')
                     </tbody> 
               
                @if(isset($scrappers))
                    {!! $scrappers->links() !!}
                @endif
            </div>

        </div>
    </div>



@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
       var counter = 0;
       $(document).on("click",'.openHistory', function(){
            // if(counter == 1){
            //     $('.close_all').hide();
            //     counter = 0;
            // }
            var id = $(this).attr('id');
            if(counter == 0){
                $('.open_request_'+id).show();
                counter = 1;
            }else{
                $('.open_request_'+id).hide();
                counter = 0;
            }
       }) ;
    });


    function filter(type) {
         order = $('#'+type).val();
         if(order == 'asc'){
            $('#'+type).val('desc')
            $("#"+type+"_class").removeClass("fa-chevron-down");
            $("#"+type+"_class").addClass("fa-chevron-down");
         }else{
            $('#'+type).val('asc')
            $("#"+type+"_class").removeClass("fa-chevron-up");
            $("#"+type+"_class").addClass("fa-chevron-up");
         }
         $.ajax({
            type: 'GET',
            url: "/scrap/server-statistics",
            data: {
                type: type,
                order : order,
            },
            beforeSend: function () {
                $("#loading-image").show();
            },
        }).done(function(data) {
            $("#loading-image").hide();
            $("#detail-table tbody").empty().html(data.tbody);
            $("#total-count").text(data.count);
            if (data.links.length > 10) {
                $('ul.pagination').replaceWith(data.links);
            } else {
                $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
            }

        }).fail(function(response) {
            alert('No response from server');
        });
    }

    function filterByServer(type) {
         order = $('#server_change').val();
         console.log(order)
         $.ajax({
            type: 'GET',
            url: "/scrap/server-statistics",
            data: {
                type: type,
                order : order,
            },
            beforeSend: function () {
                $("#loading-image").show();
            },
        }).done(function(data) {
            $("#loading-image").hide();
            $("#detail-table tbody").empty().html(data.tbody);
            $("#total-count").text(data.count);
            if (data.links.length > 10) {
                $('ul.pagination').replaceWith(data.links);
            } else {
                $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
            }

        }).fail(function(response) {
            alert('No response from server');
        });
    } 

    function filterByText(type) {
         order = $('#server_text').val();
         $.ajax({
            type: 'GET',
            url: "/scrap/server-statistics",
            data: {
                type: type,
                order : order,
            },
            beforeSend: function () {
                $("#loading-image").show();
            },
        }).done(function(data) {
            $("#loading-image").hide();
            $("#detail-table tbody").empty().html(data.tbody);
            $("#total-count").text(data.count);
            if (data.links.length > 10) {
                $('ul.pagination').replaceWith(data.links);
            } else {
                $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
            }

        }).fail(function(response) {
            alert('No response from server');
        });
    }    

</script>
@endsection