<div id="categoryCreateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <form action="{{ route('supplier/add/category') }}" method="POST">
			@csrf

			<div class="modal-header">
				<h4 class="modal-title">Add Supplier Category</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>Name:</label>
					<input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
					@if($errors->has('name'))<p style="color: red;">{{ $errors->first('name') }}</p>@endif
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-secondary">Add</button>
			</div>
        </form>
      </div>

    </div>
  </div>

  <div id="subcategoryCreateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <form action="{{ route('supplier/add/subcategory') }}" method="POST">
			@csrf

			<div class="modal-header">
				<h4 class="modal-title">Add Supplier Sub Category</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>Name:</label>
					<input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
					@if($errors->has('name'))<p style="color: red;">{{ $errors->first('name') }}</p>@endif
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-secondary">Add</button>
			</div>
        </form>
      </div>

    </div>
  </div>

  <div id="statusCreateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <form action="{{ route('supplier/add/status') }}" method="POST">
			@csrf

			<div class="modal-header">
				<h4 class="modal-title">Add Status</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>Status Name:</label>
					<input type="text" name="name" class="form-control" value="" required>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-secondary">Add</button>
			</div>
        </form>
      </div>

    </div>
  </div>

  <div id="suppliersizeCreateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <form action="{{ route('supplier/add/suppliersize') }}" method="POST">
			@csrf

			<div class="modal-header">
				<h4 class="modal-title">Add Supplier Size</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>Supplier Size:</label>
					<input type="number" name="size" class="form-control" value="" required>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-secondary">Add</button>
			</div>
        </form>
      </div>

    </div>
  </div>


<div id="create_broadcast" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Send Message to Supplier</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form id="send_message" method="POST">
				<div class="modal-body">
					<div class="form-group">
						<strong>Message</strong>
						<textarea name="message" id="message_to_all_field" rows="8" cols="80" class="form-control"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-secondary">Send Message</button>
				</div>
			</form>
		</div>

	</div>
</div>


<div id="addPriceRange" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <form action="{{ route('supplier/add/pricernage') }}" method="POST">
			@csrf

			<div class="modal-header">
				<h4 class="modal-title">Add Supplier Price Range</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>Price From:</label>
					<input type="number" name="price_from" class="form-control" value="" required>
				</div>
				<div class="form-group">
					<label>Price To:</label>
					<input type="number" name="price_to" class="form-control" value="" required>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-secondary">Add</button>
			</div>
        </form>
      </div>

    </div>
  </div>	
