@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'Social strategy')

@section('styles')
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
<style type="text/css">
	.preview-category input.form-control {
	  width: auto;
	}

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
<div class="row" id="common-page-layout">
	<div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Social strategy <span class="count-text"></span></h2>
    </div>
    <br>
    <div class="col-lg-12 margin-tb">
    	<div class="row">
	    	<div class="col col-md-9">
		    	<div class="row">
	    			
				 </div> 		
		    </div>
		    <div class="col">
		    	<div class="h" style="margin-bottom:10px;">
		    		<form class="form-inline message-search-handler" onsubmit="event.preventDefault(); saveSubject();">
					  <div class="row">
				  		<div class="col">
				  			<div class="form-group">
							    <label for="title">Add Subject:</label>
							    <?php echo Form::text("title",request("title"),["class"=> "form-control","placeholder" => "Enter Subject","id" => "add-subject"]) ?>
						  	</div>
						  	<div class="form-group">
						  		<label for="button">&nbsp;</label>
						  		<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-search-action">
						  			<img src="/images/send.png" style="cursor: default;" >
						  		</button>
						  	</div>		
				  		</div>
					  </div>	
					</form>		
					<form class="form-inline handle-search">
					  <div class="row">
				  		<div class="col">
				  			<div class="form-group">
							    <label for="keyword">Search keyword:</label>
							    <?php echo Form::text("k",request("k"),["class"=> "form-control","placeholder" => "Enter keyword","id" => "enter-keyword"]) ?>
						  	</div>
						  	<div class="form-group">
						  		<label for="button">&nbsp;</label>
						  		<button style="display: inline-block;width: 10%" type="submit" class="btn btn-sm btn-image btn-search-keyword">
						  			<img src="/images/send.png" style="cursor: default;" >
						  		</button>
						  	</div>		
				  		</div>
					  </div>	
					</form>	
		    	</div>
		    </div>
	    </div>
		<div class="col-md-12 margin-tb infinite-scroll">
			<div class="row">
				<table class="table table-bordered" id="documents-table">
					<thead>
						<tr>
						<th width="10%"></th>
						<th width="25%">Description</th>
						<th width="15%">Action</th>
						<th width="25%">Communication</th>
						<th width="5%">Created</th>
						<th width="5%">Action</th>
					</tr>
					</thead>
					<tbody>
					@include("storewebsite::social-strategy.partials.data")
					</tbody>
				</table>
				{{ $subjects->appends(request()->capture()->except('page','pagination') + ['pagination' => true])->render() }}	
			</div>
		</div>
	</div>
</div>


<div id="editSubjectModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
		<form>
	            <input type="hidden" name="subject_id" id="subject_id" value="">
	            <div class="modal-header">
	                <h4 class="modal-title">Eit Subject</h4>
	            </div>
	            <div class="modal-body">
				    	@csrf
					    <div class="form-group">
					        <label for="document">Name:</label>
					        <input type="text" class="form-control" name="subject_title" id="subject_title">
					    </div>
					    
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default" onclick="submitSubjectChange()">Save</button>
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	            </div>
			</form>
        </div>
    </div>
</div>

<div id="chat-list-history" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Communication</h4>
                <input type="text" name="search_chat_pop"  class="form-control search_chat_pop" placeholder="Search Message" style="width: 200px;">
            </div>
            <div class="modal-body" style="background-color: #999999;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="file-upload-area-section" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
           <form action="{{ route("store-website.social-strategy.upload-documents", $website->id) }}" method="POST" enctype="multipart/form-data">
	            <input type="hidden" name="store_website_id" id="hidden-store-website-id" value="">
	            <input type="hidden" name="id" id="hidden-site-id" value="">
	            <input type="hidden" name="site_development_subject_id" id="hidden-site-subject-id" value="">
	            <div class="modal-header">
	                <h4 class="modal-title">Upload File(s)</h4>
	            </div>
	            <div class="modal-body" style="background-color: #999999;">
				    	@csrf
					    <div class="form-group">
					        <label for="document">Documents</label>
					        <div class="needsclick dropzone" id="document-dropzone">

					        </div>
					    </div>
					    
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default btn-save-documents">Save</button>
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	            </div>
			</form>
        </div>
    </div>
</div>
<div id="file-upload-area-list" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        	<div class="modal-body">
        		<table class="table table-bordered">
				    <thead>
				      <tr>
				        <th width="5%">No</th>
				        <th width="45%">Link</th>
				        <th width="25%">Send To</th>
				        <th width="25%">Action</th>
				      </tr>
				    </thead>
				    <tbody class="display-document-list">
				    </tbody>
				</table>
			</div>
           <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="remark-area-list" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        	<div class="modal-body">
    			<div class="col-md-12">
	    			<div class="col-md-8" style="padding-bottom: 10px;">
	    				<textarea class="form-control" col="5" name="remarks" data-id="" id="remark-field"></textarea>
	    			</div>
	    			<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-remark-field">
						<img src="/images/send.png" style="cursor: default;" >
					</button>
				</div>
    			<div class="col-md-12">
	        		<table class="table table-bordered">
					    <thead>
					      <tr>
					        <th width="5%">No</th>
					        <th width="45%">Remark</th>
					        <th width="25%">BY</th>
					        <th width="25%">Date</th>
					      </tr>
					    </thead>
					    <tbody class="remark-action-list-view">
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

@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.7/jquery.jscroll.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
<script type="text/javascript">

function refreshPage(){
		$.ajax({
		    url: '/store-website/'+{{$website->id}}+'/social-strategy',
		    dataType: "json",
		    data: {},
		}).done(function (data) {
		    $("#documents-table tbody").empty().html(data.tbody);
		}).fail(function (jqXHR, ajaxOptions, thrownError) {
		    alert('No response from server');
		});
	}
	
	function saveSubject() {
		var text = $('#add-subject').val()
		var website_id = $('#website_id').val()
		if(text === ''){
			alert('Please Enter Text');
		}else{
			$.ajax({
				url: '/store-website/'+{{$website->id}}+'/social-strategy/add-subject',
				type: 'POST',
				dataType: 'json',
				data: {text: text , "_token": "{{ csrf_token() }}"},
				beforeSend: function () {
                    $("#loading-image").show();
                },
			})
			.done(function(data) {
				$('#add-subject').val('')
				refreshPage()
				$("#loading-image").hide();
				console.log("success");
			})
			.fail(function(data) {
				$('#add-subject').val('')
				console.log("error");
			});
			
		}
	}

	$(function(){
		$(document).on("focusout" , ".save-item" , function() {
			subject = $(this).data("subject")
			type = $(this).data("type")
			site = $(this).data("site")
			var text = $(this).val();
		    $.ajax({
				url: '/store-website/'+{{$website->id}}+'/social-strategy/add-strategy',
				type: 'POST',
				dataType: 'json',
				data: {"_token": "{{ csrf_token() }}" , subject : subject , type : type , text : text ,site : site},
				beforeSend: function () {
                    $("#loading-image").show();
                },
			})
			.done(function(data) {
				console.log(data)
				refreshPage()
				$("#loading-image").hide();
				console.log("success");
			})
			.fail(function(data) {
				console.log(data)
				$("#loading-image").hide();
				console.log("error");
			});
		});

		$(document).on("change" , ".save-item-select" , function() {
			subject = $(this).data("subject")
			type = $(this).data("type")
			site = $(this).data("site")
			var text = $(this).val();

		    $.ajax({
				url: '/store-website/'+{{$website->id}}+'/social-strategy/add-strategy',
				type: 'POST',
				dataType: 'json',
				data: {"_token": "{{ csrf_token() }}" , subject : subject , type : type , text : text , site : site},
			})
			.done(function(data) {
				console.log(data)
				refreshPage()
				console.log("success");
			})
			.fail(function(data) {
				console.log(data)
				console.log("error");
			});
		});


		$(document).on("click",".btn-remark-field",function() {
			var id  = $("#remark-field").data("id");
			var val = $("#remark-field").val();
			$.ajax({
				url: '/store-website/'+{{$website->id}}+'/social-strategy/remarks',
				type: 'POST',
				headers: {
		      		'X-CSRF-TOKEN': "{{ csrf_token() }}"
		    	},
		    	data : {remark : val,id : id},
				beforeSend: function() {
					$("#loading-image").show();
	           	}
			}).done(function (response) {
				$("#loading-image").hide();
				$("#remark-field").val("");
				toastr["success"]("Remarks fetched successfully");
				var html = "";
				$.each(response.data,function(k,v){
					html += "<tr>";
						html += "<td>"+v.id+"</td>";
						html += "<td>"+v.remarks+"</td>";
						html += "<td>"+v.created_by+"</td>";
						html += "<td>"+v.created_at+"</td>";
					html += "</tr>";
				});
				$("#remark-area-list").find(".remark-action-list-view").html(html);
				//$("#remark-area-list").modal("show");
				//$this.closest("tr").remove();
			}).fail(function (jqXHR, ajaxOptions, thrownError) {
				toastr["error"]("Oops,something went wrong");
				$("#loading-image").hide();
			});
		});
	});


	function editSubject(id){
			$.ajax({
			url: '/store-website/'+{{$website->id}}+'/social-strategy/edit-subject?id='+id,
			type: 'GET',
			dataType: 'json',
			beforeSend: function () {
                    $("#loading-image").show();
                },
		})
		.done(function(data) {
			$("#loading-image").hide();
			$('#editSubjectModal').modal('show');
			$('#subject_title').val(data.data.title);
			$('#subject_id').val(data.data.id);
			console.log("success");
		})
		.fail(function(data) {
			console.log(data)
			refreshPage()
			console.log("error");
		});
	}

	function submitSubjectChange(){

		subject_title = $('#subject_title').val();
		id = $('#subject_id').val();
		$.ajax({
			url: '/store-website/'+{{$website->id}}+'/social-strategy/edit-subject',
			type: 'POST',
			dataType: 'json',
			data: {subject_title: subject_title , "_token": "{{ csrf_token() }}" , id : id},
			beforeSend: function () {
                    $("#loading-image").show();
                },
		})
		.done(function(data) {
			refreshPage()
			$("#loading-image").hide();
			$('#editSubjectModal').modal('hide');
		})
		.fail(function(data) {
			console.log(data)
			refreshPage()
			console.log("error");
		});
	}



	
	$(document).on('click', '.send-message-site', function() {
		site = $(this).data("id")
		message = $('#message-'+site).val();
		userId = $('#user-'+site+' option:selected').val();
		if(site){
			$.ajax({
				url: '/whatsapp/sendMessage/social_strategy',
				dataType: "json",
				type: 'POST',
				data: { 'social_strategy_id' : site , 'message' : message , 'user_id' : userId , "_token": "{{ csrf_token() }}" , 'status' : 2},
				beforeSend: function() {
					$('#message-'+site).attr('disabled', true);
               	}
			}).done(function (data) {
				$('#message-'+site).attr('disabled', false);
				$('#message-'+site).val('');
			}).fail(function (jqXHR, ajaxOptions, thrownError) {
			    alert('No response from server');
			});
		}else{
			alert('Site is not saved please enter value or select User');
		} 
    });

 

	$(document).on("click",".btn-file-upload",function() {
		var $this = $(this);
		$("#file-upload-area-section").modal("show");
		$("#hidden-store-website-id").val($this.data("store-website-id"));
		$("#hidden-site-id").val($this.data("site-id"));
		$("#hidden-site-subject-id").val($this.data("site-subject-id"));
	});

	$(document).on("click",".btn-file-list",function(e) {
		e.preventDefault();
		var $this = $(this);
		var id = $(this).data("site-id");
		$.ajax({
			url: '/store-website/'+{{$website->id}}+'/social-strategy/list-documents?id='+id,
			type: 'GET',
			headers: {
	      		'X-CSRF-TOKEN': "{{ csrf_token() }}"
	    	},
	    	dataType:"json",
			beforeSend: function() {
				$("#loading-image").show();
           	}
		}).done(function (response) {
			$("#loading-image").hide();
			var html = "";
			$.each(response.data,function(k,v){
				html += "<tr>";
					html += "<td>"+v.id+"</td>";
					html += "<td>"+v.url+"</td>";
					html += "<td><div class='form-row'>"+v.user_list+"</div></td>";
					html += '<td><a class="btn-secondary" href="'+v.url+'" data-site-id="'+v.site_id+'" target="__blank"><i class="fa fa-download" aria-hidden="true"></i></a>&nbsp;<a class="btn-secondary link-delete-document" data-site-id="'+v.site_id+'" data-id='+v.id+' href="_blank"><i class="fa fa-trash" aria-hidden="true"></i></a>&nbsp;<a class="btn-secondary link-send-document" data-site-id="'+v.site_id+'" data-id='+v.id+' href="_blank"><i class="fa fa-comment" aria-hidden="true"></i></a></td>';
				html += "</tr>";
			});
			$(".display-document-list").html(html);
			$("#file-upload-area-list").modal("show");
		}).fail(function (jqXHR, ajaxOptions, thrownError) {
			toastr["error"]("Oops,something went wrong");
			$("#loading-image").hide();
		});
	});

	$(document).on("click",".btn-save-documents",function(e){
		var id = $("#hidden-site-id").val();
		e.preventDefault();
		var $this = $(this);
		var formData = new FormData($this.closest("form")[0]);
		$.ajax({
			url: '/store-website/'+{{$website->id}}+'/social-strategy/save-documents?id='+id,
			type: 'POST',
			headers: {
	      		'X-CSRF-TOKEN': "{{ csrf_token() }}"
	    	},
	    	dataType:"json",
			data: $this.closest("form").serialize(),
			beforeSend: function() {
				$("#loading-image").show();
           	}
		}).done(function (data) {
			$("#loading-image").hide();
			toastr["success"]("Document uploaded successfully");
			location.reload();
		}).fail(function (jqXHR, ajaxOptions, thrownError) {
			toastr["error"](jqXHR.responseJSON.message);
			$("#loading-image").hide();
		});
	});

	
	$(document).on("click",".link-send-document",function(e) {
		e.preventDefault();
		var id = $(this).data("id");
		var site_id = $(this).data("site-id");
		var user_id = $(this).closest("tr").find(".send-message-to-id").val();
		$.ajax({
			url: '/store-website/'+{{$website->id}}+'/social-strategy/send-document',
			type: 'POST',
			headers: {
	      		'X-CSRF-TOKEN': "{{ csrf_token() }}"
	    	},
	    	dataType:"json",
			data: { id : id , site_id : site_id, user_id: user_id},
			beforeSend: function() {
				$("#loading-image").show();
           	}
		}).done(function (data) {
			$("#loading-image").hide();
			toastr["success"]("Document sent successfully");
		}).fail(function (jqXHR, ajaxOptions, thrownError) {
			toastr["error"]("Oops,something went wrong");
			$("#loading-image").hide();
		});

	});

	$(document).on("click",".link-delete-document",function(e) {
		e.preventDefault();
		var id = $(this).data("id");
		var $this = $(this);
		if(confirm("Are you sure you want to delete records ?")) {
			$.ajax({
				url: '/store-website/'+{{$website->id}}+'/social-strategy/delete-document',
				type: 'POST',
				headers: {
		      		'X-CSRF-TOKEN': "{{ csrf_token() }}"
		    	},
		    	dataType:"json",
				data: { id : id},
				beforeSend: function() {
					$("#loading-image").show();
	           	}
			}).done(function (data) {
				$("#loading-image").hide();
				toastr["success"]("Document deleted successfully");
				$this.closest("tr").remove();
			}).fail(function (jqXHR, ajaxOptions, thrownError) {
				toastr["error"]("Oops,something went wrong");
				$("#loading-image").hide();
			});
		}
	});

	$(document).on("click",".btn-store-development-remark",function(e) {
		var id = $(this).data("site-id");
		$.ajax({
			url: '/store-website/'+{{$website->id}}+'/social-strategy/remarks',
				type: 'GET',
				headers: {
		      		'X-CSRF-TOKEN': "{{ csrf_token() }}"
		    	},
				data: {
					id:id
				},
				beforeSend: function() {
					$("#loading-image").show();
	           	}
			}).done(function (response) {
				$("#loading-image").hide();
				toastr["success"]("Remarks fetched successfully");

				var html = "";
				
				$.each(response.data,function(k,v){
					html += "<tr>";
						html += "<td>"+v.id+"</td>";
						html += "<td>"+v.remarks+"</td>";
						html += "<td>"+v.created_by+"</td>";
						html += "<td>"+v.created_at+"</td>";
					html += "</tr>";
				});

				$("#remark-area-list").find("#remark-field").attr("data-id",id);
				$("#remark-area-list").find(".remark-action-list-view").html(html);
				$("#remark-area-list").modal("show");
				//$this.closest("tr").remove();
			}).fail(function (jqXHR, ajaxOptions, thrownError) {
				toastr["error"]("Oops,something went wrong");
				$("#loading-image").hide();
			});
	});

	var uploadedDocumentMap = {}
  	Dropzone.options.documentDropzone = {

    	url: '{{ route("store-website.social-strategy.upload-documents", $website->id) }}',
    	maxFilesize: 20, // MB
    	addRemoveLinks: true,
    	headers: {
      		'X-CSRF-TOKEN': "{{ csrf_token() }}"
    	},
    	success: function (file, response) {
      		$('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
      		uploadedDocumentMap[file.name] = response.name
    	},
    	removedfile: function (file) {
      		file.previewElement.remove()
      		var name = ''
      		if (typeof file.file_name !== 'undefined') {
        		name = file.file_name
      		} else {
        		name = uploadedDocumentMap[file.name]
      		}
      		$('form').find('input[name="document[]"][value="' + name + '"]').remove()
    	},
    	init: function () {
	      
    	}
  }

</script>

@endsection