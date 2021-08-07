@extends('layouts.app')

@section("styles")
@endsection
<style type="text/css">
  .dis-none {
    display: none;
  }
</style>
@section('content')
  @include('partials.flash_messages')

  <div class="productGrid" id="productGrid">
      <form  method="POST" action="{{route('google.details.image')}}">
        {{ csrf_field() }}
        <input id="search-product-url" type="hidden" name="url">
      </form>
      <div class="row">
        <div class="col-md-12">
          <h1 class="text-center">Visually Similar Images</h1>
        </div>  
        <div class="col-md-12">
          @foreach ($productImage as $productImagesArr)
             @foreach($productImagesArr as $images) 
                @foreach ($images["visuall_similar_images"] as $img)
                <div class="col-md-3 col-xs-6 text-center">
                    <img src="{{ $img }}" class="img-responsive grid-image" alt="" />
                    <button data-href="<?php echo $img; ?>" class="btn btn-secondary btn-img-details">
                        Get Details
                    </button>
                </div>
                @endforeach
             @endforeach
          @endforeach
        </div>
       </div> 
  </div>

@endsection

@section('scripts')
 
 <script type="text/javascript">
    
    var detailsBtn = $(".btn-img-details");
        detailsBtn.on("click", function() {
            var $this = $(this);
            $("#search-product-url").val($(this).data("href"));
            $("#search-product-url").closest("form").submit();
        });

 </script> 
  

@endsection
