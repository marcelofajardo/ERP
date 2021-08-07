@extends('layouts.app')

@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <style type="text/css">
        .select-multiple-cat-list .select2-container {
            position: relative;
            z-index: 2;
            float: left;
            width: 100%;
            margin-bottom: 0;
            display: table;
            table-layout: fixed;
        }
        /*.update-product + .select2-container--default{
            width: 60% !important;
        }*/
        .no-pd {
            padding:0px;
        }

        .select-multiple-cat-list + .select2-container {
            width:100% !important;
        }

        #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
            z-index: 60;
        }

        .row .btn-group .btn {
            margin: 0px;
        }
        .btn-group-actions{
            text-align: right;
        }

        .multiselect-supplier + .select2-container{
            width: 198px !important;
        }
        .size-input{
            width: 155px !important;
        }
        .quick-sell-multiple{
            width: 98px !important;
        }
        .image-filter-btn{
            padding: 10px;
            margin-top: -12px;
        }
        .update-product + .select2-container{
            width: 150px !important;
        }
        .product-list-card > .btn, .btn-sm {
            padding: 5px;
        }

        .select2-container {
            width:100% !important;
            min-width:200px !important;
        }
        .no-pd {
            padding:3px;
        }
        .mr-3 {
            margin:3px;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
@endsection

@section('content')
<!-- START - Purpose : Add scroll Interval - DEVTASK-4271 -->
<br/>
<div style="position:fixed;z-index:1"><button class="btn btn-secondary hide start-again" onclick="callinterval();" disabled>Start Scroll</button>
<button class="btn btn-secondary stopfunc hide pause" id="clearInt">Stop Scroll</button></div>
<div class="row">
    <div class="col-sm-4">  
        <div class="form-group">
            <input type="text" class="form-control" id="scrolltime" placeholder="scroll interval in second"/>
        </div>
    </div>
    <div class="col-sm-1">  
        <div class="form-group">
        <input type="button" onclick="callinterval()" class="btn btn-secondary" value="Start"/>
        </div>
    </div>
</div>
<!-- END - DEVTASK-4271 -->

<!-- Purpose : Add class infinite-scroll - DEVTASK-4271 -->
<div class="infinite-scroll customer-count infinite-scroll-data customer-list-{{$website_id}} customer-{{$website_id}}" style="padding: 0px 10px;">
        @php
            $oldDate = null;
            $count   = 0;
            /*$images = null;
            // dd( $list->store_website_id );
            $webStore = \App\WebsiteStore::where('website_id',$list->id)->first();
            if( $webStore ){
                $website_store_views = \App\WebsiteStoreView::where('website_store_id',$webStore->id)->first();
                if( $website_store_views ){
                    $images = \App\scraperImags::where('store_website',$list->store_website_id)->where('website_id',$website_store_views->code)->get()->toArray();
                }
            }*/

            // dump($list);
            // dump($webStore);
            // dump($website_store_views);
            // dd($images);

        @endphp
        @foreach($images as $image)
                {{-- @foreach($imageM->scrapperImage->toArray() as $image) --}}

                <?php
                    if ( date( 'Y-m-d' ,strtotime($image['created_at'])) !== $oldDate ) { 
                        $count = 0; 
                        $oldDate = date( 'Y-m-d' ,strtotime($image['created_at']));
                    ?>
                        <div class="row">
                            <div class="col-md-12">
                                <br>
                                <h5 class="product-attach-date" style="margin: 5px 0px;">{{$image['created_at']}} || Number Of Images:{{count($images)}}</h5> 

                                <hr style="margin: 5px 0px;">
                            </div>
                        </div> 

                    <?php } ?>
                    
                @if ($image['img_name'] )
                    @php
                    if($count == 6){
                        $count = 0;
                    }
                    @endphp
                        {{-- START - Purpose : Comment Code - DEVTASK-4271 --}}
                        {{--  @if($count == 0)
                              <div class="row parent-row">
                        @endif --}}
                        {{-- END - DEVTASK-4271 --}}

                        <div class="col-md-12 col-xs-12 text-center product-list-card mb-4 " style="padding:0px 5px;margin-bottom:2px !important;">
                            <div style="border: 1px solid #bfc0bf;padding:0px 5px;">
                                <div data-interval="false" id="carousel_{{ $image['id'] }}" class="carousel slide" data-ride="carousel">
                                    <a href="#" data-toggle="tooltip" data-html="true" data-placement="top" >
                                        <div class="carousel-inner maincarousel">
                                            <div class="item" style="display: block;"> <a data-fancybox="gallery" href="{{ urldecode(asset( 'scrappersImages/'.$image['img_name']))}}" ><img src="{{ urldecode(asset( 'scrappersImages/'.$image['img_name']))}}" style="height: 100%; width: 80%; max-width:1200px; display: block;margin-left: auto;margin-right: auto;"> </a> </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="row pl-4 pr-4" style="padding: 0px; margin-bottom: 8px;">

                                </div>
                            </div>
                        </div>

                    {{-- START - Purpose : Comment Code - DEVTASK-4271 --}}
                    {{-- @php
                    if( $count == 0 || $count == 6){
                        echo '</div>';
                    }
                    @endphp--}}
                    {{-- END - DEVTASK-4271 --}}

                @endif
            @php $count++;  @endphp
        {{-- @endforeach --}}
        @endforeach
        <br>
</div>
<!-- START - Purpose : Add scroll Interval - DEVTASK-4271 -->
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script>
$(document).ready(function() { 
    $("#scrolltime").val('2');
    callinterval();
 });

    var i=1;
    var scroll = true;
        var old_product;
        function start_scroll_down() { 
            var img_count = $(".infinite-scroll-data img").length;
            if(scroll && i < img_count){
                if(i == 1)
                {
                    $("html, body").animate({
                    scrollTop: $(".infinite-scroll-data div").eq(1).offset().top
                    }, 300).delay(300); 
                    i+=1;
                }else{
                    $("html, body").animate({
                    scrollTop: $(".infinite-scroll-data div img").eq(i).offset().top
                    }, 500).delay(500); 
                    i+=1;
                }
                
            }else{
                console.log("no scroll")
            }
        }

    var stop;
    function callinterval(){
        
        if($("#scrolltime").val() == ""){
            toastr["error"]("please add time interval for scroll");
            return;
        }
            
        $(".start-again").removeClass("hide")
        $(".pause").removeClass("hide")

        $(".start-again").attr("disabled","disabled")
        $(".pause").attr("disabled",false)

        if(i == 1)
        {
            $("html, body").animate({
            scrollTop: $(".infinite-scroll-data div").eq(i).offset().top
            }, 300).delay(300); 
            i+=1;
        }else{
            $("html, body").animate({
            scrollTop: $(".infinite-scroll-data div img").eq(i).offset().top
            }, 500).delay(500); 
            i+=1;
        }
        
        stop = setInterval(function(){ console.log("Running");start_scroll_down() }, $("#scrolltime").val()*1000);
    }

    
    $('#clearInt').click(function(){ 
        $(".start-again").attr("disabled",false)
        $(".pause").attr("disabled","disabled")
        clearInterval(stop);
        console.log("Stopped");
    });
</script>
<!-- START - Purpose : Add scroll Interval - DEVTASK-4271 -->
@endsection