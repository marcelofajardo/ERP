@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', $title)

@section('content')
@section('link-css')
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
	<style type="text/css">
		.preview-category input.form-control {
			width: auto;
		}
		.daterangepicker .ranges li.active {
			background-color : #08c !important;
		}
	</style>
@endsection

<div class="row" id="common-page-layout">
	<div class="col-lg-12 margin-tb">
		<h2 class="page-heading">{{$title}} <span class="count-text"></span></h2>
	</div>
	<br>
	@if(session()->has('success'))
		<div class="col-lg-12 alert alert-success">
			{{ session()->get('success') }}
		</div>
	@endif
	@if(session()->has('error'))
		<div class="col-lg-12 alert alert-danger">
			{{ session()->get('error') }}
		</div>
	@endif
	<div class="col-lg-12 margin-tb">
		<div class="row">
			<div class="col col-md-3">
				<div class="row">
					<a href="/attachImages/newsletters">
						<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image">
							<img src="/images/attach.png" style="cursor: default;">
						</button>
					</a>
				</div>
			</div>
			<div class="col">
				<div class="h">
					<form class="form-inline message-search-handler mb-2 fr" method="post" style="float: right;">
						<div class="row">
							<div class="form-group" style="margin-left: 2px">
								<label for="keyword">Product Id:</label>
								<?php echo Form::text("keyword",request("keyword"),["class"=> "form-control","placeholder" => "Enter Product Id"]) ?>
							</div>
							<div class="form-group" style="margin-left: 2px">
								<label for="">Date:</label>
								<?php echo Form::date("date_from",request("date_from"),["class"=> "form-control","placeholder" => "From date"]) ?>
								<?php echo Form::date("date_to",request("date_to"),["class"=> "form-control","placeholder" => "To date"]) ?>
							</div>
							<div class="form-group" style="margin-left: 2px">
								<label for="">Send at:</label>
								<?php echo Form::date("send_at",request("send_at"),["class"=> "form-control","placeholder" => "Send at"]) ?>
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
@include("newsletter.templates.list-template")
@include("newsletter.templates.create-website-template")
@include("newsletter.templates.update-time")
@include('newsletter.partials.add-status-modals')
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="/js/common-helper.js"></script>
<script type="text/javascript" src="/js/newsletters.js"></script>

<script type="text/javascript">

	page.init({
		bodyView : $("#common-page-layout"),
		baseUrl : "<?php echo url("/"); ?>"
	});

	$(document).ready(function(){
		$('#store_id').on("click", function(){
			$('.btn-push-icon').attr('data-attr', $(this).val());
		});
	});
</script>

@endsection

