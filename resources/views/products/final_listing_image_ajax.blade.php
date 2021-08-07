@php $imageCropperRole = Auth::user()->hasRole('ImageCropers'); @endphp
<table class="table table-bordered table-striped" style="table-layout:fixed;">
        @foreach ($products as $key => $product)
        @php 
            $anyCropExist = \App\SiteCroppedImages::where('product_id', $product->id)->pluck('website_id')->toArray();
            $websiteList = $product->getWebsites();
            $gridImage = \App\Category::getCroppingGridImageByCategoryId($product->category);
        @endphp
        <thead productid="{{ $product->id }}">
            <tr>
                <th>#{{ $product->id }} [{{$product->sku}}] {{$product->name}}
                <div class="row">
                        <div class="col-md-12">
                            <button type="button" value="reject" id="reject-all-cropping{{$product->id}}" data-product_id="{{$product->id}}" class="btn btn-xs btn-secondary pull-right reject-all-cropping">
                                @if($anyCropExist)
                                    Reject All - Re Crop
                                @else 
                                    All Rejected - Re Crop
                                @endif
                            </button>
                         </div>   
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    
                    <div class="row"> 
                            @if(!$websiteList->isEmpty())
                                @foreach($websiteList as $index => $site)
                                    <div class="col-md-12" productid="{{ $product->id }}">
                                            <h5 style="text-decoration: underline; width: 100%;">{{ $site->title }} {{ $site->id }}</h5>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="button" value="reject" id="reject-product-cropping{{$site->id}}{{$product->id}}" data-product_id="{{$product->id}}" data-site_id="{{$site->id}}" class="btn btn-xs btn-secondary pull-right reject-product-cropping">
                                                        @if($anyCropExist)
                                                            Reject All - Re Crop for this website
                                                        @else 
                                                            All Rejected - Re Crop for this website
                                                        @endif
                                                    </button>
                                                </div>   
                                            </div>
                                            @php
                                                $tag        = 'gallery_'.$site->cropper_color;
                                                $testing    = false;
                                            @endphp
                                            @if ($product->hasMedia($tag))
                                                @foreach($product->getMedia($tag) as $media)
                                                    @if(strpos($media->filename, 'CROP') !== false || $testing == 1)
                                                        <?php
                                                            $width = 0;
                                                            $height = 0;
                                                            if (file_exists($media->getAbsolutePath())) {
                                                                list($width, $height) = getimagesize($media->getAbsolutePath());
                                                                $badge = "notify-red-badge";
                                                                if ($width == 1000 && $height == 1000) {
                                                                    $badge = "notify-green-badge";
                                                                }
                                                            } else {
                                                                $badge = "notify-red-badge";
                                                            }
                                                        ?>    
                                                        <div class="col-md-2 col-xs-4 text-center product-list-card mb-4 single-image-{{ $product->id }}_{{ $media->id }}" style="padding:0px 5px;margin-bottom:2px !important;">
                                                          <div style="border: 1px solid #bfc0bf;padding:0px 5px;">
                                                             <div data-interval="false" id="carousel_{{ $product->id }}_{{ $media->id }}" class="carousel slide" data-ride="carousel">
                                                                   <div class="carousel-inner maincarousel">
                                                                      <div class="item" style="display: block;"> 
                                                                        <span class="notify-badge {{$badge}}">{{ $width."X".$height}}</span>
                                                                        <img src="<?php echo $media->getUrl(); ?>" style="height: 150px; width: 150px;display: block;margin-left: auto;margin-right: auto;"> 
                                                                       </div>
                                                                   </div>
                                                             </div>
                                                             <div class="row pl-4 pr-4" style="padding: 0px; margin-bottom: 8px;">
                                                                <a href="javascript:;" title="Remove" class="btn btn-sm delete-thumbail-img"
                                                                    data-product-id="{{ $product->id }}"
                                                                    data-media-id="{{ $media->id }}">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </a>
                                                                @php $gridSrc = asset('images/'.$gridImage); @endphp
                                                                <a onclick="shortCrop('{{ $media->getUrl() }}','{{ $product->id }}','{{ $site->id }}','{{ $gridSrc }}')" 
                                                                    class="btn btn-sm">
                                                                    <i class="fa fa-crop" aria-hidden="true"></i>
                                                                </a>
                                                             </div>
                                                          </div>
                                                       </div>
                                                   @endif
                                                @endforeach

                                            @else
                                                <span>There is no images for {{ $site->title }}</span>
                                            @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="col-md-12">Product is not assigned to any store</div>
                            @endif
                    </div>
                </td>
            </tr>
        </tbody>    
        @endforeach
    </tbody>
</table>
<p class="mb-5">&nbsp;</p>
<?php echo $products->appends(request()->except("page"))->links(); ?>