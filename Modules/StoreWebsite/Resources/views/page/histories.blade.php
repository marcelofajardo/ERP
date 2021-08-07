@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', $title)

@section('content')
<style type="text/css">
    .preview-category input.form-control {
      width: auto;
    }
</style>
<link href="//cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<div class="row" id="common-page-layout">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">{{$title}} ({{$records->count()}}) <span class="count-text"></span></h2>
    </div>
    <br>
    <div class="col-lg-12 margin-tb">
        <div class="row">
            <div class="col-md-12">
                <div class="h" style="margin-bottom:10px;">
                    <div class="row">
                        <form class="form-inline message-search-handler" method="get">
                            <div class="form-group ml-2">
                                <label for="store_website_id">Store Websites:</label>
                                <?php echo Form::select("store_website_id",$storeWebsites,request("store_website_id"),["class"=> "form-control","placeholder" => "Select Store website"]) ?>
                            </div>
                            <div class="form-group ml-2">
                                <label for="keyword">Keyword:</label>
                                <?php echo Form::text("keyword",request("keyword"),["class"=> "form-control","placeholder" => "Enter keyword"]) ?>
                            </div>
                            <div class="form-group ml-2">
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
            <div class="table-responsive mt-3">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Store Website</th>
                        <th>Url Key</th>
                        <th>URl</th>
                        <th>Result</th>
                        <th>Result Type</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach($records as $record) {  ?>
                            <tr>
                                <td><?php echo $record->id; ?></td>
                                <td><?php echo $record->store_website_name; ?></td>
                                <td><?php echo $record->url_key; ?></td>
                                <td><?php echo $record->url; ?></td>
                                <td><?php echo $record->result; ?></td>
                                <td><?php echo $record->result_type; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php echo $records->links(); ?>
        </div>
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
<script src="//cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="{{ asset('/js/common-helper.js') }}"></script>
@endsection 