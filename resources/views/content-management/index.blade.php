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
            <div class="row">
                <div class="col col-md-10">
                    <div class="h" style="margin-bottom:10px;">
                        <form class="form-inline message-search-handler" method="post">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="keyword">Keyword:</label>
                                        <?php echo Form::text('keyword', request('keyword'), ['class' =>
                                        'form-control', 'placeholder' => 'Enter keyword']); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="button">&nbsp;</label>
                                        <button style="display: inline-block;width: 10%"
                                            class="btn btn-sm btn-image btn-search-action">
                                            <img src="/images/search.png" style="cursor: default;">
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col col-md-1">
                    <button style="display: inline-block;width: 10%" class="btn btn-sm btn-image add-social-account">
                        <img src="/images/add.png" style="cursor: default;">
                    </button>
                </div>
                <div class="col col-md-1">
                    <button class="btn btn-sm btn-secondary">
                        <a href="/content-management/contents" style="color:white;">View Contents</a>
                    </button>
                </div>
            </div>
            <div>
                <table class="table table-bordered" style="table-layout:fixed;">
                    <tr>
                        <th style="width:5%;">Sl no</th>
                        <th style="width:20%;">Site name</th>
                        <th style="width:35%;">Facebook</th>
                        <th style="width:30%;">Instagram</th>
                        <th style="width:10%;">Action</th>
                    </tr>
                    @foreach ($websites as $key => $website)
                        <tr>
                            <td>{{ ++$key }} </td>
                            <td>{{ $website->title }} </td>
                            <td>
                                @if ($website->facebookAccount)
                                    <p class="no-mr" style="word-break: break-all;">User :
                                        {{ $website->facebookAccount->username }} | Pass :
                                        {{ str_limit($website->facebookAccount->password, $limit = 10, $end = '...') }}
                                    </p>
                                @endif
                            </td>
                            <td>
                                @if ($website->instagramAccount)
                                    <p class="no-mr" style="word-break: break-all;">User :
                                        {{ $website->instagramAccount->username }} | Pass :
                                        {{ str_limit($website->instagramAccount->password, $limit = 10, $end = '...') }}
                                    </p>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex">
                                    <button title="Open Images" type="button"
                                        class="btn preview-attached-img-btn btn-image no-pd" data-id="{{ $website->id }}">
                                        <img src="/images/forward.png" style="cursor: default;">
                                    </button>
                                    <button title="Post instagram" type="button"
                                        class="btn post-to-instagram btn-image no-pd" data-id="{{ $website->id }}">
                                        <img src="/images/instagram.svg" style="cursor: default;">
                                    </button>
                                    <button type="button" class="btn pd-3">
                                        <a href="/content-management/manage/{{ $website->id }}">
                                            <img width="15px" title="Manage Contents" src="/images/project.png">
                                        </a>
                                    </button>
                                    <button type="button" class="btn preview-img-btn pd-3" data-id="{{ $website->id }}">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>
                        <tr class="expand-{{ $website->id }} hidden">
                            <td colspan="7" id="attach-image-list-{{ $website->id }}">

                            </td>
                        </tr>
                    @endforeach
                    @foreach ($gmail_data as $gmailData)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $gmailData->domain }}</td>
                            <td>{{ $gmailData->facebook }}</td>
                            <td>{{ $gmailData->instagram }}</td>
                            <td>
                                <div class="d-flex">
                                    <button title="Open Images" type="button"
                                        class="btn preview-attached-img-btn-gmail btn-image no-pd"
                                        data-id="{{ $gmailData->id }}" data-images="{{ $gmailData->gmailDataMedia }}">
                                        <img src="/images/forward.png" style="cursor: default;">
                                    </button>
                                    <button title="Post instagram" type="button" class="btn btn-image no-pd"
                                        data-id="{{ $gmailData->id }}">
                                        <img src="/images/instagram.svg" style="cursor: default;">
                                    </button>
                                    <button type="button" class="btn pd-3">
                                        {{-- <a href="/content-management/manage/{{$gmailData->id}}"> --}}
                                        <img width="15px" title="Manage Contents" src="/images/project.png">
                                        {{-- </a> --}}
                                    </button>
                                    <button type="button" class="btn preview-img-btn pd-3" data-id="{{ $gmailData->id }}">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>
                        <tr class="expand-gmail-{{ $gmailData->id }} hidden">
                            <td colspan="7" id="attach-gamil-image-list-{{ $gmailData->id }}">

                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
              50% 50% no-repeat;display:none;">
    </div>
    <div class="common-modal modal" role="dialog">
        <div class="modal-dialog" role="document">
        </div>
    </div>

    <div id="accountCreateModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" id="add-social-account-content">

            </div>

        </div>
    </div>


    <div id="preview-website-image" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl no</th>
                                    <th>Image</th>
                                </tr>
                            </thead>
                            <tbody class="website-image-list-view">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <form method="post" class="d-none" id="PostImgInstaMedia" action="{{ route('instagram.post.images') }}">
        @csrf
        <div class="row">
            <input type="hidden" name="media_ids" id="media_ids">
        </div>
    </form>
    <script type="text/javascript">
        $(document).on("click", ".post-to-instagram", function(event) {

            var website = $(this).data("id");
            $("#forward_suggestedproductid").val(website);
            /* alert(suggestedproductid); 
            return false; */
            var cus_cls = ".customer-" + website;
            var total = $(cus_cls).find(".select-pr-list-chk").length;
            image_array = [];
            for (i = 0; i < total; i++) {
                var customer_cls = ".customer-" + website + " .select-pr-list-chk";
                var $input = $(customer_cls).eq(i);
                var productCard = $input.parent().parent().find(".attach-photo");
                if (productCard.length > 0) {
                    var image = productCard.data("media");
                    if ($input.is(":checked") === true) {
                        image_array.push(image);
                        image_array = unique(image_array);
                    }
                }
            }

            if (image_array.length == 0) {
                alert('Please select some images');
                return;
            }
            console.log(image_array);
            $('#media_ids').val(image_array);
            $("#PostImgInstaMedia").submit();

        });

        function unique(list) {
            var result = [];
            $.each(list, function(i, e) {
                if ($.inArray(e, result) == -1) result.push(e);
            });
            return result;
        }

        $(document).on('click', '.preview-attached-img-btn', function(e) {
            e.preventDefault();
            var websiteId = $(this).data('id');
            if (websiteId == '' && websiteId != '0') {
                alert('No webiste select');
                return false;
            }
            $.ajax({
                url: '/content-management/manage/attach/images',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    websiteId: websiteId
                },
                dataType: 'html',
            }).done(function(data) {
                $('#attach-image-list-' + websiteId).html(data);
            }).fail(function() {
                alert('Error searching for images');
            });

            var expand = $('.expand-' + websiteId);
            $(expand).toggleClass('hidden');

        });

        $(document).on('click', '.preview-attached-img-btn-gmail', function(e) {
            e.preventDefault();
            var website_Id = $(this).data('id');
            $('#attach-gamil-image-list-' + website_Id).html('');

            if (website_Id == '' && website_Id != '0') {
                alert('No webiste select');
                return false;
            }

            const images__ = $(this).data('images')
            let html = ""
            images__.forEach((element, index) => {
                html += '<div class="col-md-2 col-xs-4 text-center product-list-card mb-4 single-image-' +
                    element.id + '" style="padding:0px 5px;margin-bottom:2px !important;">' +
                    '<div style="border: 1px solid #bfc0bf;padding:0px 5px;">' +
                    '<div data-interval="false" id="carousel_{{ request('websiteId') }}" class="carousel slide" data-ride="carousel">' +
                    '<div class="carousel-inner maincarousel">' +
                    '<div class="item" style="display: block;"> <img src="' + element.images +
                    '" style="height: 150px; width: 150px;display: block;margin-left: auto;margin-right: auto;"> </div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="row pl-4 pr-4" style="padding: 0px; margin-bottom: 8px;">' +
                    '<div class="custom-control custom-checkbox">' +
                    '<input type="checkbox" class="custom-control-input select-pr-list-chk"  id="defaultUnchecked_' +
                    element.id + '" >' +
                    '<label class="custom-control-label" for="defaultUnchecked_' + element.id +
                    '"></label>' +
                    '</div>' +
                    '<a data-media="' + index + '" download="" data-image_url="' + element.images +
                    '" class="btn btn-md select_row attach-photo download-attach-photo"><i class="fa fa-download"></i></a>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
            });
            // $.ajax({
            //     url: '/content-management/manage/attach/images',
            //     type: 'POST',
            //     data: { _token: "{{ csrf_token() }}", website_Id: website_Id},
            //     dataType: 'html',
            // }).done(function (data) {
            //     $('#attach-gmail-image-list-'+website_Id).html(data);
            // }).fail(function () {
            //     alert('Error searching for images');
            // });
            $('#attach-gamil-image-list-' + website_Id).append(html);

            var expand = $('.expand-gmail-' + website_Id);
            $(expand).toggleClass('hidden');

        });

        $(document).on('click', '.add-social-account', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('content-management.social.create') }}",
                type: 'GET',
                success: function(response) {
                    $("#accountCreateModal").modal("show");
                    $("#add-social-account-content").html(response);
                },
                error: function() {}
            });
        });

        $(document).on('click', '.download-attach-photo', function(e) {
            e.preventDefault();
            var image_url = $(this).data('image_url');
            var $this = $(this);
            $.ajax({
                url: "{{ route('content-management.download.image') }}",
                type: 'GET',
                data: {
                    'image_url': image_url
                },
                success: function(response) {
                    if (response.status == true) {

                        // $this.attr('href',response.image_path);
                        // $this.trigger('click');


                        $.ajax({
                            url: response.image_path,
                            method: 'GET',
                            xhrFields: {
                                responseType: 'blob'
                            },
                            success: function(data) {
                                var a = document.createElement('a');
                                var url = window.URL.createObjectURL(data);
                                a.href = url;
                                a.download = response.random;
                                document.body.append(a);
                                a.click();
                                a.remove();
                                window.URL.revokeObjectURL(url);
                            }
                        });


                    }
                },
                error: function() {}
            });
        });

        $(document).on('click', '.preview-img-btn', function(e) {
            e.preventDefault();
            id = $(this).data('id');
            $.ajax({
                url: "/content-management/preview-img/" + id,
                type: 'GET',
                success: function(response) {
                    $("#preview-website-image").modal("show");
                    $(".website-image-list-view").html(response);
                },
                error: function() {}
            });
        });
    </script>

@endsection
