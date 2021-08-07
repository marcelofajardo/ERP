@extends('layouts.app')

@section('favicon' , 'cropsequencer.png')
@section('title', 'Crop Sequencer List - ERP Sololuxury')



@section('large_content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Order Cropped Images ({{$total}}) <a href="{{ route('sop.index') }}?type=Sequence" class="pull-right">SOP</a></h2>
            <p style="font-size: 18px">Total Sequenced : <strong>{{ $count }}</strong></p>
            <p>Approved By: {{ $product->cropOrderer ? $product->cropOrderer->name : 'N/A' }}</p>
        </div>
        @if($product)
            <form action="{{ action('ProductCropperController@saveSequence', $product->id) }}" method="post">
                @csrf
                <div class="col-md-12">
                    <div id="sortable">
                        @foreach($product->getMedia(config('constants.media_tags')) as $media)
                            @if(stripos(strtolower($media->filename), 'cropped') !== false)
                                <div class="card" style="display: inline-block; background: #dddddd">
                                    <img class="order-selector" src="{{ $media->getUrl() }}" alt="" style="width:250px;" data-mediaId="{{$media->id}}">
                                    <input class="media_order" type="hidden" name="images[{{$media->id}}]" value="" id="order_{{$media->id}}">
                                    <span style="position: absolute; bottom: 10px; left: 10px; padding: 15px; font-size: 16px; font-weight: bold" class="label label-default sequence-tag" id="sequence_{{$media->id}}">-</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <div class="form-group">
                        <button class="btn btn-secondary">Save Sequence</button>
                        <span class="clear-sequence btn btn-default">Clear Sequence</span>
                        <a class="btn btn-default" href="{{action('ProductCropperController@skipSequence', $product->id)}}">Skip Product</a>
                    </div>
                </div>
            </form>
        @else
            <h2 class="text-center">No Images to be ordered at the moment!</h2>
        @endif
    </div>
@endsection

@section('scripts')
{{--    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">--}}
{{--    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>--}}

    <script>
        var progressCount = 0;
        $(document).ready(function() {
            $('.order-selector').click(function(event) {
                let mediaId = $(this).attr('data-mediaId');
                progressCount++;
                $('#sequence_'+mediaId).html(progressCount);
                $("#order_"+mediaId).val(progressCount);
            });

            $('.clear-sequence').click(function(event) {
                progressCount = 0;
                $('.sequence-tag').html('-');
                $('.media_order').val('');
            });
        });
    </script>
@endsection