@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', $title)

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<style type="text/css">
	.preview-category input.form-control {
	  width: auto;
	}
	.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover 
	{
		background-color : 	#6c757d	;
		border-color : #6c757d;
	}
	.page-item.active .page-link {
		background-color : 	#6c757d	;
		border-color : #6c757d;
	}
	.pagination>li>a, .pagination>li>span {
		color: #6c757d;
	}
</style>

<div class="row" id="common-page-layout">
	<div class="col-lg-12 margin-tb">
        <h2 class="page-heading">{{$title}} <span class="count-text"></span></h2>
    </div>
    <br>
    <div class="col-lg-12 margin-tb">
    	<div class="row">
    		<div class="col">
	    		<div class="h" style="margin-bottom:10px;">
		    		<form class="form-inline message-search-handler" method="post">
					  <div class="row">
				  			<div class="form-group ml-2	">
							    <label for="keyword">Keyword:</label>
							    <?php echo Form::text("keyword",request("keyword"),["class"=> "form-control","placeholder" => "Enter keyword"]) ?>
						  	</div>
						  	<div class="form-group ml-2">
							    <label for="created_by">User:</label>
							    <?php echo Form::select("created_by",["" => "Select -Any"] + \App\User::selectList(),request("created_by"),["class"=> "form-control select2","data-placeholder" => "Enter name"]) ?>
						  	</div>
						  	<div class="form-group ml-2">
							    <label for="created_at">Created at:</label>
							    <?php echo Form::text("created_at",request("created_at"),["class"=> "form-control datepicker-block","placeholder" => "Enter date"]) ?>
						  	</div>
						  	<div class="form-group">
						  		<label for="button">&nbsp;</label>
						  		<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-search-action">
						  			<img src="/images/search.png" style="cursor: default;">
						  		</button>
						  	</div>		
				  	 	</div>	
					</form>	
		    	</div>
		    </div>
		    <div class="col">
		    	<a data-toggle="collapse" href="#show-total-update-category" role="button" aria-expanded="false" aria-controls="show-total-update-category">
                   Show user updated
                </a>
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

@include("listing-history.templates.list-template")
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/common-helper.js"></script>
<script type="text/javascript" src="/js/listing-history.js"></script>

<script type="text/javascript">
	page.init({
		bodyView : $("#common-page-layout"),
		baseUrl : "<?php echo url("/"); ?>"
	});
</script>

@endsection

