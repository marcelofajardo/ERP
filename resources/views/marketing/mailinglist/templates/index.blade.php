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
                    <h2 class="page-heading">Mailing list Templates ({{count($mailings)}})</h2>
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
                    <button type="button" class="btn btn-primary float-right create-new-template-btn"
                            data-toggle="modal"
                            data-target="#exampleModalCenter">
                        Create a new email template
                    </button>

                    <button type="button" class="btn btn-primary float-right mr-3" data-toggle="modal"
                            data-target="#addMailingListCategoryModal">
                        Add Category
                    </button>

                </div>
            </div>
        </div>

    </div>


    <div class="modal fade template-modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Create a new email template</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" id="form-store">
                        <input type="hidden" name="id" class="py-3" id="form_id">
                        <div class="form-group">
                            <label for="exampleInputName">Name</label>
                            <input required type="text" name="name" class="form-control" id="form_name"
                                   aria-describedby="NameHelp" placeholder="Enter Name">
                            <span class="text-danger"></span>
                        </div>
						<div class="form-group">
                            <label for="form_subject">Subject</label>
                            <input required type="text" name="subject" class="form-control" id="form_subject" placeholder="Enter Subject">
                            <span class="text-danger"></span>
                        </div>
						<div class="form-group">
                            <label for="form_static_template">Static Template</label>
                            <textarea required name="static_template" id="form_static_template" class="form-control" placeholder="Enter Static Template" rows='8'></textarea>
                            <span class="text-danger"></span>
                        </div>
						
                        <div class="form-group">
                            <label for="mail_tpl">Email Template</label>
                            <?php echo Form::select("mail_tpl", ["-- None --"] + $rViewMail, null, ["class" => "form-control select2", "required" => true, "id" => "form_mail_tpl"]); ?>
                            <span class="text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="mail_tpl">Category</label>
                            <?php echo Form::select("category", ["-- None --"] + $MailingListCategory, null, ["class" => "form-control select2", "required" => true, "id" => "template_category"]); ?>
                            <span class="text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="mail_tpl">Store Website</label>
                            <?php echo Form::select("store_website", ["-- None --"] + $storeWebSites, null, ["class" => "form-control select2", "required" => true, "id" => "store_website"]); ?>
                            <span class="text-danger"></span>
                        </div>

                        <!-- <div class="form-group">
                            <label for="exampleInputImageCount">Image Count</label>
                            <input required type="text" name="image_count" class="form-control"
                                   id="exampleInputImageCount"
                                   placeholder="Enter Image Count">
                            <span class="text-danger"></span>
                        </div> -->
                        <!-- <div class="form-group">
                            <label for="exampleInputTextCount">Text Count</label>
                            <input required type="text" name="text_count" class="form-control"
                                   id="exampleInputTextCount"
                                   placeholder="Enter Text Count">
                            <span class="text-danger"></span>
                        </div> -->
                        <div class="form-group d-flex flex-column">
                            <label for="image">Template Example</label>
                            <input type="hidden" name="old_image" class="py-3" id="form_image">
                            <input required type="file" name="image" class="py-3" id="image">
                            <span class="text-danger"></span>
                        </div>
                        <!-- <div class="form-group d-flex flex-column">
                            <label for="image">File</label>
                            <input required type="file" name="file" class="py-3" id="image">
                            <span class="text-danger"></span>
                        </div> -->
                        <button id="store" type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Model Add Mailinglist category START -->
    <div id="addMailingListCategoryModal" class="modal fade in" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content" style="padding: 0 10px 10px">
                <div class="modal-header">
                    <h3>Add new category</h3>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form name="add-mailing-list-category" style="padding:10px;"
                      action="{{ route('mailingList.category.store') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group mt-3">
                        <input type="text" class="form-control" name="name" placeholder="Name" value="" required="">
                    </div>
                    <button type="submit" class="btn btn-secondary">Add Category</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Model Add Mailinglist category END -->


    



    <div class="modal fade template-modal" id="addcontent" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle1">Add Content</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body content_body">
                    
                </div>
            </div>
        </div>
    </div>

    @if(Session::has('message'))
        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif

    <div class="table-responsive mt-3">
        <table class="table table-bordered" id="passwords-table">
            <thead>
            <tr>
                <!-- <th style="">ID</th> -->
                <th style="">Name</th>
                <th style="">Mail Tpl</th>
                <th style="" width="8%">Subject</th>
                <th style="">Static Template</th>
                <th style="">Category</th>
                <th style="">Store Website</th>
                <!-- <th style="">Image Count</th>
                <th style="">Text Count</th> -->
                <th style="">Template Example</th>
                <th style="">Action</th>
                {{--  <th style="">File</th>--}}
            </tr>
            </thead>
            <tbody>
            @foreach($mailings as $value)
                <tr>
                    <td>{{$value["name"]}}</td>
                    <td>{{$value["mail_tpl"]}}</td>
                    <td>{{$value["subject"]}}</td>
                    <td>{{$value["static_template"]}}</td>
                    <td>{{$value->category !== null ? $value->category->title : '-' }}</td>
                    <td>{{$value->storeWebsite !== null ? $value->storeWebsite->title : '-' }}</td>
                <!-- <td>{{$value["image_count"]}}</td> -->
                <!-- <td>{{$value["text_count"]}}</td> -->
                    <td>
                        @if($value['example_image'])
                            <img style="width: 100px" src="{{ asset($value['example_image']) }}">
                        @endif
                    </td>
                    <td>
                        <a data-id="{{ $value['id'] }}" class="delete-template-act" href="javascript:;">
                            <i class="fa fa-trash"></i>
                        </a>
                    | <a data-id="{{ $value['id'] }}" data-storage="{{ $value }}" class="edit-template-act"
                            href="javascript:;">
                        <i class="fa fa-edit"></i>
                    </a>

                    | <a data-id="{{ $value['id'] }}"  class="add-content"
                            href="javascript:;">
                        <i class="fa fa-send"></i>
                    </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if(isset($mailings))
            {{$mailings->appends($_GET)->links()}}
        @endif
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function () {
            $(".select-multiple").multiselect();
            $(".select-multiple2").select2();
        });

        $('#filter-date').datetimepicker({
            format: 'YYYY-MM-DD',
        });

        $('#filter-whats-date').datetimepicker({
            format: 'YYYY-MM-DD',
        });

        $('#store').on('click', function (e) {
            e.preventDefault();

            var form = $('#form-store')[0];
            var formData = new FormData(form);
            $.ajax({
                url: "mailinglist-templates/store",
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                contentType: false,
                processData: false,
                type: 'POST',
                data: formData
            }).done(function (response) {
                if (response.errors) {
                    var obj = response.errors;
                    for (var prop in obj) {
                        $('input[name="' + prop + '"]').next().html(obj[prop]);
                    }
                } else {
                    location.reload()
                }
            }).fail(function (errObj) {
                console.log(errObj);
            });
        });

        $('#filter').on('click', function (e) {
            e.preventDefault();
            var term = $('#term').val();
            var date = $('#date').val();
            $.ajax({
                url: "/marketing/mailinglist-ajax",
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                data: {
                    term: term,
                    date: date
                }
            }).done(function (response) {
                $('tbody').html('');
                $('tbody').html(response.mailings);
            }).fail(function (errObj) {
                console.log(errObj);
            });
        })


        var deleteAct = function (ele) {
            var id = ele.data("id");
            $.ajax({
                type: 'GET',
                url: "/marketing/mailinglist-templates/" + id + '/delete'
            }).done(function (response) {
                if (response.code == 200) {
                    ele.closest("tr").remove();
                    toastr['success'](response.message, 'success');
                } else {
                    toastr['error'](response.message, 'error');
                }
            }).fail(function (response) {
                console.log(response);
            });
        };

        $(document).on("click", ".delete-template-act", function (e) {
            e.preventDefault();
            var c = confirm("Are you sure you want to delete this?");
            if (c === true) {
                deleteAct($(this));
            }
        });

        $(document).on("click", ".edit-template-act", function (e) {
            e.preventDefault();
            let storage = $(this).data("storage");
            var findForm = $("#form-store");
            $.each(storage, function (k, v) {
                var formField = findForm.find("#form_" + k);
                if (formField.length > 0) {
                    var tagName = formField.prop("tagName").toLowerCase();
                    if(tagName == "input" || tagName == "hidden" || tagName == "textarea") {
                        formField.val(v);
                    } else if (tagName == "select") {
                        var options = formField.find("option");
                        if (options.length > 0) {
                            $.each(options, function (k, r) {
                                if ($(r).val() == v) {
                                    $(r).prop("selected", true);
                                }
                            });
                        }
                    }
                }
            });

            /**
             * Make Category and storeWebsite Selected
             * */

            findForm.find('select[name=category]').find('option[value="'+ storage.category_id +'"]').val(storage.category_id).prop('selected', true)
            findForm.find('select[name=store_website]').find('option[value="'+ storage.store_website_id +'"]').val(storage.store_website_id).prop('selected', true)


            $(".template-modal").modal("show");
        });

        $(document).on("click", ".create-new-template-btn", function () {
            document.getElementById("form-store").reset();
            $(".template-modal").modal("show");
        });


        /**
         * Create mailingList Template Category and error notification.
         */

        function error_notification(form, path) {

            if (form !== null) {

                if (form.find('label.error').length > 0) {
                    form.find('label.error').remove();
                }

                $.each(path, function (index, element) {

                    const html = '<label class="error" for="' + index + '">' + element[0] + '</label>';
                    $(html).insertAfter(form.find('[name="' + index + '"]'));
                })
            }
        }

        $('form[name="add-mailing-list-category"]').submit(function (e) {

            e.preventDefault();
            let form = $(this);

            $(this).find('[type="submit"]').html('Please wait...').prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: '{{ route('mailingList.category.store') }}',
                data : new FormData($(this)[0]),
                contentType: false,
                processData: false,
            }).done(function (response) {

                if(response.status){
                    form[0].reset();
                    toastr['success'](' Mailinglist category has been successfully created.', 'success');

                    form.parents('#addMailingListCategoryModal').modal('hide');

                }

                form.find('[type="submit"]').html('Add Category').prop('disabled', false);

            }).fail(function (response) {

                if(response.responseJSON.errors) {
                    error_notification(form, response.responseJSON.errors)
                }

                form.find('[type="submit"]').html('Add Category').prop('disabled', false);
            });

            return false;

        });


        $('.add-content').on('click',function(){
            let id = $(this).attr('data-id');

            $.ajax({
                url : "{{ route('displayContentModal') }}",
                type : "POST",
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                data : {
                    id : id
                },
                success : function (response){
                    $('.content_body').html('');
                    $('.content_body').append(response.html);
                    $('#addcontent').modal('show');
                },
                error : function (response){

                }
            });
        });

    </script>
@endsection