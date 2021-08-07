@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'Dialog-drid | Chatbot')

@section('large_content')
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/css/dialog-node-editor.css">
<style>

    .word-wrap {
        word-break: break-all;
    }
	.pd-3 {
		padding:2px;
	}
</style>
<div class="row">
	<div class="col-lg-12 margin-tb">
	    <h2 class="page-heading">Dialog-grid | Chatbot</h2>
	</div>
</div>
<div class="row">
    <div class="col-lg-12 margin-tb" style="margin-bottom: 10px;">
      <div class="pull-left">
            <div class="form-inline">
                <input type="text" name="search" class="form-control search-query-value"  placeholder="Type for search">
                <button type="button" class="btn btn-secondary ml-3 btn-search-query">Search</button>
            </div>
        </div>
        <div class="pull-right">
            <div class="form-inline">
                <button type="button" class="btn btn-secondary ml-3"><a href="{{route('chatbot.dialog.local-error-log')}}" style="text-decoration:none;color:white;">Error log</a> </button>
                <button type="button" class="btn btn-secondary ml-3" id="create-dialog-btn-rest">Create</button>
                <button type="button" class="btn btn-secondary ml-3" id="create-dialog-folder-btn-rest">Create Folder</button>
        	  </div>
        </div>
    </div>
</div>

<!-- <div class="tab-pane">
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
</div> -->

<div class="tab-pane">
	<div class="row">
	    <div class="col-lg-12 margin-tb">
	        <table style='table-layout:fixed;' class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
			  <thead>
			    <tr>
			      <th style="width:7%" class="th-sm">Resp. type</th>
			      <th style="width:7%" class="th-sm">Total Resp.</th>
			      <th style="width:15%" class="th-sm">Name</th>
			      <th style="width:15%" class="th-sm">Title</th>
                  <th style="width:21%" class="th-sm">Match Intent / Entity</th>
			      <th style="width:15%" class="th-sm">Dialog Response</th>
			      <th style="width:5%" class="th-sm">Dialog type</th>
			      <th style="width:5%" class="th-sm">Parent id</th>
			      <th style="width:10%" class="th-sm">Action</th>
			    </tr>
			  </thead>
			  <tbody id="dialog-tree" class="node-children">

			  </tbody>
			</table>
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-secondary save-dialog-btn">Save changes</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="all-response-modal" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">All Responses</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="all-response-data">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-secondary save-dialog-btn">Save changes</button>
      </div>
    </div>
  </div>
</div>
<?php include_once(app_path()."/../Modules/ChatBot/Resources/views/dialog/includes/template-grid.php"); ?>
@include('chatbot::partial.create_dialog')
<script src="/js/bootstrap-toggle.min.js"></script>
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/dialog-grid.js"></script>
@endsection
