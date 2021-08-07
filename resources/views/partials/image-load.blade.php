
<div class="row">
  <div class="col text-center">
	  <form action="/" method="get" id="product-mail">
			
			<div class="form-group mr-pd col-md-2">
				@php $rViewMail = !empty($rViewMail) ? $rViewMail : []  @endphp
        {{ Form::select(null, ["-- Select email template --"] + $rViewMail, request('tpl'), ["class" => "form-control change-email-template", "required" => true, "id" => "form_mail_tpl"]) }}
			</div>
			<input type="hidden" name="product_ids" id="input_product_ids">
			<div class="form-group mr-pd col-md-2">    
				<button type="submit" class="btn btn-secondary btn-template-status"></i>Submit</button>
			</div>
		</form>
    <button type="button" class="btn btn-image my-3" id="sendImageMessage"><img src="/images/filled-sent.png" /></button>
  </div>
</div>
<div class="infinite-scroll">
  <div class="row">
    @foreach ($products as $kr => $product)
      @if ($product->hasMedia(config('constants.attach_image_tag')))
        @php
          $image = $product->getMedia(config('constants.attach_image_tag'))->first();
          $image_keys = [];
          $selected_all = true;
          $images = [];
          foreach ($product->getMedia(config('constants.attach_image_tag')) as $img) {
            $image_keys[] = $img->getKey();
            $images[] = [
              "abs" => $img->getAbsolutePath(),
              "url" => $img->getUrl(),
              "id"  => $img->getKey()
            ];
            if (!in_array($img->getKey(), $selected_products)) {
              $selected_all = false;
            }
          }

          $image_keys = json_encode($image_keys);
        @endphp
        {{-- @foreach ($images as $image) --}}
          <div class="col-md-3 col-xs-6 text-center mb-5 product-list-card">
              <div class="row">
                <div class="product-slider">
                  <div data-interval="false" id="carousel_{{ $product->id }}" class="carousel slide" data-ride="carousel">
                    <a href="{{ route('products.show', $product->id) }}" data-toggle="tooltip" data-html="true" data-placement="top" title="<strong>Supplier: </strong>{{ $product->supplier }} <strong>Status: </strong>{{ $product->purchase_status }}">
                      <div class="carousel-inner maincarousel">
                        @foreach($images as $p => $im)
                            <div class="item @if($p == 0) active @endif"> <img src="{{ urldecode($im['url'])}}"> </div>
                        @endforeach
                      </div>
                    </a>
                  </div>
                  <div class="clearfix">
                    <div id="thumbcarousel_{{ $product->id }}" class="carousel slide thumbcarousel attach-thumb-created" data-interval="false">
                      <div class="carousel-inner">
                          @foreach($images as $p => $im)
                              <div class="item @if($p == 0) active @endif">
                                  <div data-target="#carousel_{{ $product->id }}" data-image="{{$im['id']}}" data-slide-to="{{ $p }}" class="thumb"><img width="25px" height="25px;" src="{{ urldecode($im['url'])}}"></div>
                              </div>
                          @endforeach
                      </div>
                      <a class="left carousel-control" href="#thumbcarousel_{{ $product->id }}" role="button" data-slide="prev"> <i class="fa fa-angle-left" aria-hidden="true"></i> </a> <a class="right carousel-control" href="#thumbcarousel_{{ $product->id }}" role="button" data-slide="next"><i class="fa fa-angle-right" aria-hidden="true"></i> </a> </div>
                  </div>
                </div>  
              </div>
              <a href="{{ route('products.show', $product->id) }}" data-toggle="tooltip" data-html="true" data-placement="top" title="<strong>Supplier: </strong>{{ $product->supplier }} <strong>Status: </strong>{{ $product->purchase_status }}">  
                <div style="text-align: left;">
                  <p>Sku : {{ strlen($product->sku) > 18 ? substr($product->sku, 0, 15) . '...' : $product->sku }}</p>
                  <p>Id : {{ $product->id }}</p>
                  <p>Title : {{ $product->name }} </p>
                  <p>Category : @php
                      if(!isset($product->product_category)){
                        $id = 1;
                      }else{
                        $id = $product->product_category->id;
                      }

                      if(!isset($renderCategory)) {
                         $renderCategory = nestable()->make(\App\Category::all());
                      }

                      $render = $renderCategory->attr([
                        'name' => 'category[]', 
                        'class' => 'form-control select-multiple-cat-list update-product', 
                        'data-placeholder' => 'Select Category..' , 
                        'data-id' => $product->id 
                      ])->selected($id)->renderAsDropdown();

                      @endphp
                      @if($render)
                          {!! $render !!}
                      @endif
                  </p>
                  <p>Size : {{ strlen($product->size) > 17 ? substr($product->size, 0, 14) . '...' : $product->size }}</p>
                  <p>Price EUR Special : {{ $product->price_eur_special }}</p>
                  <p>Price INR Special : {{ $product->price_inr_special }}</p>
                  <p>Color : {{ $product->color }} </p>
                  <p>Created At : {{ date("Y-m-d g:i a",strtotime($product->created_at)) }}</p>
              </div>
            </a>
            <div style="text-align: left;">
              <p onclick="myFunction({{ $product->id }})" id="description{{ $product->id }}">Description : {{ strlen($product->short_description) > 40 ? substr($product->short_description, 0, 40).'...' : $product->short_description }}</p>
              <p onclick="myFunction({{ $product->id }})" style="display: none;" id="description_full{{ $product->id }}">Description :{{ $product->short_description }}</p>
            </div>

            <div class="custom-control custom-checkbox mb-4">
                <input type="checkbox" class="custom-control-input select-pr-list-chk" id="defaultUnchecked_{{ $product->id.$kr}}">
                <label class="custom-control-label" for="defaultUnchecked_{{ $product->id.$kr}}"></label>
            </div>
            <a href="#" class="btn btn-xs {{ in_array($image->getKey(), $selected_products) ? 'btn-success' : 'btn-secondary' }} attach-photo" data-image="{{ ($model_type == 'purchase-replace' || $model_type == 'broadcast-images' || $model_type == 'landing-page' || $model_type == 'newsletters') ? $product->id : $image->getKey() }}" data-attached="{{ in_array($image->getKey(), $selected_products) ? 1 : 0 }}">Attach</a>
            @if($model_type != 'landing-page')
              <a href="#" class="btn btn-xs {{ $selected_all ? 'btn-success' : 'btn-secondary' }} attach-photo-all" data-image="{{ ($model_type == 'purchase-replace' || $model_type == 'broadcast-images' || $model_type == 'landing-page' || $model_type == 'newsletters') ? $product->id : $image_keys }}" data-attached="{{ $selected_all ? 1 : 0 }}">Attach All</a>
            @endif
          </div>
        {{-- @endforeach --}}
      @else
        <div class="col-md-3 col-xs-6 text-center mb-5">
          <a href="{{ route('products.show', $product->id) }}" data-toggle="tooltip" data-html="true" title="{{ 'Nothing to show' }}">
            <img src="" class="img-responsive grid-image" alt="" />
            <p>Sku : {{ strlen($product->sku) > 18 ? substr($product->sku, 0, 15) . '...' : $product->sku }}</p>
            <p>Id : {{ $product->id }}</p>
            <p>Title : {{ $product->name }} </p>
          </a>
            <p>Category : 
                <select class="form-control select-multiple-cat-list update-product" data-id={{ $product->id }}>
                  @foreach($categoryArray ?? [] as $category)
                  <option value="{{ $category['id'] }}" @if($category['id'] == $product->category) selected @endif >{{ $category['value'] }}</option>
                  @endforeach
                </select>
            </p>
            <a href="{{ route('products.show', $product->id) }}" data-toggle="tooltip" data-html="true" data-placement="top" title="<strong>Supplier: </strong>{{ $product->supplier }} <strong>Status: </strong>{{ $product->purchase_status }}">

            <p>Size : {{ strlen($product->size) > 17 ? substr($product->size, 0, 14) . '...' : $product->size }}</p>
            <p>Price EUR special: {{ $product->price_eur_special }}</p>
            <p>Price INR special: {{ $product->price_inr_special }}</p>
          </a>
          <a href="#" class="btn btn-secondary attach-photo" data-image="" data-attached="0">Attach</a>
        </div>
      @endif
    @endforeach
  </div>
  <div class="row">
    <div class="col text-center">
      <button type="button" class="btn btn-image my-3" id="sendImageMessage"><img src="/images/filled-sent.png" /></button>
    </div>
  </div>
  {{ request()->request->add(["from"=>"attach-image"]) }}
  {!! $products->appends(Request::except('page'))->links() !!}
</div>

