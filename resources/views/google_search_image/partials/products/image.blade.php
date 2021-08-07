

    <button type="button" class="btn btn-secondary select-all-system-btn" data-count="0">Send All In System</button>
    <button type="button" class="btn btn-secondary select-all-page-btn" data-count="0">Send All On Page</button>
    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#product-sku">Get Product By Text</button>

     <div class="row">
        <div class="col-md-12">
            <div class="panel-group">
                <div class="panel mt-5 panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#collapse2">Result From Top Website</a>
                        </h4>
                    </div>
                    <div id="collapse2" class="panel-collapse collapse">
                        <div class="panel-body">
                            <table class="table table-bordered table-striped" id="phone-table">
                                <thead>
                                <tr>
                                    <th>URL</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($top_url as $key => $value)
                                     <tr>   
                                        <td>{{ key($value) }}</td>
                                     </tr>   
                                    @endforeach    
                                </tbody>        
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="productGrid" id="productGrid">
        {!! $products->appends(Request::except('page'))->links() !!}
        <form method="POST" action="{{route('google.search.crop')}}" id="theForm">
            {{ csrf_field() }}
            <div class="row">
                @foreach ($products as $product)
                    <div class="col-md-3 col-xs-6 text-left" style="border: 1px solid #cccccc;">
                        <?php if($product->hasMedia(config('constants.media_tags'))) { ?>
                            <a href="{{ route('products.show', $product->id) }}" target="_blank"><img src="{{ $product->getMedia(config('constants.media_tags'))->first() ? $product->getMedia(config('constants.media_tags'))->first()->getUrl() : '' }}" class="img-responsive grid-image" alt=""/></a>
                        <?php } ?>
                        <p>Status : {{ ucwords(\App\Helpers\StatusHelper::getStatus()[$product->status_id]) }}</p>
                        <p>Brand : {{ isset($product->brands) ? $product->brands->name : "" }}</p>
                        <p>Transit Status : {{ $product->purchase_status }}</p>
                        <p>Location : {{ ($product->location) ? $product->location : "" }}</p>
                        <p>Sku : {{ $product->sku }}</p>
                        <p>Id : {{ $product->id }}</p>
                        <p>Size : {{ $product->size}}</p>
                        <p>Price ({{ $product->currency }}) : {{ $product->price }}</p>
                        <p>Price (INR) : {{ $product->price_inr }}</p>
                        <p>Price Special (INR) : {{ $product->price_special }}</p>
                        <input type="checkbox" class="select-product-edit" name="product_id" value="{{ $product->id }}" style="margin: 10px !important;">
                        <?php if($product->hasMedia(config('constants.media_tags'))) { ?>
                            <input type="hidden" id="img{{ $product->id }}" value="{{ $product->getMedia(config('constants.media_tags'))->first()->getUrl() }}">
                            @if($product->status_id == 31)<a href="{{ route('products.show', $product->id) }}" target="_blank" class="btn btn-secondary">Verify</a>@endif
                        <?php } ?>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col text-center">
                    <button type="button" class="btn btn-image my-3" id="sendImageMessage" onclick="sendImage()"><img src="/images/filled-sent.png"/></button>
                </div>
            </div>
        </form>
        {!! $products->appends(Request::except('page'))->links() !!}
    </div>
