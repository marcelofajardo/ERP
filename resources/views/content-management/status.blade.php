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
	    			<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image" data-toggle="modal" data-target="#add-new-status">
		  				<img src="/images/add.png" style="cursor: default;">
		  			</button>
		  			<!-- <button type="button" class="btn btn-secondary btn-merge-status">Merge Status</button> -->
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
        <div class="row">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th width="2%">Sl no</th>
                    <th width="38%">Title</th>
                    <th width="30%">Created At</th>
                    <th width="30%">Action</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($statuses as $key => $status)
                    <tr>
                        <td>
                            {{$key + 1}}
                        </td>
                        <td>{{$status->name}}</td>
                        <td>{{$status->created_at}}</td>
                        <td>
                            <button type="button" data-id="{{$status->id}}" class="btn btn-edit-template"><img width="15px" title="Edit" src="/images/edit.png"></button>
                            |<button type="button" data-id="{{$status->id}}" class="btn btn-delete-template"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                   @endforeach
                </tbody>
            </table>
        </div>
		</div>
	</div>
</div>
<div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
          50% 50% no-repeat;display:none;">
</div>


<div id="add-new-status" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
           <form action="{{ route("content-management-status.store") }}" method="POST">
	            <div class="modal-header">
	                <h4 class="modal-title">Add new status</h4>
	            </div>
	            <div class="modal-body">
				    	@csrf
					    <div class="form-group">
					        <label for="document">Name</label>
					        <input type="text" name="name" class="form-control">
					    </div>

	            </div>
	            <div class="modal-footer">
	                <button type="submit" class="btn btn-default">Save</button>
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	            </div>
			</form>
        </div>
    </div>
</div>

<div id="edit-model" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
           <form action="{{ route("content-management-status.save") }}" method="POST">
	            <div class="modal-header">
	                <h4 class="modal-title">Edit status</h4>
	            </div>
	            <div class="modal-body">
				    	@csrf
					    <div class="form-group">
					        <label for="document">Name</label>
					        <input type="text" name="name" id="edit-status-name" class="form-control">
					        <input type="hidden" name="id" id="edit-status-id" class="form-control">
					    </div>

	            </div>
	            <div class="modal-footer">
	                <button type="submit" class="btn btn-default">Save</button>
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	            </div>
			</form>
        </div>
    </div>
</div>
<div class="common-modal modal" role="dialog">
  	<div class="modal-dialog" role="document">
  	</div>	
</div>

<script type="text/javascript">
    $(document).on("click" , ".btn-edit-template" , function() {

        var id= $(this).data('id');

    $.ajax({
        url: '/content-management-status/'+id+'/edit',
        type: 'GET',
        beforeSend: function () {
            $("#loading-image").show();
        },
    })
    .done(function(data) {
        console.log(data)
        $("#loading-image").hide();
        $('#edit-status-name').val(data.data.name);
        $('#edit-status-id').val(data.data.id);
        $("#edit-model").modal('show');
    })
    .fail(function(data) {
        $("#loading-image").hide();
    });
    });


    $(document).on("click" , ".btn-delete-template" , function() {

        var id= $(this).data('id');
        var x = window.confirm("Are you sure ? ");
        if(x) {
            $.ajax({
            url: '/content-management-status/'+id+'/delete',
            type: 'get',
            beforeSend: function () {
                $("#loading-image").show();
            },
            })
            .done(function(data) {
                if(data.code == 500) {
                    toastr['error'](data.error, 'error');
                }
                if(data.code == 200) {
                    toastr['success'](data.success, 'success');
                    location.reload();
                }
                
            $("#loading-image").hide();

            })
            .fail(function(data) {
            $("#loading-image").hide();
            });
        }
    });
</script>

@endsection

