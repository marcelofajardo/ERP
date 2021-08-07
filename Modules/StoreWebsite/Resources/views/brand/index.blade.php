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
        <h2 class="page-heading">{{ $title }} ({{ $brands->count() }})<span class="count-text"></span></h2>
    </div>
    <br>
    @if(session()->has('success'))
	    <div class="col-lg-12 margin-tb">
		    <div class="alert alert-success">
		        {{ session()->get('success') }}
		    </div>
		</div>    
	@endif
    <div class="col-lg-12 margin-tb">
    	<div class="row" style="margin-bottom: 10px;">
	    	<div class="col col-md-9">
		    	<div class="row">
	    			<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-add-action">
		  				<img src="/images/add.png" style="cursor: default;">
		  			</button>
		  			<form class="form-inline message-search-handler" action="?" method="get">
		  				<input type="hidden" name="push" value="1">
				  		<div class="form-group">
						    <label for="keyword">Store Wesbite:</label>
						    <?php echo Form::select("store_website_id",\App\StoreWebsite::pluck('title','id')->toArray(),request("store_website_id"),["class"=> "form-control select2","placeholder" => "Select Website"]) ?>
					  	</div>
					  	&nbsp;
						<div class="form-group">
					  		<label for="button">&nbsp;</label>
					  		<button type="submit" class="btn btn-secondary">
					  			Push Brand
					  		</button>
					  	</div>		
			  		</form>
				 </div>
		    </div>
		    <div class="col">
		    	<div class="h" style="margin-bottom:10px;">
					<div class="row">
						<div class="form-group">
					  				<button class="btn btn-secondary" onclick="refresh()">Refresh</button>
					  			</div>
		    			
					</div>
		    	</div>
		    </div>
	    </div>

	    <div class="row mb-3 ml-3">
		    <form class="form-inline message-search-handler" method="get">
		  		<div class="col">
		  			<div class="form-group">
					    <?php echo Form::text("keyword",request("keyword"),["class"=> "form-control","placeholder" => "Enter keyword"]) ?>
				  	</div>
					
					<div class="form-group ml-2">
						<label for="no-inventory">No Inventory</label>
						<input type="checkbox" name="no-inventory" value="1" {{ request()->has('no-inventory') ? 'checked' : '' }} />
					</div>
	
					
					<div class="form-group ml-2">
						<?php echo Form::select("category_id",$categories,request("category_id"),["class"=> "form-control select2","placeholder" => "Select Category"]) ?>
					</div>

					<div class="form-group ml-2">
						<?php echo Form::select("brd_store_website_id",$storeWebsite,request("brd_store_website_id"),["class"=> "form-control select2","placeholder" => "Select Store Website"]) ?>
					</div>

					<div class="form-group ml-2">
						<label for="no_brand">no Available brand</label>
						<input type="checkbox" name="no_brand" value="1" {{ request()->has('no_brand') ? 'checked' : '' }} />
					</div>	


				  	<div class="form-group">
				  		<label for="button">&nbsp;</label>
				  		<button type="submit" style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-search-action">
				  			<img src="/images/search.png" style="cursor: default;">
				  		</button>
				  	</div>		
		  		</div>
	  		</form>
	  	</div>	

		<div class="col-md-12 margin-tb" id="page-view-result">
			<div class="row">
				<table class="table table-bordered">
				    <thead>
				      <tr>
				      	<th width="5%">Id</th>
				        <th width="10%">Brand</th>
				        <th width="10%">Min Price</th>
				        <th width="10%">Max Price</th>
				        <?php foreach($storeWebsite as $title) { ?>
				        	<th width="10%"><?php echo $title; ?></th>
				        <?php } ?>	
				      </tr>
				    </thead>
				    <tbody>
				    	<?php foreach($brands as $brand) { ?>

				    	  <?php
				    	  	if(request()->get('brd_store_website_id')){
				    	  		if(request()->get('no_brand')){
					    	  		if(in_array(request()->get('brd_store_website_id'), $apppliedResult[$brand->id])){
					    	  			continue;
					    	  			}
					    	  	}else{
					    	  		if(!in_array(request()->get('brd_store_website_id'), $apppliedResult[$brand->id])){
					    	  			continue;
					    	  		}
					    	  	}	
				    	  	}
				    	  ?>		
 					      <tr>
					      	<td><?php echo $brand->id; ?></td>
					      	<td><a target="_blank" href="{{ route('product-inventory.new') }}?brand[]={{ $brand->id }}">{{ $brand->name }}  ( {{ $brand->counts }} )</a></td>
					      	<td><?php echo $brand->min_sale_price; ?></td>
					      	<td><?php echo $brand->max_sale_price; ?></td>
					      	<?php foreach($storeWebsite as $swid => $sw) { 
					      			$checked = (isset($apppliedResult[$brand->id]) && in_array($swid, $apppliedResult[$brand->id])) ? "checked" : ""; 
					      		?>
					        	<td>
					        		<input data-brand="<?php echo $brand->id; ?>" data-sw="<?php echo $swid; ?>" <?php echo $checked; ?> class="push-brand" type="checkbox" name="brand_website">
					        		<span>
					        			@php $magentoStoreBrandId = $brand->storewebsitebrand($swid); @endphp
					        			{{ $magentoStoreBrandId ? $magentoStoreBrandId : '' }}
					        		</span>
					        		<a href="javascript:;" data-href="{!! route('store-website.brand.history',['brand'=>$brand->id,'store'=>$swid]) !!}" class="log_history"><i class="fa fa-info-circle" aria-hidden="true"></i>
					        		</a>
					        	</td>
					        <?php } ?>
					      </tr>
					    <?php } ?>
				    </tbody>
				</table>
			</div>
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


<div id="HistoryModal" class="modal fade" role="dialog">
  	<div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	        <div class="modal-header">
	        	<h4 class="modal-title">History</h4>
	          	<button type="button" class="close" data-dismiss="modal">&times;</button>
	        </div>
	        <div class="modal-body"></div>
    	</div>
	</div>
</div>

<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/common-helper.js"></script>
<script type="text/javascript" src="/js/store-website-brand.js"></script>

<script type="text/javascript">
	page.init({
		bodyView : $("#common-page-layout"),
		baseUrl : "<?php echo url("/"); ?>"
	});

	function refresh() {
		$.ajax({
            url: '{{ route('store-website.refresh-min-max-price')}}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
            },beforeSend: function() {
              
            },
            success: function(response) {
                $("#loading-image").hide();
                alert(response);
            }
        });
	}
	jQuery(document).ready(function(){
		jQuery(".log_history").on("click",function(){
			$("#loading-image").show();
			var _this = jQuery(this);
			$.ajax({
	            url: jQuery(_this).data('href'),
	            type: 'GET',
	            success: function(response) {
	                jQuery("#loading-image").hide();
	                jQuery("#HistoryModal .modal-body").html(response);
	                jQuery("#HistoryModal").modal("show");
	            },
	            error: function(response) {
	            	alert("Something went wrong, please try after sometime.");
	            }
        	});
		});
	});
</script>

@endsection

