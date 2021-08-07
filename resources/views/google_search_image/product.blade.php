@extends('layouts.app')

@section('favicon' , 'googleimagesearch.png')
@section('title', 'Google Image Search Product- ERP Sololuxury')

@section("styles")
@endsection
<style type="text/css">
    .card {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        max-width: 300px;
        margin: auto;
        text-align: center;
        font-family: arial;
    }

    .price {
        color: grey;
        font-size: 22px;
    }

    .card button {
        border: none;
        outline: 0;
        padding: 12px;
        color: white;
        background-color: #000;
        text-align: center;
        cursor: pointer;
        width: 100%;
        font-size: 18px;
    }

    .card button:hover {
        opacity: 0.7;
    }
    
    
    #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
    }
   
</style>
@section('content')
    @include('partials.flash_messages')
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div class="row" style="padding-top: 10px;">
        <?php if(!empty($product)) { ?>
        <div class="col-md-12">
            <h2 class="page-heading">Google Image Search ({{$productCount}}) </h2>
            <?php if(!empty($supplierList)) { ?>
                <div class="card col-lg-3" style="margin:auto;float:left;">
                    <h3>Supplier List</h3>
                    <?php foreach($supplierList as $list) { ?>
                        <span style="margin:5px;">
                            <a href="<?php echo route('google.search.product', ['supplier' => $list["id"]]); ?>"><?php echo $list["supplier"] ?> 
                                <span class="badge"><?php echo $list["supplier_count"] ?></span>
                            </a>
                            <?php if(request()->get("supplier") == $list["id"] && request()->get("revise",0) != 1) { ?>
                                <i class="glyphicon glyphicon-ok"></i>
                            <?php } ?>
                        </span>
                    <?php } ?>
                </div>
                
            <?php } ?>
            <div class="card col-lg-3" style="margin:auto;float:right; overflow: auto;overflow-x: hidden;-ms-overflow-x: hidden; max-height: 603px;">
                <?php if(!empty($skippedSuppliers)) { ?>
                    
                        <h3>Skipped Supplier</h3>
                        <?php foreach($skippedSuppliers as $list) { ?>
                            <span style="margin:5px;">
                                <a href="<?php echo route('google.search.product', ['supplier' => $list["id"] , "revise" => 1]); ?>"><?php echo $list["supplier"] ?> 
                                    <span class="badge"><?php echo $list["supplier_count"] ?></span>
                                </a>
                                <?php if(request()->get("supplier") == $list["id"] && request()->get("revise",0) == 1) { ?>
                                    <i class="glyphicon glyphicon-ok"></i>
                                <?php } ?>
                            </span>
                        <?php } ?>                    
                <?php } ?>
                <br>
                <div class="server-detail"></div>
            </div>    
            <div class="card col-lg-6" style="margin:auto;float:none;">
                <h1><?php echo "#" . $product->id . " " . $product->name ?></h1>
                <?php if ($product->hasMedia(config('constants.excelimporter'))) { ?>
                    <?php $media = $product->getMedia(config('constants.excelimporter'))->first() ?>
                    <?php if($media) { ?>
                        <img style="width: 300px;height: 300px;margin: auto;" class="card-img-top" src="<?php echo $media->getUrl(); ?>" alt="">
                    <?php } ?>    
                <?php } ?>    
                <p class="price">SKU : <a href="https://www.google.com/search?q=<?= $product->sku ?>" target="_blank"><?php echo $product->sku ?></a></p>
                <p class="price">Brand : <?php echo isset($product->brands->name) ? $product->brands->name : ""; ?></p>
                <p class="price">Description : <?php echo $product->short_description ?></p>
                <?php $brand = isset($product->brands->name) ? $product->brands->name : ""; ?>
                <p>
                    <div class="row">
                        <div class="col-12 mb-4">
                            <input type="text" name="search-keyword" class="form-control" id="search-keyword" value="<?php echo implode(',', array_filter([$brand, $product->name,$product->color, $product->sku])); ?>">
                        </div>
                        <div class="col-11 mb-4">
                            <select class="form-control server-select" name="server">
                                <?php 
                                    $googleServer = \App\GoogleServer::all();
                                    foreach($googleServer as $list) { 
                                ?>
                                    <option value="{{$list->key}}" data-description="{{$list->description}}">{{$list->name}}</option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-1 mb-4" style="padding-left: 0;">
                            <a href="{{route('google-server.index')}}" class="btn btn-secondary">+</a>
                        </div>
                    </div>
                </p>
                <p>
                    <button class="get-images">Get Images</button>
                    <br/>
                    <br/>
                    <button data-keyword="<?php echo $product->sku; ?>" class="get-images">Get Images (by SKU only)</button>
                </p>
            </div>
        </div>
        <div class="col-md-12" style="text-align:right;">
            <button class="attach-and-continue btn btn-lg btn-success">Attach And Continue</button>
            <button class="skip-product btn btn-lg btn-danger pull-left">Skip Product</button>
        </div>
        <form method="post" id="save-images" action="{{ route('google.search.product-save',request()->all()) }}">
            {{ csrf_field() }}
            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
            <div class="col-md-12 image-result-show">

            </div>
        </form>
    </div>
    <?php } else { ?>
       <?php echo "No products found"; ?>
 <?php } ?>

@endsection

@section('scripts')

    <script type="text/javascript">
        $('.server-select').change(function(){
            $('.server-detail').html('<b>' + $('.server-select').find(":selected").text()+' : '+ $('.server-select').find(":selected").val()+'</b><br>'+$('.server-select').find(":selected").data('description'));
        });

        $('.server-select').trigger('change');

        var productSearch = $(".get-images");
        productSearch.on("click", function () {
            var keyword = $("#search-keyword").val();
            var googleServer = "{!! env('GOOGLE_CUSTOM_SEARCH') !!}";
            var regEx = /([?&]cx)=([^#&]*)/g;
            var googleServerUrl = googleServer.replace(regEx, '$1='+$(".server-select").val());
            $.ajax({
                url: googleServerUrl+"&q=" + keyword + "&searchType=image&imgSize=large",
                beforeSend: function () {
                                $("#loading-image").show();
                },
                success: function (result) {
                    $("#loading-image").hide();
                    
                    if (result.searchInformation.totalResults != undefined && parseInt(result.searchInformation.totalResults) > 0) {
                        var i = 1;
                        
                        $(".image-result-show").html('');
                        count = 0;
                        $.each(result.items, function (k, v) {

                            var template = '<div class="col-md-3"><div class="card" style="width: 18rem;">';
                            template += '<img title="' + v.title + '" class="card-img-top" src="' + v.link + '" alt="' + v.title + '" onclick="toggleCheckbox(' + i + ');">';
                            template += '<div class="card-body">';
                            template += '<input type="checkbox" id="checkbox-' + i + '" class="selected-image" name="images[]" value="' + v.link + '">';
                            template += '</div>';
                            template += '</div></div>';

                            $(".image-result-show").append(template);
                            i++;

                            $.ajax({
                                url: "{{ route('log.google.cse') }}",
                                type: 'POST',
                                beforeSend: function () {
                                },
                                success: function (response) {
                                },
                                data: {
                                    "url": v.link,
                                    "keyword" : keyword,
                                    "response" : result.items,
                                    "count" : count,
                                    _token: "{{ csrf_token() }}",
                                }
                            });
                            count++
                        });
                    } else {
                        alert('No images found');
                    }
                }
            });
        });

        $(".attach-and-continue").on("click", function () {
            var selectedImages = $(".selected-image:checked").length;
            if (selectedImages > 0) {
                $("#save-images").submit();
            } else {
                alert("Please Select Images from list and then proceed");
            }
        });

        $(".skip-product").on("click", function () {
            $("#save-images").submit();
        });

        function toggleCheckbox(id) {
            $('#checkbox-' + id).click();
        }

    </script>


@endsection