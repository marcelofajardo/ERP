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
					<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-add-action">
						<img src="/images/add.png" style="cursor: default;">
					</button>
				</div>
			</div>
			<div class="col">
				<div class="h">
					<form class="form-inline message-search-handler mb-2 fr" method="post" style="float: right;">
						<div class="row">
							<div class="form-group" style="margin-left: 2px">
								<label for="keyword">Keyword:</label>
								<?php echo Form::text("keyword", request("keyword"), ["class" => "form-control", "placeholder" => "Keyword"]) ?>
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
@include("size.templates.list-template")
@include("size.templates.create-form-template")
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/common-helper.js"></script>

<script type="text/javascript">

var page = {
    init: function(settings) {

        page.config = {
            bodyView: settings.bodyView
        };

        $.extend(page.config, settings);

        this.getResults();

        //initialize pagination
        page.config.bodyView.on("click",".page-link",function(e) {
            e.preventDefault();
            page.getResults($(this).attr("href"));
        });

        page.config.bodyView.on("click",".btn-search-action",function(e) {
            e.preventDefault();
            page.getResults();
        });

        page.config.bodyView.on("click",".btn-add-action",function(e) {
            e.preventDefault();
            page.createRecord();
        });

        $(".common-modal").on("click",".submit-platform",function() {
            page.submitPlatform($(this));
        });

        // delete product templates
        page.config.bodyView.on("click",".btn-delete-template",function(e) {
            if(!confirm("Are you sure you want to delete record?")) {
                return false;
            }else {
                page.deleteRecord($(this));
            }
        });

		page.config.bodyView.on("click",".btn-edit-template",function(e) {
            page.editRecord($(this));
        });

        page.config.bodyView.on("click",".btn-push-size-template",function(e){
           page.pushSize($(this));
        });
    },
    validationRule : function(response) {
        $(document).find("#product-template-from").validate({
            rules: {
                name     : "required",
            },
            messages: {
                name     : "Template name is required",
            }
        })
    },
    loadFirst: function() {
        var _z = {
            url: this.config.baseUrl + "/size/records",
            method: "get",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "showResults");
    },
    getResults: function(href) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/size/records",
            method: "get",
            data : $(".message-search-handler").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "showResults");
    },
    showResults : function(response) {
        $("#loading-image").hide();

        var common =  $(".common-modal");
        common.modal("hide");


        var addProductTpl = $.templates("#template-result-block");
        var tplHtml       = addProductTpl.render(response);

        $(".count-text").html("("+response.total+")");

        page.config.bodyView.find("#page-view-result").html(tplHtml);

    }
    ,
    deleteRecord : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/size/"+ele.data("id")+"/delete",
            method: "get",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, 'deleteResults');
    },
    deleteResults : function(response) {
        if(response.code == 200){
            this.getResults();
            toastr['success']('Message deleted successfully', 'success');
        }else{
            toastr['error']('Oops.something went wrong', 'error');
        }

    },
    createRecord : function(response) {
        var createWebTemplate = $.templates("#form-create-size-page");
        var tplHtml = createWebTemplate.render({data:{}});

        var common =  $(".common-modal");
        common.find(".modal-dialog").html(tplHtml);
        common.modal("show");
    },

    editRecord : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/size/"+ele.data("id")+"/edit",
            method: "get",
        }
        this.sendAjax(_z, 'editResult');
    },

    editResult : function(response) {
        var createWebTemplate = $.templates("#form-create-size-page");
        var tplHtml = createWebTemplate.render(response);
        var common =  $(".common-modal");
        common.find(".modal-dialog").html(tplHtml);
        common.modal("show");
    },
    submitPlatform : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/size/store",
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "loadFirst");
    },
    pushSize : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/size/push-to-store",
            method: "post",
            data : {id: ele.data("id")},
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "loadFirst");
    }
}

$.extend(page, common);

page.init({
	bodyView : $("#common-page-layout"),
	baseUrl : "<?php echo url("/"); ?>"
});

	
</script>

@endsection

