@extends('layouts.app')

@section("styles")
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

@endsection
@section('content')
	<div class="row">
		<div class="col-lg-12 margin-tb">
		    <h2 class="page-heading">Scrap Logs</h2>
		</div>
	</div>
	<div class="mt-3 col-md-12">
		<div class="col-lg-1">
			<select name="date" id="datepicker" class="form-control">
				@for($i=0; $i<=31; $i++)
					<option value="{{$i}}" @if((date("d") - 1) == $i) selected @endif>{{$i}}</option>
				@endfor
			</select>
		</div>
		<div class="col-lg-2">
			<select name="date" id="datepicker" class="form-control server_id-value">
				<option value="">Select Server</option>
					@foreach($servers as $server)
						<option value="{{ $server['server_id'] }}")>{{$server['server_id'] }}</option>
					@endforeach
			</select>
		</div>					
		<div class="col-lg-2">
			<input class="form-control" type="text" id="search" placeholder="Search name" name="search" value="{{ $name }}">
		</div>
        <div class="col-lg-2">
            <select class="form-control" name="download_option">
                <option value="no">No</option>
                <option value="yes">Yes</option>
            </select>
        </div>
		<div class="col-lg-1">
			<button type="button" id="tabledata" class="btn btn-image">
			<img src="/images/filter.png">
			</button>
		</div>
		<div class="col-lg-2 text-rights">
			<button class ="btn-dark" type="button" onclick="window.location='{{url('development/issue/create')}}'">Create an Issue</button>
		</div>
		<div class="col-lg-2 text-rights">
			<button class ="btn-dark" type="button" data-toggle="modal" data-target="#status-create">Create Status</button>
		</div>
	</div>
	<div class="mt-3 col-md-12">
		<table class="table table-bordered table-striped" id="log-table">
		    <thead>
			    <tr>
			    	<th width="10%">S.No</th>
			        <th width="10%">FolderName</th>
			        <th width="30%">FileName</th>
			        <th width="30%">Log Message</th>
			        
			        <th width="">Status</th>
			        <th width="">Remarks</th>
			    </tr>
		    	<tbody>
		    	</tbody>
		    </thead>
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
                			<thead>
                				<td>Scraper ID</td>
                				<td>File Name</td>
                				<td>Log Messages</td>
                			</thead>
                			<tbody id="log_popup_body">
                				
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

    <div id="status-create" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Status</h4>
                </div>
                <form action="{{ action('ScrapLogsController@store') }}" class="" method="POST">
                	@csrf
	                <div class="modal-body">
	                	<div class="cls_log_popup">
	                		<div class="col-md-12 mb-4">
	                			<input type="text" class="form-control" name="errortext" placeholder="Error Text Here">
	                		</div>
	                		<div class="col-md-12 mb-4">
	                			<input type="text" class="form-control" name="errorstatus" placeholder="Error Status Here">
	                		</div>
	                	</div>
	                </div>
	                <div class="modal-footer">
	                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	                    <button type="submit" class="btn btn-default">Save</button>
	                </div>
	            </form>
            </div>
        </div>
    </div>
    <div id="makeRemarkModal" class="modal fade" role="dialog">
	  <div class="modal-dialog <?php echo (!empty($type) && ($type == 'scrap' || $type == 'email')) ? 'modal-lg' : ''  ?>">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <h4 class="modal-title">Remarks</h4>
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	      </div>

	      <div class="modal-body">
	        <?php if((!empty($type) && ($type == 'scrap' || $type == 'email'))) {  ?>
	          <form id="filter-module-remark">
	            <div class="form-group">
	              <label for="filter_auto">Remove auto</label>
	              <input type="checkbox" name="filter_auto" class="filter-auto-remark">
	            </div>
	          </form>
	        <?php } ?>
	        <?php if((!empty($type) && ($type == 'scrap' || $type == 'email'))) {  ?>
	          <table class="table fixed_header table-bordered">
	              <thead class="thead-dark">
	                <tr>
	                  <th width="50%">Comment</th>
	                  <th width="10%">Created By</th>
	                  <th width="10%">Created At</th>
	                </tr>
	              </thead>
	              <tbody id="remark-list"></tbody>
	            </table>
	        <?php } else{ ?>
	        <div class="list-unstyled" id="remark-list">

	        </div>
	        <?php } ?>
	        <form id="add-remark">
	          <input type="hidden" name="id" value="">
	          <div class="form-group">
	            <textarea rows="2" name="remark" class="form-control" placeholder="Start the Remark"></textarea>
	          </div>
	          {{-- We dont need following settings for email page --}}
	          @if (empty($type) || $type != 'email')
	            <div class="form-group">
	              <label><input type="checkbox" class="need_to_send" value="1">&nbsp;Need to Send Message ?</label>
	            </div>
	            <div class="form-group">
	              <label><input type="checkbox" class="inlcude_made_by" value="1">&nbsp;Want to include Made By ?</label>
	            </div>
	          @endif
	          <button type="button" class="btn btn-secondary btn-block mt-2" id="{{ (!empty($type) && $type == 'scrap') ? 'scrapAddRemarkbutton' : 'addRemarkButton' }}">Add</button>
	        </form>
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
	$(document).ready(function() 
	{
		tableData(BASE_URL);
		$("#tabledata").click(function(e) {
			tableData(BASE_URL);
		});
		function tableData(BASE_URL) {
			var search = $("input[name='search'").val() != "" ? $("input[name='search'").val() : null;
			var date = $("#datepicker").val() !="" ? $("#datepicker").val() : null;
            var download = "?download=" + $("select[name='download_option'").val();
			var server_id = $('.server_id-value').val();


            if($("select[name='download_option'").val() == "yes") {
                window.location.href = BASE_URL+"/scrap-logs/fetch/"+search+"/"+date+"/"+download;
            }

			$.ajax({
				url: BASE_URL+"/scrap-logs/fetch/"+search+"/"+date,
				method:"get",
				headers: {
				    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				  },
				data:{'server_id':server_id},
				cache: false,
				success: function(data) {
						console.log(data)
						$("tbody").empty();
						$.each(data.file_list, function(i,row){
							$("tbody").append("<tr><td>"+(i+1)+"</td><td>"+row['foldername']+"</td><td><a href='scrap-logs/file-view/"+row['filename']+ '/' +row['foldername']+"' target='_blank'>"+row['filename']+"</a>&nbsp;<a href='javascript:;' onclick='openLasttenlogs(\""+row['scraper_id']+"\")'><i class='fa fa-weixin' aria-hidden='true'></i></a></td><td>"+row['log_msg']+"</td><td>"+row['status']+"</td><td><button style='padding:3px;' type='button' class='btn btn-image make-remark d-inline' data-toggle='modal' data-target='#makeRemarkModal' data-name='"+row['scraper_id']+"'><img width='2px;' src='/images/remark.png'/></button></td></tr>");
						});
						
					}
			});
		}
	});
	function openLasttenlogs(scraper_id){
		$.ajax({
			url: BASE_URL+"/fetchlog",
			method:"get",
			headers: {
			    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			  },
			data:{},
			cache: false,
			success: function(data) {
				$("#log_popup_body").empty();
				$.each(data.file_list, function(i,row){
					$("#log_popup_body").append("<tr><td>"+row['scraper_id']+"</td><td>"+row['filename']+"</td><td>"+row['log_msg']+"</td></tr>");
				});
			}
		});
		$('#chat-list-history').modal("show");
	}
	$(document).on('click', '.make-remark', function (e) {
            e.preventDefault();

            var name = $(this).data('name');

            $('#add-remark input[name="id"]').val(name);

            $.ajax({
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("scrap.getremark") }}',
                data: {
                    name: name
                },
            }).done(response => {
                var html = '';
                var no = 1;
                $.each(response, function (index, value) {
                    /*html += '<li><span class="float-left">' + value.remark + '</span><span class="float-right"><small>' + value.user_name + ' updated on ' + moment(value.created_at).format('DD-M H:mm') + ' </small></span></li>';
                    html + "<hr>";*/
                    html += '<tr><td>' + value.remark + '</td><td>' + value.user_name + '</td><td>' + moment(value.created_at).format('DD-M H:mm') + '</td></tr>';
                    no++;
                });
                $("#makeRemarkModal").find('#remark-list').html(html);
            });
        });
</script> 
@endsection