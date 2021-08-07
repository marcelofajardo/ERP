<html>
<head>
  <title>Sololuxury Products</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <style>
    body {
      background: #e2e1e0;
    }

    .card {
      background: #fff;
      border-radius: 2px;
      margin: 1rem;
      position: relative;
      padding: 1rem;
    }

    .card-1 {
      box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
      transition: all 0.3s cubic-bezier(.25,.8,.25,1);
    }

    .card-1:hover {
      box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
    }


    .same-color {
      color: #949494;
    }

    .pagination {
      display: inline-flex !important;
    }

    .slider .tooltip.in {
      opacity: 1;
    }

    .slider .tooltip.top .tooltip-arrow {
      bottom: 0;
      left: 50%;
      margin-left: -5px;
      border-width: 5px 5px 0;
      border-top-color: #000;
    }

    .slider .tooltip-arrow {
      position: absolute;
      width: 0;
      height: 0;
      border-color: transparent;
      border-style: solid;
    }

    .slider .tooltip.top {
      padding: 5px 0;
    }

  </style>
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h1 class="text-center mt-4 p-4">
        <img src="https://sololuxury.co.in/skin/frontend/bewear/default/images/logo_solo.png" alt="https://sololuxury.co.in/skin/frontend/bewear/default/images/logo_solo.png">
      </h1>
    </div>
    <div>
      <form action="{{ action('ProductController@affiliateProducts') }}">
        <div class="row">
          <div class="col-md-2">
            <input placeholder="sku, name.." type="text" name="sku" id="sku" class="form-control form-control-sm" value="{{Request::get('sku')}}">
          </div>
          <div class="col-md-3">
            <select style="width: 100%" name="brand" id="brand" class="select2" data-placeholder="Select Brand..">
              <option value="">Select brand...</option>
              @foreach($brands as $brand_)
                <option {{ $brand_->id==$brand ? 'selected' : '' }} value="{{ $brand_->id }}">{{ $brand_->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <select class="form-control form-control-sm" name="category[]">
              @foreach ($category_array as $data)
                <option value="{{ $data['id'] }}" {{ in_array($data['id'], $selected_categories) ? 'selected' : '' }}>{{ $data['title'] }}</option>
                @if ($data['title'] == 'Men')
                  @php
                    $color = "#D6EAF8";
                  @endphp
                @elseif ($data['title'] == 'Women')
                  @php
                    $color = "#FADBD8";
                  @endphp
                @else
                  @php
                    $color = "";
                  @endphp
                @endif

                @foreach ($data['child'] as $children)
                  <option style="background-color: {{ $color }};" value="{{ $children['id'] }}" {{ in_array($children['id'], $selected_categories) ? 'selected' : '' }}>&nbsp;&nbsp;{{ $children['title'] }}</option>

                  @foreach ($children['child'] as $child)
                    <option style="background-color: {{ $color }};" value="{{ $child['id'] }}" {{ in_array($child['id'], $selected_categories) ? 'selected' : '' }}>&nbsp;&nbsp;&nbsp;&nbsp;{{ $child['title'] }}</option>
                  @endforeach
                @endforeach
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <select class="form-control select2" name="color[]" multiple data-placeholder="Color..">
                <optgroup label="Colors">
                  @foreach ($colors as $key => $col)
                    <option value="{{ $key }}" {{ in_array($key, $c) ? 'selected' : '' }}>{{ $col }}</option>
                  @endforeach
                </optgroup>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <input type="text" name="price" data-provide="slider" data-slider-min="0" data-slider-max="400000" data-slider-step="1000" data-slider-value="[{{ isset($price) ? $price[0] : '0' }},{{ isset($price) ? $price[1] : '400000' }}]"/>
            </div>
          </div>
          <div class="col-md-2">
            <button class="btn btn-sm btn-secondary">Filter</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center mt-2">
      {!! $products->appends($request->except('page'))->links() !!}
    </div>
    @foreach($products as $product)
      <div class="col-md-12 card card-1" >
        <div class="row">
          <div class="col-md-1">
            @if ($product->hasMedia(config('constants.media_tags')))
              @foreach($product->getMedia(config('constants.media_tags')) as $media)
                <a href="{{ $media->getUrl() }}">
                  <img style="display:block; width: 70px; height: 80px; margin-top: 5px;" src="{{ $media->getUrl() }}" class="quick-image-container img-responive" alt="" data-toggle="tooltip" data-placement="top" title="ID: {{ $product->id }}">
                </a>
              @endforeach
            @endif
          </div>
          <div class="col-md-4">
            @if ($product->hasMedia(config('constants.media_tags')))
              <a href="{{ $product->getMedia(config('constants.media_tags'))->first()->getUrl() }}">
                <img src="{{ $product->getMedia(config('constants.media_tags'))->first()->getUrl() }}" class="quick-image-container img-responive" style="width: 100%;" alt="" data-toggle="tooltip" data-placement="top" title="ID: {{ $product->id }}">
              </a>
            @endif
          </div>
          <div class="col-md-7">
            <strong class="same-color">{{ $product->brands ? $product->brands->name : 'N/A' }}</strong>
            <p class="same-color">{{ $product->name }}</p>
            <br>
            <p class="same-color" style="font-size: 18px;">
              <span style="text-decoration: line-through">Rs. {{ number_format($product->price_inr) }}</span> Rs. {{ number_format($product->price_inr_special) }}
            </p>
            <br>
            <p>
              <strong class="same-color" style="text-decoration: underline">Description</strong>
              <br>
              <span class="same-color">
                {{ $product->short_description }}
              </span>
            </p>
            <p>
              <strong class="same-color" style="text-decoration: underline">Composition</strong>
              <br>
              <span class="same-color">
                {{ $product->composition }}
              </span>
            </p>
            <p class="same-color">
              <strong>Color</strong>: {{ $product->color }}<br>
              <strong>Sizes</strong>: {{ $product->size }}<br>
              <strong>Dimension</strong>: {{ $product->lmeasurement ?? 'N/A' }} x {{ $product->hmeasurement ?? 'N/A' }} x {{ $product->dmeasurement ?? 'N/A' }}<br>
            </p>
{{--            <p class="same-color">--}}
{{--              View All: <strong>{{ $product->product_category->title }}</strong>--}}
{{--              <br>--}}
{{--              View All: <strong>{{ $product->brands ? $product->brands->name : 'N/A' }}</strong>--}}
{{--            </p>--}}
            <p class="same-color">
              <strong>Style ID</strong>: {{ $product->sku }}
            </p>
          </div>
        </div>
      </div>
    @endforeach
    <div class="col-md-12 text-center mt-3 mb-4">
      {!! $products->appends($request->except('page'))->links() !!}
    </div>
  </div>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.1/css/bootstrap-slider.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.1/bootstrap-slider.min.js"></script>

<script>
  $(document).ready(function(event) {
    $('.select2').select2();
  });
</script>
</html>