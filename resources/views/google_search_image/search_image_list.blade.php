@extends('layouts.app')
@section('favicon', 'task.png')

@section('title', $title)

@section('content')
    <style type="text/css">
        .preview-category input.form-control {
            width: auto;
        }

        .no-mr {
            margin: 0px;
        }

        .pd-3 {
            padding: 0px;
        }

    </style>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">{{ $title }} <span class="count-text"></span></h2>
        </div>
        <br>
        @include("partials.flash_messages")
        <div class="col-lg-12 margin-tb">
            <div>
                <table class="table table-bordered" style="table-layout:fixed;" style="width: 100%">
                    <tr>
                        <th style="width:5%;">Sr No</th>
                        <th style="width:10%;">Product Id</th>
                        <th style="width:35%;">Product Name</th>
                        <th style="width:20%;">Search Image</th>
                        <th style="width:30%;">Action</th>
                    </tr>
                    @foreach ($image_search as $key => $img_src)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $img_src->product_id }}</td>
                            <td>{{ $img_src->product_name }}</td>
                            <td><img src="{{ asset('uploads/'.$img_src->crop_image) }}" style="cursor: default; height: 100px;"></td>
                            <td>
                                <div class="d-flex">
                                    <button title="Open Images" type="button" class="btn preview-attached-img-btn btn-image no-pd" data-id="{{ $img_src->id }}" data-images="{{ $img_src->googleSearchRelatedImages }}">
                                        <img src="/images/forward.png" style="cursor: default;">
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="expand-{{ $img_src->id }} hidden">
                            <td colspan="7" id="attach-image-list-{{ $img_src->id }}">

                            </td>
                        </tr>
                    @endforeach
                    {{ $image_search->links() }}

                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).on('click', '.preview-attached-img-btn', function(e) {
            e.preventDefault();
            var searchImageId = $(this).data('id');
            $('#attach-image-list-' + searchImageId).html('');

            if (searchImageId == '' && searchImageId != '0') {
                alert('No webiste select');
                return false;
            }
            const images__ = $(this).data('images')
            let html = ""
            images__.forEach((element, index) => {
                html += '<div class="col-md-2 col-xs-4 text-center product-list-card mb-4 single-image-' +
                    element.id + '" style="padding:0px 5px;margin-bottom:2px !important;">' +
                    '<div style="border: 1px solid #bfc0bf;padding:0px 5px;">' +
                    '<div data-interval="false" id="carousel_{{ request('searchImageId') }}" class="carousel slide" data-ride="carousel">' +
                    '<div class="carousel-inner maincarousel">' +
                    '<div class="item" style="display: block;"> <a href="' + element.image_url +'" target="_blank"><img src="' + element.google_image +'" style="height: 150px; width: 150px;display: block;margin-left: auto;margin-right: auto;"> </a></div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="row pl-4 pr-4" style="padding: 0px; margin-bottom: 8px;">' +
                    '<div class="custom-control custom-checkbox">' +
                    '<input type="checkbox" class="custom-control-input select-pr-list-chk"  id="defaultUnchecked_' + element.id + '" >' +
                    // '<label class="custom-control-label" for="defaultUnchecked_' + element.id +'"></label>' +
                    '</div>' +
                    // '<a data-media="' + index + '" download="" data-image_url="' + element.google_image +'" class="btn btn-md select_row attach-photo download-attach-photo"><i class="fa fa-download"></i></a>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
            });
            $('#attach-image-list-' + searchImageId).append(html);

            var expand = $('.expand-' + searchImageId);
            $(expand).toggleClass('hidden');

        });
    </script>
@endsection
