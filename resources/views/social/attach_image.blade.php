@extends('layouts.app')

@section('content')
    @if(Session::has('message'))
        <div class="row mt-5">
            <div class="col-md-6 col-md-offset-3">
                <div class="alert alert-info">
                    {{ Session::get('message') }}
                </div>
            </div>
        </div>
    @endif
    <div class="mt-5">
        <h1><u>Add Images</u> (<a href="{{ action('SocialController@showSchedule', $id) }}">Back To Schedule</a>)</h1>
        <form action="{{ action('SocialController@attachMedia', $id) }}" method="post">
            @csrf
            <div class="row">
                <h3 class="col-md-12">Previously Selected Images <input class="pull-right btn btn-lg btn-success" type="submit" name="save" value="Add Selected Images For Schedule"></h3>
                @if (count($selectedImages))
                    @foreach($selectedImages as $product)
                        <div class="col-md-3" style="border-radius: 5px; border: 1px solid #EEEEEE; cursor: pointer">
                            <label for="product_{{$product->id}}">
                                <img src="{{ '/uploads/social-media/' . $product->filename }}" class="img-responsive">
                                <div style="text-align: center">
                                    <input checked type="checkbox" value="{{$product->id}}" name="images[]" id="product_{{$product->id}}">
                                </div>
                            </label>
                        </div>
                    @endforeach
                @else
                    <h3 class="col-md-12 text-center m-5">No Images selected yet</h3>
                @endif
            </div>
            <div class="row">
                <h3 class="col-md-12 mb-5 row">Select More Images</h3>
                @foreach($images as $product)
                    <div class="col-md-3" style="border-radius: 5px; border: 1px solid #EEEEEE; cursor: pointer">
                        <label for="product_{{$product->id}}">
                            <img src="{{ '/uploads/social-media/' . $product->filename }}" class="img-responsive">
                            <div style="text-align: center">
                                <input type="checkbox" value="{{$product->id}}" name="images[]" id="product_{{$product->id}}">
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
        </form>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/media-card.css') }}">
@endsection

@section('scripts')
    <script>
        $(document).on('click', 'a.page-link', function(event) {
            event.preventDefault();
            let link = $(this).attr('href');
            link = link.split('page=')[1];
            $('#page').val(link);
            $('form').submit();
        })
    </script>
@endsection