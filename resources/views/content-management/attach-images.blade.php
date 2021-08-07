<div class="customer-count customer-list- customer-{{ request('websiteId') }}" style="padding: 0px 10px;">
            @php
                $count = 0;
            @endphp
            @php
                $left = count($media->toArray());
            @endphp
        @foreach($media as $list)
        @if(sizeof($list->toArray()) > 0)
            
            @php
                $left--;
            @endphp
                @php
                    if($count == 6){
                        $count = 0;
                    }
                @endphp
                @if($count == 0)
                    <div class="row parent-row">
                @endif
                <div class="col-md-2 col-xs-4 text-center product-list-card mb-4 single-image-{{$list->id ?? 0}}" style="padding:0px 5px;margin-bottom:2px !important;">
                    <div style="border: 1px solid #bfc0bf;padding:0px 5px;">
                        <div data-interval="false" id="carousel_{{ request('websiteId') }}" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner maincarousel">
                                <div class="item" style="display: block;"> <img src="{{ urldecode($list->getUrl())}}" style="height: 150px; width: 150px;display: block;margin-left: auto;margin-right: auto;"> </div>
                            </div>
                        </div>
                        <div class="row pl-4 pr-4" style="padding: 0px; margin-bottom: 8px;">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input select-pr-list-chk"  id="defaultUnchecked_{{$list->id ?? 0}}" >
                                <label class="custom-control-label" for="defaultUnchecked_{{$list->id ?? 0}}"></label>
                            </div>
                            <a href="{{ $list->getUrl() }}" download="" data-media="{{ $list->getKey() }}" class="btn btn-md select_row attach-photo"><i class="fa fa-download"></i></a>
                        </div>
                    </div>
                </div>
                @php
                  $count++;
                  if($left == 0) {
                    $count = 0;
                  }
                 if($count == 6 || $left == 0){
                   echo '</div>';
                 }
                @endphp
        @endif
        @endforeach
        <br>
        </div>