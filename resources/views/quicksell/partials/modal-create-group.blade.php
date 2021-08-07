<div id="productGroup" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Product</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="{{ route('quicksell.save.group') }}" method="POST">


                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <strong>Quick Sell Product:</strong>
                        <input type="hidden" name="type" value="2">
                        @php
                        $products = \App\Product::where('quick_product',1)->get();
                        @endphp
                        <select class="form-control" name="products[]" multiple>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
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




