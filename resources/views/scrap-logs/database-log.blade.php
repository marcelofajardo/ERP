@extends('layouts.app')

@section("styles")
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

@endsection
@section('content')
	<div class="row">
		<div class="col-lg-12 margin-tb">
		    <h2 class="page-heading">Databse Logs</h2>
		</div>
	</div>
	<form action="{{ action('ScrapLogsController@databaseLog') }}" method="get">
		<div class="mt-3 col-md-12">
			<div class="col-lg-2">
				<input class="form-control" type="text" id="search" placeholder="Search name" name="search" value="{{ $search }}">
			</div>
			<div class="col-lg-2">
				<button type="submit" id="tabledata" class="btn btn-image">
				    <img src="/images/filter.png">
				</button>
			</div>
		</div>
	</form>
	<div class="mt-3 col-md-12">
		<table class="table table-bordered table-striped" id="log-table">
		    <thead>
				<td>Index</td>
				<!-- <td>File Name</td> -->
				<td>Log Messages</td>
			</thead>
			<tbody id="log_popup_body">
				@php $count = 1;  @endphp
				@foreach($output as $key => $line)
					<tr>
	    				<td>{{$key+1}}</td>
	    				<!-- <td></td> -->
	    				<td>{{$line}}</td>
	    			</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	<div id="chat-list-history" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Log Messages</h4>
                </div>
                <div class="modal-body">
                	<div class="cls_log_popup">
                		<table class="table">
                		</table>
                	</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script>
	// $(document).ready(function(){
	// 	tableData(BASE_URL);
	// 	$("#tabledata").click(function(e) {
	// 		tableData(BASE_URL);
	// 	});
	// 	function tableData(BASE_URL) {
	// 		var search = $("input[name='search'").val() != "" ? $("input[name='search'").val() : null;
	// 		var date = $("#datepicker").val() !="" ? $("#datepicker").val() : null;
 //            var download = "?download=" + $("select[name='download_option'").val();

 //            if($("select[name='download_option'").val() == "yes") {
 //                window.location.href = BASE_URL+'?search='+search;
 //            }

	// 		$.ajax({
	// 			url: BASE_URL+"/database-log/"+search+"/"+date,
	// 			method:"get",
	// 			headers: {
	// 			    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	// 			  },
	// 			data:{},
	// 			cache: false,
	// 			success: function(data) {
	// 					console.log(data)
	// 					$("tbody").empty();
	// 					$.each(data.file_list, function(i,row){
	// 						$("tbody").append("<tr><td>"+(i+1)+"</td><td>"+row['foldername']+"</td><td><a href='scrap-logs/file-view/"+row['filename']+ '/' +row['foldername']+"' target='_blank'>"+row['filename']+"</a>&nbsp;<a href='javascript:;' onclick='openLasttenlogs(\""+row['scraper_id']+"\")'><i class='fa fa-weixin' aria-hidden='true'></i></a></td><td>"+row['log_msg']+"</td></tr>");
	// 					});
						
	// 				}
	// 		});
	// 	}
	// });
</script> 
@endsection