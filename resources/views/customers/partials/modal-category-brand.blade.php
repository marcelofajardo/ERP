<div id="categoryBrandModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Send Last Scraped Images</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- id="customerSendScrap" -->
            <form action="" id="customerSendScrap" method="GET" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="customer_id" value="">
                <input type="hidden" name="submit_type" value="">

                <div class="modal-body">
                    <div class="form-group">
                        <strong>Category</strong>
                        {!! $category_suggestion !!}
                    </div>
                    <div class="form-group">
                        <strong>Brand</strong>
                        <select class="form-control globalSelect2" data-ajax="{{ route('select2.brands') }}"
                            name="brand[]" multiple>
                            <option value="">Select a Brand</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <strong>Supplier</strong>
                        <select class="form-control globalSelect2" data-ajax="{{ route('select2.suppliers') }}"
                            name="supplier[]" multiple>
                            <option value="">Select a Supplier</option>
                            {{-- @foreach (\App\Supplier::all() as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                            @endforeach --}}
                        </select>
                    </div>
                    <div class="form-group">
                        <strong>Total</strong>
                        <input type="number" name="total_images" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <strong>Keyword</strong>
                        <input type="text" name="term" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-6 form-group">
                            <strong>Min price</strong>
                            <input type="text" min="0" max="400000" name="price_min" class="form-control">
                        </div>
                        <div class="col-6 form-group">
                            <strong>Max price</strong>
                            <input type="text" min="0" name="price_max" max="400000" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 form-group">
                            <strong>Min Discount %</strong>
                            <input type="text" min="0" max="100" name="discounted_percentage_min" class="form-control">
                        </div>
                        <div class="col-6 form-group">
                            <strong>Max Discount %</strong>
                            <input type="text" min="0" name="discounted_percentage_max" max="100" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <!-- <button type="submit" class="btn btn-secondary" id="sendScrapedButton">Send</button> -->
                    <button id="attachImages2" class="btn btn-secondary send-to-approval-btn">Send To Image
                        Approval</button>
                    <button id="attachImages1" class="btn btn-secondary old-send-btn">Send</button>
                </div>
            </form>
        </div>

    </div>
</div>
