@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'Site Development')

@section('styles')
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
<style type="text/css">
	.preview-category input.form-control {
		width: auto;
	}

	#loading-image {
		position: fixed;
		top: 50%;
		left: 50%;
		margin: -50px 0px 0px -50px;
	}

	.dis-none {
		display: none;
	}

	.pd-5 {
		padding: 3px;
	}

	.toggle.btn {
		min-height: 25px;
	}

	.toggle-group .btn {
		padding: 2px 12px;
	}

	.latest-remarks-list-view tr td {
		padding: 3px !important;
	}
	#latest-remarks-modal .modal-dialog {
		 max-width: 1100px;
		width:100%;
	}
</style>
@endsection

@section('large_content')

<div id="myDiv">
	<img id="loading-image" src="/images/pre-loader.gif" style="display:none;" />
</div>
<div class="row" id="common-page-layout">
	<div class="col-lg-12 margin-tb">
		<h2 class="page-heading">Site Development  @if($website) {{ '- ( ' .$website->website.' )' }} @endif <span class="count-text"></span></h2>
	</div>
	<br>
	<div class="col-lg-12 margin-tb">
		<div class="row">
			<div class="col col-md-12">
				<div class="row mb-3">
					<div class="col-md-3">
						<form class="form-inline message-search-handler" onsubmit="event.preventDefault(); saveCategory();">
							<div class="row">
								<div class="col">
									<div class="form-group">
										<?php /* <label for="keyword">Add Category:</label> */ ?>
										<?php echo Form::text("keyword", request("keyword"), ["class" => "form-control", "placeholder" => "Add Category", "id" => "add-category"]) ?>
									</div>
									<div class="form-group">
									<?php /* <label for="button">&nbsp;</label> */ ?>
										<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-search-action">
											<img src="/images/send.png" style="cursor: default;">
										</button>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="col-md-9">
						<form class="form-inline handle-search" style="display:inline-block;">
							<div class="form-group" style="margin-right:10px;">
							<?php /* <label for="keyword">Search keyword:</label> */ ?>
								<?php echo Form::text("k", request("k"), ["class" => "form-control", "placeholder" => "Search keyword", "id" => "enter-keyword"]) ?>
							</div>
							<div class="form-group">
								<?php /* <label for="status">Status:</label> */?>
							<?php echo Form::select("status", [""=>"All Status"]+ $allStatus, request("status"), ["class" => "form-control", "id" => "enter-status"]) ?>
							</div>
							<div class="form-group">
							<?php /* <label for="button">&nbsp;</label> */ ?>
								<button style="display: inline-block;width: 10%" type="submit" class="btn btn-sm btn-image btn-search-keyword">
									<img src="/images/send.png" style="cursor: default;">
								</button>
							</div>
						</form>
			
						<a href="{{ route('site-development-status.index') }}" target="__blank">
							<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image">
								+ Add Status
							</button>
						</a>
						<button style="display: inline-block;width: 10%;margin-right:5px;" class="btn btn-secondary latest-remarks-btn">
							Remarks
						</button>
						<a class="btn btn-secondary" data-toggle="collapse" href="#statusFilterCount" role="button" aria-expanded="false" aria-controls="statusFilterCount">
							Status Count
						</a>
					
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="collapse" id="statusFilterCount">
					<div class="card card-body">
						<?php if (!empty($statusCount)) { ?>
							<div class="row col-md-12">
								<?php foreach ($statusCount as $sC) { ?>
									<div class="col-md-2">
										<div class="card">
											<div class="card-header">
												<?php echo $sC->name; ?>
											</div>
											<div class="card-body">
												<?php echo $sC->total; ?>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						<?php } else {
							echo "Sorry , No data available";
						} ?>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-12 margin-tb infinite-scroll">
			<div class="row">
				<table class="table table-bordered" id="documents-table">
					<thead>
						<tr>
							<th width="4%">S No</th>
							<th width="10%"></th>
							<th width="18%">Title</th>
							<th width="18%">Message</th>
							<th width="30%">Communication</th>
							<th width="20%">Action</th>
						</tr>
					</thead>
					<tbody>
						@include("storewebsite::site-development.partials.data")
					</tbody>
				</table>
				{{ $categories->appends(request()->capture()->except('page','pagination') + ['pagination' => true])->render() }}
			</div>
		</div>
	</div>
</div>
<div id="chat-list-history" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Communication</h4>
				<input type="text" name="search_chat_pop" class="form-control search_chat_pop" placeholder="Search Message" style="width: 200px;">
				<input type="hidden" id="chat_obj_type" name="chat_obj_type">
                <input type="hidden" id="chat_obj_id" name="chat_obj_id">
                <button type="submit" class="btn btn-default downloadChatMessages">Download</button>
			</div>
			<div class="modal-body" style="background-color: #999999;">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div id="file-upload-area-section" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route("site-development.upload-documents") }}" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="store_website_id" id="hidden-store-website-id" value="">
				<input type="hidden" name="id" id="hidden-site-id" value="">
				<input type="hidden" name="site_development_category_id" id="hidden-site-category-id" value="">
				<div class="modal-header">
					<h4 class="modal-title">Upload File(s)</h4>
				</div>
				<div class="modal-body" style="background-color: #999999;">
					@csrf
					<div class="form-group">
						<label for="document">Documents</label>
						<div class="needsclick dropzone" id="document-dropzone">

						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-save-documents">Save</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="file-upload-area-list" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th width="5%">No</th>
							<th width="45%">Link</th>
							<th width="25%">Send To</th>
							<th width="25%">Action</th>
						</tr>
					</thead>
					<tbody class="display-document-list">
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div id="remark-area-list" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<div class="col-md-12">
					<div class="col-md-8" style="padding-bottom: 10px;">
						<textarea class="form-control" col="5" name="remarks" data-id="" id="remark-field"></textarea>
					</div>
					<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-remark-field">
						<img src="/images/send.png" style="cursor: default;">
					</button>
				</div>
				<div class="col-md-12">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th width="5%">No</th>
								<th width="45%">Remark</th>
								<th width="25%">BY</th>
								<th width="25%">Date</th>
							</tr>
						</thead>
						<tbody class="remark-action-list-view">
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>



<div id="create-quick-task" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form action="<?php echo route('task.create.task.shortcut'); ?>" method="post">
				<?php echo csrf_field(); ?>
				<div class="modal-header">
					<h4 class="modal-title">Create Task</h4>
				</div>
				<div class="modal-body">

					<input class="form-control" value="49" type="hidden" name="category_id" />
					<input class="form-control" type="hidden" name="site_id" id="site_id" />
					<div class="form-group">
						<label for="">Subject</label>
						<input class="form-control" type="text" id="hidden-task-subject" name="task_subject" />
					</div>
					<div class="form-group">
						<select class="form-control" style="width:100%;" name="task_type" tabindex="-1" aria-hidden="true">
							<option value="0">Other Task</option>
							<option value="4">Developer Task</option>
						</select>
					</div>
					<div class="form-group">
						<label for="">Details</label>
						<input class="form-control" type="text" name="task_detail" />
					</div>

					<div class="form-group">
						<label for="">Cost</label>
						<input class="form-control" type="text" name="cost" />
					</div>

					<div class="form-group">
						<label for="">Assign to</label>
						<select name="task_asssigned_to" class="form-control assign-to select2">
							@foreach($allUsers as $user)
							<option value="{{$user->id}}">{{$user->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-default create-task">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="dev_task_statistics" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h2>Dev Task statistics</h2>
				<button type="button" class="close" data-dismiss="modal">×</button>
			</div>
			<div class="modal-body" id="dev_task_statistics_content">
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						<tbody>
							<tr>
								<th>Task type</th>
								<th>Assigned to</th>
								<th>Description</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="preview-website-image" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<div class="col-md-12">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Sl no</th>
								<th>Image</th>
							</tr>
						</thead>
						<tbody class="website-image-list-view">
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div id="latest-remarks-modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<div class="col-md-12">
					<table class="table table-bordered" style="table-layout:fixed;">
						<thead>
							<tr>
								<th style="width:4%;">S no</th>
								<th style="width:13%;">Category</th>
								<th style="width:13%;">By</th>
								<th style="width:45%;">Remarks</th>
								<th style="width:25%;">Communication</th>
							</tr>
						</thead>
						<tbody class="latest-remarks-list-view">
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div id="artwork-history-modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<div class="col-md-12">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Sl no</th>
								<th>Date</th>
								<th>Status</th>
								<th>Username</th>
							</tr>
						</thead>
						<tbody class="artwork-history-list-view">
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.7/jquery.jscroll.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
<script type="text/javascript">
	$('.assign-to.select2').select2({
		width: "100%"
	});

	// $('.infinite-scroll').jscroll({
	//         autoTrigger: true,
	//         loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
	//         padding: 20,
	//         nextSelector: '.pagination li.active + li a',
	//         contentSelector: 'div.infinite-scroll',
	//         callback: function () {
	//             $('ul.pagination').first().remove();
	//         }
	//     });

	function saveCategory() {
		var text = $('#add-category').val()
		if (text === '') {
			alert('Please Enter Text');
		} else {
			$.ajax({
					url: '{{ route("site-development.category.save") }}',
					type: 'POST',
					dataType: 'json',
					data: {
						text: text,
						"_token": "{{ csrf_token() }}"
					},
					beforeSend: function() {
						$("#loading-image").show();
					},
				})
				.done(function(data) {
					$('#add-category').val('')
					refreshPage()
					$("#loading-image").hide();
					console.log(data)
					console.log("success");
				})
				.fail(function(data) {
					$('#add-category').val('')
					console.log(data)
					console.log("error");
				});

		}
	}
	$(function() {
		$(document).on("focusout", ".save-item", function() {
			websiteId = $('#website_id').val()
			category = $(this).data("category")
			type = $(this).data("type")
			site = $(this).data("site")
			var text = $(this).val();
			$.ajax({
					url: '{{ route("site-development.save") }}',
					type: 'POST',
					dataType: 'json',
					data: {
						websiteId: websiteId,
						"_token": "{{ csrf_token() }}",
						category: category,
						type: type,
						text: text,
						site: site
					},
					beforeSend: function() {
						$("#loading-image").show();
					},
				})
				.done(function(data) {
					console.log(data)
					$("#loading-image").hide();
					console.log("success");
				})
				.fail(function(data) {
					console.log(data)
					$("#loading-image").hide();
					console.log("error");
				});
		});

		$(document).on("click", ".save-artwork-status", function() {
			websiteId = $('#website_id').val()
			category = $(this).data("category")
			type = $(this).data("type")
			site = $(this).data("site")
			var text = $(this).val();
			$.ajax({
					url: '{{ route("site-development.save") }}',
					type: 'POST',
					dataType: 'json',
					data: {
						websiteId: websiteId,
						"_token": "{{ csrf_token() }}",
						category: category,
						type: type,
						text: text,
						site: site
					},
					beforeSend: function() {
						$("#loading-image").show();
					},
				})
				.done(function(data) {
					console.log(data)
					$("#loading-image").hide();
					console.log("success");
				})
				.fail(function(data) {
					console.log(data)
					$("#loading-image").hide();
					console.log("error");
				});
		});

		$(document).on("change", ".save-item-select", function() {
			websiteId = $('#website_id').val()
			category = $(this).data("category")
			type = $(this).data("type")
			site = $(this).data("site")
			var text = $(this).val();
			$.ajax({
					url: '{{ route("site-development.save") }}',
					type: 'POST',
					dataType: 'json',
					data: {
						websiteId: websiteId,
						"_token": "{{ csrf_token() }}",
						category: category,
						type: type,
						text: text,
						site: site
					},
				})
				.done(function(data) {
					toastr["success"]("Successful");
				})
				.fail(function(data) {
					console.log(data)
					console.log("error");
				});
		});

		$(document).on("click", ".save-status", function() {
			websiteId = $('#website_id').val()
			category = $(this).data("category")
			type = $(this).data("type")
			site = $(this).data("site")
			var text = $(this).data("text");
			var elem = $(this);
			$.ajax({
					url: '{{ route("site-development.save") }}',
					type: 'POST',
					dataType: 'json',
					data: {
						websiteId: websiteId,
						"_token": "{{ csrf_token() }}",
						category: category,
						type: type,
						text: text,
						site: site
					},
				})
				.done(function(data) {
					toastr["success"]("Successful");
					if(typeof data.html !=='undefined' || data.html !==''){
						elem.parent('span').html(data.html);
					}
				})
				.fail(function(data) {
					console.log(data)
					console.log("error");
				});
		});


		$(document).on("click", ".btn-remark-field", function() {
			var id = $("#remark-field").data("id");
			var val = $("#remark-field").val();
			$.ajax({
				url: '/site-development/' + id + '/remarks',
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': "{{ csrf_token() }}"
				},
				data: {
					remark: val
				},
				beforeSend: function() {
					$("#loading-image").show();
				}
			}).done(function(response) {
				$("#loading-image").hide();
				$("#remark-field").val("");
				toastr["success"]("Remarks fetched successfully");
				var html = "";
				$.each(response.data, function(k, v) {
					html += "<tr>";
					html += "<td>" + v.id + "</td>";
					html += "<td>" + v.remarks + "</td>";
					html += "<td>" + v.created_by + "</td>";
					html += "<td>" + v.created_at + "</td>";
					html += "</tr>";
				});
				$("#remark-area-list").find(".remark-action-list-view").html(html);
				//$("#remark-area-list").modal("show");
				//$this.closest("tr").remove();
			}).fail(function(jqXHR, ajaxOptions, thrownError) {
				toastr["error"]("Oops,something went wrong");
				$("#loading-image").hide();
			});
		});
	});


	function editCategory(id) {
		$('#editCategory' + id).modal('show');
	}

	function submitCategoryChange(id) {
		category = $('#category-name' + id).val()
		categoryId = id
		$.ajax({
				url: '{{ route("site-development.category.edit") }}',
				type: 'POST',
				dataType: 'json',
				data: {
					category: category,
					"_token": "{{ csrf_token() }}",
					categoryId: categoryId
				},
				beforeSend: function() {
					$("#loading-image").show();
				},
			})
			.done(function(data) {
				console.log(data)
				refreshPage()
				$("#loading-image").hide();
				$('#editCategory' + id).modal('hide');
				console.log("success");
			})
			.fail(function(data) {
				console.log(data)
				refreshPage()
				console.log("error");
			});
	}


	function refreshPage() {
		$.ajax({
			url: '{{ route("site-development.index" , $website->id)}}',
			dataType: "json",
			data: {},
		}).done(function(data) {
			$("#documents-table tbody").empty().html(data.tbody);
			if (data.links.length > 10) {
				$('ul.pagination').replaceWith(data.links);
			} else {
				$('ul.pagination').replaceWith('<ul class="pagination"></ul>');
			}

		}).fail(function(jqXHR, ajaxOptions, thrownError) {
			alert('No response from server');
		});
	}

	$(document).on('click', '.create-quick-task', function() {
		var $this = $(this);
		site = $(this).data("id");
		title = $(this).data("title");
		if (!title || title == '') {
			toastr["error"]("Please add title first");
			return;
		}

		$("#create-quick-task").modal("show");
		$("#hidden-task-subject").val(title);
		$('#site_id').val(site);

		// $.ajax({
		// 		url: '/site-development/get-user-involved/'+site,
		// 		dataType: "json",
		// 		type: 'GET',
		// 	}).done(function (response) {
		// 		var option = '<option value="" > Select user </option>';
		// 		$.each(response.data,function(k,v){
		// 			option = option + '<option value="'+v.id+'" > '+v.name+' </option>';
		// 		});

		// 	}).fail(function (jqXHR, ajaxOptions, thrownError) {
		// 	    toastr["error"](jqXHR.responseJSON.message);
		// });
	});

	$(document).on('click', '.send-message-site-quick', function() {
		$this = $(this);
		var id = $(this).data("id");
		var val = $(this).siblings('input').val();
		
		$.ajax({
			url: '/site-development/' + id + '/remarks',
			type: 'POST',
			headers: {
				'X-CSRF-TOKEN': "{{ csrf_token() }}"
			},
			data: {
				remark: val
			},
			beforeSend: function() {
				$("#loading-image").show();
			}
		}).done(function(response) {
			$("#loading-image").hide();
			$this.siblings('input').val("");
			// $('#latest-remarks-modal').modal('hide');
			toastr["success"]("Remarks fetched successfully");
		}).fail(function(jqXHR, ajaxOptions, thrownError) {
			toastr["error"]("Oops,something went wrong");
			$("#loading-image").hide();
		});
	});

	$(document).on('click', '.send-message-site', function() {
		var $this = $(this);
		site = $(this).data("id");
		category = $(this).data("category");
		message = $('#message-' + site).val();
		userId = $('#user-' + site + ' option:selected').val();
		prefix = $this.data("prefix");
		var users = [];

		var hidden_row_class = 'hidden_row_' + category;

		if ($this.closest("tr").find("input[name='developer']:checked").length > 0) {
			var value = $this.closest("tr").find("select[name='developer_id']").val();
			if (value != "") {
				users.push(value);
			}
		}
		if ($this.closest("tr").find("input[name='designer']:checked").length > 0) {
			var value = $this.closest("tr").find("select[name='designer_id']").val();
			if (value != "") {
				users.push(value);
			}
		}
		if ($this.closest("tr").find("input[name='html']:checked").length > 0) {
			var value = $this.closest("tr").find("select[name='html_designer']").val();
			if (value != "") {
				users.push(value);
			}
		}
		if ($this.closest("tr").find("input[name='tester']:checked").length > 0) {
			var value = $this.closest("tr").find("select[name='tester_id']").val();
			if (value != "") {
				users.push(value);
			}
		}

		if (site) {
			$.ajax({
				url: '/whatsapp/sendMessage/site_development',
				dataType: "json",
				type: 'POST',
				data: {
					'site_development_id': site,
					'message': prefix + ' => ' + message,
					'users': users,
					"_token": "{{ csrf_token() }}",
					'status': 2
				},
				beforeSend: function() {
					$('#message-' + site).attr('disabled', true);
				}
			}).done(function(data) {
				toastr["success"]("Message Sent successfully");//Purpose : Display success message - DEVATSK-4361
				$('#message-' + site).attr('disabled', false);
				$('#message-' + site).val('');
			}).fail(function(jqXHR, ajaxOptions, thrownError) {
				alert('No response from server');
			});
		} else {
			alert('Site is not saved please enter value or select User');
		}
	});

	$(document).on("click", ".fa-ignore-category", function() {
		var $this = $(this);
		var msg = "disallow";
		var status = $this.data("status");
		if (status) {
			msg = "allow";
		}
		if (confirm("Are you sure want to " + msg + " this category?")) {
			var store_website_id = $this.data("site-id");
			var category = $this.data("category-id");
			$.ajax({
				url: '/site-development/disallow-category',
				dataType: "json",
				type: 'POST',
				data: {
					'store_website_id': store_website_id,
					'category': category,
					"_token": "{{ csrf_token() }}",
					status: status
				},
				beforeSend: function() {
					$("#loading-image").show();
				}
			}).done(function(data) {
				$("#loading-image").hide();
				toastr["success"]("Category removed successfully");
				$this.closest("tr").remove();
			}).fail(function(jqXHR, ajaxOptions, thrownError) {
				toastr["error"]("Oops,something went wrong");
				$("#loading-image").hide();
			});
		}
	});

	$(document).on("click", ".btn-file-upload", function() {
		var $this = $(this);
		$("#file-upload-area-section").modal("show");
		$("#hidden-store-website-id").val($this.data("store-website-id"));
		$("#hidden-site-id").val($this.data("site-id"));
		$("#hidden-site-category-id").val($this.data("site-category-id"));
	});

	$(document).on("click", ".btn-file-list", function(e) {
		e.preventDefault();
		var $this = $(this);
		var id = $(this).data("site-id");
		$.ajax({
			url: '/site-development/' + id + '/list-documents',
			type: 'GET',
			headers: {
				'X-CSRF-TOKEN': "{{ csrf_token() }}"
			},
			dataType: "json",
			beforeSend: function() {
				$("#loading-image").show();
			}
		}).done(function(response) {
			$("#loading-image").hide();
			var html = "";
			$.each(response.data, function(k, v) {
				html += "<tr>";
				html += "<td>" + v.id + "</td>";
				html += "<td>" + v.url + "</td>";
				html += "<td><div class='form-row'>" + v.user_list + "</div></td>";
				html += '<td><a class="btn-secondary" href="' + v.url + '" data-site-id="' + v.site_id + '" target="__blank"><i class="fa fa-download" aria-hidden="true"></i></a>&nbsp;<a class="btn-secondary link-delete-document" data-site-id="' + v.site_id + '" data-id=' + v.id + ' href="_blank"><i class="fa fa-trash" aria-hidden="true"></i></a>&nbsp;<a class="btn-secondary link-send-document" data-site-id="' + v.site_id + '" data-id=' + v.id + ' href="_blank"><i class="fa fa-comment" aria-hidden="true"></i></a></td>';
				html += "</tr>";
			});
			$(".display-document-list").html(html);
			$("#file-upload-area-list").modal("show");
		}).fail(function(jqXHR, ajaxOptions, thrownError) {
			toastr["error"]("Oops,something went wrong");
			$("#loading-image").hide();
		});
	});

	$(document).on("click", ".btn-save-documents", function(e) {
		e.preventDefault();
		var $this = $(this);
		var formData = new FormData($this.closest("form")[0]);
		$.ajax({
			url: '/site-development/save-documents',
			type: 'POST',
			headers: {
				'X-CSRF-TOKEN': "{{ csrf_token() }}"
			},
			dataType: "json",
			data: $this.closest("form").serialize(),
			beforeSend: function() {
				$("#loading-image").show();
			}
		}).done(function(data) {
			$("#loading-image").hide();
			toastr["success"]("Document uploaded successfully");
			location.reload();
		}).fail(function(jqXHR, ajaxOptions, thrownError) {
			toastr["error"](jqXHR.responseJSON.message);
			$("#loading-image").hide();
		});
	});


	$(document).on("click", ".link-send-document", function(e) {
		e.preventDefault();
		var id = $(this).data("id");
		var site_id = $(this).data("site-id");
		var user_id = $(this).closest("tr").find(".send-message-to-id").val();
		$.ajax({
			url: '/site-development/send-document',
			type: 'POST',
			headers: {
				'X-CSRF-TOKEN': "{{ csrf_token() }}"
			},
			dataType: "json",
			data: {
				id: id,
				site_id: site_id,
				user_id: user_id
			},
			beforeSend: function() {
				$("#loading-image").show();
			}
		}).done(function(data) {
			$("#loading-image").hide();
			toastr["success"]("Document sent successfully");
		}).fail(function(jqXHR, ajaxOptions, thrownError) {
			toastr["error"]("Oops,something went wrong");
			$("#loading-image").hide();
		});

	});

	$(document).on("click", ".link-delete-document", function(e) {
		e.preventDefault();
		var id = $(this).data("id");
		var $this = $(this);
		if (confirm("Are you sure you want to delete records ?")) {
			$.ajax({
				url: '/site-development/delete-document',
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': "{{ csrf_token() }}"
				},
				dataType: "json",
				data: {
					id: id
				},
				beforeSend: function() {
					$("#loading-image").show();
				}
			}).done(function(data) {
				$("#loading-image").hide();
				toastr["success"]("Document deleted successfully");
				$this.closest("tr").remove();
			}).fail(function(jqXHR, ajaxOptions, thrownError) {
				toastr["error"]("Oops,something went wrong");
				$("#loading-image").hide();
			});
		}
	});

	$(document).on("click", ".btn-store-development-remark", function(e) {
		var id = $(this).data("site-id");
		$.ajax({
			url: '/site-development/' + id + '/remarks',
			type: 'GET',
			headers: {
				'X-CSRF-TOKEN': "{{ csrf_token() }}"
			},
			beforeSend: function() {
				$("#loading-image").show();
			}
		}).done(function(response) {
			$("#loading-image").hide();
			toastr["success"]("Remarks fetched successfully");

			var html = "";

			$.each(response.data, function(k, v) {
				html += "<tr>";
				html += "<td>" + v.id + "</td>";
				html += "<td>" + v.remarks + "</td>";
				html += "<td>" + v.created_by + "</td>";
				html += "<td>" + v.created_at + "</td>";
				html += "</tr>";
			});

			$("#remark-area-list").find("#remark-field").data("id", id);
			$("#remark-area-list").find(".remark-action-list-view").html(html);
			$("#remark-area-list").modal("show").css('z-index',1051);
			//$this.closest("tr").remove();
		}).fail(function(jqXHR, ajaxOptions, thrownError) {
			toastr["error"]("Oops,something went wrong");
			$("#loading-image").hide();
		});
	});

	var uploadedDocumentMap = {}
	Dropzone.options.documentDropzone = {
		url: '{{ route("site-development.upload-documents") }}',
		maxFilesize: 20, // MB
		addRemoveLinks: true,
		headers: {
			'X-CSRF-TOKEN': "{{ csrf_token() }}"
		},
		success: function(file, response) {
			$('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
			uploadedDocumentMap[file.name] = response.name
		},
		removedfile: function(file) {
			file.previewElement.remove()
			var name = ''
			if (typeof file.file_name !== 'undefined') {
				name = file.file_name
			} else {
				name = uploadedDocumentMap[file.name]
			}
			$('form').find('input[name="document[]"][value="' + name + '"]').remove()
		},
		init: function() {

		}
	}

	$(document).on('click', '.preview-img-btn', function(e) {
		e.preventDefault();
		id = $(this).data('id');
		if (!id) {
			alert("No data found");
			return;
		}
		$.ajax({
			url: "/site-development/preview-img/" + id,
			type: 'GET',
			beforeSend: function() {
				$("#loading-image").show();
			},
			success: function(response) {
				var tr = '';
				for (var i = 1; i <= response.data.length; i++) {
					tr = tr + '<tr><td>' + i + '</td><td><img style="height:100px;" src="' + response.data[i - 1].url + '"></td></tr>';
				}
				$("#preview-website-image").modal("show");
				$(".website-image-list-view").html(tr);
				$("#loading-image").hide();
			},
			error: function() {
				$("#loading-image").hide();
			}
		});
	});

	$(document).on('click', '.latest-remarks-btn', function(e) {
		websiteId = $('#website_id').val();
		websiteId = $.trim(websiteId);
		$.ajax({
			url: "/site-development/latest-reamrks/" + websiteId,
			type: 'GET',
			beforeSend: function() {
				$("#loading-image").show();
			},
			success: function(response) {
				var tr = '';
				for (var i = 1; i <= response.data.length; i++) {
					var siteId = response.data[i - 1].site_id;
					var cateogryId = response.data[i - 1].category_id;
					var user_id = response.data[i - 1].user_id;
					var storeWebsite = response.data[i - 1].sw_website;
					var storeDev = response.data[i - 1].sd_title;
					var user_id = response.data[i - 1].user_id;
					tr = tr + '<tr><td>' + i + '</td><td>' + response.data[i - 1].title + '</td><td>' + response.data[i - 1].username + '</td><td>' + response.data[i - 1].remarks + '<button type="button" data-site-id="' + response.data[i - 1].site_id + '" class="btn btn-store-development-remark pd-5"><i class="fa fa-comment" aria-hidden="true"></i></button></td><td><div class="d-flex"><input type="text" class="form-control quick-message-field" name="message" placeholder="Message" value="" id="message-' + siteId + '"><button style="padding: 2px;" class="btn btn-sm btn-image send-message-site-quick" data-prefix="# ' + storeWebsite + ' ' + storeDev + '" data-user="' + user_id + '" data-category="' + cateogryId + '" data-id="' + siteId + '"><img src="/images/filled-sent.png"/></button></div></td></tr>';
				}
				$("#latest-remarks-modal").modal("show");
				$(".latest-remarks-list-view").html(tr);
				$("#loading-image").hide();
			},
			error: function() {
				$("#loading-image").hide();
			}
		});
	});

	$(document).on('click', '.artwork-history-btn', function(e) {
		id = $(this).data('id');
		if (!id) return;
		$.ajax({
			url: "/site-development/artwork-history/" + id,
			type: 'GET',
			beforeSend: function() {
				$("#loading-image").show();
			},
			success: function(response) {
				console.log(response);
				var tr = '';
				for (var i = 1; i <= response.data.length; i++) {
					tr = tr + '<tr><td>' + i + '</td><td>' + response.data[i - 1].date + '</td><td> Status changed from ' + response.data[i - 1].from_status + ' to ' + response.data[i - 1].to_status + '</td><td>' + response.data[i - 1].username + '</td></tr>';
				}
				$("#artwork-history-modal").modal("show");
				$(".artwork-history-list-view").html(tr);
				$("#loading-image").hide();
			},
			error: function() {
				$("#loading-image").hide();
			}
		});
	});


	$(document).on("click", ".create-task", function(e) {
		e.preventDefault();
		var form = $(this).closest("form");
		$.ajax({
			url: form.attr("action"),
			type: 'POST',
			data: form.serialize(),
			beforeSend: function() {
				$(this).text('Loading...');
			},
			success: function(response) {
				if (response.code == 200) {
					form[0].reset();
					toastr['success'](response.message);
					$("#create-quick-task").modal("hide");
				} else {
					toastr['error'](response.message);
				}
			}
		}).fail(function(response) {
			toastr['error'](response.responseJSON.message);
		});
	});

	$(document).on("click", ".toggle-class", function() {
		$(".hidden_row_" + $(this).data("id")).toggleClass("dis-none");
	});
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$(".developer").change(function() {
			$(this).closest("tr").find("input[name='developer']").prop('checked', true)
		});

		$(".designer").change(function() {
			$(this).closest("tr").find("input[name='designer']").prop('checked', true)
		});

		$(".html").change(function() {
			$(this).closest("tr").find("input[name='html']").prop('checked', true)
		});
	});
</script>
<script>
	$(document).on("click", ".count-dev-customer-tasks", function() {

		var $this = $(this);
		// var user_id = $(this).closest("tr").find(".ucfuid").val();
		var site_id = $(this).data("id");
		$.ajax({
			type: 'get',
			url: '/site-development/countdevtask/' + site_id,
			dataType: "json",
			beforeSend: function() {
				$("#loading-image").show();
			},
			success: function(data) {
				$("#dev_task_statistics").modal("show");
				var table = '<div class="table-responsive"><table class="table table-bordered table-striped"><tr><th>Task type</th><th>Assigned to</th><th>Description</th><th>Status</th><th>Communicate</th><th>Action</th></tr>';
				for (var i = 0; i < data.taskStatistics.length; i++) {
					var str = data.taskStatistics[i].subject;
					var res = str.substr(0, 100);
					var status = data.taskStatistics[i].status;
					if(typeof status=='undefined' || typeof status=='' || typeof status=='0' ){ status = 'In progress'};
					table = table + '<tr><td>' + data.taskStatistics[i].task_type + '</td><td>' + data.taskStatistics[i].assigned_to_name + '</td><td>' + res + '</td><td>' + status + '</td><td><div class="col-md-10 pl-0 pr-1"><input type="text" style="width: 100%; float: left;" class="form-control quick-message-field input-sm" name="message" placeholder="Message" value=""></div><div class="col-md-2"><button style="float: left;" class="btn btn-sm btn-image send-message" title="Send message" data-taskid="'+ data.taskStatistics[i].id +'"><img src="/images/filled-sent.png" style="cursor: default;"></button></div></td><td><button type="button" class="btn btn-xs btn-image load-communication-modal load-body-class" data-object="' + data.taskStatistics[i].message_type + '" data-id="' + data.taskStatistics[i].id + '" title="Load messages" data-dismiss="modal"><img src="/images/chat.png" alt=""></button>';
					table = table + '| <a href="javascript:void(0);" data-task-type="'+data.taskStatistics[i].task_type +'" data-id="' + data.taskStatistics[i].id + '" class="delete-dev-task-btn btn btn-image pd-5"><img title="Delete Task" src="/images/delete.png" /></a></td>';
					table = table + '</tr>';
				}
				table = table + '</table></div>';
				$("#loading-image").hide();
				$(".modal").css("overflow-x", "hidden");
				$(".modal").css("overflow-y", "auto");
				$("#dev_task_statistics_content").html(table);
			},
			error: function(error) {
				console.log(error);
				$("#loading-image").hide();
			}
		});


	});
	$(document).on('click', '.send-message', function () {
            var thiss = $(this);
            var data = new FormData();
            var task_id = $(this).data('taskid');
            var message = $(this).siblings('input').val();

            data.append("task_id", task_id);
            data.append("message", message);
            data.append("status", 1);

            if (message.length > 0) {
                if (!$(thiss).is(':disabled')) {
                    $.ajax({
                        url: '/whatsapp/sendMessage/task',
                        type: 'POST',
                        "dataType": 'json',           // what to expect back from the PHP script, if anything
                        "cache": false,
                        "contentType": false,
                        "processData": false,
                        "data": data,
                        beforeSend: function () {
                            $(thiss).attr('disabled', true);
                        }
                    }).done(function (response) {
                        $(thiss).siblings('input').val('');

                        if (cached_suggestions) {
                            suggestions = JSON.parse(cached_suggestions);

                            if (suggestions.length == 10) {
                                suggestions.push(message);
                                suggestions.splice(0, 1);
                            } else {
                                suggestions.push(message);
                            }
                            localStorage['message_suggestions'] = JSON.stringify(suggestions);
                            cached_suggestions = localStorage['message_suggestions'];

                            console.log('EXISTING');
                            console.log(suggestions);
                        } else {
                            suggestions.push(message);
                            localStorage['message_suggestions'] = JSON.stringify(suggestions);
                            cached_suggestions = localStorage['message_suggestions'];

                            console.log('NOT');
                            console.log(suggestions);
                        }

                        // $.post( "/whatsapp/approve/customer", { messageId: response.message.id })
                        //   .done(function( data ) {
                        //
                        //   }).fail(function(response) {
                        //     console.log(response);
                        //     alert(response.responseJSON.message);
                        //   });

                        $(thiss).attr('disabled', false);
                    }).fail(function (errObj) {
                        $(thiss).attr('disabled', false);

                        alert("Could not send message");
                        console.log(errObj);
                    });
                }
            } else {
                alert('Please enter a message first');
            }
    });
	$(document).on("click",".delete-dev-task-btn",function() {
		var x = window.confirm("Are you sure you want to delete this ?");
            if(!x) {
                return;
            }
            var $this = $(this);
            var taskId = $this.data("id");
			var tasktype = $this.data("task-type");
            if(taskId > 0) {
                $.ajax({
                    beforeSend : function() {
                        $("#loading-image").show();
                    },
                    type: 'get',
                    url: "/site-development/deletedevtask",
                    headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
                    data: {id : taskId,tasktype:tasktype},
                    dataType: "json"
                }).done(function (response) {
                    $("#loading-image").hide();
                    if(response.code == 200) {
                        $this.closest("tr").remove();
                    }
                }).fail(function (response) {
                    $("#loading-image").hide();
                    alert('Could not update!!');
                });
            }

        });


		//START - Purpose : Show / Hide Chat & Remarks - #DEVTASK-19918
		$(document).on('click', '.expand-row-msg', function () {
            var id = $(this).data('id');
            var full = '.expand-row-msg .td-full-container-'+id;
            var mini ='.expand-row-msg .td-mini-container-'+id;
            $(full).toggleClass('hidden');
            $(mini).toggleClass('hidden');
        });

		$(document).on('click', '.expand-row-msg-chat', function () {
            var id = $(this).data('id');
			console.log(id);
            var full = '.expand-row-msg-chat .td-full-chat-container-'+id;
            var mini ='.expand-row-msg-chat .td-mini-chat-container-'+id;
            $(full).toggleClass('hidden');
            $(mini).toggleClass('hidden');
        });
		//END - #DEVTASK-19918
</script>
@endsection