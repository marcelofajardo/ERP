    <div id="whatsAppConfigCreateModal" class="modal fade" role="dialog">
    	<div class="modal-dialog">

    		<!-- Modal content-->
    		<div class="modal-content">
    			<form action="{{ route('platforms.store') }}" method="POST">
    				@csrf

    				<div class="modal-header">
    					<h4 class="modal-title">Store Marketing Platform</h4>
    					<button type="button" class="close" data-dismiss="modal">&times;</button>
    				</div>
    				<div class="modal-body">


    					<div class="form-group">
    						<strong>Name:</strong>
    						<input type="text" name="name" class="form-control" value="{{ old('name') }}" required>

    						@if ($errors->has('name'))
    						<div class="alert alert-danger">{{$errors->first('name')}}</div>
    						@endif
    					</div>

    				</div>
    				<div class="modal-footer">
    					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    					<button type="submit" class="btn btn-secondary">Store</button>
    				</div>
    			</form>
    		</div>

    	</div>
    </div>