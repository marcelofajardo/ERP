<div class="modal fade" id="product-sku" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
           
                <div class="modal-body">
                     <div class="form-group">
                        @php $brands = \App\Brand::all();
                        @endphp
                        <strong>Brand:</strong>

                        <select class="form-control selectpicker" data-live-search="true" data-size="15" name="first_customer" title="Choose Brand" onchange="addDataToTextInput('0')" id="brand">
                            @foreach($brands as $brand)
                            
                            <option class="form-control" value="{{ $brand->name }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('first_customer'))
                            <div class="alert alert-danger">{{$errors->first('first_customer')}}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <strong>SKU:</strong>
                        <input class="form-control" onkeypress="addDataToTextInput('1')" id="sku">
                        @if ($errors->has('first_customer'))
                            <div class="alert alert-danger">{{$errors->first('first_customer')}}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <strong>Title:</strong>
                       <input class="form-control" name="first_customer" onkeypress="addDataToTextInput('2')" id="title">

                        @if ($errors->has('first_customer'))
                            <div class="alert alert-danger">{{$errors->first('first_customer')}}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <strong>Search:</strong>
                        <input type="text" name="" id="input-field-search" class="form-control">

                        @if ($errors->has('first_customer'))
                            <div class="alert alert-danger">{{$errors->first('first_customer')}}</div>
                        @endif
                    </div>
                   
                
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-secondary" onclick="getProductsFromText()">Save Products</button>
                </div>
        </div>
    </div>
</div>