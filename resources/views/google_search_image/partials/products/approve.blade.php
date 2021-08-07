


    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#product-sku">Get Product By Text</button>

    <div class="productGrid" id="productGrid">
        {!! $products->appends(Request::except('page'))->links() !!}
        <form method="POST" action="{{route('google.search.crop')}}" id="theForm">
            {{ csrf_field() }}
            <div class="row">
                <table class="table table-bordered" style="width: 100% !important;">
        <thead>
        <tr>
            <th width="25%">Product</th>
            <th width="75%">Images</th>
            
        </tr>
        </thead>
        <tbody>
                @foreach ($products as $product)
                <tr id="product{{ $product->id }}">
                <td><p>Status : {{ ucwords(\App\Helpers\StatusHelper::getStatus()[$product->status_id]) }}</p>
                        <p>Brand : {{ isset($product->brands) ? $product->brands->name : "" }}</p>
                        <p>Transit Status : {{ $product->purchase_status }}</p>
                        <p>Location : {{ ($product->location) ? $product->location : "" }}</p>
                        <p>Sku : {{ $product->sku }}</p>
                        <p>Id : {{ $product->id }}</p>
                        <p>Size : {{ $product->size}}</p>
                        <p>Price ({{ $product->currency }}) : {{ $product->price }}</p>
                        <p>Price (INR) : {{ $product->price_inr }}</p>
                        <p>Price Special (INR) : {{ $product->price_special }}</p></td>
                <td>
                    <div class="row">
                        @php
                        $images = $product->getMedia(config('constants.google_text_search'))->all();
                        @endphp
                        @if(count($images) != 0)
                        @foreach($images as $image)
                            <div class="col-md-2 col-half-offset"><div class="text-center"><img height="100" width="100" src="{{ $image->getUrl() }}" onclick="openImage(this.src)"><br><input type="checkbox" class="checkbox{{ $product->id }}" value="{{ $image->id }}"></div></div>
                        @endforeach
                        
                        </div>
                        <div class="row padding-x-md">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-secondary" onclick="selectAll({{ $product->id }})" id="selected{{ $product->id }}">Select All</button>
                                <button type="button" class="btn btn-secondary" onclick="approveProduct({{ $product->id }})">Approve</button>
                                <button type="button" class="btn btn-secondary" onclick="rejectProduct({{ $product->id }})">Reject</button>
                            </div>
                        </div>    
                        @endif
                    
                </td>
                </tr>
                    @endforeach
        
                </tbody>
            </table>
            <div class="row">
                <div class="col text-center">
                    <button type="button" class="btn btn-image my-3" id="sendImageMessage" onclick="sendImage()"><img src="/images/filled-sent.png"/></button>
                </div>
            </div>
        </form>
        {!! $products->appends(Request::except('page'))->links() !!}
    </div>
    @include('google_search_image.partials.image-crop')
