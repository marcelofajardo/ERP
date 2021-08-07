@extends('layouts.app')

@section('styles')

<style type="text/css">
    #loading-image {
        position: fixed;
        top: 50%;
        left: 50%;
        margin: -50px 0px 0px -50px;
        z-index: 60;
    }

    .select2-container .select2-selection--single {
        height: 34px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 32px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 32px;
        right: 5px;
    }

    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ccc;
    }

    .select2-container .select2-selection--multiple {
        min-height: 34px;
    }

    .select2-selection select2-selection--multiple {
        padding: 0 5px;
    }

    .select2-container .select2-search--inline .select2-search__field {
        padding: 0 5px;
    }

    .btn.btn-secondary>.fa-filter {
        color: white;
    }
</style>
@endsection
@section('content')
<div id="myDiv">
    <img id="loading-image" src="/images/pre-loader.gif" style="display:none;" />
</div>
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Product translations</h2>

        <div class="row">

            <div class="col-sm-2">
                <div class="form-group">
                    <input id="term" name="term" type="text" class="form-control" value="{{ isset($term) ? $term : '' }}" placeholder="Search" id="term">
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <select class="form-control select2" name="language" id="language" data-placeholder="Select Language" required>
                        <option></option>
                        @foreach ($languages as $lang)
                        <option value="{{ $lang->locale }}" {{ $language == $lang->locale ? 'selected' : ''}}>{{ strtoupper($lang->locale) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <select class="form-control select2 " name="is_rejected" id="is_rejected" data-placeholder="Select Rejection" required>
                        <option></option>
                        <option value='1' {{ isset($language) ? 'selected' : ''}}>Rejected</option>
                        <option value='0' {{ isset($language) ? 'selected' : ''}}>Approved</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    {{-- <button type="button" class="btn btn-image" onclick="submitSearch()"><img src="/images/filter.png"/></button>--}}
                    <a class="btn btn-secondary" onclick="submitSearch()"><i class="fa fa-filter" aria-hidden="true"></i></a>
                    <a href="{{url()->current()}}" class="btn  btn-secondary" title="Clear"><i type="submit" class="fa fa-times" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2">
                        <button type="button" class="btn btn-secondary" data-target="#addLanguage" data-toggle="modal">Add Language
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.flash_messages')

<div class="table-responsive">
    <table class="table table-bordered" id="translation-table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Product Image</th>
                <th>Locale</th>
                <th>Title</th>
                <th>Description</th>
                <th>Composition</th>
                <th>Color</th>
                <th>Size</th>
                <th>Manufacturing country</th>
                <th>Dimension</th>
                <th>Site</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @include('products.translations.product-search')
        </tbody>
    </table>
    {{$product_translations->links()}}
</div>

@foreach ($product_translations as $key => $product)

<div id="product_image_{{ $product->id }}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title">Images</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">

                <div class="col-md-5">


                    <!-- {{ $product = $product->product }} -->
                    <!--                        --><?php //dd($product); 
                                                    ?>
                    <?php $gridImage = ''; ?>
                    @if ($product->hasMedia(config('constants.media_gallery_tag')))
                    @foreach($product->getMedia(config('constants.media_gallery_tag')) as $media)
                    @if(stripos($media->filename, 'crop') !== false)
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
                    {{--Get cropping grid image--}}
                    <!-- {{$gridImage = \App\Category::getCroppingGridImageByCategoryId($product->category)}} -->
                    <?php
                    if ($width == 1000 && $height == 1000) {
                    ?>
                        <div class="thumbnail-pic">
                            <div class="thumbnail-edit"><a class="delete-thumbail-img" data-product-id="{{ $product->id }}" data-media-id="{{ $media->id }}" data-media-type="gallery" href="javascript:;"><i class="fa fa-trash fa-lg"></i></a></div>
                            <span class="notify-badge {{$badge}}">{{ $width."X".$height}}</span>
                            <img style="display:block; width: 70px; height: 80px; margin-top: 5px;" src="{{ $media->getUrl() }}" class="quick-image-container img-responive" alt="" data-toggle="tooltip" data-placement="top" title="ID: {{ $product->id }}" onclick="replaceThumbnail('{{ $product->id }}','{{ $media->getUrl() }}','{{$gridImage}}')">
                        </div>
                    <?php } ?>
                    @endif
                    @endforeach
                    @endif
                </div>
                <div class="col-md-7" id="col-large-image{{ $product->id }}">
                    @if ($product->hasMedia(config('constants.media_gallery_tag')))
                    <div onclick="bigImg('{{ $product->getMedia(config('constants.media_gallery_tag'))->first()->getUrl() }}')" style=" margin-bottom: 5px; width: 300px;height: 300px; background-image: url('{{ $product->getMedia(config('constants.media_gallery_tag'))->first()->getUrl() }}'); background-size: 300px" id="image{{ $product->id }}">
                        <img style="width: 300px;" src="{{ asset('images/'.$gridImage) }}" class="quick-image-container img-responive" style="width: 100%;" alt="" data-toggle="tooltip" data-placement="top" title="ID: {{ $product->id }}" id="image-tag{{ $product->id }}">
                    </div>
                    <button onclick="cropImage('{{ $product->getMedia(config('constants.media_gallery_tag'))->first()->getUrl() }}','{{ $product->id }}')" class="btn btn-secondary">Crop Image
                    </button>
                    <button onclick="crop('{{ $product->getMedia(config('constants.media_gallery_tag'))->first()->getUrl() }}','{{ $product->id }}','{{ $gridImage }}')" class="btn btn-secondary">Crop
                    </button>

                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endforeach

<div id="translationModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" id="translated-content">
        </div>
    </div>
</div>

<div id="addLanguage" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content ">
            {{-- <form class="add_translation_language" action="{{ route('translation.language.add') }}" method="POST">--}}
            {{-- <div class="modal-header">--}}
            {{-- <h4 class="modal-title">Add Language </h4>--}}
            {{-- <button type="button" class="close" data-dismiss="modal">&times;</button>--}}
            {{-- </div>--}}
            {{-- <div class="modal-body">--}}
            {{-- <div class="row">--}}
            {{-- {{ csrf_field() }}--}}
            {{-- <label class="form-label col-sm-12">Choose Locale :</label>--}}

            {{-- @php--}}
            {{-- $added_languages = $languages->pluck('locale')->toArray();--}}
            {{-- @endphp--}}

            {{-- <div class="col-sm-12">--}}
            {{-- <select name="locale" class="select-multiple">--}}
            {{-- <option></option>--}}
            {{-- @foreach($all_languages as $lng_)--}}
            {{-- @if(!in_array($lng_->locale, $added_languages))--}}
            {{-- <option value="{{ $lng_->locale }}">{{ strtoupper($lng_->locale) }}</option>--}}
            {{-- @endif--}}
            {{-- @endforeach--}}
            {{-- </select>--}}
            {{-- </div>--}}
            {{-- </div>--}}
            {{-- <div class="modal-footer">--}}
            {{-- <button type="submit" class="btn btn-secondary saveLanguage">Add</button>--}}
            {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
            {{-- </div>--}}
            {{-- </div>--}}
            {{-- </form>--}}
            <form class="add_translation_language" action="{{ route('translation.language.add') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h4 class="modal-title">Store a language</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <strong>Locale:</strong>
                        <input type="text" name="locale" class="form-control" value="{{ old('locale') }}">

                        @if ($errors->has('locale'))
                        <div class="alert alert-danger">{{$errors->first('locale')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Magento Code:</strong>
                        <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>

                        @if ($errors->has('code'))
                        <div class="alert alert-danger">{{$errors->first('code')}}</div>
                        @endif
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary">Store</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach ($product_translation_history as $key => $product)
<div id="showHistory_{{$product->product_translation_id}}" class="modal fade " role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title">Product Translation History </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="translation-table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Locale</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- {{ $product_translation_history2 = \App\ProductTranslationHistory::where('product_translation_id',$product->product_translation_id)->get() }}-->
                                @foreach($product_translation_history2 as $key => $product)
                                @if($product->is_rejected == 1)
                                <tr>
                                    <td>{{$product->id}} </td>
                                    <td style="text-align: center;vertical-align : middle;" colspan="3">
                                        Rejected
                                    </td>
                                    <td>{{$product->user->name}} </td>
                                </tr>
                                @else
                                <tr>
                                    <td>{{$product->id}} </td>
                                    <td>{{$product->locale}}</td>
                                    <td>{{$product->title}}</td>
                                    <td>{{$product->description}}</td>
                                    <td>{{$product->user->name}}</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection


@section('scripts')
<script>
    function submitSearch() {
        src = '/products/product-translation';
        var term = $('#term').val();
        var language = $('#language').val();
        var is_rejected = $('#is_rejected').val();
        $.ajax({
            url: src,
            dataType: "json",
            data: {
                term: term,
                language: language,
                is_rejected: is_rejected
            },
            beforeSend: function() {
                $("#loading-image").show();
            },

        }).done(function(data) {
            $("#loading-image").hide();
            $("#translation-table tbody").empty().html(data.tbody);
            if (data.links.length > 10) {
                $('ul.pagination').replaceWith(data.links);
            } else {
                $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
            }

        }).fail(function(jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });

    }

    $(document).ready(function() {
        $(document).on('click', '.view-btn', function(e) {
            var id = $(this).attr("data-id");
            $.ajax({
                url: '/products/product-translation/' + id,
                type: 'GET',
                success: function(response) {
                    $("#translated-content").html(response);
                }
            })
        });

        $(document).on('click', '.edit-translation', function(e) {

            var locale = $('#select-locale').val();
            var site_id = $('#site_id').val();
            var product_id = $('#product_id').val();
            var product_translation_id = $('#product_translation_id').val();
            var title = $('#title').val();
            var description = $('#description').val();

            $.ajax({
                url: '/products/product-translation/submit/' + product_translation_id,
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    language: locale,
                    site_id: site_id,
                    title: title,
                    description: description,
                    product_id: product_id
                },
                success: function(response) {
                    alert(response.message);
                    $('#translationModal').modal('hide');
                }
            })
        });

        $('.add_translation_language').submit(function(e) {

            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: new FormData($(this)[0]),
                processData: false,
                contentType: false,
                success: function(response) {
                    location.reload();
                }
            });

            return false;

        });
    });

    // $(document).on('change', 'select', function (e) {
    //     var locale = $('#select-locale').val();
    //     var id = $('#product_id').val();
    //     $.ajax({
    //         url: '/products/product-translation/details/' + id + '/' + locale,
    //         type: 'GET',
    //         success: function (response) {
    //             if (response.product_translation) {
    //                 $('#title').val(response.product_translation.title);
    //                 $('#description').val(response.product_translation.description);
    //                 $('#product_translation_id').val(response.product_translation.id);
    //             } else {
    //                 $('#title').val('');
    //                 $('#description').val('');
    //             }
    //         }
    //     })
    // });

    $('.rejectProduct').click(function() {
        var id = $(this).attr('data-id');
        var value = $(this).attr('data-value');
        var thiss = $(this);
        $.ajax({
            url: '/productTranslation/reject',
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                product_translation_id: id,
                value: value,
            },
            success: function(response) {
                $('.rejectProduct').remove();
                alert(response.message);
                // if (response.value == 1) {
                //     // thiss.removeClass('fa fa-close');
                //     // thiss.addClass('fa fa-check');
                // }
                $("#loading-image").hide();
                // location.reload();
            }
        });
    });

    $('#is_rejected').select2({
        minimumResultsForSearch: -1
    });

    $('#language').select2({
        minimumResultsForSearch: -1
    });
    $(document).on('click', '.readmore', function() {
        $(this).parent('.lesstext').hide();
        $(this).parent('.lesstext').next('.alltext').show();
    });
    $(document).on('click', '.readless', function() {
        $(this).parent('.alltext').hide();
        $(this).parent('.alltext').prev('.lesstext').show();
    });
</script>
@endsection