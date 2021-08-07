@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', $title)

@section('content')
<style type="text/css">
	.preview-category input.form-control {
	  width: auto;
	}
	.mar-right-5 {
		margin-right: 1px;
	}
</style>

<div class="row" id="common-page-layout">
	<div class="col-lg-12 margin-tb">
        <h2 class="page-heading">{{$title}} <span class="count-text"></span></h2>
    </div>
    <br>
    <div class="col-lg-12 margin-tb">
    	<div class="row">
	    	<div class="col col-md-8">
				 <div class="row">
				 	<form method="get">
					 	<div class="form-group mar-right-5 col-md-3">
						    <label for="product_id">Product id</label>
						    <?php echo Form::select("product_id", [], null, ["class" => "form-control search-broduct-select", 'placeholder' => 'Select a product', "style" => "width:100%;"]); ?>
                            <span class="product-title-show"></span>
						</div>
						<div class="form-group mar-right-5 col-md-3">
						    <label for="country_code">Country</label>
						    <?php echo Form::select("country_code",[ "" => "-- None --"] + \App\SimplyDutyCountry::getSelectList(),null, ["class" => "form-control"]); ?>
						</div>
						<div class="form-group mar-right-2 col-md-3">
						    <label for="store_website">StoreWebsite</label>
						    <?php echo Form::select("store_website",\App\StoreWebsite::list(),null, ["class" => "form-control"]); ?>
						</div>
						<div class="form-group mar-right-5">
							<button class="btn btn-secondary calculate-price-and-duty" style="margin-top: 25px;"><i class="fa fa-calculator"></i></button>
						</div>
					</form>
				 </div>
		    </div>
		    <div class="col">
		    	<div class="h" style="margin-bottom:10px;">
					<div class="row">
		    			<form class="form-inline message-search-handler" method="post">
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
							  		<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-add-action">
						  				<img src="/images/add.png" style="cursor: default;">
						  			</button>
							  	</div>		
					  		</div>
				  		</form>
					</div>
		    	</div>
		    </div>
	    </div>
        <div class="col-md-12 margin-tb">
            <span class="calculated-result-display"></span>
        </div>
	    <div class="col-md-12 margin-tb">
	    	<span class="btn-danger">* Please note all price will be EUR</span>
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

@include("storewebsite::price-override.templates.list-template")
@include("storewebsite::price-override.templates.create-solution-template")
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/common-helper.js"></script>
<script type="text/javascript" src="/js/store-website-price-override.js"></script>
<script type="text/javascript">
	page.init({
		bodyView : $("#common-page-layout"),
		baseUrl : "<?php echo url("/"); ?>"
	});

	function formatProduct(product) {
        if (product.loading) {
            return product.sku;
        }
        if (product.sku) {
            return "<p> <b>Id:</b> " + product.id + (product.name ? " <b>Name:</b> " + product.name : "") + " <b>Sku:</b> " + product.sku + " </p>";
        }
    }

	jQuery('.search-broduct-select').select2({
        ajax: {
          url: '/productSearch/',
          dataType: 'json',
          delay: 750,
          data: function (params) {
            return {
              q: params.term, // search term
            };
          },
          processResults: function (data,params) {
            params.page = params.page || 1;
            return {
              results: data,
              pagination: {
                more: (params.page * 30) < data.total_count
              }
            };
          },
        },
        placeholder: 'Search for Product by id, Name, Sku',
        escapeMarkup: function (markup) { return markup; },
        minimumInputLength: 2,
        width: '100%',
        templateResult: formatProduct,
        templateSelection:function(product) {
           if(typeof product.name != "undefined") {
             $(".product-title-show").html("["+product.sku+"] => "+product.name) 
           } 
          return product.text || product.id;
        }
    });
</script>
@endsection

