@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', $title)

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />	
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

    .daterangepicker select.hourselect, .daterangepicker select.minuteselect, .daterangepicker select.secondselect, .daterangepicker select.ampmselect {
	    width: 50px !important;
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
	    			<button style="display: inline-block;" class="btn btn-secondary btn-move-to-dnd m-2" data-toggle="modal" data-target="#move-to-dnd">
		  				Move To DND
		  			</button>
				 </div>
		    </div>
		    <div class="col col-md-6">
		    	<div class="h">
					<div class="row">
		    			<form class="form-inline message-search-handler" method="get">
                            <div class="col">
                                <div class="form-group">
                                    <label for="time_range">Range:</label>
                                    <?php echo Form::text("time_range",request("time_range",date("Y-m-d h:i:s A")." - ".date("Y-m-d h:i:s A")),["class"=> "form-control time-range"]) ?>
                                </div>
                                <div class="form-group">
                                    <label for="whatsapp_number">Whatsapp number:</label>
                                     <select name="whatsapp_number" class="form-control">
                                     	<option value="">--Select--</option>
                                     	@foreach(array_filter(config("apiwha.instances")) as $number => $apwCate)
									  		@if($number != "0")
									  			<option {{ ($number == request('whatsapp_number')) ? "selected='selected'" : "" }} value="{{ $number }}">{{ $number }}</option>
									  		@endif
									  	@endforeach
									  </select>
                                </div>
                            	<div class="form-group">
								    <label for="keyword">Keyword:</label>
								    <?php echo Form::text("keyword",request("keyword"),["class"=> "form-control","placeholder" => "Enter keyword"]) ?>
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

@include("dnd-list.templates.list-template")

<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script src="/js/jquery-ui.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script type="text/javascript" src="{{ asset('/js/common-helper.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/dnd-list.js') }}"></script>

<script type="text/javascript">
	page.init({
		bodyView : $("#common-page-layout"),
		baseUrl : "<?php echo url("/"); ?>"
	});
</script>
@endsection