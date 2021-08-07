@extends('layouts.app')

@section('styles')

    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <style type="text/css">
        #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
        }
        i{
            cursor: pointer;
            font-size: 18px !important;
        }
        @media (min-width: 768px){
            .modal-dialog {
           /*     width: 1500px !important;*/
                width: 100%;
                max-width: 1500px !important;
                margin: 30px auto;
                min-height: 600px !important;
            }
            .modal-content {
                min-height: 600px !important;
            }
        }
        @media (min-width: 576px){
            .modal-dialog {
       /*         width: 1500px !important;*/
                max-width: 1500px !important;
                margin: 30px auto;
                min-height: 600px !important;
            }
        }




    </style>
@endsection


@section('content')
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <h2 class="page-heading">Mailinglist Emails</h2>
                    <div class="pull-left">
                        <form action="{{--{{ route('whatsapp.config.queue', $id) }}--}}" method="GET"
                              class="form-inline align-items-start form-filter">
                            <div class="form-group mr-3 mb-3">
                                <input name="term" type="text" class="form-control global" id="term"
                                       value="{{ isset($term) ? $term : '' }}"
                                       placeholder="name , image count, text count">
                            </div>
                            <div class="form-group ml-3">
                                <div class='input-group date' id='filter-date'>
                                    <input type='text' class="form-control global" name="date"
                                           value="{{ isset($date) ? $date : '' }}" placeholder="Date" id="date"/>

                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                                </div>
                            </div>
                            <button id="filter" type="submit" class="btn btn-image"><img src="/images/filter.png"/>
                            </button>
                        </form>
                    </div>
                    <button type="button" id="create-email" class="btn btn-primary float-right" data-toggle="modal"
                            data-target="#exampleModalCenter">
                        Create Email
                    </button>
                    <button type="button" class="btn btn-primary float-right d-none open-modal-img" data-id="" data-toggle="modal"  data-target="#exampleModalImages">
                         Images
                    </button>
                </div>
            </div>
        </div>

    </div>


    <button style="display: none" id="ModalPreview" type="button" class="btn btn-primary" data-toggle="modal" data-target="#previewModal">
        Preview
    </button>


    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Template Preview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body preview-body">

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade w-auto" id="exampleModalCenter" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable " role="document">
            <div class="modal-content " >
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Create a new email template</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="row" >
                    <div class="col-md-6">
                        <div class="modal-body">
                            <form enctype="multipart/form-data" id="form-store">

                                <div class="form-group">
                                    <label for="exampleInputName">Audience</label>
                                    {{--   <input required type="text" name="audience" class="form-control" id="exampleInputName"
                                              aria-describedby="NameHelp" placeholder="Enter Audience">
                                       <span class="text-danger"></span>--}}
                                    <select required class="form-control" name="mailinglist_id" id="exampleInputName">
                                        @foreach($audience as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-span"></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputTemplates">Templates</label>
                                    {{--   <input required type="text" name="audience" class="form-control" id="exampleInputName"
                                              aria-describedby="NameHelp" placeholder="Enter Audience">
                                       <span class="text-danger"></span>--}}
                                    <select required class="form-control" name="template_id" id="template">
                                        <option value="">Select One</option>
                                        @foreach($templates as $template)
                                            <option data-img="{{$template->example_image}}" data-textcount = "{{$template->text_count}}" data-image-count = "{{$template->image_count}}" value="{{$template->id}}">{{$template->name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-span"></span>
                                </div>
                                <div class="form-group ">
                                    <img src="" alt="" class="template_image img-fluid mb-2">
                                    <div class="tempaltes_put"></div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputImageCount">Subject</label>
                                    <input required type="text" name="subject" class="form-control"
                                           id="exampleSubject"
                                           placeholder="Enter Subject">
                                    <span class="text-danger error-span"></span>
                                </div>
                                <div class="form-group">
                                    <label for="date-modal">Date/Time</label>
                                    <div class='input-group date' id='filter-date-modal'>
                                        <input required type='text' class="form-control global" name="scheduled_date"
                                               value="{{ isset($date) ? $date : '' }}" placeholder="Date" id="date-modal"/>
                                        <span class="text-danger error-span"></span>

                                        <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                                    </div>
                                </div>

                                <button id="store" type="submit" class="btn btn-primary send-modal-btn">Submit</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="modal-body put-index-here">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div  class="modal fade w-auto" id="exampleModalImages" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable "  role="document">
            <div class="modal-content " >
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">Choose Image</h4>
                    <button type="button" class="close close-images" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="row p-4">
                    <div class="col-md-6" style="border-right: 1px solid grey">
                        <h4 class="d-block">LifeStyle</h4>
                        <div class="row">
                        @foreach($images as $image)
                            <div class="col-md-3"><img class="img-fluid modal-img-item" data-src="{{$image->filename}}" src="{{asset('uploads/social-media/'.$image->filename)}}" alt=""></div>
                        @endforeach
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 class="d-block">Gmail</h4>
                        <div class="row">

                    @foreach($images_gmail as $image)
                        @if($image->images)
                                @foreach($image->images as $item)
                                    <div class="col-md-3"><img class="img-fluid modal-img-item" data-src="{{$item}}" src="{{asset($item)}}" alt=""></div>
                                    @endforeach
                            @endif
                    @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="table-responsive mt-3">
        <table class="table table-bordered" id="passwords-table">
            <thead>
            <tr>
                <!-- <th style="">ID</th> -->
                <th style=""># (id)</th>
        {{--        <th style="">Date/Time</th>
                <th style="">Subject</th>
                <th style="">Content</th>
                <th style="">Mailinglist</th>
                <th style="">Actions</th>--}}
                <td>Subject</td>
                <td>Total Subscribers</td>
                <td>Send Mail</td>
                <td>Pending Mail</td>
                <td>Audience</td>
                <td>Template</td>
{{--                <td>Subject</td>--}}
                <td>Date</td>
                <td>Actions</td>
                {{--  <th style="">File</th>--}}
            </tr>
            </thead>
               <tbody>
                @foreach($mailings as $value)

                    <tr>
                        <td>{{$value->id}}</td>
                        <td>{{$value->subject}}</td>
                        <td>{{$value->total_emails_scheduled}}</td>
                        <td>{{$value->total_emails_sent}}</td>
                        <td>{{$value->total_emails_undelivered}}</td>
                        <td>{{$value->audience->name}}</td>
                        <td>{{$value->template->name}}</td>
              {{--          <td>{{$value["subject"]}}</td>--}}
                        <td>{{$value->scheduled_date}}</td>
                        <td>
                            <i data-id="{{$value->id}}" title="Preview" id="preview" class="fa fa-eye preview" aria-hidden="true"></i>
                            <i data-id="{{$value->id}}" title="Duplicate" id="duplicate"  class="fa fa-clone duplicate" aria-hidden="true"></i>
                        </td>
                    </tr>
                @endforeach
                </tbody>
        </table>
        {{--        @if(isset($mailings))
                    {{$mailings->appends($_GET)->links()}}
                @endif--}}
    </div>
@endsection
@php
@endphp

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function () {
            $(".select-multiple").multiselect();
            $(".select-multiple2").select2();
        });

        $('#template').on('change',function () {
            var count =  $(this).find('option:selected').data('textcount');
            if($(this).val() != ""){
          /*      $('.templates_textarea').fadeIn();*/
               /* $('.templates_textarea').attr('placeholder','Text count length is ' + $(this).find('option:selected').data('textcount'));*/
           /*     for(var i =0; i<count; i++ ){
                    $('.tempaltes_put').append(`
                    <textarea  class="form-control  templates_textarea mb-2" style="height: 110px;" name="template_textarea[]" ></textarea>`);
                }*/
                $('.template_image').attr('src','http://erp.ec/' + $(this).find('option:selected').data('img'));
                $.ajax({
                    type: "POST",
                    url: "/marketing/mailinglist-ajax-index",
                    data: {
                        id: $(this).val()
                    },
                    beforeSend: function (request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                }).done(function( data ) {
                    $('.put-index-here').html(data.template_html);
                    console.log(data);
                }).fail(function(response) {
                });

            }else{
                $('.templates_textarea').fadeOut();
                $('.tempaltes_put').html('');
                $('.template_image').attr('src','');
            }

        });
        var imgSrcSel = null;
        $(document).on('click','.put-index-here img',function () {
            $(this).addClass('img-fluid');
            $('.open-modal-img').click();
            $('.open-modal-img').attr('data-id',$(this).attr('id'));
            imgSrcSel = $(this);
        });

        $('.modal-img-item').on('click',function () {
            var src = $(this).attr('src');
            if(imgSrcSel.length > 0) {
                imgSrcSel.attr('src',src);
            }
            //var id = $('.open-modal-img').attr('data-id');
            //$('img[id="'+id+'"]').attr('src',src);
            $('#exampleModalCenter').css('overflow-y','scroll');
            $('.close-images').click();
        });
        $('.put-index-here').not('img').attr('contentEditable','true');

        $('#filter-date').datetimepicker({
            format: 'YYYY-MM-DD',
        });

        $('#filter-date-modal').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
        });

        $('#filter-whats-date').datetimepicker({
            format: 'YYYY-MM-DD',
        });

        $(document).on('click','.preview', function () {
            $('#ModalPreview').click();
            $('.preview-body').html('');
            var id = $(this).data('id');
            $.ajax({
                type: "POST",
                url: "/marketing/mailinglist-ajax-show",
                data: {id:id},

                beforeSend: function (request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
            }).done(function(data) {
                if(data.html){
                    $('.preview-body').html(data.html.html);
                }
            }).fail(function(data) {
            });

        });

        $(document).on('click','.duplicate', function () {
            $('#create-email').click();
            var id = $(this).data('id');
                $.ajax({
                    type: "POST",
                    url: "/marketing/mailinglist-ajax-duplicate",
                    data: {id:id},

                    beforeSend: function (request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                }).done(function(data) {
/*
                    console.log(data.html['html']);
*/
                    if(data.html){
                        $('.put-index-here').html(data.html['html']);
                        $('select[name="mailinglist_id"]').val(data.html['mailinglist_id']);
                        $('select[name="template_id"]').val(data.html['template_id']);
                        $('input[name="subject"]').val(data.html['subject']);
                        $('input[name="scheduled_date"]').val(data.html['scheduled_date']);
                    }

                }).fail(function(data) {
            });

        });

        $('#create-email').on('click',function () {
  /*          $('select[name="mailinglist_id"]').val('');*/
            $('select[name="template_id"]').val('');
            $('input[name="subject"]').val('');
            $('input[name="scheduled_date"]').val('');
            $('.template_image').attr('src','');
            $('.put-index-here').html('');
        })


        $('.send-modal-btn').on('click',function (e) {
            e.preventDefault();
            $('.error-span').html('');
            var html =    $('.put-index-here').html();
            var formData = $('#form-store').serialize() + '&html=' + escape(html);
            $.ajax({
                type: "POST",
                url: "/marketing/mailinglist-ajax-store",
                data: formData,

                beforeSend: function (request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
            }).done(function(data) {
                if (data.errors) {
                    var obj = data.errors;
                    for (var prop in obj) {
                        $('input[name="' + prop + '"]').next().html(obj[prop]);
                        $('select[name="' + prop + '"]').next().html(obj[prop]);
                    }

                }else{
                    $('tbody').prepend(data.item);
                    $('.close').click();
                }

            }).fail(function(data) {


            });
        });

        
        function getStats(id){

            $.ajax({
                type: "POST",
                url: "/marketing/mailinglist-stats",
                data: { 'id' : id},

                beforeSend: function (request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
            }).done(function(data) {
                console.log(data)
            }).fail(function(data) {
            });
        }
    </script>
@endsection