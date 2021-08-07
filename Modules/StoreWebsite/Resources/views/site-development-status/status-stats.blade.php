@extends('layouts.app')

@section('title', $title)

@section('content')
<style type="text/css">
	.preview-category input.form-control {
	  width: auto;
	}
</style>

<div class="row" id="common-page-layout">
	<div class="col-lg-12 margin-tb">
        <h2 class="page-heading">{{$title}} <span class="count-text"></span></h2>
    </div>
    <br>
    <div class="col-lg-12 margin-tb">
    	<!-- <div class="row" style="margin: 0px;">
	    	<div class="col col-md-9">
            <button type="button" class="btn btn-secondary btn-merge-status">Remarks</button>
		    </div>
	    </div>	 -->
        <!-- <br> -->

		<div class="col-md-12 margin-tb" id="page-view-result">
        <table class="table table-bordered" id="documents-table">
					<thead>
						<tr>
						<th width="4%">Sl no</th>
						<th>Website</th>
						<th>Status</th>
						<th>Remarks</th>
					    </tr>
					</thead>
					<tbody>
                    @foreach($storeWebsites as $key => $website)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$website->website}}</td>
                            <td>
                                @if($website->statusStats)
                                @foreach($website->statusStats as $stats)
                                    <span style="padding:5px 10px;">{{$stats->name}} : {{$stats->total}}</span>
                                @endforeach
                                @endif
                            </td>
                            <td>
                            <button type="button" class="btn btn-xs btn-secondary latest-remarks-btn" data-id="{{$website->id}}">Remarks</button>
                            <button type="button" class="btn btn-xs btn-secondary artwork-history-btn" data-id="{{$website->id}}">Artwork history</button>
                            </td>
                        </tr>
                    @endforeach
					</tbody>
				</table>
		</div>
	</div>
</div>
<div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
          50% 50% no-repeat;display:none;">
</div>
<div class="common-modal modal" role="dialog">
  	<div class="modal-dialog" role="document">
  	</div>	
</div>

<div id="latest-remarks-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        	<div class="modal-body">
    			<div class="col-md-12">
	        		<table class="table table-bordered">
					    <thead>
					      <tr>
					        <th>Sl no</th>
					        <th>Category</th>
					        <th>Remarks</th>
					      </tr>
					    </thead>
					    <tbody class="latest-remarks-list-view">
					    </tbody>
					</table>
				</div>
			</div>
           <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<div id="artwork-history-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        	<div class="modal-body">
    			<div class="col-md-12">
	        		<table class="table table-bordered">
					    <thead>
					      <tr>
					        <th>Sl no</th>
					        <th>Date</th>
					        <th>Title</th>
					        <th>Status</th>
					        <th>Username</th>
					      </tr>
					    </thead>
					    <tbody class="artwork-history-list-view">
					    </tbody>
					</table>
				</div>
			</div>
           <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).on('click', '.latest-remarks-btn', function (e) {
			websiteId = $(this).data('id');
            $.ajax({
                url: "/site-development/latest-reamrks/"+websiteId,
				type: 'GET',
				beforeSend: function() {
					$("#loading-image").show();
	           	},
                success: function (response) {
					console.log(response);
					var tr = '';
					for(var i=1;i<=response.data.length;i++) {
						tr = tr + '<tr><td>'+ i +'</td><td>'+response.data[i-1].title+'</td><td>'+response.data[i-1].remarks+'</td></tr>';
					}
					$("#latest-remarks-modal").modal("show");
					$(".latest-remarks-list-view").html(tr);
					$("#loading-image").hide();
                },
                error: function () {
					$("#loading-image").hide();
                }
            });
        });


		$(document).on('click', '.artwork-history-btn', function (e) {
			websiteId = $(this).data('id');
            $.ajax({
                url: "/site-development/artwork-history/all-histories/"+websiteId,
				type: 'GET',
				beforeSend: function() {
					$("#loading-image").show();
	           	},
                success: function (response) {
					console.log(response);
					var tr = '';
					for(var i=1;i<=response.data.length;i++) {
						tr = tr + '<tr><td>'+ i +'</td><td>'+response.data[i-1].date+'</td><td>'+response.data[i-1].title+'</td><td> Status changed from '+response.data[i-1].from_status+ ' to '+response.data[i-1].to_status+'</td><td>'+response.data[i-1].username+'</td></tr>';
					}
					$("#artwork-history-modal").modal("show");
					$(".artwork-history-list-view").html(tr);
					$("#loading-image").hide();
                },
                error: function () {
					$("#loading-image").hide();
                }
            });
        });
        
</script>
@endsection

