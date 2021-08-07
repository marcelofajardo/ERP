<div id="productGroupDetails" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Products Group</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="{{ route('quicksell.group.update') }}" method="POST">

            	
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="product_id" id="product_group_id">
                    <div class="form-group">
                        <strong>Add Product To Existing Group:</strong>
                        @php
                        $groups = \App\QuickSellGroup::all();
                        @endphp
                        <select class="form-control selectpicker" data-live-search="true" name="groups">
                        	<option value="">Select Group</option>
                        @foreach($groups as $group)
                        	<option value="{{ $group->id }}">@if($group->name != null) {{ $group->name }} @else {{ $group->group }}  @endif</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="row">
                    <div class="col-md-6">
                    	<div class="form-group">
                        <strong>Brand</strong>
                       
                        <select class="form-control selectpicker" data-live-search="true" name="brands[]" multiple>
                        @php
                        $brands = \App\Brand::orderBy('name','asc')->get();
                        @endphp	
                        <option selected value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                        </select>
                    	</div>
                    </div>	


                 
                    </div>
                    
                    <div class="row">
                    	
                    <div class="col-md-6">
                    <div class="form-group">
                        <strong>Category</strong>
                        @php
                        $category_parent = \App\Category::where('parent_id', 0)->orderby('title','asc')->get();
                        $category_child = \App\Category::where('parent_id', '!=', 0)->orderby('title','asc')->get();
                        @endphp
                         <select class="form-control selectpicker" data-live-search="true" name="categories[]" multiple >
                         	<option selected value="">Select Category</option>
                    	@foreach($category_parent as $c)
                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                            @if($c->childs)
                            	@foreach($c->childs as $categ)
                            	<option value="{{ $categ->id }}">---{{ $categ->title }}</option>
                            	@endforeach
                            @endif
                        @endforeach
                        @foreach($category_child as $c)
                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                            @if($c->childs)
                            	@foreach($c->childs as $categ)
                            	<option value="{{ $categ->id }}">---{{ $categ->title }}</option>
                            	@endforeach
                            @endif
                        @endforeach
                        </select>
                    </div>
               		</div>
                    </div>
                    <div class="form-group">
                        <strong>Supplier:</strong>
                       
                        <select class="form-control selectpicker" data-live-search="true" name="suppliers[]" multiple>
                        	 <option selected value="">Select Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <strong>Buying Price:</strong>
                        <input type="text" class="form-control" name="buying_price" placeholder="Enter Buying Price" >
                        </select>
                    </div>
                    <div class="form-group">
                        <strong>Special Price:</strong>
                        <input type="text" class="form-control" name="special_price" placeholder="Enter Special Price" >
                    </div>
                    <div class="form-group">
                        <strong>Group Id:</strong>
                        <input type="text" class="form-control" name="group_id" placeholder="Enter Group Name">
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary">Create</button>
                </div>
            </form>
        </div>

    </div>
</div>