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
	    			<button style="display: inline-block" class="btn btn-sm btn-image" data-toggle="modal" data-target="#accountmodal">
		  				Create Account
		  			</button>
	    			<button style="display: inline-block" class="btn btn-sm btn-image btn-add-action" data-toggle="modal" data-target="#campaningmodal">
		  				Create Campaign
		  			</button>
		  			<button style="display: inline-block" class="btn btn-sm btn-image btn-add-action" data-toggle="modal" data-target="#adgroupmodal">
		  				Create Ads Group
		  			</button>
		  			<button style="display: inline-block" class="btn btn-sm btn-image btn-add-action" data-toggle="modal" data-target="#admodal">
		  				Create Ads
		  			</button>
				 </div>
		    </div>
		    <div class="col">
		    	<div class="h" style="margin-bottom:10px;">
		    		<form class="form-inline message-search-handler" method="post">
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
@section('models')
<div class="modal fade" id="accountmodal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
             <form method="POST" action="{{route('ads.saveaccount')}}" id="create-ad-account-form" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Create Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
	                <div class="row mt-4">
	                	<div class="col-md-6">
	                		<span>Account Name</span>
	                		<input type="text" name="account_name" class="form-control" placeholder="Enter account name" required="">
	                	</div>
	                	<div class="col-md-6">
	                		<span>Config File</span>
	                		<input type="file" name="config_file" accept="*/ini" class="form-control" required="">
	                	</div>
	                </div>
	                <div class="row mt-4">
	                	<div class="col-md-6">
	                		<span>Note</span>
	                		<textarea name="note" class="form-control" placeholder="Note" required=""></textarea>
	                	</div>
	                	<div class="col-md-6">
	                		<span>Status</span>
	                		<select name="status" class="form-control" required="">
	                			<option value="ENABLED">ENABLED</option>
	                			<option value="DISBLED">DISBLED</option>
	                		</select>
	                	</div>
	                </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="create-camp-btn" class="btn btn-secondary">Create</button>
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="campaningmodal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
             <form method="POST" id="create-ad-campaign-form" action="{{route('ads.savecampaign')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Create Campaign</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body create-campaning">
	                <div class="form-group row">
	                    <div class="col-md-6">
							<input type="hidden" value="1" id="addAccountStatus">
	                    	<span for="account">Select Account</span>
	                        <select class="browser-default custom-select" id="account" name="account_id" style="height: auto" required="">
	                            <option value="" selected>-----Select account-----</option>
	                           	@foreach($adaccounts as $ac)
	                            <option value="{{$ac->id}}" >{{$ac->account_name}}</option>
	                            @endforeach
	                        </select>
	                    </div>
	                    <div class="col-md-6">
	                    	<span for="goal">Select the goal</span>
	                        <select class="browser-default custom-select" id="goal" name="goal" style="height: auto" required="">
	                            <option value="" selected>-----Select goal-----</option>
	                            <option value="Sales" >Sales</option>
	                            <option value="Leads">Leads</option>
	                            <option value="Web traffic">Web traffic</option>
	                            <option value="Product and brand consideration">Product and brand consideration</option>
	                            <option value="Brand awareness and reach">Brand awareness and reach</option>
	                            <option value="App promotion">App promotion</option>
	                            <option value="Local store visits and promotions">Local store visits and promotions</option>
	                            <option value="Create a campaign without a goal's guidance">Create a campaign without a goal's guidance</option>
	                        </select>
	                    </div>
	                </div>
                </div>
                <div class="modal-body create-campaning-phase-2" style="display: none;">
                	
                </div>
                <div class="modal-footer">
					<button type="submit" id="continue-phase-1" class="btn btn-secondary">Continue</button>
                    <button type="submit" id="create-campaign-btn" style="display: none;" class="btn btn-secondary">Create</button>
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="adgroupmodal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
             <form method="POST" action="{{route('ads.savegroup')}}" id="create-ad-group-form" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Create Ads Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
	                <div class="form-group row">
	                    <div class="col-sm-6">
	                    	<span for="status">Campaign</span>
	                        <select class="browser-default custom-select" id="campaign" required="" name="campaign" style="height: auto">
	                            <option value="" selected>-----Select campaign-----</option>
	                            @foreach($campigns as $campign)
	                           	<option value="{{$campign->id}}">{{$campign->campaign_name}}</option>
	                           	@endforeach
	                        </select>
	                    </div>
	                    <div class="col-sm-6">
	                    	<span for="status">Ad group type</span>
	                        <select class="browser-default custom-select" id="type" name="type" style="height: auto">
	                           	<option value="Standard" selected="">Standard</option>
	                           	<option value="Dynamic">Dynamic</option>
	                        </select>
	                    </div>
	                </div>
	                <div class="form-group row" style="margin:10px 0px 10px 0px; border: 1px solid #f2f2f2; padding: 20px 0px 20px 0px;">
	                    <div class="col-md-6">
                			<span>Ad group name</span>
                			<input type="text" name="adgroup[0][name]" class="form-control" placeholder="Enter Ad group name" value="Ad group 1" required="">
	                    </div>
	                	<div class="col-md-6">
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<span class="mt-5">Keywords</span>
	                    		</div>
	                    		<div class="col-md-12">
	                    			<input type="url" name="adgroup[0][url]" class="form-control" placeholder="Enter relate web page URL " required="">
	                    		</div>
	                    		<div class="col-md-12 mt-3">
	                    			<input type="text" value="" name="adgroup[0][keywords]"  class="form-control" placeholder="Enter Keywords" required=""/>
	                    		</div>
	                    		<div class="col-md-12 mt-3">
	                    			<input type="number" step="0.00" name="adgroup[0][budget]" class="form-control" placeholder="Enter budget" required="">
	                    		</div>
	                    	</div>
	                    </div>
	                </div>
                   	<span id="addGroupbefore"></span>
                   	<div class="row">
                   		<div class="col-md-12">
                   			<button type="button" class="btn btn-default" id="addmoreGroup">
                   				New Ad Group
                   			</button>
                   		</div>
                   	</div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="create-camp-btn" class="btn btn-secondary">Create</button>
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="admodal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
             <form method="POST" action="{{route('ads.adsstore')}}" id="create-ad-store-form" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Create Ads</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
	                <div class="form-group row">
	                    <div class="col-sm-6">
	                    	<span for="campaignAds">Campaign</span>
	                        <select class="browser-default custom-select" id="campaignAds" name="campaign" required="" style="height: auto">
	                            <option value="" selected>-----Select campaign-----</option>
	                            @foreach($campigns as $campign)
	                           	<option value="{{$campign->id}}">{{$campign->campaign_name}}</option>
	                           	@endforeach
	                        </select>
	                    </div>
	                    <div class="col-sm-6">
	                    	<span for="status">Ad group</span>
	                        <select class="browser-default custom-select" id="adgroupSelect" name="adgroup" style="height: auto" required="">
	                           	<option value="">-----Select Group-----</option>
	                        </select>
	                    </div>
	                </div>
	                <div class="row mb-4">
	                	<div class="col-md-6">
	                		<input type="url" name="finalurl" class="form-control" placeholder="Final URL" required="">
	                	</div>
	                	<div class="col-md-6">
	                		<input type="url" name="displayurl" class="form-control" placeholder="Display URL" required="">
	                	</div>
	                </div>
	                <div class="row mb-4 mt-5">
	                	<div class="col-md-6">
	                		<div class="row">
	                			<div class="col-md-12">
			                		<span>Headlines</span>
			                	</div>
			                	<div class="col-md-12">
			                		<input type="text" name="headlines[]" class="form-control" placeholder="New headline" required="">
			                	</div>
			                	<div class="col-md-12">
			                		<input type="text" name="headlines[]" class="form-control" placeholder="New headline" required="">
			                	</div>
			                	<div class="col-md-12">
			                		<input type="text" name="headlines[]" class="form-control" placeholder="New headline" required="">
			                	</div>
			                	<div class="col-md-12">
			                		<a href="javascript:void(0)" id="addHeadline">ADD HEADLINE</a>
			                	</div>
	                		</div>	
	                	</div>
	                	<div class="col-md-6">
	                		<div class="row">
	                			<div class="col-md-12">
			                		<span>Descriptions</span>
			                	</div>
			                	<div class="col-md-12">
			                		<input type="text" name="descriptions[]" class="form-control" placeholder="New descriptions" required="">
			                	</div>
			                	<div class="col-md-12">
			                		<a href="javascript:void(0)" id="addDescriptions">ADD DESCRIPTION</a>
			                	</div>
	                		</div>
	                	</div>
	                </div>
	                
	                <div class="row mb-4">
	                	<div class="col-md-12">
	                		<span>Ad URL Options</span>
	                	</div>
	                </div>
	                <div class="row mb-4">
	                	<div class="col-md-6">
	                		<input type="text" name="tracking_tamplate" class="form-control" placeholder="Tracking template" required="">
	                	</div>
	                	<div class="col-md-6">
	                		<input type="text" name="final_url_suffix" class="form-control" placeholder="Final URL suffix" required="">
	                	</div>
	                </div>
	                <div class="row mb-4">
	                	<div class="col-md-12">
	                		<span>Custom parameter</span>
	                	</div>
	                </div>
	                <div class="row mb-4">
	                	<div class="col-md-6">
	                		<input type="text" name="customparam[0][name]" class="form-control" placeholder="Name">
	                	</div>
	                	<div class="col-md-6">
	                		<input type="text" name="customparam[0][value]" class="form-control" placeholder="Value">
	                	</div>
	                </div>
	                <div class="row mb-5">
	                	<div class="col-md-12">
	                		<a href="javascript:void(0)" id="addCustomParam">ADD PPARAM</a>
	                	</div>
	                </div>
	                <div class="row mb-5">
	                	<div class="col-md-12">
	                		<input type="checkbox" name="different_url_mobile" id="different_url_mobile" value="different_url_mobile" checked>
	                		Use a different final URL for mobile
	                	</div>
	                </div>
	                <div class="row mb-5 mobile-url-container">
	                	<div class="col-md-12">
	                		<input type="url" name="mobile_final_url" class="form-control" placeholder="m.example.com" required="">
	                	</div>
	                </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="create-camp-btn" class="btn btn-secondary">Create</button>
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@include("ads.templates.list-template")
@include("ads.templates.create-website-template")
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/common-helper.js"></script>
<script type="text/javascript" src="/js/ads.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.16/js/bootstrap-multiselect.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.16/css/bootstrap-multiselect.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript">
	page.init({
		bodyView : $("#common-page-layout"),
		baseUrl : "<?php echo url("/"); ?>"
	});
	$(document).ready(function(){
		//TempJs---------------------
		// $('#continue-phase-1').click();
		$(document).on('change','#campaignAds',function(){
			$.ajax({
				url:'{{route("ads.getgroups")}}',
				dataType:'json',
				data:{
					id:$(this).val(),
				},
				success:function(result){
					let html = `<option value="">-----Select Group-----</option>`;
					$.each(result.data,function(key,value){
						html += `<option value="${value.id}">${value.group_name}</option>`; 
					});
					$('#adgroupSelect').html(html);
				},
				error:function(exx){

				}
			})
		});
		$('#dates-field2').multiselect({
	        includeSelectAllOption: true,
	        selectAllText: 'All Languages',
	    });
	    $(".taginput").tagsinput('items')
	    $('.bootstrap-tagsinput').css('width', '100%');
	});
</script>

@endsection

