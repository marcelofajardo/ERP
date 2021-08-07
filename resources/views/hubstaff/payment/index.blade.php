@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', $title)

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
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
		  			<a href="?run_command=true">
			  			<button class="btn btn-sm btn-secondary">
			  				Run Payment Command
			  			</button>
			  		</a>
		  		</div>
		    </div>
		    <div class="col">
		    	<div class="h" style="margin-bottom:10px;">
		    		<form class="form-inline message-search-handler" method="post">
					  <div class="row">
			  			<div class="form-group">
						    <label for="keyword">Keyword:</label>
						    <?php echo Form::text("keyword",request("keyword"),["class"=> "form-control","placeholder" => "Enter keyword"]) ?>
					  	</div>
					  	<div class="form-group">
		                    <strong>Date Range</strong>
		                    <input type="text" value="<?php echo date("Y-m-d"); ?>" name="start_date" hidden/>
		                    <input type="text" value="<?php echo date("Y-m-d"); ?>" name="end_date" hidden/>
		                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
		                        <i class="fa fa-calendar"></i>&nbsp;
		                        <span></span> <i class="fa fa-caret-down"></i>
		                    </div>
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

@include("hubstaff.payment.templates.list-template")
@include("hubstaff.payment.templates.create-website-template")
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/common-helper.js"></script>
<script type="text/javascript" src="/js/hubstaff-payment.js"></script>

<script type="text/javascript">
	page.init({
		bodyView : $("#common-page-layout"),
		baseUrl : "<?php echo url("/"); ?>"
	});
</script>

@endsection

