@extends('layouts.app')
@section('favicon' , 'task.png')

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
    	<div class="row">
	    	<div class="col col-md-9">
		    	<div class="row">
	    			<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-add-action">
		  				<img src="/images/add.png" style="cursor: default;">
		  			</button>
				 </div> 		
		    </div>
		    <div class="col">
		    	<div class="h" style="margin-bottom:10px;">
		    		<form class="form-inline message-search-handler flex-end" method="post">
					  <div class="row">
				  		<div class="col">
				  			<div class="form-group">
							    <label for="keyword">Keyword:</label>
							    <?php echo Form::text("keyword",request("keyword"),["class"=> "form-control","placeholder" => "Enter keyword"]) ?>
						  	</div>
						  	<div class="form-group">
						  		<label for="button">&nbsp;</label>
						  		<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-search-action">
						  			<img src="/images/search.png" style="cursor: default;">
						  		</button>
						  	</div>		
				  		</div>
					  </div>	
					</form>	
		    	</div>
		    </div>
	    </div>	
		<div class="col-md-12 margin-tb" id="page-view-result">

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
<div id="emails" class="modal fade" role="dialog">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Email List</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
            	<div class="table-responsive" style="margin-top:20px;">
	            	<table class="table table-bordered text-nowrap">
	            		<tr>
	            			<th>Date</th>
	            			<th>Sender</th>
	            			<th>Receiver</th>
	            			<th>Mail Type</th>
	            			<th>Subject</th>
	            			<th width="30%">Body</th>
	            		</tr>
	            		<tbody class="email-content"></tbody>
	            	</table>
	            </div>
            </div>
            <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
        </div>
    </div>
</div>
@include("digital-marketing.templates.list-template")
@include("digital-marketing.templates.create-website-template")
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/common-helper.js"></script>
<script type="text/javascript" src="/js/digital-marketing.js"></script>

<script type="text/javascript">
	page.init({
		bodyView : $("#common-page-layout"),
		baseUrl : "<?php echo url("/"); ?>"
	});

	$(document).ready(function(){

	$(document).on("change",".upload_file",function(){
		//alert(0);
		 var fd = new FormData();
		 var files = $(this)[0].files;
		 var fileArray = []
		console.log(files)
		// // Check file selected or not
		 if(files.length > 0 ){

			$.each(files,function(i,e){
				console.log(e)
				fd.append('file[]',e);
			})
			fd.append('id',$(this).data("id"))
			fd.append('_token',"{{ csrf_token() }}");
			fd.append('type',"marketing")
			console.log(fd)
			$.ajax({
				url: '{{route("digital-marketing.saveimages")}}',
				type: 'post',
				data: fd,
				contentType: false,
				processData: false,
				success: function(response){
					console.log(response)
					toastr['success'](response.msg, 'Success');
				},
			});
		 }else{
		 alert("Please select a file.");
		 }
	});
});
	$(document).on('click','.show-emails',function(event){
		console.log($(this).data('id'));
		$.ajax({
		      type: 'POST',
		      url: '{{action("DigitalMarketingController@getEmails")}}',
		      data: { 
		        _token: "{{ csrf_token() }}",
		        id: $(this).data('id'),
		      },
		      beforeSend: function () {
		          $("#loading-image").show();
		      }
		  }).done(function (data) {
		    console.log(data.strength);
		      $("#plan-action").modal("hide");
		      //toastr["success"]('Data save successfully.');
		      var $html='';
		      $.each(data, function(i, item) {
		      	console.log(item);
		          $html+="<tr>";
		          $html+="<td>"+item.created_at+"</td>";
		          $html+="<td>"+item.from+"</td>";
		          $html+="<td>"+item.to+"</td>";
		          $html+="<td>"+item.type+"</td>";
		          $html+="<td>"+item.subject+"</td>";
		          $html+="<td>"+item.message+"</td>";
		          $html+="</tr>";
		      });
		      $('.email-content').html($html)
		      $("#emails").modal("show");
		      $("#loading-image").hide();
		      //email-content
				//#emails
		  }).fail(function (jqXHR, ajaxOptions, thrownError) {
		      $("#plan-action").modal("hide");
		      toastr["error"]('No record found!');
		      $("#loading-image").hide();
		  });
	});
</script>

@endsection

