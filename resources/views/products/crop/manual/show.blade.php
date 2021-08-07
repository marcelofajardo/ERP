@extends('layouts.app')

@section('large_content')
    <div class="row">
        <div class="col-md-12">
            <h4 class="page-heading">
                 Manual Cropping
            </h4>
        </div>
        <div class="col-md-12">
            <table class="table table-striped table-bordered" style="width: 100%">
                <tr>
                    <td>
                        {{ $product->name }}
                        <br>
                        {{ $product->sku }}
                        <br>
                        <a href="{{ action('ProductController@show', $product->id) }}" target="_new">{{ $product->id }}</a>
                        <br>
                        {{ $product->product_category->title }}
                        <br>
                        <a target="_new" class="btn btn-secondary" href="{{ action('ProductController@show', $product->id) }}">Product Details</a>
                    </td>
                    <td>
                        <p>Reject Remark : {{ $product->crop_remark ?? 'N/A' }}</p>
                        @if($product->is_image_processed)
                            <a class="btn btn-secondary btn-sm" href="{{ action('ProductCropperController@downloadImagesForProducts', [$product->id, 'cropped']) }}">Download Cropped</a>
                        @endif
                        @if($originalMediaCount)
                            <a class="btn btn-secondary btn-sm" href="{{ action('ProductCropperController@downloadImagesForProducts', [$product->id, 'original']) }}">Download Original</a>
                        @endif
                        <hr>
                        <strong>References:</strong>
                        <p>Note: If images are not available, please download images from one of these sites and then crop and save it.</p>
                        @foreach($references as $website=>$reference)
                            <li>{{$website }}: <a href="{{ $reference }}">Visit Site</a></li>
                        @endforeach
                    </td>
                    <td>
                        <strong>Dimension: {{$product->lmeasurement }} X {{ $product->hmeasurement }} X {{ $product->dmeasurement }}</strong>
                    <td>
                        <form method="post" enctype="multipart/form-data" action="{{ action('Products\ManualCroppingController@update', $product->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="images">Cropped Images</label>
                                <input type="file" multiple accept="image/*" name="images[]" id="images">
                            </div>
                            <div class="form-group text-right">
                                <button class="btn btn-secondary">Send For Approval</button>
                            </div>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Fotorama from CDNJS, 19 KB -->
    <link  href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>

    {{--    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>--}}

    @if (Session::has('mesage'))
        <script>
            toastr['success'](
                '{{Session::get('message')}}',
                'success'
            )
        </script>
    @endif
@endsection