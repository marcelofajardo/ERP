@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'Dialog | Chatbot')

@section('content')
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/css/dialog-node-editor.css">

<style>
.select2-container--default .select2-selection--single {
  height : 30px;
}
</style>
<div class="row">
	<div class="col-lg-12 margin-tb">
	    <h2 class="page-heading">Dialog | Chatbot</h2>
	</div>
</div>
<div class="row">
    <div class="col-lg-12 margin-tb" style="margin-bottom: 10px;">
        <div class="pull-right">
            <div class="form-inline">
                <button type="button" class="btn btn-secondary ml-3" id="create-dialog-btn-rest">Create</button>
                <button type="button" class="btn btn-secondary ml-3" id="create-dialog-folder-btn-rest">Create Folder</button>
        	  </div>
        </div>
    </div>
</div>

<div class="tab-pane">
	<div class="row">
	    <div class="col-lg-12 margin-tb" id="tree-container-dialog">
	    	<dialog-x store="store" segment="segment" workspace="workspace" plan="plan" search-config="searchConfig">
			   <div class="wc--dialog">
			      <div class="dialog">
			         <div class="dialog-tree-container">
			            <ul id="dialog-tree" class="node-children">

			            </ul>
			         </div>
			      </div>
			   </div>
			</dialog-x>
	    </div>
	 </div>
</div>

<div class="modal fade" id="leaf-editor-model" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-secondary save-dialog-btn">Save changes</button>
      </div>
    </div>
  </div>
</div>
<?php include_once(app_path()."/../Modules/ChatBot/Resources/views/dialog/includes/template.php"); ?>
@include('chatbot::partial.create_dialog')
<script src="/js/bootstrap-toggle.min.js"></script>
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/dialog-build.js"></script>
<script>
    $(document).on('click', '#create-dialog-btn-rest', function(){
        $('#create-dialog').modal("show");
    })
</script>
@endsection
