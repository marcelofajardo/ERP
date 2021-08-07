@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'Entities | Chatbot')

@section('content')
<style type="text/css">
	table.dataTable thead .sorting:after,
	table.dataTable thead .sorting:before,
	table.dataTable thead .sorting_asc:after,
	table.dataTable thead .sorting_asc:before,
	table.dataTable thead .sorting_asc_disabled:after,
	table.dataTable thead .sorting_asc_disabled:before,
	table.dataTable thead .sorting_desc:after,
	table.dataTable thead .sorting_desc:before,
	table.dataTable thead .sorting_desc_disabled:after,
	table.dataTable thead .sorting_desc_disabled:before {
	bottom: .5em;
	}
</style>
<div class="row">
	<div class="col-lg-12 margin-tb">
	    <h2 class="page-heading">Entities | Chatbot</h2>
	</div>
</div>
<div class="row">
        <div class="col-lg-12 margin-tb" style="margin-bottom: 10px;">
            <div class="pull-right">
                <div class="form-inline">
                    <button type="button" class="btn btn-secondary ml-3" id="create-keyword-btn">Create</button>
            	</div>
            </div>
        </div>
    </div>
<div class="tab-pane">
	<div class="row">
	    <div class="col-lg-12 margin-tb">
	        <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
			  <thead>
			    <tr>
			      <th class="th-sm">Id</th>
			      <th class="th-sm">Entity</th>
			      <th class="th-sm">Values</th>
			      <th class="th-sm">Action</th>
			    </tr>
			  </thead>
			  <tbody>
			    <?php foreach($chatKeywords as $chatKeyword) { ?>
				    <tr>
				      <td><?php echo $chatKeyword->id; ?></td>
				      <td><?php echo $chatKeyword->keyword; ?></td>
				      <td><?php echo $chatKeyword->values; ?></td>
				      <td>
                        <a class="btn btn-image edit-button" data-id="<?php echo $chatKeyword->id; ?>" href="<?php echo route("chatbot.keyword.edit",[$chatKeyword->id]); ?>"><img src="/images/edit.png"></a>
                        <a class="btn btn-image delete-button" data-id="<?php echo $chatKeyword->id; ?>" href="<?php echo route("chatbot.keyword.delete",[$chatKeyword->id]); ?>"><img src="/images/delete.png"></a>
				      </td>
				    </tr>
				<?php } ?>
			  </tbody>
			  <tfoot>
			    <tr>
			      <th>Id</th>
			      <th>Entity</th>
			      <th>Values</th>
			      <th>Action</th>
			    </tr>
			  </tfoot>
			</table>
	    </div>
	    <div class="col-lg-12 margin-tb">
	    	<?php echo $chatKeywords->links(); ?>
	    </div>
	</div>
</div>
@include('chatbot::partial.create_keyword')
<script type="text/javascript">
	$("#create-keyword-btn").on("click",function() {
		$("#create-keyword").modal("show");
	});
	$(".form-save-btn").on("click",function(e) {
		e.preventDefault();
		var form = $(this).closest("form");
		$.ajax({
			type: form.attr("method"),
            url: form.attr("action"),
            data: form.serialize(),
            dataType: "json",
            success: function (response) {
               if(response.code == 200) {
               	  toastr['success']('data updated successfully!');
               	  window.location.replace(response.redirect);
               }else{
				  errorMessage = response.error ? response.error : 'data is not correct or duplicate!';
               	  toastr['error'](errorMessage);
               }
            },
            error: function () {
               toastr['error']('Could not change module!');
            }
        });
	});
</script>
@endsection