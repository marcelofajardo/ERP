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
	    	<div class="col col-md-10">
		    	<div class="h" style="margin-bottom:10px;">
		    		<form class="form-inline message-search-handler" method="post">
		    		<?php echo csrf_field(); ?>	
					  <div class="row">
				  		<div class="col">
				  			<div class="form-group">
							    <label for="hs_code">HS Code:</label>
							    <?php echo Form::text("hs_code",request("hs_code","4203.30.0000"),["class"=> "form-control","placeholder" => "Enter hs code"]) ?>
						  	</div>
						  	<div class="form-group">
							    <label for="item_value">Item Value:</label>
							    <?php echo Form::text("item_value",request("item_value","500"),["class"=> "form-control","placeholder" => "Enter item value"]) ?>
						  	</div>
						  	<div class="form-group">
							    <label for="hs_code">Origin Country:</label>
							    <?php echo Form::select("origin_country",\App\SimplyDutyCountry::getSelectList(),request("origin_country","AE"),[
							    	"class"=> "form-control",
							    	"data-placeholder" => "Select origin country",
							    ]) ?>
						  	</div>
						  	<div class="form-group">
							    <label for="hs_code">Destination country:</label>
							    <?php echo Form::textarea("destination_country",request("destination_country"),[
							    	"class"=> "form-control",
							    	"placeholder" => "Enter destination country",
							    	"rows" => 2
							    ]) ?>
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
		    <div class="col col-md-2">
		    	<div class="h" style="margin-bottom:10px;">
			    	<button class="btn btn-secondary btn-create-group-modal">+ Create a group</button>
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

@include("country-duty.templates.list-template")
@include("country-duty.templates.create-website-template")
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/common-helper.js"></script>
<script type="text/javascript" src="/js/country-duty.js"></script>
<script type="text/javascript">
	page.init({
		bodyView : $("#common-page-layout"),
		baseUrl : "<?php echo url("/"); ?>"
	});
</script>
@endsection

