<div id="createstatusModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content ">
			<div class="modal-header">
				<h4 class="modal-title">Create Status</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="{{route('return-exchange.createStatus')}}" id="createStatusForm" method="POST">
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-2">
								<strong>Status:</strong>
							</div>
							<div class="col-md-8">
								<div class="form-group">
								<input type="text" name="status_name" required class="form-control" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-secondary">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>

