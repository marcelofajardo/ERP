@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', $title)

@section('content')
<style type="text/css">
	.preview-category input.form-control {
	  width: auto;
	}
    .badge-danger {
        color: #fff;
        background-color: #dc3545;
    }
    .badge-success {
        color: #fff;
        background-color: #28a745;
    }
</style>

<div class="row" id="common-page-layout">
	<div class="col-lg-12 margin-tb">
        <h2 class="page-heading">{{$title}} <span class="count-text"></span></h2>
    </div>
    <br>
    <div class="col-lg-12 margin-tb">
    	<div class="row">
	    	<div class="col col-md-6">
		    	<div class="row">
	    			<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-add-action" data-toggle="modal" data-target="#colorCreateModal">
		  				<img src="/images/add.png" style="cursor: default;">
		  			</button>
				 </div>
		    </div>
		    <div class="col col-md-6">
		    	<div class="h" style="float: right;">
					<div class="row">
		    			<form class="form-inline message-search-handler" method="get">
                            <div class="form-group">
                                <label for="store_website_id">Store Websites:</label>
                                <?php echo Form::select("store_website_id",$storeWebsites,request("store_website_id"),["class"=> "form-control","placeholder" => "Select Store website"]) ?>
                            </div>
						  	<div class="form-group">
						  		<label for="button">&nbsp;</label>
						  		<button type="submit" style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-search-action">
						  			<img src="/images/search.png" style="cursor: default;">
						  		</button>
						  	</div>
				  		</form>
					</div>
		    	</div>
		    </div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-success" id="alert-msg" style="display: none;">
					<p></p>
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

<div id="add-default-store" class="modal" role="dialog">
  	<div class="modal-dialog" role="document">
		<div class="modal-content">
	      <div class="modal-header">
	        <h4 class="modal-title">Add Default Store</h4>
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	      </div>
	     <div class="modal-body">
			<div class="form-group">
          		<div class="form-group">
            		<strong>Store websites</strong>
            		<?php echo Form::select("store_website_id",$storeWebsites,null, ["class" => "form-control default-store-website-select"]);  ?>
          		</div>
	        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-secondary create-default-stores">Create Store</button>
        </div>
	    </div>
  	</div>
</div>


@include("storewebsite::product-attribute.templates.list-template")
@include("storewebsite::product-attribute.templates.create-website-template")

<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="{{ asset('/js/common-helper.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/website-product-attribute.js') }}"></script>

<script type="text/javascript">
	page.init({
		bodyView : $("#common-page-layout"),
		baseUrl : "<?php echo url("/"); ?>"
	});
</script>
@endsection