<div id="suggestionModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('customer.send.suggestion') }}" method="POST">
        @csrf
        <input type="hidden" name="customer_id" value="{{ isset($customer) ? $customer->id : 0 }}">

        <div class="modal-header">
          <h4 class="modal-title">Send Suggestion</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <strong>Brands:</strong>
                <select style="width: 100%" data-placeholder="Select brand.." id="customer_brands" class="form-control select-multiple select2" name="brand[]" multiple>
                  @foreach ($brands as $brand)
                    <option value="{{ $brand['id'] }}">{{ $brand['name'] }}</option>
                  @endforeach
                </select>

                @if ($errors->has('brand'))
                  <div class="alert alert-danger">{{$errors->first('brand')}}</div>
                @endif
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <strong>Suppliers:</strong>
                <select style="width: 100%" data-placeholder="Seelct Supplier..." id="customer_suppliers" class="form-control select-multiple select2" name="supplier[]" multiple>
                  @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                  @endforeach
                </select>

                @if ($errors->has('supplier'))
                  <div class="alert alert-danger">{{$errors->first('supplier')}}</div>
                @endif
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <strong>Sizes:</strong>
                <select style="width: 100%" data-placeholder="Select Sizes..." class="form-control select-multiple select2" name="size[]" id="size_selection" multiple>
                  {{-- @foreach ($brands as $brand)
                   <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                 @endforeach --}}
                </select>

                @if ($errors->has('size'))
                  <div class="alert alert-danger">{{$errors->first('size')}}</div>
                @endif
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <strong>Number of Images:</strong>
                <input type="number" class="form-control" name="number" min="0" value="5" required>

                @if ($errors->has('number'))
                  <div class="alert alert-danger">{{$errors->first('number')}}</div>
                @endif
              </div>
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <strong>Categories:</strong>
              {!! $category_suggestion !!}

              @if ($errors->has('category'))
                <div class="alert alert-danger">{{$errors->first('category')}}</div>
              @endif
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <strong class="mr-3">Price</strong>
              <input type="text" name="price" data-provide="slider" data-slider-min="0" data-slider-max="400000" data-slider-step="1000" data-slider-value="[0,40000]"/>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary submit-suggestion-modal">Send Suggestion</button>
        </div>
      </form>
    </div>

  </div>
</div>
