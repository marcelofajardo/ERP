@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', $title)

@section('content')
<style type="text/css">
    .preview-category input.form-control {
      width: auto;
    }
    .keyword-list {
        cursor: pointer;
        
    }
     .height-fix {
        height: 220px;
        /* display: inline-block; */
        overflow: auto;
        
    }
    textarea {
        overflow: hidden;
    }
</style>
<link href="//cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<div class="row" id="common-page-layout">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">{{$title}} <span class="count-text"></span></h2>
    </div>
    <br>
    <div class="col-lg-12 margin-tb">
        <div class="row">
            <div class="col col-md-4">
                <div class="row">
                    <button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-add-action" data-toggle="modal" data-target="#colorCreateModal">
                        <img src="/images/add.png" style="cursor: default;">
                    </button>
                </div>
                <div class="row">
                    <button class="btn btn-secondary push-by-store-website"  data-toggle="modal" data-target="#push-by-store-website-modal">Push By Storewebsite</button> 
                    <button class="btn btn-secondary pull-by-store-website"  data-toggle="modal" data-target="#pull-by-store-website-modal">Pull By Storewebsite</button>
                </div>
            </div>
            <div class="col">
                <div class="h" style="margin-bottom:10px;">
                    <div class="row">
                        <form class="form-inline message-search-handler" method="get">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="language">Languages:</label>
                                    <?php echo Form::select("language",$languagesList,request("language"),["class"=> "form-control","placeholder" => "Select Language"]) ?>
                                </div>
                            </div>     
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="store_website_id">Store Websites:</label>
                                    <?php echo Form::select("store_website_id",$storeWebsites,request("store_website_id"),["class"=> "form-control","placeholder" => "Select Store website"]) ?>
                                </div>
                            </div>
                            <div class="col-4">
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
    <div class="modal-dialog modal-lg" role="document">
    </div>  
</div>

<div class="preview-history-modal modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Content</th>
                                <th>Updated By</th>
                                <th>Updated At</th>
                            </tr>
                        </thead>
                        <tbody id="preview-history-tbody">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="preview-activities-modal modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Description</th>
                                <th>Updated By</th>
                                <th>Updated At</th>
                            </tr>
                        </thead>
                        <tbody id="preview-activities-tbody">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="push-by-store-website-modal modal" id="push-by-store-website-modal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form>
                    <div class="form-row col-md-12">
                        <div class="form-group">
                            <strong>Store websites</strong>
                            <?php echo Form::select("store_website_id",$storeWebsites,null, ["class" => "form-control push-website-store-id"]);  ?>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-secondary push-pages-store-wise">Push Store(s)</button>
            </div>
        </div>
    </div>
</div>

<div class="pull-by-store-website-modal modal" id="pull-by-store-website-modal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form>
                    <div class="form-row col-md-12">
                        <div class="form-group">
                            <strong>Store websites</strong>
                            <?php echo Form::select("store_website_id",$storeWebsites,null, ["class" => "form-control pull-website-store-id"]);  ?>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-secondary pull-pages-store-wise">Pull Store(s)</button>
            </div>
        </div>
    </div>
</div>

@include("storewebsite::page.templates.list-template")
@include("storewebsite::page.templates.create-website-template")
<script src="//cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="{{ asset('/js/common-helper.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/store-website-page.js') }}"></script>
<script type="text/javascript">
    page.init({
        bodyView : $("#common-page-layout"),
        baseUrl : "<?php echo url("/"); ?>"
    });
    function save_platform_id(page_id) {
        var platform_id = $(this.event.target).val();
        $.ajax({
            url: '{{ route('store_website_page.store_platform_id') }}',
            method: 'PUT',
            data: {
                _token: "{{ csrf_token() }}",
                'page_id': page_id,
                'platform_id': platform_id
            }
        });
    }
</script>
@endsection 