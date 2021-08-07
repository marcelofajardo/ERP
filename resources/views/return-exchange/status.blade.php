@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'List | Return Exchange')

@section('large_content')

<div class="row" id="return-exchange-page">
	<div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Return Exchange Status <span id="total-counter">({{$status->count()}})</span></h2>
    </div>
    <br>
    <div class="col-lg-12 margin-tb">
    	<div class="row" style="margin-bottom:10px;">
	    	<div class="col">
		    	<div class="h" style="margin-bottom:0px;">
		    		<form class="form-inline return-exchange-handler" method="get">
					  <div class="row">
				  		<div class="col">
				  			<div class="form-group">
							    <?php echo Form::text("search",request("search"),["class"=> "form-control","placeholder" => "Search by keyword"]) ?>
						  	</div>
						  	<div class="form-group">
						  		<!--<label for="button">&nbsp;</label>-->
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
		<div class="col-md-12 margin-tb infinite-scroll" id="page-view-result">
            <table class="table table-bordered" style="table-layout:fixed;">
            <thead>
              <tr>
                <th width="2%">Id</th>
                <th>Status</th>
                <th>Message</th>
                <th width="10%">Action</th>
              </tr>
            </thead>
            <tbody>
                <?php foreach($status as $s) {  ?>
                    <tr data-id="<?php echo $s->id; ?>">
                        <td><?php echo $s->id; ?></td>
                        <td>
                            <div class="form-group">
                                <input type="text" value="<?php echo $s->status_name; ?>" class="form-control text-editor-textarea" data-field="status_name" placeholder="Enter status">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <textarea class="form-control text-editor-textarea" data-field="message"><?php echo $s->message; ?></textarea>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-delete-template no_pd"  data-id="<?php echo $s->id; ?>"><img width="15px" src="/images/delete.png"></button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
          </table>          
		</div>
	</div>
</div>
<div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
          50% 50% no-repeat;display:none;">
</div>
@endsection

@section('scripts')
	<script type="text/javascript">
        $(document).on("keyup",".text-editor",function(e) {
            var $this = $(this);
            if(e.keyCode == 13) {
                var field   = $this.data("field");
                var value   = $this.val();
                var id      = $this.closest("tr").data("id");
                $.ajax({
                    url: "/return-exchange/status/store",
                    dataType: "json",
                    type: "post",
                    data: {
                        "field" : field,
                        "value": value,
                        "id" : id,
                        "_token" : "{{ csrf_token() }}"
                    },
                    beforeSend: function () {
                        $("#loading-image").show();
                    }
                }).done(function (data) {
                    $("#loading-image").hide();
                    toastr["success"]("data updated successfully");
                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    $("#loading-image").hide();
                    alert('No response from server');
                });
            }
        });

        $(document).on("focusout",".text-editor-textarea",function(e) {
            var $this = $(this);
            var field   = $this.data("field");
            var value   = $this.val();
            var id      = $this.closest("tr").data("id");
            $.ajax({
                url: "/return-exchange/status/store",
                dataType: "json",
                type: "post",
                data: {
                    "field" : field,
                    "value": value,
                    "id" : id,
                    "_token" : "{{ csrf_token() }}"
                },
                beforeSend: function () {
                    $("#loading-image").show();
                }
            }).done(function (data) {
                $("#loading-image").hide();
                toastr["success"]("data updated successfully");
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                $("#loading-image").hide();
                alert('No response from server');
            });
        });

        $(document).on("click",".btn-delete-template",function(e) {
            if(confirm("Are you sure you want to delete this request ?")) {
                var id = $(this).data("id");
                $.ajax({
                    url: "/return-exchange/status/delete",
                    dataType: "json",
                    type: "post",
                    data: {
                        "id" : id,
                        "_token" : "{{ csrf_token() }}"
                    },
                    beforeSend: function () {
                        $("#loading-image").show();
                    }
                }).done(function (data) {
                    $("#loading-image").hide();
                    toastr["success"]("data updated successfully");
                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    $("#loading-image").hide();
                    alert('No response from server');
                });
            }

        });
	</script>
@endsection