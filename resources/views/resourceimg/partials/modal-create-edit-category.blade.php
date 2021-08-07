<div class="modal fade" id="addcategory" tabindex="-1" role="dialog">
	   	<div class="modal-dialog modal-lg">
		    <div class="modal-content">
		    	{!! Form::open(['route'=>'add.resourceCat']) !!}
					<div class="modal-header">
						<h2 class="modal-title" style="font-size: 24px;">Create Resource Category</h2>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
				    <div class="modal-body">
						<div class="row">
							<div class="col-md-8">
				                <div class="row">
				                	<div class="col-md-12">
  		                                <div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
  		                                   
  		                                   <select class="form-control" name="parent_id" data-live-search="true" id="category_id">
  		                                   		<option>Dont Select For Category</option>
					  		                	@foreach($categories as $category)
					  		                		<option value="{{ $category->id }}">{{ $category->title }}</option>
					  		                	@endforeach
					  		                	</select>
  		                                    <span class="text-danger">{{ $errors->first('parent_id') }}</span>
  		                                </div>
				                	</div>
				                	<div class="col-md-12">
				  		                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
				  		                    {!! Form::label('Category Name:') !!}
				  			                <input type="text" name="title" class="form-control" required placeholder="Create Category">
				  		                    <span class="text-danger">{{ $errors->first('title') }}</span>
				  		                </div>
				                	</div>
				                </div>
				            </div>
						</div>
				    </div>
				    <div class="modal-footer">
			            <button type="submit" class="btn btn-secondary"><i class="fa fa-plus"></i></button>
				        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i></button>
				    </div>
				{!! Form::close() !!}
			</div>	
	  	</div>
	</div>
	<div class="modal fade" id="editcategory" tabindex="-1" role="dialog">
	   	<div class="modal-dialog modal-lg">
		    <div class="modal-content">
		    	{!! Form::open(['route'=>'edit.resourceCat']) !!}
					<div class="modal-header">
						<h2 class="modal-title" style="font-size: 24px;">Edit Resource Category</h2>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
				    <div class="modal-body">
						<div class="row">
							<div class="col-md-12">
				                <div class="row">
				                	<div class="col-md-8 col-md-offset-2">
  		                                <div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
  		                                    {!! Form::label('Category:') !!}
  		                	                <?=@$Categories?>
  		                                    <span class="text-danger">{{ $errors->first('parent_id') }}</span>
  		                                </div>
				                	</div>
				                </div>
				            </div>
						</div>
				    </div>
				    <div class="modal-footer">
				    	<button type="submit" name="type" value="edit" class="btn btn-image"><i class="fa fa-pencil"></i></button>
                        <button type="submit" name="type" value="delete" class="btn btn-image"><i class="fa fa-trash"></i></button>
				        <button type="button" class="btn btn-image" data-dismiss="modal"><i class="fa fa-times"></i></button>
				    </div>
				{!! Form::close() !!}
			</div>	
	  	</div>
	</div>